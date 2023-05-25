<?php 
require 'vendor/autoload.php';
use Safaricom\Mpesa\Mpesa;

class Api extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('auth_model');
        $this->load->model('products_model');
        $this->load->model('mpesa_model');
        $this->load->model('pos_model');
        $this->load->model('sales_model');
        $this->load->library('ion_auth');
        $this->load->library('form_validation');
    }
    
    function  addSale(){
        $this->form_validation->set_rules('staff_id', lang("Attendant"), 'required');
        $this->form_validation->set_rules('total', lang("Total"), 'required');
        
        $this->form_validation->set_rules('json', lang('JSON'), 'required');
        
        $this->form_validation->set_rules('payment_status', lang("Payment Status"), 'required');
            $this->form_validation->set_rules('payments', lang('Payments'), 'required');
            
        
        if ($this->form_validation->run() == true) {

            $reference = $this->input->post('reference_no') ?
                $this->input->post('reference_no') :
                $this->site->getReference('so');
            $date = date("Y-m-d H:i:s");
            $warehouse_id = 2;
            $payments = json_decode($this->input->post('payments'), true);
            $ttl = $this->input->post('total');
             $created_by = $this->input->post('staff_id');
            $payment_status = $this->input->post('payment_status');
            $shipping = $this->input->post('shipping') ? $this->input->post('shipping') : 0;
            $total = 0;
            $product_tax = 0;
            $order_tax = 0;
            $product_discount = 0;
            $order_discount = 0;
            $percentage = '%';
            $params = json_decode($this->input->post('json'), true);
            
            foreach($params['items'] as $item){
                $item_id = $item['product_id'];
                $item_type = "standard";
                $item_code = $item['code'];
                $item_name = $item['name'];
                $item_discount = $item['discount'];
                $item_option = NULL;
                $real_unit_price = $this->sma->formatDecimal($item['price']);
                $unit_price = $this->sma->formatDecimal($item['price']);
                $item_quantity = $item['quantity'];
                $item_serial = '';
                $item_tax_rate = NULL;
                

                if (isset($item_code) && isset($real_unit_price) && isset($unit_price) && isset($item_quantity)) {
                    $product_details = $item_type != 'manual' ? $this->sales_model->getProductByCode($item_code) : NULL;
                    $unit_price = $real_unit_price;
                    $pr_discount = 0;
                
                    $product_discount = 0;

                    $item_net_price = $unit_price;
                    
                    $pr_tax = 0; $pr_item_tax = 0; $item_tax = 0; $tax = "";

                    if (isset($item_tax_rate) && $item_tax_rate != 0) {
                        $pr_tax = $item_tax_rate;
                        $tax_details = $this->site->getTaxRateByID($pr_tax);
                        if ($tax_details->type == 1 && $tax_details->rate != 0) {

                            if ($product_details && $product_details->tax_method == 1) {
                                $item_tax = $this->sma->formatDecimal((($unit_price) * $tax_details->rate) / 100);
                                $tax = $tax_details->rate . "%";
                            } else {
                                $item_tax = $this->sma->formatDecimal((($unit_price) * $tax_details->rate) / (100 + $tax_details->rate));
                                $tax = $tax_details->rate . "%";
                                $item_net_price = $unit_price - $item_tax;
                            }

                        } elseif ($tax_details->type == 2) {

                            if ($product_details && $product_details->tax_method == 1) {
                                $item_tax = $this->sma->formatDecimal((($unit_price) * $tax_details->rate) / 100);
                                $tax = $tax_details->rate . "%";
                            } else {
                                $item_tax = $this->sma->formatDecimal((($unit_price) * $tax_details->rate) / (100 + $tax_details->rate));
                                $tax = $tax_details->rate . "%";
                                $item_net_price = $unit_price - $item_tax;
                            }

                            $item_tax = $this->sma->formatDecimal($tax_details->rate);
                            $tax = $tax_details->rate;

                        }
                        $pr_item_tax = $this->sma->formatDecimal($item_tax * $item_quantity);

                    }

                    $product_tax += $pr_item_tax;
                    $subtotal = (($item_net_price * $item_quantity) + $pr_item_tax);

                    $total += $item_net_price * $item_quantity;
                }
                $products[] = array(
                    'product_id' => $item_id,
                    'product_code' => $item_code,
                    'product_name' => $item_name,
                    'product_type' => $item_type,
                    'option_id' => $item_option,
                    'net_unit_price' => $item_net_price,
                    'unit_price' => $this->sma->formatDecimal($item_net_price + $item_tax),
                    'quantity' => $item_quantity,
                    'warehouse_id' => $warehouse_id,
                    'item_tax' => $pr_item_tax,
                    'tax_rate_id' => $pr_tax,
                    'tax' => $tax,
                    'discount' => $item_discount,
                    'item_discount' => $product_discount,
                    'subtotal' => $this->sma->formatDecimal($subtotal),
                    'serial_no' => $item_serial,
                    'real_unit_price' => $real_unit_price
                );
                
            }

            $order_discount_id = NULL;

            $total_discount = $this->sma->formatDecimal($order_discount + $product_discount);

            if ($this->Settings->tax2) {
                $order_tax_id = $this->input->post('order_tax');
                if ($order_tax_details = $this->site->getTaxRateByID($order_tax_id)) {
                    if ($order_tax_details->type == 2) {
                        $order_tax = $this->sma->formatDecimal($order_tax_details->rate);
                    }
                    if ($order_tax_details->type == 1) {
                        $order_tax = $this->sma->formatDecimal((($total + $product_tax - $order_discount) * $order_tax_details->rate) / 100);
                    }
                }
            } else {
                $order_tax_id = NULL;
            }

            $total_tax = $this->sma->formatDecimal($product_tax + $order_tax);
            $grand_total = $this->sma->formatDecimal($this->sma->formatDecimal($total) + $total_tax + $this->sma->formatDecimal($shipping) - $order_discount);
            $data = array('date' => $date,
                'reference_no' => $reference,
                'total' => $this->sma->formatDecimal($ttl),
                'total_discount' => $total_discount,
                'product_tax' => $this->sma->formatDecimal($product_tax),
                'shipping' => $this->sma->formatDecimal($shipping),
                'grand_total' => $ttl,
                'paid' => 0,
                'created_by' => $created_by,
                'payment_status'=> $payment_status
            );
            
            foreach($payments['items'] as $item){
                $payment[] = array(
                        'date' => $date,
                        'reference_no' => 0,
                        'amount' => $item['amount'],
                        'paid_by' => $item['paid_by'],
                        'cheque_no' => $item['cheque_no'],
                        'cc_no' => "",
                        'pos_paid' =>  $item['amount'],
                        'pos_balance' => 0,
                        'cc_holder' => "",
                        'cc_month' => "",
                        'cc_year' => "",
                        'cc_type' => "",
                        'created_by' => $created_by,
                        'note' => "",
                        'type' => $item['type'],
                    );
            }

            $result = $this->sales_model->addSale2($data, $products, $payment);
             if ($result) {
                 $response= array("success" => "1", "message" => "Sale added successfully");
                }else{
                    $response= array("success" => "0", "message" => "Sale not added");
                }

        }else{
            $response= array("success" => "0", "message" => (validation_errors() ? validation_errors() : $this->session->flashdata('error')));
        }
        
        //$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
//fwrite($myfile, json_encode($response));
//fclose($myfile);
        
        
        echo json_encode($response);
        
    }
    
    function fetchProducts(){
        $products = $this->products_model->getAppProducts();
        
        echo json_encode($products);
    }
    
    function fetchSales()
    {
        $this->form_validation->set_rules('user_id', lang("User ID"), 'required');
        
        if ($this->form_validation->run() == true) {
           $user_id = $this->input->post('user_id');

           $response = $this->sales_model->fetchSales($user_id);

       }else{
           $response= array("success" => "0", "message" => "Could not validate");
       }
       echo json_encode($response);
    }
    
    
    
    function getCurrentSoldItems(){
       $this->form_validation->set_rules('user_id', lang("User ID"), 'required');
       $this->form_validation->set_rules('start_date', lang("Start Date"), 'required');
       $this->form_validation->set_rules('end_date', lang("End Date"), 'required');
       if ($this->form_validation->run() == true) {
           $user_id = $this->input->post('user_id');
           $start_date = $this->input->post('start_date');
           $end_date = $this->input->post('end_date');

           $response = $this->sales_model->getSoldStock($user_id,$start_date,$end_date);

       }else{
           $response= array("success" => "0", "message" => (validation_errors() ? validation_errors() : $this->session->flashdata('error')));
       }
       echo json_encode($response);
    }
    
    function login()
    {
        $this->form_validation->set_rules('email', lang('email_address'), 'required');
        $this->form_validation->set_rules('password', lang('password'), 'required');
        
        
        if ($this->form_validation->run() == true) {

            if ($this->ion_auth->login($this->input->post('email'), $this->input->post('password'), false)) {

                $users = $this->auth_model->get_user_data($this->input->post('email'));

                if($users->active==1){
                    $response = array("success" => "1", "message" => "login successful", "user" => $users);
                }else{
                    $response = array("success" => "0", "message" => "Login unsuccessful. Account has been deactivated please contact admin");
                }
                
            }else{
                
                $response= array("success" => "0", "message" => $this->ion_auth->errors());die(json_encode($response));
            }
        } else {
            $response= array("success" => "0", "message" => (validation_errors() ? validation_errors() : $this->session->flashdata('error')));
        }
        //$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
//fwrite($myfile, json_encode($response));
//fclose($myfile);

        echo json_encode($response);
    }
    
	function generateAccessToken(){
		$mpesa= new Mpesa();
		$result=$mpesa->generateLiveToken();
		 return $result;
	}

    function validation(){
		$result_code= "0";
		$result_description="Accepted";
		$result=json_encode(["ResultCode"=>$result_code, "ResultDesc"=>$result_description]);
		echo $result;
		
	}
	    public function registerUrls()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://api.safaricom.co.ke/mpesa/c2b/v2/registerurl');
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization: Bearer '.$this->generateAccessToken()));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(array(
            'ShortCode' => "656483",
            'ResponseType' => 'Completed',
            'ConfirmationURL' => "https://techsavanna.technology/sipit/api/confirmation",
            'ValidationURL' => "https://techsavanna.technology/sipit/api/validation"
        )));
        $curl_response = curl_exec($curl);
	//	print_r($curl_response);
        echo $curl_response;
    }
        public function confirmation()
    {
         $confirmationJSONData = file_get_contents('php://input');
         
        $confirmationData = json_decode($confirmationJSONData);
        
         $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
        fwrite($myfile, $confirmationJSONData);
        fclose($myfile);
        
        $response = $this->mpesa_model->insert($confirmationData);

       $result_code= "0";
		$result_description="Accepted";
		$result=json_encode(["ResultCode"=>$result_code, "ResultDesc"=>$result_description]);
		echo $response;
    }
}