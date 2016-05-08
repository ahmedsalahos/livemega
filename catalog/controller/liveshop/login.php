<?php
class ControllerLiveshopLogin extends Controller {
	private $error = array();

	public function index() {
		$this->load->model('account/customer');

		// Login override for admin users
		if (!empty($this->request->get['token'])) {
			$this->event->trigger('pre.customer.login');

			$this->customer->logout();
			$this->cart->clear();

			unset($this->session->data['wishlist']);
			unset($this->session->data['payment_address']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['shipping_address']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);

			$customer_info = $this->model_account_customer->getCustomerByToken($this->request->get['token']);
			if ($customer_info && $this->customer->login($customer_info['email'], '', true)) {
				// Default Addresses
				$this->load->model('account/address');

				if ($this->config->get('config_tax_customer') == 'payment') {
					$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
				}

				if ($this->config->get('config_tax_customer') == 'shipping') {
					$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
				}

				$this->event->trigger('post.customer.login');

				$this->response->redirect($this->url->link('account/account', '', 'SSL'));
			}
		}

		if ($this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/account', '', 'SSL'));
		}

		$this->load->language('account/login');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			unset($this->session->data['guest']);

			// Default Shipping Address
			$this->load->model('account/address');

			if ($this->config->get('config_tax_customer') == 'payment') {
				$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
			}

			if ($this->config->get('config_tax_customer') == 'shipping') {
				$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
			}

			// Add to activity log
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);

			$this->model_account_activity->addActivity('login', $activity_data);

			// Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
			if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
				$this->response->redirect(str_replace('&amp;', '&', $this->request->post['redirect']));
			} else {
				$this->response->redirect($this->url->link('account/account', '', 'SSL'));
			}
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_login'),
			'href' => $this->url->link('account/login', '', 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_new_customer'] = $this->language->get('text_new_customer');
		$data['text_register'] = $this->language->get('text_register');
		$data['text_register_account'] = $this->language->get('text_register_account');
		$data['text_returning_customer'] = $this->language->get('text_returning_customer');
		$data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
		$data['text_forgotten'] = $this->language->get('text_forgotten');

		$data['entry_email'] = $this->language->get('entry_email');
		//updated by kareem
		$data['entry_username'] = $this->language->get('entry_username');
		//
		$data['entry_password'] = $this->language->get('entry_password');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_login'] = $this->language->get('button_login');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['action'] = $this->url->link('liveshop/login', '', 'SSL');
		$data['register'] = $this->url->link('account/register', '', 'SSL');
		$data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');

		// Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
		if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
			$data['redirect'] = $this->request->post['redirect'];
		} elseif (isset($this->session->data['redirect'])) {
			$data['redirect'] = $this->session->data['redirect'];

			unset($this->session->data['redirect']);
		} else {
			$data['redirect'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		/*
		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} else {
			$data['email'] = '';
		}
		*/

		if (isset($this->request->post['username'])) {
			$data['username'] = $this->request->post['username'];
		} else {
			$data['username'] = '';
		}

		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['store_id'] = $this->config->get('config_store_id');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/login.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/login.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/login.tpl', $data));
		}
	}

	protected function validate() {
		// Check how many login attempts have been made.
		//print_r($this->soap->encrypt('aaa'));
		//print_r($this->Encryption->decrypt('6h6SD6yzZJqGJQ76VwPf6IhPRcd6KLf5sLiz1pRuE6g,'));die();
		//$total = $this->cart->getTotal();
		//print_r($this->currency->convert($total, 'USD','Pon'));die();
		//print_r($this->currency->getCode());die();
		//print_r($this->customer->updateLiveFreePoints("dsds","fadsf",2,24));die();
		//print_r($this->customer->livetoursplusLogin("ltp910331", "Sei6vwIo5t"));die();
		//print_r($this->customer->livetoursplusPointTransaction("ltp910331", "Sei6vwIo5t", 4242567, 100000, 0));die();
		if(trim($this->request->post['username']) && trim($this->request->post['password'])) {
		    //echo trim($this->request->post['username'])." && ".trim($this->request->post['password']);die();
			$customer_info = $this->model_account_customer->getCustomerByUsername($this->request->post['username']);
			if($customer_info) {
				$type = $customer_info['type'];
				if($type && $type == "livefree") {
					$isLiveFree = $this->customer->isLiveFreeUser(trim($this->request->post['username']), trim($this->request->post['password']));
					//print_r($isLiveFree);
					if($isLiveFree->status == 0) {
						$this->error['warning'] = $this->language->get('error_login');
					} else {
		
						if($isLiveFree->status == 1) {
							$userEmailExist = $this->model_account_customer->getCustomerByEmail($isLiveFree->email);
							//if(count($userEmailExist['customer_id']) == 1) {
							$isLiveFreeAndOlUser = $this->model_account_customer->getCustomerByUsername(trim($this->request->post['username']));
								
							if($isLiveFreeAndOlUser) { 
							
								$liveFreeData['customer_id'] = $isLiveFreeAndOlUser['customer_id'];
								$user_name_arr = explode(" ",trim($isLiveFree->fullname));
	            				if(trim($user_name_arr[0])) {
	            					$liveFreeData['firstname'] = trim($user_name_arr[0]);
	            				} else {
	            					$liveFreeData['firstname'] = "";
	            				}

				            	if(count($user_name_arr) > 1) {
				            	    $arr_count = count($user_name_arr);
				            		$liveFreeData['lastname'] = trim($user_name_arr[$arr_count - 1]);
				            	} else {
				            		$liveFreeData['lastname'] = "";
				            	}

				            	if(trim($this->request->post['username'])) {
				            		$liveFreeData['username'] = trim($this->request->post['username']);
				            	} else {
				            		$liveFreeData['username'] = "";
				            	}


				            	if(trim($isLiveFree->telephone)) {
				            		$liveFreeData['telephone'] = trim($isLiveFree->telephone);
				            	} else {
				            		$liveFreeData['telephone'] = "";
				            	}
				            	
				            	if(trim($isLiveFree->fax)) {
				            		$liveFreeData['fax'] = trim($isLiveFree->fax);
				            	} else {
				            		$liveFreeData['fax'] = "";
				            	}
				            	
				            	if(trim($isLiveFree->country_id)) {
				            		$liveFreeData['country_id'] = trim($isLiveFree->country_id);
				            	} else {
				            		$liveFreeData['country_id'] = "";
				            	}

								$liveFreeData['type'] = 'livefree';
				            	$this->model_account_customer->editLiveFreeCustomer($liveFreeData);
							// is livefree user and ol user
								$this->session->data['points'] = $isLiveFree->rsp_premium_point;
								$login_info = $this->model_account_customer->getLoginAttempts($isLiveFreeAndOlUser['email']);
	 				                        $this->model_account_customer->editPassword($isLiveFreeAndOlUser['email'], $this->request->post['password']);
	 							if ($login_info && ($login_info['total'] > $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
									$this->error['warning'] = $this->language->get('error_attempts');
								}
			
								// Check if customer has been approved.
								if ($isLiveFreeAndOlUser && !$isLiveFreeAndOlUser['approved']) {
									$this->error['warning'] = $this->language->get('error_approved');
								}
			
								if (!$this->error) {
									if (!$this->customer->login($isLiveFreeAndOlUser['email'], $this->request->post['password'])) {
										$this->error['warning'] = $this->language->get('error_login');
										$this->model_account_customer->addLoginAttempt($isLiveFreeAndOlUser['email']);
									} else {
										$this->model_account_customer->deleteLoginAttempts($isLiveFreeAndOlUser['email']);
										$this->session->data['user_im'] = trim($this->request->post['username']);
									}			
								}
								//echo "login"; die();
							} else {
							// is livefree user and not ol user
	            			//print_r($isLiveFree);die();
	            			$this->error['warning'] = $this->language->get('error_login');
							}
							//} else {
							//	$this->error['warning'] = "you have a livetoursplus account";
							//}
						} else {
							// is not livefree user 
							//echo "is not isLiveFree";die();
							$this->error['warning'] = $this->language->get('error_login');
						}
					}
				} else if($type == "livetoursplus") {
					$isLiveToursPlus = $this->customer->livetoursplusLogin(trim($this->request->post['username']), trim($this->request->post['password']));
					//print_r($isLiveToursPlus);
					if($isLiveToursPlus->IsSuccessful == 0) {
						$this->error['warning'] = $this->language->get('error_login');
					} else {
						
						if($isLiveToursPlus->IsSuccessful == 1) {
							$account_detail = $isLiveToursPlus->AccountDetail;
							$userEmailExist = $this->model_account_customer->getCustomerByEmail($account_detail->Email);
							if(count($userEmailExist['customer_id']) == 1) {
							
							$isLiveToursPlusAndOlUser = $this->model_account_customer->getCustomerByUsername(trim($this->request->post['username']));
							if($isLiveToursPlusAndOlUser) { 
							
								$liveToursPlusData['customer_id'] = $isLiveToursPlusAndOlUser['customer_id'];
								$user_name_arr = explode(" ",trim($account_detail->FullName));
	            				if(trim($user_name_arr[0])) {
	            					$liveToursPlusData['firstname'] = trim($user_name_arr[0]);
	            				} else {
	            					$liveToursPlusData['firstname'] = "";
	            				}

				            	if(count($user_name_arr) > 1) {
				            	    $arr_count = count($user_name_arr);
				            		$liveToursPlusData['lastname'] = trim($user_name_arr[$arr_count - 1]);
				            	} else {
				            		$liveToursPlusData['lastname'] = "";
				            	}

				            	if(trim($this->request->post['username'])) {
				            		$liveToursPlusData['username'] = trim($this->request->post['username']);
				            	} else {
				            		$liveToursPlusData['username'] = "";
				            	}


				            	if(trim($account_detail->Mobile)) {
				            		$liveToursPlusData['telephone'] = trim($account_detail->Mobile);
				            	} else {
				            		$liveToursPlusData['telephone'] = "";
				            	}
				            	/*
				            	if(trim($isLiveFree->fax)) {
				            		$liveFreeData['fax'] = trim($isLiveFree->fax);
				            	} else {
				            		$liveFreeData['fax'] = "";
				            	}
				            	*/
								/*
				            	if(trim($isLiveFree->country_id)) {
				            		$liveFreeData['country_id'] = trim($isLiveFree->country_id);
				            	} else {
				            		$liveFreeData['country_id'] = "";
				            	}
								*/

								$liveToursPlusData['type'] = 'livetoursplus';
				            	$this->model_account_customer->editLiveFreeCustomer($liveToursPlusData);
							// is livefree user and ol user
								$this->session->data['points'] = $account_detail->PlatinumPoints;
								$login_info = $this->model_account_customer->getLoginAttempts($account_detail->Email);
	 				            $this->model_account_customer->editPassword($account_detail->Email, $this->request->post['password']);
	 							if ($login_info && ($login_info['total'] > $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
									$this->error['warning'] = $this->language->get('error_attempts');
								}
			
								// Check if customer has been approved.
								if ($isLiveToursPlusAndOlUser && !$isLiveToursPlusAndOlUser['approved']) {
									$this->error['warning'] = $this->language->get('error_approved');
								}
			
								if (!$this->error) {
									if (!$this->customer->login($account_detail->Email, $this->request->post['password'])) {
										$this->error['warning'] = $this->language->get('error_login');
										$this->model_account_customer->addLoginAttempt($account_detail->Email);
									} else {
										$this->model_account_customer->deleteLoginAttempts($account_detail->Email);
										$this->session->data['user_im'] = trim($this->request->post['username']);
									}			
								}
								//echo "login"; die();
							} else {
								$this->error['warning'] = "you have a livefree account";
							}
							} else {
							// is livefree user and not ol user
	            			//print_r($isLiveFree);die();
	            			$this->error['warning'] = $this->language->get('error_login');
							}
						} else {
							// is not livefree user 
							//echo "is not isLiveFree";die();
							$this->error['warning'] = $this->language->get('error_login');
						}
					}
				} else if($type == "external" && $this->config->get('config_store_id') != 0 && $this->config->get('config_store_id') != 1) {
					$login_info = $this->model_account_customer->getLoginAttempts($customer_info['email']);
				
					if ($login_info && ($login_info['total'] > $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
						$this->error['warning'] = $this->language->get('error_attempts');
					}
		
					// Check if customer has been approved.
					$customer_information = $this->model_account_customer->getCustomerByEmail($customer_info['email']);

					if ($customer_information && !$customer_information['approved']) {
						$this->error['warning'] = $this->language->get('error_approved');
					}
		
					if (!$this->error) {
						if (!$this->customer->login($customer_info['email'], $this->request->post['password'])) {
							$this->error['warning'] = $this->language->get('error_login');
			
							$this->model_account_customer->addLoginAttempt($customer_info['email']);
						} else {
							$this->model_account_customer->deleteLoginAttempts($customer_info['email']);
						}			
					}


				} else {
					$this->error['warning'] = $this->language->get('error_login');
				}
			} else {
				$isLiveFree = $this->customer->isLiveFreeUser(trim($this->request->post['username']), trim($this->request->post['password']));
				$isLiveToursPlus = $this->customer->livetoursplusLogin(trim($this->request->post['username']), trim($this->request->post['password']));
					//print_r($isLiveFree);
					//print_r($isLiveToursPlus->AccountDetail->Username);die;
					if($isLiveFree != "false" && $isLiveFree->status == 1) {
						if($isLiveFree->status == 1) {
							$userEmailExist = $this->model_account_customer->getCustomerByEmail($isLiveFree->email);
							if(!$userEmailExist) {
							// is livefree user and not ol user
	            			//print_r($isLiveFree);die();
	            			    $user_name_arr = explode(" ",trim($isLiveFree->fullname));
	            				if(trim($user_name_arr[0])) {
	            					$liveFreeData['firstname'] = trim($user_name_arr[0]);
	            				} else {
	            					$liveFreeData['firstname'] = "";
	            				}

				            	if(count($user_name_arr) > 1) {
				            	    $arr_count = count($user_name_arr);
				            		$liveFreeData['lastname'] = trim($user_name_arr[$arr_count - 1]);
				            	} else {
				            		$liveFreeData['lastname'] = "";
				            	}

				            	if(trim($this->request->post['username'])) {
				            		$liveFreeData['username'] = trim($this->request->post['username']);
				            	} else {
				            		$liveFreeData['username'] = "";
				            	}

				            	if(trim($this->request->post['password'])) {
				            		$liveFreeData['password'] = trim($this->request->post['password']);
				            	} else {
				            		$liveFreeData['password'] = "";
				            	}

				            	if(trim($isLiveFree->email)) {
				            		$liveFreeData['email'] = trim($isLiveFree->email);
				            	} else {
				            		$liveFreeData['email'] = "";
				            	}

				            	if(trim($isLiveFree->telephone)) {
				            		$liveFreeData['telephone'] = trim($isLiveFree->telephone);
				            	} else {
				            		$liveFreeData['telephone'] = "";
				            	}

				            	if(trim($isLiveFree->fax)) {
				            		$liveFreeData['fax'] = trim($isLiveFree->fax);
				            	} else {
				            		$liveFreeData['fax'] = "";
				            	}

				            	if(trim($isLiveFree->address)) {
				            		$liveFreeData['address_1'] = trim($isLiveFree->address);
				            	} else {
				            		$liveFreeData['address_1'] = "";
				            	}
								
				            	if(trim($isLiveFree->city)) {
				            		$liveFreeData['city'] = trim($isLiveFree->city);
				            	} else {
				            		$liveFreeData['city'] = "";
				            	}

				            	if(trim($isLiveFree->country_id)) {
				            		$liveFreeData['country_id'] = trim($isLiveFree->country_id);
				            	} else {
				            		$liveFreeData['country_id'] = "";
				            	}
								
								$liveFreeData['type'] = 'livefree';
				            	/*$liveFreeData['firstname'] = "";
				       			$liveFreeData['lastname'] = "";
				       			$liveFreeData['username'] = trim($this->request->post['username']);
				       			$liveFreeData['password'] = trim($this->request->post['password']);
				       			$liveFreeData['email'] = trim($isLiveFree->email);
				       			$liveFreeData['telephone'] = "";
				       			$liveFreeData['fax'] = "";
				       			$liveFreeData['address_1'] = "";


				                $liveFreeData['newsletter'] = 0;
				            	$liveFreeData['company'] = "";
				            	$liveFreeData['address_2'] = "";
				            	$liveFreeData['city'] = "";
				            	$liveFreeData['postcode'] = "";
				            	$liveFreeData['country_id'] = 0;
				            	$liveFreeData['zone_id'] = 0;*/

	            				$this->model_account_customer->addLiveFreeCustomer($liveFreeData);
				
								// Clear any previous login attempts for unregistered accounts.
								$this->model_account_customer->deleteLoginAttempts(trim($isLiveFree->email));
				                $this->session->data['user_im'] = trim($this->request->post['username']);
								if (!$this->error) {
									if (!$this->customer->login($isLiveFree->email, $this->request->post['password'])) {
										$this->error['warning'] = $this->language->get('error_login');
										$this->model_account_customer->addLoginAttempt($isLiveFree->email);
									} else {
										$this->model_account_customer->deleteLoginAttempts($isLiveFree->email);
									}			
								}
					
								unset($this->session->data['guest']);

								// Add to activity log
								$this->load->model('account/activity');

								$activity_data = array(
									'customer_id' => $this->customer->getId(),
									'name'        => $liveFreeData['firstname'] . ' ' . $liveFreeData['lastname']
								);

								$this->model_account_activity->addActivity('register', $activity_data);
	            	
								//echo "account > login"; die();
							} else {
								$this->error['warning'] = "you have livetoursplus account";
							}
							
						} else {
							// is not livefree user 
							//echo "is not isLiveFree";die();
							$this->error['warning'] = $this->language->get('error_login');
						}
					} else if ($isLiveToursPlus->IsSuccessful == 1) {
					
						// is livefree user and not ol user
	            			//print_r($isLiveFree);die();
							$account_detail = $isLiveToursPlus->AccountDetail;
							$userEmailExist = $this->model_account_customer->getCustomerByEmail($account_detail->Email);
							if(!$userEmailExist) {
								
	            			    $user_name_arr = explode(" ",trim($account_detail->FullName));
	            				if(trim($user_name_arr[0])) {
	            					$liveToursPlusData['firstname'] = trim($user_name_arr[0]);
	            				} else {
	            					$liveToursPlusData['firstname'] = "";
	            				}

				            	if(count($user_name_arr) > 1) {
				            	    $arr_count = count($user_name_arr);
				            		$liveToursPlusData['lastname'] = trim($user_name_arr[$arr_count - 1]);
				            	} else {
				            		$liveToursPlusData['lastname'] = "";
				            	}

				            	if(trim($this->request->post['username'])) {
				            		$liveToursPlusData['username'] = trim($this->request->post['username']);
				            	} else {
				            		$liveToursPlusData['username'] = "";
				            	}

				            	if(trim($this->request->post['password'])) {
				            		$liveToursPlusData['password'] = trim($this->request->post['password']);
				            	} else {
				            		$liveToursPlusData['password'] = "";
				            	}

				            	if(trim($account_detail->Email)) {
				            		$liveToursPlusData['email'] = trim($account_detail->Email);
				            	} else {
				            		$liveToursPlusData['email'] = "";
				            	}

				            	if(trim($account_detail->Mobile)) {
				            		$liveToursPlusData['telephone'] = trim($account_detail->Mobile);
				            	} else {
				            		$liveToursPlusData['telephone'] = "";
				            	}
								/*
				            	if(trim($isLiveFree->fax)) {
				            		$liveFreeData['fax'] = trim($isLiveFree->fax);
				            	} else {
				            		$liveFreeData['fax'] = "";
				            	}
								*/
				            	if(trim($account_detail->Address)) {
				            		$liveToursPlusData['address_1'] = trim($account_detail->Address);
				            	} else {
				            		$liveToursPlusData['address_1'] = "";
				            	}
								
				            	if(trim($account_detail->City)) {
				            		$liveToursPlusData['city'] = trim($account_detail->City);
				            	} else {
				            		$liveToursPlusData['city'] = "";
				            	}
								/*
				            	if(trim($isLiveFree->country_id)) {
				            		$liveFreeData['country_id'] = trim($isLiveFree->country_id);
				            	} else {
				            		$liveFreeData['country_id'] = "";
				            	}
								*/
								$liveToursPlusData['type'] = 'livetoursplus';

	            				$this->model_account_customer->addLiveFreeCustomer($liveToursPlusData);
				
								// Clear any previous login attempts for unregistered accounts.
								$this->model_account_customer->deleteLoginAttempts(trim($account_detail->Email));
				                $this->session->data['user_im'] = trim($this->request->post['username']);
								if (!$this->error) {
									if (!$this->customer->login($account_detail->Email, $this->request->post['password'])) {
										$this->error['warning'] = $this->language->get('error_login');
										$this->model_account_customer->addLoginAttempt($account_detail->Email);
									} else {
										$this->model_account_customer->deleteLoginAttempts($account_detail->Email);
									}			
								}
					
								unset($this->session->data['guest']);

								// Add to activity log
								$this->load->model('account/activity');

								$activity_data = array(
									'customer_id' => $this->customer->getId(),
									'name'        => $liveToursPlusData['firstname'] . ' ' . $liveToursPlusData['lastname']
								);

								$this->model_account_activity->addActivity('register', $activity_data);
							} else {
								$this->error['warning'] = "You have livefree account";
							}
	            	
					}else {
						$this->error['warning'] = $this->language->get('error_login');
					}
	 		}
	
		}else {
			$this->error['warning'] = $this->language->get('error_login');
		}
		/*	
		$login_info = $this->model_account_customer->getLoginAttempts($this->request->post['email']);
				
		if ($login_info && ($login_info['total'] > $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
			$this->error['warning'] = $this->language->get('error_attempts');
		}
		
		// Check if customer has been approved.
		$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

		if ($customer_info && !$customer_info['approved']) {
			$this->error['warning'] = $this->language->get('error_approved');
		}
		
		if (!$this->error) {
			if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
				$this->error['warning'] = $this->language->get('error_login');
			
				$this->model_account_customer->addLoginAttempt($this->request->post['email']);
			} else {
				$this->model_account_customer->deleteLoginAttempts($this->request->post['email']);
			}			
		}
		*/
		return !$this->error;
	}
	public function save() {
	    $this->load->model('account/customer');
		//$this->load->language('checkout/checkout');
		$this->load->language('account/login');

		$json = array();

		if ($this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		}

		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}


		if (!$json && $this->validate() && !isset($this->error['warning'])) {
			unset($this->session->data['guest']);

			$this->load->model('account/address');

			if ($this->config->get('config_tax_customer') == 'payment') {
				$this->session->data['payment_addess'] = $this->model_account_address->getAddress($this->customer->getAddressId());
			}

			if ($this->config->get('config_tax_customer') == 'shipping') {
				$this->session->data['shipping_addess'] = $this->model_account_address->getAddress($this->customer->getAddressId());
			}

			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');

			// Add to activity log
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);

			$this->model_account_activity->addActivity('login', $activity_data);
		}
        $json['error']['warning'] = $this->error['warning'];
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}	
}