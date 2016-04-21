<?php
class ControllerPaymentLiveBarclay extends Controller {
	public function accept() {
		$ORDERID        = $this->request->get['OrderID'];
		$CURRENCY       = $this->request->get['Currency'];
		$AMOUNT         = $this->request->get['Amount'];
		$PM             = $this->request->get['Pm'];
		$ACCEPTANCE     = $this->request->get['Acceptance'];
		$STATUS         = $this->request->get['Status'];
		$CARDNO         = $this->request->get['CardNo'];
		$ED             = $this->request->get['ED'];
		$CN             = $this->request->get['CN'];
		$TRXDATE        = $this->request->get['TraxDate'];
		$PAYID          = $this->request->get['PayID'];
		$NCERROR        = $this->request->get['NCError'];
		$BRAND          = $this->request->get['Brand'];
		$IP             = $this->request->get['IP'];

		$encryption = $_GET['OrderID'].$_GET['Currency'].$_GET['Amount'].$_GET['Pm'].$_GET['Acceptance'].$_GET['Status'].$_GET['CardNo'].$_GET['ED'].$_GET['CN'].$_GET['TraxDate'].$_GET['PayID'].$_GET['NCError'].$_GET['Brand'].$_GET['IP'].'G@teW@yP0rt@l';
    	$encryption = strtoupper(sha1($encryption));

    	if($_GET['CCode'] == $encryption && $_GET['Status'] == 9) {
			$date_of_transaction = date("Y-m-d H:i:s");
			$this->db->query("UPDATE `barclay_transaction` SET pm='" . $PM . "',acceptance='".$acceptance."',status='".$STATUS."',card_no='".$CARDNO."',ed='".$ED."',trx_date='" . $TRXDATE . "',pay_id='".$PAYID."',updated_at='".$date_of_transaction."',nc_error='".$NCERROR."',brand='".$BRAND."',ip='".$IP."' WHERE torder_id = '" . $ORDERID. "'");

			// 
			$query = $this->db->query("SELECT order_id, points_amount_paid FROM `barclay_transaction` WHERE torder_id='" . $ORDERID . "'");
			$points_amount_paid = $query->row['points_amount_paid'];

	  	  	$response = $this->customer->livetoursplusPointTransaction($this->customer->getUsername(), $this->customer->getPassword(), $query->row['order_id'], -(round($points_amount_paid,0)), 0);
	  	  	if($response->IsSuccessful != 1) {
	  	  		$this->response->redirect($this->url->link('common/home', '', 'SSL'));
	  	  	}
		  	
			$this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
		} else {
			$this->response->redirect($this->url->link('common/home', '', 'SSL'));
		}

		

	}

	public function decline() {
        $this->response->redirect($this->url->link('common/home', '', 'SSL'));

	}

	public function exception() {
        $this->response->redirect($this->url->link('common/home', '', 'SSL'));
	}

	public function canceled() {
        $ORDERID        = $this->request->get['OrderID'];
		$CURRENCY       = $this->request->get['Currency'];
		$AMOUNT         = $this->request->get['Amount'];
		$PM             = $this->request->get['Pm'];
		$ACCEPTANCE     = $this->request->get['Acceptance'];
		$STATUS         = $this->request->get['Status'];
		$CARDNO         = $this->request->get['CardNo'];
		$ED             = $this->request->get['ED'];
		$CN             = $this->request->get['CN'];
		$TRXDATE        = $this->request->get['TraxDate'];
		$PAYID          = $this->request->get['PayID'];
		$NCERROR        = $this->request->get['NCError'];
		$BRAND          = $this->request->get['Brand'];
		$IP             = $this->request->get['IP'];

		$encryption = $_GET['OrderID'].$_GET['Currency'].$_GET['Amount'].$_GET['Pm'].$_GET['Acceptance'].$_GET['Status'].$_GET['CardNo'].$_GET['ED'].$_GET['CN'].$_GET['TraxDate'].$_GET['PayID'].$_GET['NCError'].$_GET['Brand'].$_GET['IP'].'G@teW@yP0rt@l';
    	$encryption = strtoupper(sha1($encryption));


    	if($_GET['CCode'] == $encryption && $_GET['Status'] == 1) {
			$date_of_transaction = date("Y-m-d H:i:s");
			$this->db->query("UPDATE `barclay_transaction` SET pm='" . $PM . "',acceptance='".$acceptance."',status='".$STATUS."',card_no='".$CARDNO."',ed='".$ED."',trx_date='" . $TRXDATE . "',pay_id='".$PAYID."',updated_at='".$date_of_transaction."',nc_error='".$NCERROR."',brand='".$BRAND."',ip='".$IP."' WHERE torder_id = '" . $ORDERID. "'");	

			$this->response->redirect($this->url->link('checkout/checkout', '', 'SSL'));	
		} else {
			$this->response->redirect($this->url->link('common/home', '', 'SSL'));
		}

		
	}

	public function error() {
        $this->response->redirect($this->url->link('common/home', '', 'SSL'));
	}

	public function back() {
        $this->response->redirect($this->url->link('common/home', '', 'SSL'));
	}

	public function pay() {
		$paid_in_points = $this->request->post['paid_in_points'];
		$orderID       = $this->request->post['torderID'];
		$real_order_id  = substr($orderID, 2);
		$IMAccount     = $this->customer->getUsername();
		$totalAmount   = $this->request->post['totalAmount'];
		$buyCurrency   = $this->request->post['buyCurrency'];
		$CName         = $this->request->post['CName'];
		$CEmail        = $this->request->post['CEmail'];
		$CAddress      = $this->request->post['CAddress'];
		$CCity         = $this->request->post['CCity'];
		$CCountry      = $this->request->post['CCountry'];
		$CZip          = $this->request->post['CZip'];
		$CTelephone    = $this->request->post['CTelephone'];

		$fixed_parameter = "G@teW@yP0rt@l";
		$CCode = strtoupper(sha1($orderID.$IMAccount.$totalAmount.$buyCurrency.$CName.$CEmail.$CAddress.$CCity.$CCountry.$CZip.$CTelephone.$fixed_parameter));
		$result = array();
		$result['CCode'] = $CCode;
		$current_points_balance = 0;
 		$date_of_transaction = date("Y-m-d H:i:s");

	  	  $user = $this->customer->livetoursplusLogin($IMAccount, $this->customer->getPassword());
	  	  if($user->IsSuccessful == 1) {
	  	  	$current_points_balance = $user->AccountDetail->GoldPoints;
	  	  	if($user->AccountDetail->GoldPoints > round($paid_in_points,0)) { 
	  	  	
       		} else {
       			$result['status'] = 0;
		    	$result['message'] = "Your Points less than Cart amount !"; 
		    }
       	  } else {
       		$result['status'] = 0;
		    $result['message'] = $response->ErrorMessage; 
       	  }
       	  
       	$points_amount_after = $current_points_balance - $paid_in_points;

       	$this->load->model('checkout/order');
	    $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
	    $data['x_currency_rate'] = $this->currency->getValue('PTS');
	    $total_cart_points = $data['x_currency_rate'] * $order_info['total'];

 		$this->db->query("INSERT INTO `barclay_transaction` SET torder_id = '" . $orderID . "', order_id = '" . $real_order_id . "', im_account = '" . $IMAccount . "', total_amount = '" . $totalAmount . "', buy_currency = '" . $buyCurrency . "', c_name='" . $CName . "', c_email='" . $CEmail ."', c_address='" . $CAddress . "', c_city='" . $CCity . "', c_country='" . $CCountry . "', c_zip='" . $CZip . "', c_telephone='" . $CTelephone . "', c_code='" . $CCode . "', points_amount_paid='". $paid_in_points . "', points_amount_before='" . $current_points_balance . "', points_amount_after='" . $points_amount_after . "', total_cart_usd='" . $order_info['total'] . "', total_cart_points='" . $total_cart_points . "', created_at='" . $date_of_transaction . "'");

		$this->response->addHeader('Content-Type: application/json');
	  	$this->response->setOutput(json_encode($result));
	}

	private function update_status($ORDERID, $STATUS) {

		
	}

}