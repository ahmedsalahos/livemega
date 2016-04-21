<?php
class Liveshop {


	public function __construct($registry) {
	  $this->db = $registry->get('db');
		$this->config = $registry->get('config');
	}

  /*
   * get unique reference number that not exist in session_purchase_details table
   * Author: Hamdy
   */
  public function getUniqueReferrenceNumber() {
    $referrence_number = time() * rand(1, 100);
 $rs = $this->db->query("SELECT transaction_uid FROM ".DB_PREFIX."session_purchase_details WHERE transaction_uid = '".$referrence_number."'");
    if ($rs->num_rows == 0) {
      return $referrence_number;
    } else {
      return $this->getUniqueReferrenceNumber();
    }

  }

    public function verifyLiveFreeUserBalance($username,$total_cart_price) {
	    $url = USER_BALANCE_CHECK;
	    $result = "";
	    $options["connection_timeout"] = 25;
	    $options["location"] = $url;
	    $options['trace'] = true;
	    $options['exceptions'] = true;
	    $options['cache_wsdl'] = WSDL_CACHE_NONE;
	    //$username = "liv1909518";
	    //$password = "pass000";
	    try {
		    $client = new SoapClient($url,$options);
		    $res = $client->verifyLiveFreeUserBalance($username,$total_cart_price);
	    } catch (Exception $e) { 
		    $result = "false";
	    }
	    if(is_soap_fault($res)) {
		    $result = "false";
	    } else {
		    $result = json_decode($res);
	    }
	    return $result;
    }  

  /**
   * insert session data into oc_sesssion purchase detials
   */
  public function insert_session_data($method=''){
    $transaction_uid = $this->getUniqueReferrenceNumber();
    $session_data = serialize($this->session->data);
    $payment_method = $method;
    $date_of_transaction = date("Y-m-d H:i:s");
    $this->db->query("INSERT INTO `" . DB_PREFIX . "session_purchase_details` SET transaction_uid = '" . $transaction_uid . "', session_data = '" . $session_data . "',date_of_transaction='".$date_of_transaction."',payment_method='".$payment_method."',order_id='".$this->session->data['order_id']."'");
  
  }
 
  public function update_session_data($status = '',$order_id){

  $this->db->query("UPDATE `" . DB_PREFIX . "session_purchase_details` SET transaction_status='".$status."' WHERE order_id = '".$order_id."'");

 
  } 
  public function get_egypt_currency_rate(){
    $egp_rate = $this->db->query("SELECT * FROM ".DB_PREFIX."currency WHERE code = 'EGP'");
    $row = $egp_rate->rows; 
    return $row[0]['value'];  
  }
  public function get_usd_currency_rate(){
    $egp_rate = $this->db->query("SELECT * FROM ".DB_PREFIX."currency WHERE code = 'USD'");
    $row = $egp_rate->rows;
    return $row[0]['value'];
  }
    public function deductLiveFreeUserBalance($amount,$user_name,$security_answer,$security_word,$pin_code,$order_id) {
	    $url = USER_BALANCE_DEDUCT;
	    $result = "";
	    $options["connection_timeout"] = 25;
	    $options["location"] = $url;
	    $options['trace'] = true;
	    $options['exceptions'] = true;
	    $options['cache_wsdl'] = WSDL_CACHE_NONE;
	    //$username = "liv1909518";
	    //$password = "pass000";
	    try {
		    $client = new SoapClient($url,$options);
		    $res = $client->deductLiveFreeUserBalance($amount,$user_name,$security_answer,$security_word,$pin_code,$order_id);
	    } catch (Exception $e) { 
		    $result = "false";
	    }
	    if(is_soap_fault($res)) {
		    $result = "false";
	    } else {
		    $result = json_decode($res);
	    }
	    return $result;
    }  
    public function get_product_commission($product_id) {
        $commission = 0;
        $commission_rate = $this->db->query("SELECT users_commission FROM ".DB_PREFIX."product_commission WHERE product_id = $product_id");
        if($commission_rate) {
            $row = $commission_rate->rows; 
            $commission = $row[0]['users_commission'];
        }
        return  $commission;
    }
    public function check_user_points($username, $cart_total_points) {
	    $url = CHECK_USER_POINTS;
	    $result = "";
	    $options["connection_timeout"] = 25;
	    $options["location"] = $url;
	    $options['trace'] = true;
	    $options['exceptions'] = true;
	    $options['cache_wsdl'] = WSDL_CACHE_NONE;
	    //$username = "liv1909518";
	    //$password = "pass000";
	    try {
		    $client = new SoapClient($url,$options);
		    $res = $client->checkUserPoints($username, $cart_total_points);
	    } catch (Exception $e) { 
		    $result = "false";
	    }
	    if(is_soap_fault($res)) {
		    $result = "false";
	    } else {
		    $result = json_decode($res);
	    }
	    return $result;
    }
    public function deduct_user_points($username, $cart_total_points) {
	    $url = DEDUCT_USER_POINTS;
	    $result = "";
	    $options["connection_timeout"] = 25;
	    $options["location"] = $url;
	    $options['trace'] = true;
	    $options['exceptions'] = true;
	    $options['cache_wsdl'] = WSDL_CACHE_NONE;
	    //$username = "liv1909518";
	    //$password = "pass000";
	    try {
		    $client = new SoapClient($url,$options);
		    $res = $client->deductLiveFreeUserPoints($username, $cart_total_points);
	    } catch (Exception $e) { 
		    $result = "false";
	    }
	    if(is_soap_fault($res)) {
		    $result = "false";
	    } else {
		    $result = json_decode($res);
	    }
	    return $result;
    }
    public function get_user_points($username) {
	    $url = GET_USER_POINTS;
	    $result = "";
	    $options["connection_timeout"] = 25;
	    $options["location"] = $url;
	    $options['trace'] = true;
	    $options['exceptions'] = true;
	    $options['cache_wsdl'] = WSDL_CACHE_NONE;
	    //$username = "liv1909518";
	    //$password = "pass000";
	    try {
		    $client = new SoapClient($url,$options);
		    $res = $client->getUserPoints($username);
	    } catch (Exception $e) { 
		    $result = "false";
	    }
	    if(is_soap_fault($res)) {
		    $result = "false";
	    } else {
		    $result = json_decode($res);
	    }
	    return $result;
    }
    
  /*
   * get session data
   * Author: Marwa
   */
  public function getSessionData($order_id) {
        $rs = $this->db->query("SELECT session_data FROM ".DB_PREFIX."session_purchase_details WHERE order_id = '".$order_id."'");
        $row = $rs->rows; 
        return unserialize($row[0]['session_data']); 

  }
  public function createExpectedCommissionTable($order_id) {
	    $url = CREATE_EXPECTED_COMMISSION_TABLE;
	    $result = "";
	    $options["connection_timeout"] = 25;
	    $options["location"] = $url;
	    $options['trace'] = true;
	    $options['exceptions'] = true;
	    $options['cache_wsdl'] = WSDL_CACHE_NONE;
	    //$username = "liv1909518";
	    //$password = "pass000";
	    try {
		    $client = new SoapClient($url,$options);
		    $res = $client->createExpectedCommissionTable($order_id);
	    } catch (Exception $e) { 
		    $result = "false";
	    }
	    if(is_soap_fault($res)) {
		    $result = "false";
	    } else {
		    $result = json_decode($res);
	    }
	    return $result;  
  }
}
