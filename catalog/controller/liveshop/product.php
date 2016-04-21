<?php
class ControllerLiveshopProduct extends Controller {
	private $error = array();
    public function addPromotedBy() {
        $this->load->model('liveshop/product');
	    if(isset($this->request->get['shareoptions'])) {
	        $shareoptions = $this->request->get['shareoptions'];
	        $shared_product_info = unserialize(base64_decode($shareoptions));
		    $this->load->language('checkout/cart');
		    if (isset($shared_product_info['product_id'])) {
			    $product_id = (int)$shared_product_info['product_id'];
		    } else {
			    $product_id = 0;
		    }

		    $this->load->model('catalog/product');

		    $product_info = $this->model_catalog_product->getProduct($product_id);
		    if ($product_info) {
			    if(isset($shared_product_info['user_id'])) {
			        $shared_user_id = $shared_product_info['user_id'];//echo $shared_user_id;die();
			        $user_info = $this->model_liveshop_product->getUserInfo($shared_user_id);
			        if($user_info) {
			            $LiveFreeResponse = get_object_vars($this->customer->verifyLiveFreeUser(trim($user_info[0]['username'])));
			            //print_r($LiveFreeResponse);die();
			            if($LiveFreeResponse['status'] == 1) {
			                $user_expiry = $LiveFreeResponse['expiry_date'];
			                $current_date = date("Y-m-d H:i:s");
			                if($user_expiry >= $current_date) {
			                    $option = array('shared_by' => $user_info[0]['username']);
			                    $this->session->data['promoted_by'] = $user_info[0]['username'];
			                    $this->session->data['promoted_by_full_name'] = $LiveFreeResponse['fullname'];
			                }
			            }		            
			        }
			    }
		    }
    	    $this->response->redirect($this->url->link('product/product&product_id='.$shared_product_info['product_id'], '', 'SSL'));
	        return;		    	    		    
		}    
    }
	public function liveshopAddToCart() {
	    $this->load->model('liveshop/product');
	    if(isset($this->request->get['shareoptions'])) {
	        $shareoptions = $this->request->get['shareoptions'];
	        $shared_product_info = unserialize(base64_decode($shareoptions));
		    $this->load->language('checkout/cart');

		    $json = array();

		    if (isset($shared_product_info['product_id'])) {
			    $product_id = (int)$shared_product_info['product_id'];
		    } else {
			    $product_id = 0;
		    }

		    $this->load->model('catalog/product');

		    $product_info = $this->model_catalog_product->getProduct($product_id);
		    if ($product_info) {
			    if (isset($shared_product_info['quantity'])) {
				    $quantity = (int)$shared_product_info['quantity'];
			    } else {
				    $quantity = 1;
			    }
			    //check the user end_date
			    //$valid_livefree_user = FALSE;
			    if(isset($shared_product_info['user_id'])) {
			        $shared_user_id = $shared_product_info['user_id'];//echo $shared_user_id;die();
			        $user_info = $this->model_liveshop_product->getUserInfo($shared_user_id);
			        if($user_info) {
			            //$this->load->model('liveshop/product');
			            $LiveFreeResponse = get_object_vars($this->customer->verifyLiveFreeUser(trim($user_info[0]['username'])));
			            //print_r($LiveFreeResponse);die();
			            if($LiveFreeResponse['status'] == 1) {
			                $user_expiry = $LiveFreeResponse['message'];
			                $current_date = date("Y-m-d H:i:s");
			                if($user_expiry >= $current_date) {
			                    $option = array('shared_by' => $user_info[0]['username']);
			                }
			            }
			            $option = array('shared_by' => $user_info[0]['username']);
			            
			        }
			    }    
			    $product_options = $this->model_catalog_product->getProductOptions($shared_product_info['product_id']);

			    foreach ($product_options as $product_option) {
				    if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
					    $json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
				    }
			    }

			    $recurring_id = 0;

			    $recurrings = $this->model_catalog_product->getProfiles($product_info['product_id']);

			    if ($recurrings) {
				    $recurring_ids = array();

				    foreach ($recurrings as $recurring) {
					    $recurring_ids[] = $recurring['recurring_id'];
				    }

				    if (!in_array($recurring_id, $recurring_ids)) {
					    $json['error']['recurring'] = $this->language->get('error_recurring_required');
				    }
			    }

			    if (!$json) {
			    //echo $shared_product_info['product_id']. ",$quantity";print_r( $option);print_r( $recurring_id);die();
				    $this->cart->add($shared_product_info['product_id'], $quantity, $option, $recurring_id);

				    $json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $shared_product_info['product_id']), $product_info['name'], $this->url->link('checkout/cart'));

				    unset($this->session->data['shipping_method']);
				    unset($this->session->data['shipping_methods']);
				    unset($this->session->data['payment_method']);
				    unset($this->session->data['payment_methods']);

				    // Totals
				    $this->load->model('extension/extension');

				    $total_data = array();
				    $total = 0;
				    $taxes = $this->cart->getTaxes();

				    // Display prices
				    if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					    $sort_order = array();

					    $results = $this->model_extension_extension->getExtensions('total');

					    foreach ($results as $key => $value) {
						    $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
					    }

					    array_multisort($sort_order, SORT_ASC, $results);

					    foreach ($results as $result) {
						    if ($this->config->get($result['code'] . '_status')) {
							    $this->load->model('total/' . $result['code']);

							    $this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
						    }
					    }

					    $sort_order = array();

					    foreach ($total_data as $key => $value) {
						    $sort_order[$key] = $value['sort_order'];
					    }

					    array_multisort($sort_order, SORT_ASC, $total_data);
				    }

				    $json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));
			    } else {
				    $json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $shared_product_info['product_id']));
			    }
		    }

		    $this->response->addHeader('Content-Type: application/json');
		    $this->response->setOutput(json_encode($json));
		    $this->response->redirect($this->url->link('product/product&product_id='.$shared_product_info['product_id'], '', 'SSL'));
	    }
	    else {die('ss');
	        $this->error['warning'] = 'sdfhjl,fcdxghduhfhf';
	        $this->response->redirect($this->url->link('product/category&path=20', '', 'SSL'),false);
	    }
    }
    public function insertCommission() {
        $order_id = $this->request->get['order_id'];
        $order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");
        //print_r($order_product_query->rows);die();
        $order_product_query_rows_count = 0;
		foreach ($order_product_query->rows as $order_product) {
					//marwa start
					$order_product_query_rows_count++;  
					$session_data = $this->liveshop->getSessionData((int)$order_id);
					$this->load->model('account/customer');
					$order_data = array();
					if(isset($session_data['promoted_by']))
					    $promoted_by = $session_data['promoted_by'];
					else
					    $promoted_by = '';
					if(isset($session_data['referrer_code']))
					    $ref_code = $session_data['referrer_code'];
					else
					    $ref_code = '';        
					$customer_id = $order_info['customer_id'];
		            $this->load->model('liveshop/product');
		            //$user_info = $this->model_liveshop_product->getUserInfo($shared_user_id);
		    	    $order_data['marketer_name'] = $promoted_by;
		    	    $order_data['referrer_name'] = $ref_code;
					$order_data['product_id'] = (int)$order_product['product_id'];
					$order_data['commission'] = $this->liveshop->get_product_commission((int)$order_product['product_id']);
					$order_data['order_id'] = (int)$order_id;
					$order_data['qty'] = (int)$order_product['quantity'];
					$order_data['application'] = 'livemegastore';

		            $customer_info = $this->model_account_customer->getCustomer($session_data['customer_id']);

		            if($customer_info['type'] != 'livefree') {
		                $points = (($order_data['commission'] * $order_data['qty'] ) / 10) * 3;
		                $order_info = array('customer_id' => $session_data['customer_id'],'order_id' => (int)$order_id, 'description' => 'Guest user points','points' => $points);
		                $this->model_liveshop_product->insert_user_reward_points($order_info);
		            }
		            else
		    	        $order_data['buyer_name'] = $customer_info['username'];
					$LiveFreeResponse = get_object_vars($this->customer->insertOrderIntoLivefree($order_data));
					if($order_product_query_rows_count == count($order_product_query->rows)) {
					    $LiveFreeExpectedResponse = get_object_vars($this->liveshop->createExpectedCommissionTable((int)$order_id));
					}
					//print_r($LiveFreeResponse);die();
					//marwa end		
		}        
    }	
}
