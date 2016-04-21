<?php
class ControllerPaymentLivePoints extends Controller {
	public function index() {
	  //print_r($this->session->data);
		$this->load->language('payment/live_points');

		$data['text_live_points'] = $this->language->get('text_live_points');
		$data['text_wait'] = $this->language->get('text_wait');

		// barclay text
		$data['text_barclay_information'] = $this->language->get('text_barclay_information');
		$data['text_customer_name'] = $this->language->get('text_customer_name');
		$data['text_customer_email'] = $this->language->get('text_customer_email');
		$data['text_customer_city'] = $this->language->get('text_customer_city');
		$data['text_customer_country'] = $this->language->get('text_customer_country');
		$data['text_customer_address'] = $this->language->get('text_customer_address');
		$data['text_customer_zip'] = $this->language->get('text_customer_zip');
		$data['text_customer_telephone'] = $this->language->get('text_customer_telephone');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_paid_in_points'] = $this->language->get('text_paid_in_points');
		$data['text_paid_in_usd'] = $this->language->get('text_paid_in_usd');



		$data['lp_im'] = $this->language->get('lp_im');
		$data['lp_points_balance'] = $this->language->get('lp_points_balance');
		$data['lp_total_cart'] = $this->language->get('lp_total_cart');
		$data['lp_remaining_balance'] = $this->language->get('lp_remaining_balance');
		//print_r($this->session->data);
		//replace from session
		//$data['lp_im_val'] = "liv1909518";
		$data['lp_im_val'] = $this->session->data['user_im'];
		
		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['button_check'] = $this->language->get('button_check');

		$data['order_id'] = $this->session->data['order_id'];
		$order_info  = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		$data['user_type'] = $this->customer->getType();
		$data['user_email'] = $this->customer->getEmail();
		$data['user_full_name'] = $order_info['payment_firstname'].' '.$order_info['payment_lastname'];
		$data['user_phone']  = $this->customer->getTelephone();
		$data['user_country'] = $order_info['payment_country'];
		$data['user_address'] = $order_info['payment_address_1'];
		$data['user_city'] = $order_info['payment_city'];
		$data['user_zone'] = $order_info['payment_zone'];
		$data['user_zip'] = $order_info['payment_postcode'];

		
		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		if (isset($this->session->data['shipping_address']['country_id'])) {
			$data['country_id'] = $this->session->data['shipping_address']['country_id'];
		} else {
			$data['country_id'] = $this->config->get('config_country_id');
		}

		
		$data['x_currency_rate'] = $this->currency->getValue('PTS');
		$total_cart = round($order_info['total'] * $data['x_currency_rate']);
		$data['total_cart'] = $total_cart;

		if($_SERVER['SERVER_NAME'] == 'www.livemegastore.com') {
			$data['site_id'] = '11'; 
		} else {
			$data['site_id'] = '12';
		}

		$data['buy_currency'] = "USD";


		$orderID = $data['site_id'] . $this->session->data['order_id'];
		$fixed_parameter = "G@teW@yP0rt@l";		
		$data['orderID'] = $orderID;

		$response = $this->customer->livetoursplusLogin($this->customer->getUsername(), $this->customer->getPassword());		
		if($response->IsSuccessful == 1) {
			$data['available_points'] = $response->AccountDetail->GoldPoints;
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/live_points.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/live_points.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/live_points.tpl', $data);
		}
	}

	public function check_balance(){
	  $this->load->language('payment/live_account');
	   
	  $this->load->model('checkout/order');
	   
	  $order_info  = $this->model_checkout_order->getOrder($this->session->data['order_id']);
	  $amount = $this->currency->format($order_info['total'], $order_info['currency_code'], 1.00000, false);
	  $data['x_amount'] = $amount;
	  $data['x_currency_code'] = $this->currency->getCode();
	  $data['x_currency_rate'] = $this->currency->getValue('PTS');
	  //print_r($data);die();
	   
	  $user_name = $_POST['lp_im'];
	  $total_cart_points = $order_info['total']*$data['x_currency_rate'];
	  
	  if($this->customer->getType() == 'livefree') {
		  $response = $this->liveshop->check_user_points($user_name,round($total_cart_points,0));
		  //$response = array( "status" => 1 ,"message" => "enough balance!");
		  //print_r($response);die();
		  if($response->status == 1){
		    $result['status'] = "1";
		    $result['total_balance'] = $response->total_points;
		    $result['total_cart'] = round($total_cart_points,0);
		    $result['remaining_points'] = $response->remaining_points;
		  }else {
		    $result['status'] = "0";
		    $result['message'] = $response->message;
		    /*$result['username'] = $user_name;
		    $result['amtt'] = round($total_cart_points,0);
		    $result['rate']	    = $data['x_currency_rate'];
		    $result['amountt'] = $data['x_amount']; 
		    $result['all'] = $order_info;*/
		
		  }
	  } else {
		  $response = $this->customer->livetoursplusLogin($user_name, $this->customer->getPassword());
		  if($response->IsSuccessful == 1) {
		  	if($response->AccountDetail->GoldPoints > 0 && $response->AccountDetail->GoldPoints > round($total_cart_points,0)) {
		  		$result['status'] = "1";
		    	$result['total_balance'] = $response->AccountDetail->GoldPoints;
		    	$result['total_cart'] = round($total_cart_points,0);
		    	$result['remaining_points'] = $response->AccountDetail->GoldPoints - round($total_cart_points,0); 
		    } else {
		    	$result['status'] = "0";
		    	$result['message'] = "You points less than cart amount";
		    }
		  } else {
		    $result['status'] = "0";
		    $result['message'] = "invalid username";
		  }
	  }
	   
	   
	  $this->response->addHeader('Content-Type: application/json');
	  $this->response->setOutput(json_encode($result));
	}
	public function checkout_order(){
	  $this->load->language('payment/live_account');
	  
	  
	  $this->load->model('checkout/order'); 
	  
	  $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
	  
	  $amount = $this->currency->format($order_info['total'], $order_info['currency_code'], 1.00000, false);
	  $data['x_amount'] = $amount;
	  $data['x_currency_code'] = $this->currency->getCode();
	  $data['x_currency_rate'] = $this->currency->getValue('PTS');
	  //$total_cart_points = $data['x_amount']*$data['x_currency_rate'];
	  $total_cart_points = $order_info['total']*$data['x_currency_rate']; 
	  $user_name = $_POST['lp_im'];
	  if($this->customer->getType() == 'livefree') {
		  $response = $this->liveshop->deduct_user_points($user_name,round($total_cart_points,0));
		  //$response = array( "status" => 1 ,"message" => "enough balance!");
		  //print_r($response);die();
		  $result = array();
		  if($response->status !=0){
		    $result['status'] = 1;
		    $result['redirect'] = $this->url->link('checkout/success', '', 'SSL');
		    $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('live_points_order_status_id'));

		  
		  
		  }else {
		    $result['status'] = 0;
		    $result['message'] = $response->message;
		  
		  }
	  } else {
	  	  $paid_in_points = $this->request->post['paid_in_points'];
	  	  if($paid_in_points < 0) {
	  	  	$result['status'] = 0;
		    $result['message'] = "Must Positive amount"; 
	  	  } else {
		  	  $IMAccount = $_POST['IMAccount'];
		  	  $user = $this->customer->livetoursplusLogin($IMAccount, $this->customer->getPassword());
		  	  if($user->IsSuccessful == 1) {
		  	  	if($user->AccountDetail->GoldPoints > round($total_cart_points,0)) { 
		  	  		$response = $this->customer->livetoursplusPointTransaction($IMAccount, $this->customer->getPassword(), $this->session->data['order_id'], -(round($total_cart_points,0)), 0);
			  		$result = array();
			  		if($response->IsSuccessful == 1){
			    		$result['status'] = 1;
			    		$result['redirect'] = $this->url->link('checkout/success', '', 'SSL');
			    		//$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('live_points_order_status_id'));
					}else {
			    		$result['status'] = 0;
			    		$result['message'] = $response->ErrorMessage; 
	       		    }
	       		} else {
	       			$result['status'] = 0;
			    	$result['message'] = "Your Points less than Cart amount !"; 
			    }
	       	  } else {
	       		$result['status'] = 0;
			    $result['message'] = $response->ErrorMessage; 
	       	  }
	      }
      }
       

	  $this->response->addHeader('Content-Type: application/json');
	  $this->response->setOutput(json_encode($result));
	}
}
