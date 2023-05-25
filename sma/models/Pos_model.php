<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pos_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function getSetting()
    {
        $q = $this->db->get('pos_settings');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function updateSetting($data)
    {
        $this->db->where('pos_id', '1');
        if ($this->db->update('pos_settings', $data)) {
            return true;
        }
        return false;
    }

    public function products_count($category_id, $subcategory_id = NULL)
    {
        $this->db->where('category_id', $category_id)->from('products');
        if ($subcategory_id) {
            $this->db->where('subcategory_id', $subcategory_id);
        }
        return $this->db->count_all_results();
    }

    public function fetch_products($category_id, $limit, $start, $subcategory_id = NULL)
    {
        $this->db->limit($limit, $start);
        $this->db->where('category_id', $category_id);
        if ($subcategory_id) {
            $this->db->where('subcategory_id', $subcategory_id);
        }
       // $this->db->where('iskitchen', 1);
        $this->db->order_by("name", "asc");
        $query = $this->db->get("products");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }
   public function poslogin($userid, $password){
        
    return $this->auth_model->hash_password_db($userid, $password);
      
  }
    public function registerData($user_id)
    {
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $q = $this->db->get_where('pos_register', array('user_id' => $user_id, 'status' => 'open'), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function openRegister($data)
    {
        if ($this->db->insert('pos_register', $data)) {
            return true;
        }
        return FALSE;
    }

    public function getOpenRegisters()
    {
        $this->db->select("date, user_id, cash_in_hand, CONCAT(" . $this->db->dbprefix('users') . ".first_name, ' ', " . $this->db->dbprefix('users') . ".last_name, ' - ', " . $this->db->dbprefix('users') . ".email) as user", FALSE)
            ->join('users', 'users.id=pos_register.user_id', 'left');
        $q = $this->db->get_where('pos_register', array('status' => 'open'));
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;

    }

    public function closeRegister($rid, $user_id, $data)
    {
        if (!$rid) {
            $rid = $this->session->userdata('register_id');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        if ($data['transfer_opened_bills'] == -1) {
            $this->db->delete('suspended_bills', array('created_by' => $user_id));
        } elseif ($data['transfer_opened_bills'] != 0) {
            $this->db->update('suspended_bills', array('created_by' => $data['transfer_opened_bills']), array('created_by' => $user_id));
        }
        if ($this->db->update('pos_register', $data, array('id' => $rid, 'user_id' => $user_id))) {
            return true;
        }
        return FALSE;
    }

    public function getUsers()
    {
        $q = $this->db->get_where('users', array('company_id' => NULL));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getProductsByCode($code)
    {
        $this->db->like('code', $code, 'both')->order_by("code");
        $q = $this->db->get('products');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    
    
    public function getWHProduct($code, $warehouse_id)
    {
        $this->db->select('products.id, code, name, type, warehouses_products.quantity, price, tax_rate, tax_method')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->group_by('products.id');
        $q = $this->db->get_where("products", array('code' => $code));
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getProductOptions($product_id, $warehouse_id)
    {
        $this->db->select('product_variants.id as id, product_variants.name as name, product_variants.price as price, product_variants.quantity as total_quantity, warehouses_products_variants.quantity as quantity')
            ->join('warehouses_products_variants', 'warehouses_products_variants.option_id=product_variants.id', 'left')
            //->join('warehouses', 'warehouses.id=product_variants.warehouse_id', 'left')
            ->where('product_variants.product_id', $product_id)
            ->where('warehouses_products_variants.warehouse_id', $warehouse_id)
            ->group_by('product_variants.id');
            if(! $this->Settings->overselling) {
                $this->db->where('warehouses_products_variants.quantity >', 0);
            }
        $q = $this->db->get('product_variants');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	public function getProductOptions2($product_id)
    {
        $this->db->select('product_variants.id as id, product_variants.name as name, product_variants.price as price, product_variants.quantity as total_quantity')
            //->join('product_variants', 'product_variants.product_id=product_variants.id', 'left')
            //->join('warehouses', 'warehouses.id=product_variants.warehouse_id', 'left')
            ->where('product_variants.product_id', $product_id)
            //->where('warehouses_products_variants.warehouse_id', $warehouse_id)
            ->group_by('product_variants.id');
            
        $q = $this->db->get('product_variants');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getProductComboItems($pid, $warehouse_id)
    {
        $this->db->select('products.id as id, combo_items.item_code as code, combo_items.quantity as qty, products.name as name, warehouses_products.quantity as quantity')
            ->join('products', 'products.code=combo_items.item_code', 'left')
            ->join('warehouses_products', 'warehouses_products.product_id=products.id', 'left')
            ->where('warehouses_products.warehouse_id', $warehouse_id)
            ->group_by('combo_items.id');
        $q = $this->db->get_where('combo_items', array('combo_items.product_id' => $pid));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
        return FALSE;
    }
    
    public function updateOptionQuantity($option_id, $quantity)
    {
        if ($option = $this->getProductOptionByID($option_id)) {
            $nq = $option->quantity - $quantity;
            if ($this->db->update('product_variants', array('quantity' => $nq), array('id' => $option_id))) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function addOptionQuantity($option_id, $quantity)
    {
        if ($option = $this->getProductOptionByID($option_id)) {
            $nq = $option->quantity + $quantity;
            if ($this->db->update('product_variants', array('quantity' => $nq), array('id' => $option_id))) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function getProductOptionByID($id)
    {
        $q = $this->db->get_where('product_variants', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getPurchasedItems($product_id, $warehouse_id, $option_id = NULL)
    {
        $orderby = ($this->Settings->accounting_method == 1) ? 'asc' : 'desc';
        $this->db->select('id, quantity, quantity_balance, net_unit_cost, item_tax');
        $this->db->where('product_id', $product_id)->where('warehouse_id', $warehouse_id)->where('quantity_balance !=', 0);
        if ($option_id) {
            $this->db->where('option_id', $option_id);
        }
        $this->db->group_by('id');
        $this->db->order_by('date', $orderby);
        $this->db->order_by('purchase_id', $orderby);
        $q = $this->db->get('purchase_items');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getProductWarehouseOptionQty($option_id, $warehouse_id)
    {
        $q = $this->db->get_where('warehouses_products_variants', array('option_id' => $option_id, 'warehouse_id' => $warehouse_id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function updateProductOptionQuantity($option_id, $warehouse_id, $quantity, $product_id)
    {
        if ($option = $this->getProductWarehouseOptionQty($option_id, $warehouse_id)) {
            $nq = $option->quantity - $quantity;
            if ($this->db->update('warehouses_products_variants', array('quantity' => $nq), array('option_id' => $option_id, 'warehouse_id' => $warehouse_id))) {
                $this->site->syncVariantQty($option_id, $warehouse_id);
                return TRUE;
            }
        } else {
            $nq = 0 - $quantity;
            if ($this->db->insert('warehouses_products_variants', array('option_id' => $option_id, 'product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'quantity' => $nq))) {
                $this->site->syncVariantQty($option_id, $warehouse_id);
                return TRUE;
            }
        }
        return FALSE;
    }

    public function addSale($data = array(), $items = array(), $payments = array(), $sid = NULL)
    {
        $cost = $this->site->costing($items);
        // $this->sma->print_arrays($cost);
$array=array();
            foreach ($items as $item) {
                $result=$this->getPortions($item['product_id']);
                array_push($array, $result);
                    
            }
            if (in_array($array,FALSE)){
                exit();
            }
            
        if ($this->db->insert('sales', $data)) {
            $sale_id = $this->db->insert_id();
            $this->site->updateReference('pos');
            foreach ($items as $item) {
                

                $item['sale_id'] = $sale_id;
                $this->db->insert('sale_items', $item);
                $sale_item_id = $this->db->insert_id();
				$this->reduceQuantity($item['product_id'], $item['warehouse_id'], $item['quantity']);
				
                if ($data['sale_status'] == 'completed' && $this->site->getProductByID($item['product_id'])) {
                     //update warehouse quantities
                 
                  //  $this->getPortions($item['product_id']);
                    $item_costs = $this->site->item_costing($item);
                    foreach ($item_costs as $item_cost) {
                        $item_cost['sale_item_id'] = $sale_item_id;
                        $item_cost['sale_id'] = $sale_id;
                        if(! isset($item_cost['pi_overselling'])) {
                            $this->db->insert('costing', $item_cost);
                        }
                    }
                    
                }

            }

            if ($data['sale_status'] == 'completed') {
                $this->site->syncPurchaseItems($cost);
            }

            $msg = array();
            if (!empty($payments)) {
                $paid = 0;
                foreach ($payments as $payment) {
                    if (!empty($payment) && isset($payment['amount']) && $payment['amount'] != 0) {
                        $payment['sale_id'] = $sale_id;
                        if ($payment['paid_by'] == 'ppp') {
                            $card_info = array("number" => $payment['cc_no'], "exp_month" => $payment['cc_month'], "exp_year" => $payment['cc_year'], "cvc" => $payment['cc_cvv2'], 'type' => $payment['cc_type']);
                            $result = $this->paypal($payment['amount'], $card_info);
                            if (!isset($result['error'])) {
                                $payment['transaction_id'] = $result['transaction_id'];
                                $payment['date'] = $this->sma->fld($result['created_at']);
                                $payment['amount'] = $result['amount'];
                                $payment['currency'] = $result['currency'];
                                unset($payment['cc_cvv2']);
                                $this->db->insert('payments', $payment);
                                $this->site->updateReference('pay');
                                $paid += $payment['amount'];
                            } else {
                                $msg[] = lang('payment_failed');
                                if (!empty($result['message'])) {
                                    foreach ($result['message'] as $m) {
                                        $msg[] = '<p class="text-danger">' . $m['L_ERRORCODE'] . ': ' . $m['L_LONGMESSAGE'] . '</p>';
                                    }
                                } else {
                                    $msg[] = lang('paypal_empty_error');
                                }
                            }
                        } elseif ($payment['paid_by'] == 'stripe') {
                            $card_info = array("number" => $payment['cc_no'], "exp_month" => $payment['cc_month'], "exp_year" => $payment['cc_year'], "cvc" => $payment['cc_cvv2'], 'type' => $payment['cc_type']);
                            $result = $this->stripe($payment['amount'], $card_info);
                            if (!isset($result['error'])) {
                                $payment['transaction_id'] = $result['transaction_id'];
                                $payment['date'] = $this->sma->fld($result['created_at']);
                                $payment['amount'] = $result['amount'];
                                $payment['currency'] = $result['currency'];
                                unset($payment['cc_cvv2']);
                                $this->db->insert('payments', $payment);
                                $this->site->updateReference('pay');
                                $paid += $payment['amount'];
                            } else {
                                $msg[] = lang('payment_failed');
                                $msg[] = '<p class="text-danger">' . $result['code'] . ': ' . $result['message'] . '</p>';
                            }
                        } else {
                            if ($payment['paid_by'] == 'gift_card') {
                                $this->db->update('gift_cards', array('balance' => $payment['pos_balance']), array('card_no' => $payment['cc_no']));
                            }
                            unset($payment['cc_cvv2']);
                            $this->db->insert('payments', $payment);
                            $this->site->updateReference('pay');
                            $paid += $payment['amount'];
                        }
                    }
                }
                $this->site->syncSalePayments($sale_id);
            }

            //$this->site->syncQuantity($sale_id);
            if ($sid) {
                $this->deleteBill($sid);
            }
            return array('sale_id' => $sale_id, 'message' => $msg);

        }

        return false;
    }

    public function getPortions($product_id)
    {

        
         $q = $this->db->query('SELECT id, portion1,portion1qty, portion2,
             portion2qty,portion3,portion3qty,portion4,portion4qty,portion5,
             portion5qty,portion6,portion6qty,portion7,portion7qty,portion8,portion8qty
             ,portion9,portion9qty,portion10,portion10qty,portion11,portion11qty
             ,portion12,portion12qty,portion13,portion13qty FROM sma_products WHERE id='.$product_id.' AND iskitchen=1');
                $j=5;
                $product_ids=array();
                $quantitys=array();
                $warehouse_ids=array();
                $warehouse_qtys=array();
                $msgs="";
                //print_r($q->num_rows());
                //die();
        if ($q->num_rows() > 0) {
           
            foreach (($q->result()) as $row) {
                
         
                   if($row->portion1){
                       //echo $row->portion1;
                      
                    $warehouse_id=$this->getProductsWHId($row->portion1);
                    $warehouse_qty=$this->getProductQuantity($row->portion1, $warehouse_id);
					//$warehouse_qty=$this->site->getproductqtybywarehouse($row->portion1, $warehouse_id);
                    $msgs.= $this->getProductByID($warehouse_qty['product_id'])->name."<br>";    
                    array_push($product_ids,$row->portion1); 
                    array_push($warehouse_ids,$warehouse_id);  
                    array_push($quantitys,$row->portion1qty); 
                    array_push($warehouse_qtys,$warehouse_qty['quantity']); 
                    if($warehouse_qty['quantity']<$row->portion1qty){
                     $j--;   
                    }
                   }
                   if($row->portion2){
                       //echo $row->portion2;
                   $warehouse_id=$this->getProductsWHId($row->portion2);
                   $warehouse_qty=$this->getProductQuantity($row->portion2, $warehouse_id);
				   //$warehouse_qty=$this->site->getproductqtybywarehouse($row->portion2, $warehouse_id);
                   $msgs.= $this->getProductByID($warehouse_qty['product_id'])->name."<br>";  
                    array_push($product_ids,$row->portion2); 
                    array_push($warehouse_ids,$warehouse_id);  
                    array_push($quantitys,$row->portion2qty); 
                    array_push($warehouse_qtys,$warehouse_qty['quantity']);
                     if($warehouse_qty['quantity']< $row->portion2qty){
                     $j--;   
                    }
                   }
                   if($row->portion3){
                       //echo $row->portion3;
                   $warehouse_id=$this->getProductsWHId($row->portion3);
                   $warehouse_qty=$this->getProductQuantity($row->portion3, $warehouse_id);
					 //$warehouse_qty=$this->site->getproductqtybywarehouse($row->portion3, $warehouse_id);
                    $msgs.= $this->getProductByID($warehouse_qty['product_id'])->name."<br>";  
                    array_push($product_ids,$row->portion3); 
                    array_push($warehouse_ids,$warehouse_id);  
                    array_push($quantitys,$row->portion3qty); 
                    array_push($warehouse_qtys,$warehouse_qty['quantity']);  
                     if($warehouse_qty['quantity']< $row->portion3qty){
                     $j--;   
                    }
                   }
                   if($row->portion4){
                    $warehouse_id=$this->getProductsWHId($row->portion4);
                    $warehouse_qty=$this->getProductQuantity($row->portion4, $warehouse_id);
					//$warehouse_qty=$this->site->getproductqtybywarehouse($row->portion4, $warehouse_id);
                    $msgs.= $this->getProductByID($warehouse_qty['product_id'])->name."<br>";  
                    array_push($product_ids,$row->portion4); 
                    array_push($warehouse_ids,$warehouse_id);  
                    array_push($quantitys,$row->portion4qty); 
                    array_push($warehouse_qtys,$warehouse_qty['quantity']); 
                     if($warehouse_qty['quantity']< $row->portion4qty){
                     $j--;   
                    }
                   }
                   if($row->portion5){
                    $warehouse_id=$this->getProductsWHId($row->portion5);
                    $warehouse_qty=$this->getProductQuantity($row->portion5, $warehouse_id);
					//$warehouse_qty=$this->site->getproductqtybywarehouse($row->portion5, $warehouse_id);
                    $msgs.= $this->getProductByID($warehouse_qty['product_id'])->name."<br>";  
                    array_push($product_ids,$row->portion5); 
                    array_push($warehouse_ids,$warehouse_id);  
                    array_push($quantitys,$row->portion5qty); 
                    array_push($warehouse_qtys,$warehouse_qty['quantity']); 
                     if($warehouse_qty['quantity']< $row->portion5qty){
                     $j--;   
                    }
                   }
                    if($row->portion6){
                    $warehouse_id=$this->getProductsWHId($row->portion6);
                    $warehouse_qty=$this->getProductQuantity($row->portion6, $warehouse_id);
					//$warehouse_qty=$this->site->getproductqtybywarehouse($row->portion6, $warehouse_id);
                    $msgs.= $this->getProductByID($warehouse_qty['product_id'])->name."<br>";  
                    array_push($product_ids,$row->portion6); 
                    array_push($warehouse_ids,$warehouse_id);  
                    array_push($quantitys,$row->portion6qty); 
                    array_push($warehouse_qtys,$warehouse_qty['quantity']); 
                     if($warehouse_qty['quantity']< $row->portion6qty){
                     $j--;   
                    }
                   }
                    if($row->portion7){
                    $warehouse_id=$this->getProductsWHId($row->portion7);
                    $warehouse_qty=$this->getProductQuantity($row->portion7, $warehouse_id);
					//$warehouse_qty=$this->site->getproductqtybywarehouse($row->portion7, $warehouse_id);
                    $msgs.= $this->getProductByID($warehouse_qty['product_id'])->name."<br>";  
                    array_push($product_ids,$row->portion7); 
                    array_push($warehouse_ids,$warehouse_id);  
                    array_push($quantitys,$row->portion7qty); 
                    array_push($warehouse_qtys,$warehouse_qty['quantity']); 
                     if($warehouse_qty['quantity']< $row->portion7qty){
                     $j--;   
                    }
                   }
                    if($row->portion8){
                    $warehouse_id=$this->getProductsWHId($row->portion8);
                    $warehouse_qty=$this->getProductQuantity($row->portion8, $warehouse_id);
					//$warehouse_qty=$this->site->getproductqtybywarehouse($row->portion8 ,$warehouse_id);
                    $msgs.= $this->getProductByID($warehouse_qty['product_id'])->name."<br>";  
                    array_push($product_ids,$row->portion8); 
                    array_push($warehouse_ids,$warehouse_id);  
                    array_push($quantitys,$row->portion8qty); 
                    array_push($warehouse_qtys,$warehouse_qty['quantity']); 
                     if($warehouse_qty['quantity']< $row->portion8qty){
                     $j--;   
                    }
                   }
                        if($row->portion9){
                    $warehouse_id=$this->getProductsWHId($row->portion9);
                    $warehouse_qty=$this->getProductQuantity($row->portion9, $warehouse_id);
					//$warehouse_qty=$this->site->getproductqtybywarehouse($row->portion9, $warehouse_id);
                    $msgs.= $this->getProductByID($warehouse_qty['product_id'])->name."<br>";  
                    array_push($product_ids,$row->portion9); 
                    array_push($warehouse_ids,$warehouse_id);  
                    array_push($quantitys,$row->portion9qty); 
                    array_push($warehouse_qtys,$warehouse_qty['quantity']); 
                     if($warehouse_qty['quantity']< $row->portion9qty){
                     $j--;   
                    }
                   }
                    if($row->portion10){
                    $warehouse_id=$this->getProductsWHId($row->portion10);
                    $warehouse_qty=$this->getProductQuantity($row->portion10, $warehouse_id);
					//$warehouse_qty=$this->site->getproductqtybywarehouse($row->portion10, $warehouse_id);
                    $msgs.= $this->getProductByID($warehouse_qty['product_id'])->name."<br>";  
                    array_push($product_ids,$row->portion10); 
                    array_push($warehouse_ids,$warehouse_id);  
                    array_push($quantitys,$row->portion10qty); 
                    array_push($warehouse_qtys,$warehouse_qty['quantity']); 
                     if($warehouse_qty['quantity']< $row->portion10qty){
                     $j--;   
                    }
                   }
                   if($row->portion11){
                    $warehouse_id=$this->getProductsWHId($row->portion11);
                    $warehouse_qty=$this->getProductQuantity($row->portion11, $warehouse_id);
					//$warehouse_qty=$this->site->getproductqtybywarehouse($row->portion11, $warehouse_id);
                    $msgs.= $this->getProductByID($warehouse_qty['product_id'])->name."<br>";  
                    array_push($product_ids,$row->portion11); 
                    array_push($warehouse_ids,$warehouse_id);  
                    array_push($quantitys,$row->portion11qty); 
                    array_push($warehouse_qtys,$warehouse_qty['quantity']); 
                     if($warehouse_qty['quantity']< $row->portion11qty){
                     $j--;   
                    }
                   }
                               if($row->portion12){
                    $warehouse_id=$this->getProductsWHId($row->portion12);
                    $warehouse_qty=$this->getProductQuantity($row->portion12, $warehouse_id);
					//$warehouse_qty=$this->site->getproductqtybywarehouse($row->portion12, $warehouse_id);
                    $msgs.= $this->getProductByID($warehouse_qty['product_id'])->name."<br>";  
                    array_push($product_ids,$row->portion12); 
                    array_push($warehouse_ids,$warehouse_id);  
                    array_push($quantitys,$row->portion12qty); 
                    array_push($warehouse_qtys,$warehouse_qty['quantity']); 
                     if($warehouse_qty['quantity']< $row->portion12qty){
                     $j--;   
                    }
                   }
                    if($row->portion13){
                    $warehouse_id=$this->getProductsWHId($row->portion13);
                    $warehouse_qty=$this->getProductQuantity($row->portion13, $warehouse_id);
					//$warehouse_qty=$this->site->getproductqtybywarehouse($row->portion13, $warehouse_id);
                    $msgs.= $this->getProductByID($warehouse_qty['product_id'])->name."<br>";  
                    array_push($product_ids,$row->portion13); 
                    array_push($warehouse_ids,$warehouse_id);  
                    array_push($quantitys,$row->portion13qty); 
                    array_push($warehouse_qtys,$warehouse_qty['quantity']); 
                     if($warehouse_qty['quantity']< $row->portion13qty){
                     $j--;   
                    }
                   }
                }
                //die($j." ".count($product_ids));
       //     if($j<5){
//$this->session->set_flashdata('message', "<b style='color:red;'><b>Error! Not Enough Quantity in the Main Store for:</b><b>".
//$msgs."</b> in the Main Store Please Purchase");   
 //redirect("pos/");
 //return FALSE;
 //            die();
 //           }else{    
 //           for($i=0; $i<count($product_ids); $i++){
         
            
 //           $this->reduceQuantity($product_ids[$i], $warehouse_ids[$i], $quantitys[$i]);
             
        //     }
                
          //  }      
            
            
            

            
            return FALSE;
        }

    }
    
    public function getProductsWHId($product_id)
    {
       // $this->db->like('warehouse_id', $code, 'both')->order_by("code");
       $q = $this->db->get_where('warehouses_products', array('product_id' => $product_id), 1);
        if ($q->num_rows() > 0) {
            
            return $q->row()->warehouse_id;
        }

        return FALSE;
    }
     
    
    public function getProductByCode($code)
    {

        $q = $this->db->get_where('products', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getProductByName($name)
    {
        $q = $this->db->get_where('products', array('name' => $name), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getAllBillerCompanies()
    {
        $q = $this->db->get_where('companies', array('group_name' => 'biller'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getAllCustomerCompanies()
    {
        $q = $this->db->get_where('companies', array('group_name' => 'customer'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getCompanyByID($id)
    {

        $q = $this->db->get_where('companies', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

     public function getUserByID($id)
    {

        $q = $this->db->get_where('users', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getAllProducts()
    {
        $q = $this->db->query('SELECT * FROM products ORDER BY id');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getProductByID($id)
    {

        $q = $this->db->get_where('products', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getAllTaxRates()
    {
        $q = $this->db->get('tax_rates');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getTaxRateByID($id)
    {

        $q = $this->db->get_where('tax_rates', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function updateProductQuantity($product_id, $warehouse_id, $quantity)
    {

        if ($this->addQuantity($product_id, $warehouse_id, $quantity)) {
            return true;
        }

        return false;
    }

    public function addQuantity($product_id, $warehouse_id, $quantity)
    {
       //print_r($warehouse_quantity."_TUT");
       //die();
        if ($warehouse_quantity = $this->site->getproductqtybywarehouse($product_id, $warehouse_id)) {
            $new_quantity = $warehouse_quantity['quantity'] - $quantity;
            if ($this->updateQuantity($product_id, $warehouse_id, $new_quantity)) {
                $this->site->syncProductQty($product_id, $warehouse_id);
                return TRUE;
            }
        } else {
            if ($this->insertQuantity($product_id, $warehouse_id, -$quantity)) {
                $this->site->syncProductQty($product_id, $warehouse_id);
                return TRUE;
            }
        }
        return FALSE;
    }

    public function reduceQuantity($product_id, $warehouse_id, $quantity)
    {
        //print_r($this->getProductQuantity($product_id, $warehouse_id)['quantity']);
        $warehouse_quantity=$this->getProductQuantity($product_id, $warehouse_id);
       // print_r($warehouse_quantity);
		//echo $warehouse_quantity['quantity'];
     //  echo($quantity);
     
        if ($warehouse_quantity) {
            $new_quantity = $warehouse_quantity['quantity'] - $quantity;
           //  die($product_id);
            if ($this->updateQuantity($product_id, $warehouse_id, $new_quantity)) {
				//$this->db->update('products', array('quantity' => 'quantity'+ $new_quantity), array('id' => $product_id));
                //$this->site->syncProductQty($product_id, $warehouse_id);
                return TRUE;
            }
        } else {
            if ($this->insertQuantity($product_id, $warehouse_id, -$quantity)) {
                //$this->site->syncProductQty($product_id, $warehouse_id);
                return TRUE;
            }
        }
        return FALSE;
    }
    public function insertQuantity($product_id, $warehouse_id, $quantity)
    {
        if ($this->db->insert('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse_id, 'quantity' => $quantity))) {
            return true;
        }
        return false;
    }

    public function updateQuantity($product_id, $warehouse_id, $quantity)
    {
        if ($this->db->update('warehouses_products', array('quantity' => $quantity), array('product_id' => $product_id, 'warehouse_id' => $warehouse_id))) {
            return true;
        }
        return false;
    }
   
    public function getProductQuantity($product_id, $warehouse)
    {
        $q = $this->db->get_where('warehouses_products', array('product_id' => $product_id, 'warehouse_id' => $warehouse), 1);
        if ($q->num_rows() > 0) {
            return $q->row_array(); //$q->row();
        }
        return FALSE;
    }

    public function getItemByID($id)
    {
        $q = $this->db->get_where('sale_items', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllSales()
    {
        $q = $this->db->get('sales');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function sales_count()
    {
        return $this->db->count_all("sales");
    }

    public function fetch_sales($limit, $start)
    {
        $this->db->limit($limit, $start);
        $this->db->order_by("id", "desc");
        $query = $this->db->get("sales");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getAllInvoiceItems($sale_id)
    {
        $this->db->select('sale_items.*, tax_rates.code as tax_code, tax_rates.name as tax_name, tax_rates.rate as tax_rate, product_variants.name as variant')
            ->join('tax_rates', 'tax_rates.id=sale_items.tax_rate_id', 'left')
            ->join('product_variants', 'product_variants.id=sale_items.option_id', 'left')
            ->group_by('sale_items.id')
            ->order_by('id', 'asc');
        $q = $this->db->get_where('sale_items', array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	  public function getAllprdctsales($sdate,$tdate,$warehouseid)
    {
        $this->db->select('product_name, sma_sales.pmethod ,SUM(sma_sale_items.quantity) AS QTY')
            ->join('sale_items', 'sale_items.sale_id = sales.id', 'left')
			->join('products', 'products.id = sale_items.product_id')
			->where("date >= '$sdate' and date <= '$tdate'")
			->where("sma_sales.warehouse_id = '$warehouseid'")
			->where("pos ='1'")
            ->group_by('product_id')
            ->order_by('products.category_id', 'asc');
        $q = $this->db->get_where('sales');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	  public function getPaymntTypesales($sdate,$tdate)
    {
        $this->db->select('SUM(sma_payments.amount) amnt,paid_by')
            ->join('sales', 'payments.sale_id = sales.id')
			->where("sma_payments.date >= '$sdate' and sma_payments.date <= '$tdate'")
			->where("pos ='1'")
            ->group_by('paid_by');
           // ->order_by('products.category_id', 'asc');
        $q = $this->db->get_where('payments');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	public function getPrevPaymnt($sdate,$tdate)
    {
        $this->db->select('SUM(sma_reception_payments.amount) amnt')
            ->join('sales', 'reception_payments.sale_id = sales.id')
			->where("sma_reception_payments.date >= '$sdate' and sma_reception_payments.date <= '$tdate'");
			
            //->group_by('paid_by');
           // ->order_by('products.category_id', 'asc');
        $q = $this->db->get_where('reception_payments');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	public function getUpaidsales_departmentwise($sdate,$tdate,$department)
    {
		//CHECK DETAILS ON CATEGORIES TABLE
		if($department=="Bar"){
			$filter = "";
		}else if($department=="Rest") {
			$filter = "and sma_products.category_id = '57'";
		}else{
			$filter = "and sma_products.category_id = '53'";
		}
$q = $this->db->query("SELECT SUM(amount) AS amnt,paid_by FROM 
(SELECT sma_sales.total as amount,'' as paid_by 
FROM sma_sales 
LEFT JOIN sma_sale_items ON sma_sales.id = sma_sale_items.sale_id 
LEFT JOIN sma_products ON sma_products.id = sma_sale_items.product_id 
WHERE sma_sales.date >= '$sdate' and sma_sales.date <= '$tdate'
$filter AND pos = '1' AND sma_sales.pmethod =''  GROUP BY sma_sales.id )as t7 GROUP BY t7.paid_by ");

			
			
		
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	public function getPaymntTypesales_departmentwise($sdate,$tdate,$department)
    {
		//CHECK DETAILS ON CATEGORIES TABLE
		if($department=="Bar"){
			$filter = "";
		}else if($department=="Rest") {
			$filter = "and sma_products.category_id = '57'";
		}else{
			$filter = "and sma_products.category_id = '53'";
		}
$q = $this->db->query("SELECT SUM(amount) AS amnt,paid_by FROM 
(SELECT IF(sma_products.category_id = '53',sma_sale_items.subtotal,sma_payments.amount) as amount,sma_payments.paid_by 
FROM sma_sales 
JOIN sma_payments ON sma_payments.sale_id = sma_sales.id
LEFT JOIN sma_sale_items ON sma_sales.id = sma_sale_items.sale_id 
LEFT JOIN sma_products ON sma_products.id = sma_sale_items.product_id 
WHERE sma_payments.date >= '$sdate' and sma_payments.date <= '$tdate'
$filter AND pos = '1' GROUP BY sma_payments.id )as t7 GROUP BY t7.paid_by ");

			
			
		
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
		public function getcombinedtable_bill($tableid,$user_id,$sdate,$edate)
    {
		//CHECK DETAILS ON CATEGORIES TABLE
		if($department=="Bar"){
			$filter = "and sma_products.category_id <> '57'  ";
		}else if($department=="Rest") {
			$filter = "and sma_products.category_id = '57'";
		}else{
			$filter = "and sma_products.category_id = '53'";
		}
$q = $this->db->query("SELECT * FROM `sma_sales` 
LEFT JOIN sma_sale_items ON sma_sales.id = sma_sale_items.sale_id 
WHERE sma_sales.date <= '$edate' 
AND sma_sales.table_id = '$tableid' AND sma_sales.created_by = '$user_id' AND sma_sales.payment_status <> 'paid'  ");

			
			
		
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
			public function getunique_billno($tableid,$user_id,$sdate,$edate)
    {
		//CHECK DETAILS ON CATEGORIES TABLE
		if($department=="Bar"){
			$filter = "and sma_products.category_id <> '57' AND sma_products.category_id > '53' ";
		}else if($department=="Rest") {
			$filter = "and sma_products.category_id = '57'";
		}else{
			$filter = "and sma_products.category_id = '53'";
		}
$q = $this->db->query("SELECT sma_sales.id FROM `sma_sales` 
LEFT JOIN sma_sale_items ON sma_sales.id = sma_sale_items.sale_id 
WHERE sma_sales.date <= '$edate' 
AND sma_sales.table_id = '$tableid' AND sma_sales.created_by = '$user_id' AND sma_sales.payment_status <> 'paid' GROUP BY sma_sales.id ");

			
			
		
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
    public function getSuspendedSaleItems($id)
    {
        $q = $this->db->get_where('suspended_items', array('suspend_id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getSuspendedSales($user_id = NULL)
    {
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $q = $this->db->get_where('suspended_bills', array('created_by' => $user_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }


    public function getOpenBillByID($id)
    {

        $q = $this->db->get_where('suspended_bills', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function getInvoiceByID($id)
    {

        $q = $this->db->get_where('sales', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }

        return FALSE;
    }

    public function bills_count()
    {
        if (!$this->Owner && !$this->Admin) {
            $this->db->where('created_by', $this->session->userdata('user_id'));
        }
        return $this->db->count_all_results("suspended_bills");
    }

    public function fetch_bills($limit, $start)
    {
        if (!$this->Owner && !$this->Admin) {
            $this->db->where('created_by', $this->session->userdata('user_id'));
        }
        $this->db->limit($limit, $start);
        $this->db->order_by("id", "asc");
        $query = $this->db->get("suspended_bills");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getTodaySales()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('payments.date >', $date);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getCosting()
    {
        $date = date('Y-m-d');
        $this->db->select('SUM( COALESCE( purchase_unit_cost, 0 ) * quantity ) AS cost, SUM( COALESCE( sale_unit_price, 0 ) * quantity ) AS sales, SUM( COALESCE( purchase_net_unit_cost, 0 ) * quantity ) AS net_cost, SUM( COALESCE( sale_net_unit_price, 0 ) * quantity ) AS net_sales', FALSE)
            ->where('date', $date);

        $q = $this->db->get('costing');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTodayCCSales()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cc_slips, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('payments.date >', $date)->where('paid_by', 'CC');

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTodayCashSales()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('payments.date >', $date)->where('paid_by', 'cash');

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTodayRefunds()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS returned', FALSE)
            ->join('return_sales', 'return_sales.id=payments.return_id', 'left')
            ->where('type', 'returned')->where('payments.date >', $date);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTodayExpenses()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('SUM( COALESCE( amount, 0 ) ) AS total', FALSE)
            ->where('date >', $date);

        $q = $this->db->get('expenses');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTodayCashRefunds()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS returned', FALSE)
            ->join('return_sales', 'return_sales.id=payments.return_id', 'left')
            ->where('type', 'returned')->where('payments.date >', $date)->where('paid_by', 'cash');

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTodayChSales()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cheques, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('payments.date >', $date)->where('paid_by', 'Cheque');

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
    public function getTodayMPESASales()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cheques, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('payments.date >', $date)->where('paid_by', 'mpesa');

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
        public function getTodayCostCenterSales()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cheques, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('payments.date >', $date)->where('paid_by', 'costcenter');

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
    public function getTodayPPPSales()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cheques, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('payments.date >', $date)->where('paid_by', 'ppp');

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getTodayStripeSales()
    {
        $date = date('Y-m-d 00:00:00');
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cheques, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('payments.date >', $date)->where('paid_by', 'stripe');

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('payments.date >', $date);
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }


    public function getRegisterCCSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cc_slips, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('payments.date >', $date)->where('paid_by', 'CC');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterCashSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('payments.date >', $date)->where('paid_by', 'cash');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterRefunds($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS returned', FALSE)
            ->join('return_sales', 'return_sales.id=payments.return_id', 'left')
            ->where('type', 'returned')->where('payments.date >', $date);
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterCashRefunds($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS returned', FALSE)
            ->join('return_sales', 'return_sales.id=payments.return_id', 'left')
            ->where('type', 'returned')->where('payments.date >', $date)->where('paid_by', 'cash');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterExpenses($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('SUM( COALESCE( amount, 0 ) ) AS total', FALSE)
            ->where('date >', $date);
        $this->db->where('created_by', $user_id);

        $q = $this->db->get('expenses');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterChSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cheques, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('payments.date >', $date)->where('paid_by', 'Cheque');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
 public function getRegisterMPESASales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cheques, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('payments.date >', $date)->where('paid_by', 'mpesa');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
     public function getRegisterCostCenterSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cheques, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('payments.date >', $date)->where('paid_by', 'costcenter');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }
    public function getRegisterPPPSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cheques, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('payments.date >', $date)->where('paid_by', 'ppp');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getRegisterStripeSales($date, $user_id = NULL)
    {
        if (!$date) {
            $date = $this->session->userdata('register_open_time');
        }
        if (!$user_id) {
            $user_id = $this->session->userdata('user_id');
        }
        $this->db->select('COUNT(' . $this->db->dbprefix('payments') . '.id) as total_cheques, SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS paid', FALSE)
            ->join('sales', 'sales.id=payments.sale_id', 'left')
            ->where('type', 'received')->where('payments.date >', $date)->where('paid_by', 'stripe');
        $this->db->where('payments.created_by', $user_id);

        $q = $this->db->get('payments');
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return false;
    }

    public function getDailySales($year, $month)
    {

        $myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date, SUM( COALESCE( total, 0 ) ) AS total
        FROM " . $this->db->dbprefix('sales') . "
        WHERE DATE_FORMAT( date,  '%Y-%m' ) =  '{$year}-{$month}'
        GROUP BY DATE_FORMAT( date,  '%e' )";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function getMonthlySales($year)
    {

        $myQuery = "SELECT DATE_FORMAT( date,  '%c' ) AS date, SUM( COALESCE( total, 0 ) ) AS total
        FROM " . $this->db->dbprefix('sales') . "
        WHERE DATE_FORMAT( date,  '%Y' ) =  '{$year}'
        GROUP BY date_format( date, '%c' ) ORDER BY date_format( date, '%c' ) ASC";
        $q = $this->db->query($myQuery, false);
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }
    }

    public function suspendSale($data = array(), $items = array(), $did = NULL)
    {
        $sData = array(
            'count' => $data['total_items'],
            'biller_id' => $data['biller_id'],
            'customer_id' => $data['customer_id'],
            'warehouse_id' => $data['warehouse_id'],
            'customer' => $data['customer'],
            'date' => $data['date'],
            'suspend_note' => $data['suspend_note'],
            'total' => $data['grand_total'],
            'order_tax_id' => $data['order_tax_id'],
            'order_discount_id' => $data['order_discount_id'],
            'created_by' => $this->session->userdata('user_id')
        );

        if ($did) {

            if ($this->db->update('suspended_bills', $sData, array('id' => $did)) && $this->db->delete('suspended_items', array('suspend_id' => $did))) {
                $addOn = array('suspend_id' => $did);
                end($addOn);
                foreach ($items as &$var) {
                    $var = array_merge($addOn, $var);
                }
                if ($this->db->insert_batch('suspended_items', $items)) {
                    return TRUE;
                }
            }

        } else {

            if ($this->db->insert('suspended_bills', $sData)) {
                $suspend_id = $this->db->insert_id();
                $addOn = array('suspend_id' => $suspend_id);
                end($addOn);
                foreach ($items as &$var) {
                    $var = array_merge($addOn, $var);
                }
                if ($this->db->insert_batch('suspended_items', $items)) {
                    return TRUE;
                }
            }

        }
        return FALSE;
    }

    public function deleteBill($id)
    {

        if ($this->db->delete('suspended_items', array('suspend_id' => $id)) && $this->db->delete('suspended_bills', array('id' => $id))) {
            return true;
        }

        return FALSE;
    }

    public function getSubCategoriesByCategoryID($category_id)
    {
        $this->db->order_by('name');
        $q = $this->db->get_where("subcategories", array('category_id' => $category_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
    }

    public function getInvoicePayments($sale_id)
    {
        $q = $this->db->get_where("payments", array('sale_id' => $sale_id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }

            return $data;
        }

        return FALSE;
    }

    function stripe($amount = 0, $card_info = array(), $desc = '')
    {
        $this->load->model('stripe_payments');
        //$card_info = array( "number" => "4242424242424242", "exp_month" => 1, "exp_year" => 2016, "cvc" => "314" );
        //$amount = $amount ? $amount*100 : 3000;
        $amount = $amount * 100;
        if ($amount && !empty($card_info)) {
            $token_info = $this->stripe_payments->create_card_token($card_info);
            if (!isset($token_info['error'])) {
                $token = $token_info->id;
                $data = $this->stripe_payments->insert($token, $desc, $amount, $this->default_currency->code);
                if (!isset($data['error'])) {
                    $result = array('transaction_id' => $data->id,
                        'created_at' => date($this->dateFormats['php_ldate'], $data->created),
                        'amount' => ($data->amount / 100),
                        'currency' => strtoupper($data->currency)
                    );
                    return $result;
                } else {
                    return $data;
                }
            } else {
                return $token_info;
            }
        }
        return false;
    }

    function paypal($amount = NULL, $card_info = array(), $desc = '')
    {
        $this->load->model('paypal_payments');
        //$card_info = array( "number" => "5522340006063638", "exp_month" => 2, "exp_year" => 2016, "cvc" => "456", 'type' => 'MasterCard' );
        //$amount = $amount ? $amount : 30.00;
        if ($amount && !empty($card_info)) {
            $data = $this->paypal_payments->Do_direct_payment($amount, $this->default_currency->code, $card_info, $desc);
            if (!isset($data['error'])) {
                $result = array('transaction_id' => $data['TRANSACTIONID'],
                    'created_at' => date($this->dateFormats['php_ldate'], strtotime($data['TIMESTAMP'])),
                    'amount' => $data['AMT'],
                    'currency' => strtoupper($data['CURRENCYCODE'])
                );
                return $result;
            } else {
                return $data;
            }
        }
        return false;
    }

    public function addPayment($payment = array())
    {
        //print_r($payment); die();
        if (isset($payment['sale_id']) && isset($payment['paid_by']) && isset($payment['amount'])) {
            $payment['pos_paid'] = $payment['amount'];
            $inv = $this->getInvoiceByID($payment['sale_id']);
            $paid = $inv->paid + $payment['amount'];
            if ($payment['paid_by'] == 'ppp') {
                $card_info = array("number" => $payment['cc_no'], "exp_month" => $payment['cc_month'], "exp_year" => $payment['cc_year'], "cvc" => $payment['cc_cvv2'], 'type' => $payment['cc_type']);
                $result = $this->paypal($payment['amount'], $card_info);
                if (!isset($result['error'])) {
                    $payment['transaction_id'] = $result['transaction_id'];
                    $payment['date'] = $this->sma->fld($result['created_at']);
                    $payment['amount'] = $result['amount'];
                    $payment['currency'] = $result['currency'];
                    unset($payment['cc_cvv2']);
                    $this->db->insert('payments', $payment);
                     $this->updatePayment($payment);
                    $paid += $payment['amount'];
                } else {
                    $msg[] = lang('payment_failed');
                    if (!empty($result['message'])) {
                        foreach ($result['message'] as $m) {
                            $msg[] = '<p class="text-danger">' . $m['L_ERRORCODE'] . ': ' . $m['L_LONGMESSAGE'] . '</p>';
                        }
                    } else {
                        $msg[] = lang('paypal_empty_error');
                    }
                }
            } elseif ($payment['paid_by'] == 'stripe') {
                $card_info = array("number" => $payment['cc_no'], "exp_month" => $payment['cc_month'], "exp_year" => $payment['cc_year'], "cvc" => $payment['cc_cvv2'], 'type' => $payment['cc_type']);
                $result = $this->stripe($payment['amount'], $card_info);
                if (!isset($result['error'])) {
                    $payment['transaction_id'] = $result['transaction_id'];
                    $payment['date'] = $this->sma->fld($result['created_at']);
                    $payment['amount'] = $result['amount'];
                    $payment['currency'] = $result['currency'];
                    unset($payment['cc_cvv2']);
                    $this->db->insert('payments', $payment);
                    $this->updatePayment($payment);
                    $paid += $payment['amount'];
                } else {
                    $msg[] = lang('payment_failed');
                    $msg[] = '<p class="text-danger">' . $result['code'] . ': ' . $result['message'] . '</p>';
                }
            } else {
                if ($payment['paid_by'] == 'gift_card') {
                    $gc = $this->site->getGiftCardByNO($payment['cc_no']);
                    $this->db->update('gift_cards', array('balance' => ($gc->balance - $payment['amount'])), array('card_no' => $payment['cc_no']));
                }
                unset($payment['cc_cvv2']);
                $this->db->insert('payments', $payment);
                $this->updatePayment($payment);
                $paid += $payment['amount'];
            }
            if (!isset($msg)) {
                if ($this->site->getReference('pay') == $data['reference_no']) {
                    $this->site->updateReference('pay');
                }
              //  $this->site->syncSalePayments($payment['sale_id']);
                return array('status' => 1, 'msg' => '','sale_id'=>$payment['sale_id']);
            }
            return array('status' => 0, 'msg' => $msg,'sale_id'=>$payment['sale_id']);

        }
        return false;
    }
	    public function addReceptionPayment($payment = array())
    {
        //print_r($payment); die();
        if (isset($payment['sale_id']) && isset($payment['paid_by']) && isset($payment['amount'])) {
            $payment['pos_paid'] = $payment['amount'];
            $inv = $this->getInvoiceByID($payment['sale_id']);
            $paid = $inv->paid + $payment['amount'];
            if ($payment['paid_by'] == 'ppp') {
                $card_info = array("number" => $payment['cc_no'], "exp_month" => $payment['cc_month'], "exp_year" => $payment['cc_year'], "cvc" => $payment['cc_cvv2'], 'type' => $payment['cc_type']);
                $result = $this->paypal($payment['amount'], $card_info);
                if (!isset($result['error'])) {
                    $payment['transaction_id'] = $result['transaction_id'];
                    $payment['date'] = $this->sma->fld($result['created_at']);
                    $payment['amount'] = $result['amount'];
                    $payment['currency'] = $result['currency'];
                    unset($payment['cc_cvv2']);
                    $this->db->insert('reception_payments', $payment);
                     $this->updateReceptionPayment($payment);
                    $paid += $payment['amount'];
                } else {
                    $msg[] = lang('payment_failed');
                    if (!empty($result['message'])) {
                        foreach ($result['message'] as $m) {
                            $msg[] = '<p class="text-danger">' . $m['L_ERRORCODE'] . ': ' . $m['L_LONGMESSAGE'] . '</p>';
                        }
                    } else {
                        $msg[] = lang('paypal_empty_error');
                    }
                }
            } elseif ($payment['paid_by'] == 'stripe') {
                $card_info = array("number" => $payment['cc_no'], "exp_month" => $payment['cc_month'], "exp_year" => $payment['cc_year'], "cvc" => $payment['cc_cvv2'], 'type' => $payment['cc_type']);
                $result = $this->stripe($payment['amount'], $card_info);
                if (!isset($result['error'])) {
                    $payment['transaction_id'] = $result['transaction_id'];
                    $payment['date'] = $this->sma->fld($result['created_at']);
                    $payment['amount'] = $result['amount'];
                    $payment['currency'] = $result['currency'];
                    unset($payment['cc_cvv2']);
                    $this->db->insert('reception_payments', $payment);
                    $this->updateReceptionPayment($payment);
                    $paid += $payment['amount'];
                } else {
                    $msg[] = lang('payment_failed');
                    $msg[] = '<p class="text-danger">' . $result['code'] . ': ' . $result['message'] . '</p>';
                }
            } else {
                if ($payment['paid_by'] == 'gift_card') {
                    $gc = $this->site->getGiftCardByNO($payment['cc_no']);
                    $this->db->update('gift_cards', array('balance' => ($gc->balance - $payment['amount'])), array('card_no' => $payment['cc_no']));
                }
                unset($payment['cc_cvv2']);
                $this->db->insert('reception_payments', $payment);
                $this->updateReceptionPayment($payment);
                $paid += $payment['amount'];
            }
            if (!isset($msg)) {
                if ($this->site->getReference('pay') == $data['reference_no']) {
                    $this->site->updateReference('pay');
                }
              //  $this->site->syncSalePayments($payment['sale_id']);
                return array('status' => 1, 'msg' => '','sale_id'=>$payment['sale_id']);
            }
            return array('status' => 0, 'msg' => $msg,'sale_id'=>$payment['sale_id']);

        }
        return false;
    }
        public function updateroomId($payment = array(),$custid)
    {
        //print_r($payment); die();
        if (isset($payment['sale_id']) && isset($payment['paid_by']) && isset($payment['amount'])) {
            
            if ($payment['paid_by'] == 'rooms') {
                             // $this->db->insert('payments', $payment);
                     $this->updateRoomPayment($payment,$custid);
                    $paid += $payment['amount'];
               
            } 
            if (!isset($msg)) {
                if ($this->site->getReference('pay') == $data['reference_no']) {
                    $this->site->updateReference('pay');
                }
              //  $this->site->syncSalePayments($payment['sale_id']);
                return array('status' => 1, 'msg' => '','sale_id'=>$payment['sale_id']);
            }
            return array('status' => 0, 'msg' => $msg,'sale_id'=>$payment['sale_id']);

        }
        return false;
    }
	   function updateRoomPayment($payment = array(),$custid)
    {
      // print_r($payment);  
       //die();
       if (isset($payment['sale_id']) && isset($payment['paid_by']) && isset($payment['amount'])) {
          
             
              $q = $this->db->get_where('sales', array('id' => $payment['sale_id']), 1);
            
        if ($q->num_rows() > 0) {
            $status="";
            //get total amount
              $amount=$q->row()->paid + $payment['amount'];
             if($q->row()->grand_total <=$amount){
            $status="paid";
             }else if($q->row()->grand_total > $amount){
                $status="due";
             }else if($q->row()->grand_total > $amount){
                 $status="partial";
             }
      

                    $q=$this->db->query('UPDATE sma_sales SET payment_status = "paid" ,room_id ="'.$payment['room_id'].'", pmethod="'.$payment['paid_by'].'",customer_id="'.$custid.'" WHERE id='.$payment['sale_id']);
           

        } 
        
             
             
             
       }
      return FALSE;
    }
function updateReceptionPayment($payment = array())
    {
      // print_r($payment);  
       //die();
       if (isset($payment['sale_id']) && isset($payment['paid_by']) && isset($payment['amount'])) {
          
             
              $q = $this->db->get_where('sales', array('id' => $payment['sale_id']), 1);
            
        if ($q->num_rows() > 0) {
            $status="";
            //get total amount
              $bal_amount= $q->row()->grand_total - $q->row()->paid;
			  // $payment['amount']
             if( $payment['amount'] >= $bal_amount){
            $status="paid";
			$q=$this->db->query('UPDATE sma_sales SET cleared="1" , paid = paid + '.$payment['amount'].'
                             WHERE id='.$payment['sale_id']);
             }else if($payment['amount'] <$bal_amount){
                $status="due";
				$q=$this->db->query('UPDATE sma_sales SET  paid = paid + '.$payment['amount'].'
                             WHERE id='.$payment['sale_id']);
             }else if($q->row()->grand_total > $amount){
                 $status="partial";
             }
					

        } 
        
             
             
             
       }
      return FALSE;
    }
   function updatePayment($payment = array())
    {
      // print_r($payment);  
       //die();
       if (isset($payment['sale_id']) && isset($payment['paid_by']) && isset($payment['amount'])) {
          
             
              $q = $this->db->get_where('sales', array('id' => $payment['sale_id']), 1);
            
        if ($q->num_rows() > 0) {
            $status="";
            //get total amount
              $amount=$q->row()->paid + $payment['amount'];
             if($q->row()->grand_total <=$amount){
            $status="paid";
             }else if($q->row()->grand_total > $amount){
                $status="due";
             }else if($q->row()->grand_total > $amount){
                 $status="partial";
             }
      
					if($payment['paid_by']=='nonchrg'){
						$q=$this->db->query('UPDATE sma_sales SET  bill_change= "0", payment_status="'.$status.'", total ="0",grand_total = "0",pmethod="'.$payment['paid_by'].'",
                              paid="0" WHERE id='.$payment['sale_id']);
           
					} else if($payment['paid_by']=='costcenter'){
					$q=$this->db->query('UPDATE sma_sales SET bill_change="0", payment_status="'.$status.'",pmethod="'.$payment['paid_by'].'",
                              paid="0" WHERE id='.$payment['sale_id']);	
					}
					else {
                    $q=$this->db->query('UPDATE sma_sales SET bill_change='
                            .$payment['bill_change'].', payment_status="'.$status.'",pmethod="'.$payment['paid_by'].'",
                              paid='.$amount.' WHERE id='.$payment['sale_id']);
					}

        } 
        
             
             
             
       }
      return FALSE;
    }
	 function updatePrintSale($sale)
    {
      if (isset($sale)) {
                    $q=$this->db->query('UPDATE sma_sales SET printed ="1" WHERE id='.$sale);
		
	  }
      return FALSE;
    }  
	function combineBill($payment = array())
    {
      // print_r($payment);  
       //die();
       if (isset($payment['sale_id']) && isset($payment['bill_no'])) {
          
             
              $q = $this->db->get_where('sales', array('id' => $payment['sale_id']), 1);
            
        if ($q->num_rows() > 0) {
            $status="";
            //get total amount
			$sale_id = $payment['bill_no'];
            $total = $q->row()->total;
			$product_discount = $q->row()->product_discount;
			$totaltax = $q->row()->total_tax;
			$grandtotal = $q->row()->grand_total;
			$totalitems = $q->row()->total_items;

                    $q1=$this->db->query('UPDATE sma_sale_items SET sale_id ='
                            .$sale_id.' WHERE sale_id='.$payment['sale_id']);
					$q12=$this->db->query('UPDATE sma_sales SET total = total+'.$total.', product_discount = product_discount + '.$product_discount.' , 
											total_tax = total_tax + '.$totaltax.' , grand_total = grand_total + '.$grandtotal.', total_items = total_items + '.$totalitems.'
								WHERE id='.$sale_id);
					
				$this->db->query('DElETE FROM sma_sales WHERE id ='.$payment['sale_id']);
								
								
           return array('status' => 1, 'msg' => '','sale_id'=>$sale_id);

        } else{
			return array('status' => 0, 'msg' => $msg,'sale_id'=>$sale_id);
		}

       }
     // return FALSE;
    }
	
}
