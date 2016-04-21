<?php
class ControllerLiveshopReferrerOptions extends Controller {
	public function index() {
		$this->load->language('checkout/checkout');

		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_loading'] = $this->language->get('text_loading');
		//$data['text_set_referrer'] = $this->language->get('text_set_referrer');
		$data['text_checkout_set_referrer'] = $this->language->get('text_checkout_set_referrer');

		$data['entry_buy_with_referrer'] = $this->language->get('entry_buy_with_referrer');
		$data['entry_buy_without_referrer'] = $this->language->get('entry_buy_without_referrer');
		$data['entry_referrer'] = $this->language->get('entry_referrer');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_upload'] = $this->language->get('button_upload');

		/*
		if (isset($this->session->data['shipping_address']['firstname'])) {
			$data['firstname'] = $this->session->data['shipping_address']['firstname'];
		} else {
			$data['firstname'] = '';
		}
        */
		
		$data['shipping_required'] = $this->cart->hasShipping();

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/liveshop/referrer_options.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/liveshop/referrer_options.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/checkout/referrer_options.tpl', $data));
		}
	}

	public function save() {
		$this->load->language('checkout/checkout');

		$json = array();

		// Validate if customer is logged in.
		if ($this->customer->isLogged()) {
			//$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}

		// Check if guest checkout is available.
		if (!$this->config->get('config_checkout_guest') || $this->config->get('config_customer_price') || $this->cart->hasDownload()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		}

		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$json['redirect'] = $this->url->link('checkout/cart');

				break;
			}
		}
		
		if (!$json) {
			/*
			if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
				$json['error']['firstname'] = $this->language->get('error_firstname');
			}
			*/
			
			/*
			if($this->request->post['referrer_options'] == 'without-referrer') {
				$json['error']['warning'] = "Without referrer";
			} 
			*/
			
			if ($this->request->post['referrer_options'] == 'with-referrer') {
				if($this->request->post['referrer']) {
					$referrer_code = $this->request->post['referrer'];
					$LiveFreeResponse = get_object_vars($this->customer->verifyLiveFreeUser($referrer_code));
					if($LiveFreeResponse['status'] == 1) {
						//valid IM
						$this->session->data['referrer_code'] = $this->request->post['referrer'];
						$json['username'] = $LiveFreeResponse['fullname'];
					} else {
						// not valid
						$json['error']['warning'] = $this->language->get('error_not_valid_referrer');
					}
					//$json['error']['warning'] = $referrer_code;
				} else {
					$json['error']['warning'] = $this->language->get('error_enter_referrer');
				}
			}
		}

		if (!$json) {
			/*
			$this->session->data['shipping_address']['firstname'] = $this->request->post['firstname'];
		    */

			/*
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			*/
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
