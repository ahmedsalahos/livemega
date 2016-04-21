<?php
class ControllerPaymentLiveAccount extends Controller {
	public function index() {
		$this->load->language('payment/live_account');

		$data['text_live_account'] = $this->language->get('text_live_account');
		$data['text_wait'] = $this->language->get('text_wait');

		$data['entry_la_im'] = $this->language->get('entry_la_im');
		$data['entry_la_security_answer'] = $this->language->get('entry_la_security_answer');
		$data['entry_la_security_word'] = $this->language->get('entry_la_security_word');
		$data['entry_la_pin_code'] = $this->language->get('entry_la_pin_code');

		
		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['button_check'] = $this->language->get('button_check');
		
		$data['months'] = array();

		for ($i = 1; $i <= 12; $i++) {
			$data['months'][] = array(
				'text'  => strftime('%B', mktime(0, 0, 0, $i, 1, 2000)),
				'value' => sprintf('%02d', $i)
			);
		}

		$today = getdate();

		$data['year_expire'] = array();

		for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
			$data['year_expire'][] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i))
			);
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/live_account.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/live_account.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/live_account.tpl', $data);
		}
	}

	public function check_balance(){
	  $this->load->language('payment/live_account');
	   
	  $this->load->model('checkout/order');
	   
	  $order_info  = $this->model_checkout_order->getOrder($this->session->data['order_id']);
	  $amount = $this->currency->format($order_info['total'], $order_info['currency_code'], 1.00000, false);
	  $data['x_amount'] = $amount;
	  $data['x_currency_code'] = $this->currency->getCode();
	  $data['x_currency_rate'] = $this->currency->getValue();
	  //print_r($data);die();
	   
	  $user_name = $_POST['la_im'];
	  $total_cart_price = $amount;
	  $response = $this->liveshop->verifyLiveFreeUserBalance($user_name,$total_cart_price);
	  //$response = array( "status" => 1 ,"message" => "enough balance!");
	  //print_r($response);die();
	  if($response->status == 1){
	    $result['status'] = "1";
	  }else {
	    $result['status'] = "0";
	    $result['message'] = $response->message;
	
	  }
	   
	   
	  $this->response->addHeader('Content-Type: application/json');
	  $this->response->setOutput(json_encode($result));
	}
	

	public function checkout(){
	  $this->load->language('payment/live_account');
	  
	  
	  $this->load->model('checkout/order');
	  
	  $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
	  
	  $amount = $this->currency->format($order_info['total'], $order_info['currency_code'], 1.00000, false);
	  
	  $user_name = $_POST['la_im'];
	  $security_answer = $_POST['la_security_answer'];
	  $security_word = $_POST['la_security_word'];
	  $pin_code = $_POST['la_pin_code'];
	  
	  $response = $this->liveshop->deductLiveFreeUserBalance($amount,$user_name,$security_answer,$security_word,$pin_code,$this->session->data['order_id']);
	  //$response = array( "status" => 1 ,"message" => "enough balance!");
	  //print_r($response);die();
	  //$result = array();
	  if($response->status != 0 ){
	    $result['status'] = 1;
	    //$result['redirect'] = $this->url->link('checkout/success', '', 'SSL');
	    $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('live_account_order_status_id'));
	    $result['redirect'] = $this->url->link('checkout/success', '', 'SSL');
	    
	  }else {
	    $result['status'] = 0;
	    $result['message'] = $response->message;
	  }//print_r($result);die();
	  $this->response->addHeader('Content-Type: application/json');
	  $this->response->setOutput(json_encode($result));
	}

}
