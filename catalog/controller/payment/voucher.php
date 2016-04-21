<?php
class ControllerPaymentVoucher extends Controller {
	public function index() {
		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['continue'] = $this->url->link('checkout/success');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/voucher.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/voucher.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/voucher.tpl', $data);
		}
	}

	public function confirm() {
		if ($this->session->data['payment_method']['code'] == 'voucher') {
			$this->load->model('checkout/order');
			if($this->customer->isLogged()){
				$result = $this->customer->isLiveFreeUser($this->customer->getUsername(), $this->customer->getPassword());
				if($result->status == 1) {
					$userPoints = $result->rsp_golden_point;
					$total = $this->cart->getTotal();
					$currentCurrency = $this->currency->getCode();
					$convertedToPoints = $this->currency->convert($total, $currentCurrency,'Pon');
					//$pointsDif = $userPoints - $convertedToPoints;
					$this->customer->updateLiveFreePoints($this->customer->getUsername(), $this->customer->getPassword() , ($convertedToPoints * -1));
				}	
			}
			//$result = $this->customer->isLiveFreeUser();
			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('voucher_order_status_id'));
		}
	}
}