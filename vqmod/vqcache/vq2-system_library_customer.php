<?php
class Customer {
	private $customer_id;
	private $firstname;
	private $lastname;
	private $email;

	            private $username;
	            private $type;
	            private $password;
				private $percentage_country;
			
	private $telephone;
	private $fax;
	private $newsletter;
	private $customer_group_id;
	private $address_id;

	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');

	            $this->encryption = new Encryption($this->config->get('config_encryption'));
			

		if (isset($this->session->data['customer_id'])) {
			$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$this->session->data['customer_id'] . "' AND status = '1'");

			if ($customer_query->num_rows) {
				$this->customer_id = $customer_query->row['customer_id'];
				$this->firstname = $customer_query->row['firstname'];
				$this->lastname = $customer_query->row['lastname'];
				$this->email = $customer_query->row['email'];
				$this->telephone = $customer_query->row['telephone'];
				$this->fax = $customer_query->row['fax'];
				$this->newsletter = $customer_query->row['newsletter'];
				$this->customer_group_id = $customer_query->row['customer_group_id'];
				$this->address_id = $customer_query->row['address_id'];


	            $this->username = $customer_query->row['username'];
				$this->type = $customer_query->row['type'];
				$this->password = $customer_query->row['password'];
				$this->percentage_country = $customer_query->row['percentage_country_id'];
			
				$this->db->query("UPDATE " . DB_PREFIX . "customer SET cart = '" . $this->db->escape(isset($this->session->data['cart']) ? serialize($this->session->data['cart']) : '') . "', wishlist = '" . $this->db->escape(isset($this->session->data['wishlist']) ? serialize($this->session->data['wishlist']) : '') . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE customer_id = '" . (int)$this->customer_id . "'");

				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int)$this->session->data['customer_id'] . "' AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");

				if (!$query->num_rows) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "customer_ip SET customer_id = '" . (int)$this->session->data['customer_id'] . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', date_added = NOW()");
				}
			} else {
				$this->logout();
			}
		}
	}

	public function login($email, $password, $override = false) {
		if ($override) {
			$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND status = '1'");
		} else {
			
	            $customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND (password = '" . $this->db->escape(base64_encode($this->encryption->encrypt($password))) . "' OR password = '" . $this->db->escape(md5($password)) . "') AND status = '1' AND approved = '1'");
			
		}

		if ($customer_query->num_rows) {
			$this->session->data['customer_id'] = $customer_query->row['customer_id'];

			if ($customer_query->row['cart'] && is_string($customer_query->row['cart'])) {
				$cart = unserialize($customer_query->row['cart']);

				foreach ($cart as $key => $value) {
					if (!array_key_exists($key, $this->session->data['cart'])) {
						$this->session->data['cart'][$key] = $value;
					} else {
						$this->session->data['cart'][$key] += $value;
					}
				}
			}

			if ($customer_query->row['wishlist'] && is_string($customer_query->row['wishlist'])) {
				if (!isset($this->session->data['wishlist'])) {
					$this->session->data['wishlist'] = array();
				}

				$wishlist = unserialize($customer_query->row['wishlist']);

				foreach ($wishlist as $product_id) {
					if (!in_array($product_id, $this->session->data['wishlist'])) {
						$this->session->data['wishlist'][] = $product_id;
					}
				}
			}

			$this->customer_id = $customer_query->row['customer_id'];
			$this->firstname = $customer_query->row['firstname'];
			$this->lastname = $customer_query->row['lastname'];
			$this->email = $customer_query->row['email'];
			$this->telephone = $customer_query->row['telephone'];
			$this->fax = $customer_query->row['fax'];
			$this->newsletter = $customer_query->row['newsletter'];
			$this->customer_group_id = $customer_query->row['customer_group_id'];
			$this->address_id = $customer_query->row['address_id'];


	            $this->username = $customer_query->row['username'];
			    $this->type = $customer_query->row['type'];
			    $this->password = $customer_query->row['password'];
				$this->percentage_country = $customer_query->row['percentage_country_id'];
			
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE customer_id = '" . (int)$this->customer_id . "'");

			return true;
		} else {
			return false;
		}
	}

	public function logout() {

					unset($this->session->data['multiseller']);
				
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET cart = '" . $this->db->escape(isset($this->session->data['cart']) ? serialize($this->session->data['cart']) : '') . "', wishlist = '" . $this->db->escape(isset($this->session->data['wishlist']) ? serialize($this->session->data['wishlist']) : '') . "' WHERE customer_id = '" . (int)$this->customer_id . "'");

		unset($this->session->data['customer_id']);

		        $this->username = '';
		        $this->type = '';
		        $this->password = '';
				$this->percentage_country = '';
			

		$this->customer_id = '';
		$this->firstname = '';
		$this->lastname = '';
		$this->email = '';
		$this->telephone = '';
		$this->fax = '';
		$this->newsletter = '';
		$this->customer_group_id = '';
		$this->address_id = '';
	}

	public function isLogged() {
		return $this->customer_id;
	}

	public function getId() {
		return $this->customer_id;
	}

	public function getFirstName() {
		return $this->firstname;
	}

	public function getLastName() {
		return $this->lastname;
	}

	public function getEmail() {
		return $this->email;
	}

	public function getTelephone() {
		return $this->telephone;
	}

	public function getFax() {
		return $this->fax;
	}

	public function getNewsletter() {
		return $this->newsletter;
	}

	public function getGroupId() {
		return $this->customer_group_id;
	}

	public function getAddressId() {
		return $this->address_id;
	}

	public function getBalance() {
		$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$this->customer_id . "'");

		return $query->row['total'];
	}


	            public function getUsername() {
		            return $this->username;
	            }
	            public function getType() {
		            return $this->type;
	            }
				
				public function getPercentageCountryId() {
		            return $this->percentage_country;
	            }
				
	            public function getPassword() {
		            return $this->encryption->decrypt(base64_decode($this->password));
	            }	            
	            public function isLiveFreeUser($username, $password) {
		            $url = LOGIN_WSDL;
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
			            $res = $client->getTourPlusPoint($username, $password);
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
                public function verifyLiveFreeUser($username) {
	                $url = USER_VERIVY;
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
		                $res = $client->verifyLiveFreeUser($username);
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
	            public function insertOrderIntoLivefree($data) {
		            $url = INSERT_ORDER_INTO_LIVEFREE;
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
			            $res = $client->insertOrderIntoLivefree(serialize($data));
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
	            public function checkLiveFreePremiumPoints($username, $password) {
		            $result = $this->isLiveFreeUser($username, $password);
		            return $result->rsp_premium_point;
	            }

	            public function checkLiveFreeGoldenPoints($username, $password) {
		            $result = $this->isLiveFreeUser($username, $password);
		            return $result->rsp_golden_point;
	            }

	            public function updateLiveFreePoints($username, $password, $rsp_golden_point) {
		            $url = UPDATE_POINTS_WSDL;
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
			            $res = $client->updateTourPlusPoint($username, $password, $rsp_golden_point);
		            }catch (Exception $e) {
			            $result = "false";
		            }
		            if(is_soap_fault($res)) {
			            $result = "false";
		            } else {
			            $result = json_decode($res);
		            }
		            return $result;
	            }

				public function livetoursplusLogin($username, $password) {
		            $ch = curl_init();
					//$username = 'ltp910331';
					//$password = 'Sei6vwIo5t';
					$vars = ['Username'=>$username, 'Password'=>$password];
                    curl_setopt($ch, CURLOPT_URL, LOGIN_LIVETOURSPLUS);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($vars));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // On dev server only!
 
                    $response = curl_exec($ch);
					$data = json_decode($response);
					
					return $data;
	            }
				
				public function livetoursplusPointTransaction($username, $password, $invoice_id, $gold_points, $platinum_points) {
		            $ch = curl_init();
					//$username = 'ltp910331';
					//$password = 'Sei6vwIo5t';
					$vars = ['Username'=>$username, 'Password'=>$password, 'InvoiceID'=>$invoice_id, 'GoldPoints'=>$gold_points, 'PlatinumPoints'=> $platinum_points];
                    curl_setopt($ch, CURLOPT_URL, POINT_TRANSACTION_LIVETOURSPLUS);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($vars));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // On dev server only!
 
                    $response = curl_exec($ch);
					$data = json_decode($response);
					
					return $data;
	            }

	            public function testApi() {
		            $url = "http://testapi.travelstart.com/ts-ext-services/flight-service?wsdl";
		            $result = "";
		            $options["connection_timeout"] = 25;
		            $options["location"] = $url;
		            $options['trace'] = true;
		            $options['exceptions'] = true;
		            $options['cache_wsdl'] = WSDL_CACHE_NONE;
		            //$username = "liv1909518";
		            //$password = "pass000";
		
		            $client = new SoapClient($url,$options);
		            $res = $client->search("ghfjgf");
		            $result = $res;
		            return $result;
	            }


	            private function curlTest() {
		            $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'localhost/api-test/api.php');
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, "id=".$email);
                    //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                    $response = curl_exec($ch);
                    $data = json_decode($response);
                    
                    if($data) {
                    	echo "yes";var_dump($data[2]);

                    			$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '1', 
                    				store_id = '0', firstname = 'kimoz', lastname = 'alioz', email = 'kmoms@email.com', 
                    				telephone = '242342342', fax = '242342', custom_field = 'custom', salt = 'salt', password = 'password', newsletter = 'news', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '1', date_added = NOW()");
			            die();
		
		             }else{
                    	echo "no";die();
                    }

                    return $data;
	            }	            
			
	public function getRewardPoints() {
		$query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$this->customer_id . "'");

		return $query->row['total'];
	}
}