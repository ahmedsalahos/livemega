<?php
class ControllerPaymentLivefreeCreditCard extends Controller {
	public function index() {
	  $data = array();
	  
		$this->load->language('payment/livefree_credit_card');

		$data['text_credit_card'] = $this->language->get('text_credit_card');
		$data['text_wait'] = $this->language->get('text_wait');

		//added by hamdy
		$data['entry_card_type'] = $this->language->get('entry_card_type');
		$data['entry_master_card'] = $this->language->get('entry_master_card');
		$data['entry_visa_card'] = $this->language->get('entry_visa_card');
		$data['button_confirm'] = $this->language->get('button_confirm');
		
		//to get order info
		$this->load->model('checkout/order');
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		//print_r($order_info);die();
		
		$data['x_order_id'] = $order_info['order_id'];
		$data['x_login'] = $this->config->get('livefree_credit_card_login');
		$data['x_tran_key'] = $this->config->get('livefree_credit_card_key');
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
		$data['x_type'] = ($this->config->get('livefree_credit_card_method') == 'capture') ? 'AUTH_CAPTURE' : 'AUTH_ONLY';
		$data['x_card_num'] = str_replace(' ', '', $this->request->post['cc_number']);
		$data['x_exp_date'] = $this->request->post['cc_expire_date_month'] . $this->request->post['cc_expire_date_year'];
		$data['x_card_code'] = $this->request->post['cc_cvv2'];
		$data['x_invoice_num'] = $this->session->data['order_id'];
		$data['x_solution_id'] = 'A1000015';
		$data['x_user_im'] = $this->session->data['user_im'];
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
		

    $this->insert_session_data();
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/livefree_credit_card.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/livefree_credit_card.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/livefree_credit_card.tpl', $data);
		}
	}

	public function insert_session_data(){
	  $transaction_uid = $this->liveshop->getUniqueReferrenceNumber();
	  $session_data = serialize($this->session->data);
	  $payment_method = 'livfree_credit_card';
	  $date_of_transaction = date("Y-m-d H:i:s");
	  $this->db->query("INSERT INTO `" . DB_PREFIX . "session_purchase_details` SET transaction_uid = '" . $transaction_uid . "', session_data = '" . $session_data . "',date_of_transaction='".$date_of_transaction."',payment_method='".$payment_method."',order_id='".$this->session->data['order_id']."'");
	
	   
	}
	public function order_checkout(){
	  
	  $this->load->model('checkout/order');	  
	  
	  $this->db->query("UPDATE `" . DB_PREFIX . "session_purchase_details` SET transaction_status='accepted' WHERE order_id = '".$_GET['order_id']."'");
	   
	  $this->model_checkout_order->addOrderHistory($_GET['order_id'], $this->config->get('livefree_credit_card_order_status_id'));

			$this->load->language('checkout/success');

		if (isset($_GET['order_id'])) {
			$this->cart->clear();


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
			'text' => $this->language->get('text_success'),
			'href' => $this->url->link('checkout/success')
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

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/success.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/success.tpl', $data));
		}
	   
	}
	public function order_cancelled(){
	   
	  $this->load->model('checkout/order');
	  $this->db->query("UPDATE `" . DB_PREFIX . "session_purchase_details` SET transaction_status='cancelled' WHERE order_id = '".$_GET['order_id']."'");
	
	  $this->response->redirect($this->url->link('common/home', '', 'SSL'));
	
	}
	
	public function send() {
	  if ($this->config->get('livefree_credit_card_server') == 'live') {
	    $url = 'https://secureacceptance.cybersource.com/pay';
	  } elseif ($this->config->get('livefree_credit_card_server') == 'test') {
	    $url = 'https://testsecureacceptance.cybersource.com/pay';
	  }
	  
	  print_r($this->request->post);die();
	  
		if (!$this->request->post['cc_card_type']) {
	    $json['error'] = $this->language->get('error_card_type');
	  }
	  //$url = 'https://secure.networkmerchants.com/gateway/transact.dll';
	
	  $this->load->model('checkout/order');
	
	  $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
	  //print_r($order_info);die();
	
	  $data = array();
	
	  $data['x_login'] = $this->config->get('livefree_credit_card_login');
	  $data['x_tran_key'] = $this->config->get('livefree_credit_card_key');
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
	  $data['x_method'] = 'CC';
	  $data['x_type'] = ($this->config->get('livefree_credit_card_method') == 'capture') ? 'AUTH_CAPTURE' : 'AUTH_ONLY';
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
	
	  if ($this->config->get('livefree_credit_card_mode') == 'test') {
	    $data['x_test_request'] = 'true';
	  }
	
	  $this->response->addHeader('Content-Type: application/json');
	  $this->response->setOutput(json_encode($json));
	}

	public function order_checkout_man(){
	  $order_id = $_GET['order_id'];
	   
	  $this->load->model('checkout/order');
	
	  $this->db->query("UPDATE `" . DB_PREFIX . "session_purchase_details` SET transaction_status='accepted' WHERE order_id = '".$_GET['order_id']."'");
	   
	  $this->model_checkout_order->addOrderHistory($_GET['order_id'], $this->config->get('livefree_credit_card_order_status_id'));
	  echo "Order id ".$order_id ." has been placed";
	  //$this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
	
	
	}
	
}
