<?php
class ControllerPaymentPayAtStore extends Controller {
  private $error = array();
  
	public function index() {
	  $data = array();
	  $this->load->language('payment/pay_at_store');
	  
		$data['text_pay_at_store'] = $this->language->get('text_pay_at_store');
	  $data['text_wait'] = $this->language->get('text_wait');
	  
	  $data['entry_pas_first_name'] = $this->language->get('entry_pas_first_name');
	  $data['entry_pas_last_name'] = $this->language->get('entry_pas_last_name');
	  $data['entry_pas_email'] = $this->language->get('entry_pas_email');
	  $data['entry_pas_phone'] = $this->language->get('entry_pas_phone');
	  $data['entry_pas_address'] = $this->language->get('entry_pas_address');
	  $data['entry_pas_country'] = $this->language->get('entry_pas_country');
	  $data['entry_pas_state'] = $this->language->get('entry_pas_state');
	  $data['entry_pas_city'] = $this->language->get('entry_pas_city');
	  $data['entry_pas_post_code'] = $this->language->get('entry_pas_post_code');
	  $data['entry_kindly_phone_number'] = $this->language->get('text_kindly_phone_number');
	   
	  
	  $data['button_confirm'] = $this->language->get('button_confirm');

		//to get order info
		$this->load->model('checkout/order');
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		//print_r($order_info);
		
		$data['x_order_id'] = $order_info['order_id'];
		$data['x_login'] = $this->config->get('pay_at_store_login');
		$data['x_tran_key'] = $this->config->get('pay_at_store_key');
		$data['x_version'] = '3.1';
		$data['x_delim_data'] = 'true';
		$data['x_delim_char'] = '|';
		$data['x_encap_char'] = '"';
		$data['x_relay_response'] = 'false';
		$data['x_first_name'] = html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');
		$data['x_last_name'] = html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
		$data['x_company'] = html_entity_decode($order_info['payment_company'], ENT_QUOTES, 'UTF-8');
		$data['x_address'] = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');
		$data['x_city'] = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');
		$data['x_state'] = html_entity_decode($order_info['payment_zone'], ENT_QUOTES, 'UTF-8');
		$data['x_zip'] = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
		$data['x_country'] = html_entity_decode($order_info['payment_country'], ENT_QUOTES, 'UTF-8');
		$data['x_phone'] = $order_info['telephone'];
		$data['x_customer_ip'] = $this->request->server['REMOTE_ADDR'];
		$data['x_email'] = $order_info['email'];
		$data['x_description'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');
		$data['x_amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], 1.00000, false);
		$data['x_currency_code'] = $this->currency->getCode();
		$data['x_currency_rate'] = $this->currency->getValue();
		$data['x_method'] = 'CC';
		$data['x_type'] = ($this->config->get('pay_at_store_method') == 'capture') ? 'AUTH_CAPTURE' : 'AUTH_ONLY';
		$data['x_card_num'] = str_replace(' ', '', $this->request->post['cc_number']);
		$data['x_exp_date'] = $this->request->post['cc_expire_date_month'] . $this->request->post['cc_expire_date_year'];
		$data['x_card_code'] = $this->request->post['cc_cvv2'];
		$data['x_invoice_num'] = $this->session->data['order_id'];
		$data['x_solution_id'] = 'A1000015';
		
		/* Customer Shipping Address Fields */
		if ($order_info['shipping_method']) {
		  $data['x_ship_to_first_name'] = html_entity_decode($order_info['shipping_firstname'], ENT_QUOTES, 'UTF-8');
		  $data['x_ship_to_last_name'] = html_entity_decode($order_info['shipping_lastname'], ENT_QUOTES, 'UTF-8');
		  $data['x_ship_to_company'] = html_entity_decode($order_info['shipping_company'], ENT_QUOTES, 'UTF-8');
		  $data['x_ship_to_address'] = html_entity_decode($order_info['shipping_address_1'], ENT_QUOTES, 'UTF-8') . ' ' . html_entity_decode($order_info['shipping_address_2'], ENT_QUOTES, 'UTF-8');
		  $data['x_ship_to_city'] = html_entity_decode($order_info['shipping_city'], ENT_QUOTES, 'UTF-8');
		  $data['x_ship_to_state'] = html_entity_decode($order_info['shipping_zone'], ENT_QUOTES, 'UTF-8');
		  $data['x_ship_to_zip'] = html_entity_decode($order_info['shipping_postcode'], ENT_QUOTES, 'UTF-8');
		  $data['x_ship_to_country'] = html_entity_decode($order_info['shipping_country'], ENT_QUOTES, 'UTF-8');
		} else {
		  $data['x_ship_to_first_name'] = html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');
		  $data['x_ship_to_last_name'] = html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
		  $data['x_ship_to_company'] = html_entity_decode($order_info['payment_company'], ENT_QUOTES, 'UTF-8');
		  $data['x_ship_to_address'] = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8') . ' ' . html_entity_decode($order_info['payment_address_2'], ENT_QUOTES, 'UTF-8');
		  $data['x_ship_to_city'] = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');
		  $data['x_ship_to_state'] = html_entity_decode($order_info['payment_zone'], ENT_QUOTES, 'UTF-8');
		  $data['x_ship_to_zip'] = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
		  $data['x_ship_to_country'] = html_entity_decode($order_info['payment_country'], ENT_QUOTES, 'UTF-8');
		}
		
		//print_r($data);
		//to get product name
		$products = $this->cart->getProducts();
		//print_r($products);
	  foreach ($products as $product){
	  	$product_name .= ','.$product['name'];
	  	$product_category = $this->model_catalog_product->getCategories($product['product_id']);
	  	$category = $this->model_catalog_category->getCategory($product_category[0]['category_id']);
	  	$category_name .=','.$category['name'];
	  }
		$data['x_product_name']  = trim($product_name,',');
		$data['x_category_name'] = trim($category_name,',');
		$data['x_products_count'] = $this->cart->countProducts();
		
    // save session data into oc_session_purchase_details
    //$this->liveshop->insert_session_data('pay_at_store');
    
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/pay_at_store.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/pay_at_store.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/pay_at_store.tpl', $data);
		}
	}

	/**
	 * validate form fields
	 * @return boolean
	 */
	public function validate() {
	  $this->load->language('payment/pay_at_store');
	   
	  if ((utf8_strlen($this->request->post['pas_first_name']) < 3) || (utf8_strlen($this->request->post['pas_first_name']) > 32) || (utf8_strlen($this->request->post['pas_first_name']) =='')) {
	    $result['error']['first_name'] = $this->language->get('error_first_name');
	  }
	  if ((utf8_strlen($this->request->post['pas_last_name']) < 3) || (utf8_strlen($this->request->post['pas_last_name']) > 32)) {
	    $result['error']['last_name'] = $this->language->get('error_last_name');
	  }
	  if (!preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['pas_email'])) {
	    $result['error']['email'] = $this->language->get('error_email');
	  }
	  if (!preg_match('/^[0-9]{13}$/', $this->request->post['pas_phone'])) {
	    $result['error']['phone'] = $this->language->get('error_phone');
	  }
	  if ((utf8_strlen($this->request->post['pas_address']) < 3) || (utf8_strlen($this->request->post['pas_address']) > 100)) {
	     $result['error']['address'] = $this->language->get('error_address');
	  }
	  /*if ((utf8_strlen($this->request->post['pas_country']) < 3) || (utf8_strlen($this->request->post['pas_country']) > 32)) {
	     $result['error']['country'] = $this->language->get('error_country');
	  }
	 if ((utf8_strlen($this->request->post['pas_state']) < 3) || (utf8_strlen($this->request->post['pas_state']) > 32)) {
	     $result['error']['state'] = $this->language->get('error_state');
	  }*/
	 if ((utf8_strlen($this->request->post['pas_city']) < 3) || (utf8_strlen($this->request->post['pas_city']) > 32)) {
	     $result['error']['city'] = $this->language->get('error_city');
	 }
	  if (!preg_match('/^[0-9]{4,5}$/', $this->request->post['pas_post_code'])) {
	    $result['error']['post_code'] = $this->language->get('error_post_code');
	  }
	
	  //print_r($result);
	  if(!$result['error']) {
	    $result['redirect'] = $this->url->link('pay_at_store/send', '', 'SSL');
	  
	  } 
	  $this->response->addHeader('Content-Type: application/json');
	  $this->response->setOutput(json_encode($result));
	}
	
	/**
	 * payment api
	 * Author:Hamdy
	 */
	public function send() {
	  $this->load->language('payment/pay_at_store');
	   
	  //print_r($_POST);die();
	  $data = array();	  
	  if ($this->config->get('pay_at_store_server') == 'live') {
	    $url = 'https://payment.payfort.com/payat/merchants/payments.wsdl';
	    $server_name = 'livemegastore';
	    $data['server'] = 'live';
	  } elseif ($this->config->get('pay_at_store_server') == 'test') {
	    $url = 'https://sandbox.payfort.com/payment/payat/merchants/payments.wsdl';
	    $server_name = 'LiveFreeConnection1';
	    $data['server'] = 'test';
	  }
	  
	  ini_set("soap.wsdl_cache_enabled", "0");
	  ini_set('user_agent', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:20.0) Gecko/20100101 Firefox/20.0');
	 
	  $client = new SoapClient($url, array("trace" => 1));
	  $header = array(
	      'msgType' => 'PAYatSTORE',
	      'msgDate' => date('Y-m-d\TH:i:s', time()),
	  );
	  
	  //print_r(Session::all());die();
	 $this->load->model('checkout/order');
	 $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
	   
	 $cart_amount = $this->currency->format($order_info['total'], $order_info['currency_code'], 1.00000, false);
	 //echo $cart_amount;die();
	 $egp_rate = $this->currency->getValue('EGP');
		 
   $amount = $cart_amount*$egp_rate;
   $currncy = 'EGP';

	  $y = 0;
	  while($amount > 0){
	    if($amount < 10000)
	      $amount_arr[$y] = $amount;
	    else
	      $amount_arr[$y] = 10000;
	  
	    $amount-=10000;
	    $y++;
	  }
	  //print_r($amount_arr);die();
	  
	  $session_data = $this->session->data;
	  $serial_data = serialize($session_data);
	  $transaction_uid = $this->liveshop->getUniqueReferrenceNumber();
	  $date_of_transaction = date("Y-m-d H:i:s");
	  $payment_method = pay_at_store;

	  $this->db->query("INSERT INTO `" . DB_PREFIX . "session_purchase_details` SET transaction_uid = '" . $transaction_uid . "', session_data = '" . $serial_data . "',date_of_transaction='".$date_of_transaction."',payment_method='".$payment_method."',order_id='".$this->session->data['order_id']."'");
	   
	  $last_id = $this->db->getLastId();
	  //echo $last_id;die();
	  
	  $i = 0;
	  $enroll_steps_pay_at_store = '';
	  foreach ($amount_arr as $amount_arr_item){
	    $expiry_date = date ( "c", strtotime ( "+3 days", strtotime ( date('Y-m-d H:i:s') ) ) );
	    $client_name = $_POST['pas_first_name'].' '.$_POST['pas_last_name'];
	    $order_id = $this->liveshop->getUniqueReferrenceNumber();
	    
	    $itemName = $_POST['pas_product_name'];
	    $item_name_length = strlen($itemName);
	    if($item_name_length > 100)
	      $itemName = "Liveshop Products";
	    else
	      $itemName = $itemName;
	    $payAtStore = array(
	        'merchantID' 	=> $this->config->get('pay_at_store_login') ,//4homesFZCO
	        'orderID' 		=> $order_id,
	        'amount' 		=> round($amount_arr_item,2),
	        'currency' 		=> $currncy ,
	        'itemName' 		=> $itemName ,
	        'expiryDate' 	=> $expiry_date ,
	        'clientName'	=> $client_name,
	        'clientEmail'	=> $_POST['pas_email'],
	        'clientMobile'	=> $_POST['pas_phone'],
	        //'ticketNumber' 	=> '',

	        'serviceName'=> $server_name
	    ) ;
	    ksort($payAtStore);
	    $signature ="";
	    foreach ($payAtStore as $key => $value) {
	      $signature=$signature.$value;
	    }
	    global $EncryptionKey;
	    $EncryptionKey = $this->config->get('pay_at_store_key');
	    $signature=strtoupper(sha1($signature.$EncryptionKey));
	  
	    $messageBody = array(
	        'header' => $header ,
	        'PAYatSTORE' => $payAtStore ,
	        'signature' => $signature
	    );
	  
	    $params = array( 'message'=> $messageBody );
	  
      // insert into pay at store transactions
	    $this->db->query("INSERT INTO `" . DB_PREFIX . "pay_at_store_transactions` SET order_id = '" . $order_id . "',oc_order_id = '".$this->session->data['order_id']."', amount = '" . round($amount_arr_item,2) . "',status='no',session_purchase_id='".$last_id."',date_of_transaction='".date("Y-m-d H:i:s")."'");
	     
	    $result = $client->__SoapCall('PAYMENT_METHOD', array($params));
	    //echo "<pre>"; print_r($client->__last_request) . "<hr>";
	    //echo "<pre>"; print_r($client->__last_response) . "<hr>";
	    //echo "<pre>"; print_r($result) . "<hr>";
	  
	    if($result->return->responseStatus->status == "Success" ){

	      $this->db->query("UPDATE `" . DB_PREFIX . "pay_at_store_transactions` SET status='waiting_process' WHERE order_id = '" . $result->return->PAYatSTORE->orderID . "'");
	       
	  	      $data['vouchers'][$i]['amount'] = round($amount_arr_item,0);
	  	      $data['vouchers'][$i]['voucher_number'] = $result->return->PAYatSTORE->voucherNumber;
	  	      $data['vouchers'][$i]['request_id'] = $result->return->PAYatSTORE->requestID;
	  
	    }else {
	    $data['vouchers'][$i]['voucher_number'] = $result->return->PAYatSTORE->voucherNumber;
	    $data['vouchers'][$i]['request_id'] = $result->return->PAYatSTORE->requestID;

	      $this->db->query("UPDATE `" . DB_PREFIX . "pay_at_store_transactions` SET status='failed_process' WHERE order_id = '" . $result->return->error->reqRef->orderID. "'");
	       
	    }
	    $i++;
	  }



		if (isset($this->session->data['order_id'])) {
			$this->cart->clear();

			// Add to activity log
			$this->load->model('account/activity');

			if ($this->customer->isLogged()) {
				$activity_data = array(
					'customer_id' => $this->customer->getId(),
					'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
					'order_id'    => $this->session->data['order_id']
				);

				$this->model_account_activity->addActivity('order_account', $activity_data);
			} else {
				$activity_data = array(
					'name'     => $this->session->data['guest']['firstname'] . ' ' . $this->session->data['guest']['lastname'],
					'order_id' => $this->session->data['order_id']
				);

				$this->model_account_activity->addActivity('order_guest', $activity_data);
			}

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['guest']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);
			unset($this->session->data['totals']);
		}


	  $this->document->setTitle($this->language->get('heading_title'));
	  
	  $data['breadcrumbs'] = array();
	  
	  $data['breadcrumbs'][] = array(
	      'text' => $this->language->get('text_home'),
	      'href' => $this->url->link('common/home')
	  );
	  
	  $data['breadcrumbs'][] = array(
	      'text' => $this->language->get('text_basket'),
	      'href' => $this->url->link('checkout/cart')
	  );
	  
	  $data['breadcrumbs'][] = array(
	      'text' => $this->language->get('text_checkout'),
	      'href' => $this->url->link('checkout/checkout', '', 'SSL')
	  );
	  
	  $data['breadcrumbs'][] = array(
	      'text' => $this->language->get('text_voucher'),
	      'href' => $this->url->link('payment/pay_at_store/send')
	  );
	  
	  $data['heading_title'] = $this->language->get('heading_title');
	  
	  if ($this->customer->isLogged()) {
	    $data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', 'SSL'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/download', '', 'SSL'), $this->url->link('information/contact'));
	  } else {
	    $data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
	  }
	  
	  $data['button_continue'] = $this->language->get('button_continue');
	  
	  $data['continue'] = $this->url->link('common/home');
	  
	  $data['column_left'] = $this->load->controller('common/column_left');
	  $data['column_right'] = $this->load->controller('common/column_right');
	  $data['content_top'] = $this->load->controller('common/content_top');
	  $data['content_bottom'] = $this->load->controller('common/content_bottom');
	  $data['footer'] = $this->load->controller('common/footer');
	  $data['header'] = $this->load->controller('common/header');
	  
	  $data['request_url'] = $this->url->link('payment/pay_at_store/get_voucher','','SSL');
	  $data['text_amount'] = $this->language->get('text_amount');
	  $data['text_generate_invoice'] = $this->language->get('text_generate_invoice');
	  
	  $data['text_kindly_note1'] = $this->language->get('text_kindly_note1');
	  $data['text_kindly_note2'] = $this->language->get('text_kindly_note2');
	  $data['text_kindly_note3'] = $this->language->get('text_kindly_note3');
	  $data['text_kindly_note4'] = $this->language->get('text_kindly_note4');
	   
	  
	  
	  //print_r($data);die();
	  //print_r($msg);die();
	  // array for testing
    //$data['vouchers'] = array('0'=>array('voucher_number'=>'1234567890','request_id'=>'12345698','amount'=>'123'),'1'=>array('voucher_number'=>'12345678902','request_id'=>'123456982','amount'=>'1234'));
     if(count($data['vouchers']) > 1){
      $this->response->setOutput($this->load->view('default/template/payment/confirm_pay_at_store.tpl', $data));
     } else{
       $data['voucher_number'] = $data['vouchers'][0]['voucher_number'];
       $data['request_id'] = $data['vouchers'][0]['request_id'];
       $this->response->setOutput($this->load->view('default/template/payment/pay_at_store_voucher.tpl', $data));

    
     }
    
	}  	
	/**
	 * to generate single voucher
	 * Author:Hamdy
	 * @param unknown $voucher_number
	 * @param unknown $request_id
	 */
	public function get_voucher($voucher_number,$request_id){
	  $this->load->language('payment/pay_at_store');
	  
	  $this->document->setTitle($this->language->get('heading_title'));
	   
	  $data['breadcrumbs'] = array();
	   
	  $data['breadcrumbs'][] = array(
	      'text' => $this->language->get('text_home'),
	      'href' => $this->url->link('common/home')
	  );
	   
	  $data['breadcrumbs'][] = array(
	      'text' => $this->language->get('text_basket'),
	      'href' => $this->url->link('checkout/cart')
	  );
	   
	  $data['breadcrumbs'][] = array(
	      'text' => $this->language->get('text_checkout'),
	      'href' => $this->url->link('checkout/checkout', '', 'SSL')
	  );
	   
	  $data['breadcrumbs'][] = array(
	      'text' => $this->language->get('text_voucher'),
	      'href' => $this->url->link('payment/pay_at_store/send')
	  );
	   
	  $data['heading_title'] = $this->language->get('heading_title');
	   
	  if ($this->customer->isLogged()) {
	    $data['text_message'] = sprintf($this->language->get('text_customer'), $this->url->link('account/account', '', 'SSL'), $this->url->link('account/order', '', 'SSL'), $this->url->link('account/download', '', 'SSL'), $this->url->link('information/contact'));
	  } else {
	    $data['text_message'] = sprintf($this->language->get('text_guest'), $this->url->link('information/contact'));
	  }
	   
	  $data['button_continue'] = $this->language->get('button_continue');
	   
	  $data['continue'] = $this->url->link('common/home');
	   
	  $data['column_left'] = $this->load->controller('common/column_left');
	  $data['column_right'] = $this->load->controller('common/column_right');
	  $data['content_top'] = $this->load->controller('common/content_top');
	  $data['content_bottom'] = $this->load->controller('common/content_bottom');
	  $data['footer'] = $this->load->controller('common/footer');
	  $data['header'] = $this->load->controller('common/header');	  
	  
	 
	  $data['voucher_number'] = $_GET['voucher_number'];
	  $data['request_id'] = $_GET['request_id'];
	  
	  if ($this->config->get('pay_at_store_server') == 'live') {
	
	    $data['server'] = 'live';
	  } elseif ($this->config->get('pay_at_store_server') == 'test') {

	    $data['server'] = 'test';
	  }
	  $this->response->setOutput($this->load->view('default/template/payment/pay_at_store_voucher.tpl', $data));
	   
	}
	
	/**
	 * the callback function after the user pay the amount through the store
	 * Author:Hamdy
	 */
	
	public function pay_at_store_notification(){
	 
	  ini_set('display_errors', '1');
	  ini_set('user_agent', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:20.0) Gecko/20100101 Firefox/20.0');
	  ini_set("soap.wsdl_cache_enabled", "0");
	  if ($this->config->get('pay_at_store_server') == 'live') {
	    $url = 'https://payment.payfort.com/payat/merchants/payments.wsdl';
	    $server_name = 'livemegastore';
	  } elseif ($this->config->get('pay_at_store_server') == 'test') {
	    $url = 'https://sandbox.payfort.com/payment/payat/merchants/payments.wsdl';
	    $server_name = 'LiveFreeConnection1';
	  }
	  //get the XML notification
	  //$inputs=(object)Input::all();
	  $inputs=simplexml_load_string( $_POST['notification']);
	  $is_valid = $this->isValidOrderId($inputs->orderID,"waiting_process");
	  if($is_valid !='no'){
	    if($is_valid =='processed'){
	      $rs = $this->db->query("SELECT * FROM ".DB_PREFIX."pay_at_store_transactions WHERE order_id = '".$inputs->orderID."'");
	       
	      $this->order_checkout($rs->row['oc_order_id']);
	    }
	    //start the PAYMENT_NOTIFICATION SOAP request

	    $client = new SoapClient($url ,array("trace"      => 1));
	    $header = array(
	        'msgType' => 'PAYMENT_NOTIFICATION',
	        'msgDate' => date('Y-m-d\TH:i:s') // example : 2014-06-14T10:10:48
	    );
	
	    //fill the data of the order
	    $requestArr = array(
	        'merchantID' => $this->config->get('pay_at_store_login') ,
	        'orderID' => $inputs->orderID , //$inputs->orderID,
	        'payment'=>'PAYatSTORE',
	        'currency' => $inputs->currency,
	        'status' => 'success',
	        'serviceName' => $server_name,
	
	    ) ;
	
	    $inputs=$requestArr;
	    ksort($inputs);
	
	    $string="";
	    foreach ($inputs as $key => $input) {
	      $string=$string.$input;
	    }
	    /////concat with Encryption Key
	    $encryptionKey = $this->config->get('pay_at_store_key');
	    $string = $string . $encryptionKey ;
	    //// encryption with sha1
	    $signature=sha1($string);
	
	    $messageBody = array(
	        'header' => $header ,
	        'PAYMENT_NOTIFICATION' => $requestArr ,
	        'signature' => $signature
	    );
	
	    $params = array( 'message'=> $messageBody );
	    $result = $client->__SoapCall('PAYMENT_METHOD_SERVICES', array($params));
	    /*$req_dump = print_r($result, TRUE);
	     $fp = fopen('../../../request.log', 'a');
	    fwrite($fp, $req_dump);
	    fclose($fp);*/
	     
	    // check if the result returned is success
	    /*if($result->return->responseStatus->status =='Success'){
	    $data = array(
	        'transaction_status' => "processed"
	    );
	    DB::table('session_purchase_details')->where('transaction_uid',$inputs->orderID)->update($data);
	    //$this->payAtStoreCheckout($inputs->orderID);
	    }*/
	    //echo "<pre>";
	    //var_dump($result);
	    //return "done";
	  }
	}

	/**
	 * check if the order id is exist in the db with the status no
	 * Author:Hamdy
	 * @param int $transaction_id
	 * @return boolean
	 */
	public function isValidOrderId($order_id,$status='no') {
	  $flag = 'no';
	  $rs = $this->db->query("SELECT * FROM ".DB_PREFIX."pay_at_store_transactions WHERE order_id = '".$order_id."' AND status = '".$status."'");
	  
	  if ($rs->num_rows > 0) {
	    $this->db->query("UPDATE `" . DB_PREFIX . "pay_at_store_transactions` SET status='processed' WHERE order_id = '" . $order_id. "'");
	     
	    $rs = $this->db->query("SELECT * FROM ".DB_PREFIX."pay_at_store_transactions WHERE session_purchase_id = '".$rs->row['session_purchase_id']."' AND status = 'waiting_process'");
	     
	    if($rs->num_rows == 0)
	      $flag = 'processed';
	    else
	      $flag = 'waiting_process';
	  }
	  return $flag;
	}	
	
	
	public function order_checkout($oc_order_id){
	   
	  $this->load->model('checkout/order');
	  $json = array();
	  $this->model_checkout_order->addOrderHistory($oc_order_id, $this->config->get('pay_at_store_order_status_id'));
	  //$this->model_checkout_order->addOrderHistory($oc_order_id, 2);
	
	  //$this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
	
	}
	
	public function order_checkout_man(){
	  $order_id = $_GET['order_id'];
	  $is_valid = $this->isValidOrderId($order_id,"waiting_process");
	  if($is_valid !='no'){
	    if($is_valid =='processed'){
	     $rs = $this->db->query("SELECT * FROM ".DB_PREFIX."pay_at_store_transactions WHERE order_id = '".$order_id."'");
	      	  
	      $this->order_checkout($rs->row['oc_order_id']);
	      echo "processed";
	    }
	  }
	
	}
	
	public function test_price(){
	  $this->load->model('checkout/order');
	  $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
	  
	  $cart_amount = $this->currency->format($order_info['total'], $order_info['currency_code'], 1.00000, false);
	  echo "cart amount:".$cart_amount."****<br/>";
	  $egp_rate = $this->currency->getValue('EGP');
	  echo "egp rate:".$egp_rate."****<br/>";
	   
	  $amount = $cart_amount*$egp_rate;
	  echo "pay at store amount:".$amount."****<br/>";
	   
	}
}
