<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Mpesa_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }
    
    public function confirm($checkoutRequestID)
    {
        $q = $this->db->get_where('mpesa', array('checkoutRequestID' => $checkoutRequestID));
        if ($q->num_rows() > 0) {
            return array("success"=>"1", "message"=>"Payment Successful", "response"=>$q->row());
        }else {
           return array("success"=>"0", "message"=>"Payment not successful"); 
        }
    }
    
     public function insert($callbackData)
    {
       
        $TransactionType = $callbackData->TransactionType;
        
        $TransID = $callbackData->TransID;
        
        $TransTime = $callbackData->TransTime;
        
        $TransAmount = $callbackData->TransAmount;
    
        $BusinessShortCode = $callbackData->BusinessShortCode;
        
        $BillRefNumber = $callbackData->BillRefNumber;
        
        $InvoiceNumber = $callbackData->InvoiceNumber;
    
        $OrgAccountBalance = $callbackData->OrgAccountBalance;
		$ThirdPartyTransID = $callbackData->ThirdPartyTransID;
		$MSISDN = $callbackData->MSISDN;
		$FirstName = $callbackData->FirstName;
            
        $data = array(
            'transactionType' => $TransactionType,
            'transID' => $TransID,
            'transTime' => $TransTime,
            'transAmount' => $TransAmount,
            'businessShortCode' => $BusinessShortCode,
            'billRefNumber' => $BillRefNumber,
            'invoiceNumber' => $InvoiceNumber,
            'orgAccountBalance' => $OrgAccountBalance,
			'thirdPartyTransID' => $ThirdPartyTransID,
			'MSISDN' => $MSISDN,
			'firstName' => $FirstName
        );
      $this->db->insert('mpesa', $data);
    }
}