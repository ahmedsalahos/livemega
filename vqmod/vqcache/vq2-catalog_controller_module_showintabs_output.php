<?php  
class ControllerModuleShowintabsoutput extends Controller {
	public function index($setting) {
    	$this->load->language('module/showintabs');
    	$this->load->model('catalog/product');
  		
		$data['text_tax'] = $this->language->get('text_tax');
		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');
		
		$data['cosyone_category_per_row'] = $this->config->get('cosyone_category_per_row');
		$data['cosyone_rollover_effect'] = $this->config->get('cosyone_rollover_effect');
		$data['cosyone_percentage_sale_badge'] = $this->config->get('cosyone_percentage_sale_badge');
		$cosyone_quicklook = $this->config->get('cosyone_text_ql');
		$data['cosyone_text_ql'] = html_entity_decode($cosyone_quicklook[$this->language->get('code')], ENT_QUOTES, 'UTF-8');
		$data['cosyone_brand'] = $this->config->get('cosyone_brand');
		
		$data['carousel'] = $setting['carousel'];
		$data['columns'] = $setting['columns'];
		
		static $module = 0;
		
		$data['tabs'] = array();

		$this->load->model('tool/image');
		
		$tabs = $this->config->get('showintabs_tab');
		
		$tabs = isset($tabs) ? $tabs : array();

    	foreach ($tabs as $key => $tab) {
			if(in_array($key, $setting['selected_tabs']['tabs'])) {
				if (!empty($tab['title'][$this->config->get('config_language_id')])) {
					$title = $tab['title'][$this->config->get('config_language_id')];
				}else{
					$title = $this->language->get('heading_default');
				}	
	
				$products = array();
	
				switch ($tab['data_source']) {
					case 'SP': //Select Products
						$results = $this->getSelectProducts($tab,$setting['limit']);
						break;
					case 'PG': //Product Group
						$results = $this->getProductGroups($tab,$setting['limit']);
						break;
					case 'CQ': //Custom Query
						$results = $this->getCustomQuery($tab,$setting['limit']);
						break;
					default:
						$this->log->write('SHOW_IN_TAB::ERROR: The tab don\'t have product configured.');
						break;
				}
				
	
				//Preparo la info de cada producto
				foreach ($results as $result) {
					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $setting['image_width'], $setting['image_height']);
					} else {
						$image = false;
					}
					
					$images = $this->model_catalog_product->getProductImages($result['product_id']);
					if(isset($images[0]['image']) && !empty($images[0]['image'])){
					$images =$images[0]['image'];
				   	} else {
					$images = false;
					}
					
					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));

				if($this->customer->isLogged() && $this->customer->getType() == 'livetoursplus') {
					$USD_price = $this->currency->format($this->currency->convert($result['price'], $result['tax_class_id'], $this->config->get('config_tax'), $this->currency->getCode ,'USD'), 'USD');
				}
			
					} else {
						$price = false;

				$USD_price = false;
			
					}
							
					if ((float)$result['special']) {
						$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
					} else {
						$special = false;
					}
					
					if ((float)$result['special']) {
					$sales_percantage = ((($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')))-($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'))))/(($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')))/100));
					} else {
					$sales_percantage = false;
					}
					
					if ((float)$result['special']) {
    				$special_info = $this->model_catalog_product->getSpecialPriceEnd($result['product_id']);
        			$special_date_end = strtotime($special_info['date_end']) - time();
    				} else {
        			$special_date_end = false;
    				}
					
					if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
					} else {
						$tax = false;
					}	
					
					if ($this->config->get('config_review_status')) {
						$rating = $result['rating'];
					} else {
						$rating = false;
					}
					if(isset($this->session->data['customer_id'])) $user_id = $this->session->data['customer_id'];
                                        else $user_id = "";
                                        $shareoptions = base64_encode(serialize(array('product_id' => $result['product_id'],'user_id' => $user_id,'quantity' => 1)));
                                        $share_url = $this->url->link('liveshop/product/addPromotedBy&shareoptions='.$shareoptions, '', 'SSL');
                                        $current_url = $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
                                        

                        $this->load->model('liveshop/product');  
                        $user_commission = $this->liveshop->get_product_commission((int)$result['product_id']);
                        $lfp_plus = ($user_commission /10) * 3;

                        if(isset($this->session->data['customer_id'])) $user_id = $this->session->data['customer_id'];
                        else $user_id = "";
                        $productid = $result['product_id'];
                        $shareoptions = base64_encode(serialize(array('product_id' => $productid,'user_id' => $user_id,'quantity' => 1)));
                        $share_url = $this->url->link('liveshop/product/addPromotedBy&shareoptions='.$shareoptions, '', 'SSL');
                        $data['store_id'] = $this->config->get('config_store_id'); 
			
					$products[] = array('product_id' => $result['product_id'],
						'thumb'   	 => $image,
						'name'    	 => $result['name'],
						'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
     
		        'lfp' => $lfp_plus,
                'share_url' => $share_url,				    
			
                                                'share_url' => $share_url,	
                                                'price'   	 => $price,
						'special' 	 => $special,
						'tax'         => $tax,
						'rating'     => $rating,

				'USD_price'     => $USD_price,
			
						'reviews'    => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
						'href'    	 => $this->url->link('product/product', 'product_id=' . $result['product_id']),
						'quickview'  => $this->url->link('product/quickview', 'product_id=' . $result['product_id'], '', 'SSL'),
						'sales_percantage' => number_format($sales_percantage, 0, ',', '.'),
						'special_date_end' => $special_date_end,
						'brand_name'		=> $result['manufacturer'],
						 
				'thumb_hover'  => $this->model_tool_image->resize($images, $setting['image_width'], $setting['image_width']),
		        'attributes'   => $this->model_catalog_product->getProductAttributes($result['product_id'])			    
			
					);
				}	

				$data['tabs'][] = array(
					'title' => $title,
					'products' => $products
				);
			}
    	}
		
		
    	$data['button_cart'] = $this->language->get('button_cart');
		
		$data['module'] = $module++;

		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/showintabs_output.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/showintabs_output.tpl', $data);
		} else {
			return $this->load->view('default/template/module/showintabs_output.tpl', $data);
		}
  	}

  	// Obtiene los productos de los grupos predefinidos de opencart
  	private function getProductGroups( $tabInfo , $limit ){
  		$results = array();

  		//Obtengo listado de productos en funcion del criterio
  		switch ( $tabInfo['product_group'] ) {
  			case 'BS':
  				$results = $this->model_catalog_product->getBestSellerProducts($limit);
  				break;
  			case 'LA':
  				$results = $this->model_catalog_product->getLatestProducts($limit);
  				break;
  			case 'SP':
  				$results = $this->model_catalog_product->getProductSpecials(array('start' => 0,'limit' => $limit));
  				break;
  			case 'PP':
  				$results = $this->model_catalog_product->getPopularProducts($limit);
  				break;
  		}

  		return $results;
  	}

	// Obtiene los productos seleccionados por el user con toda la info necesaria
  	private function getSelectProducts( $tabInfo , $limit ){
  		$results = array();

  		if(isset($tabInfo['products'])){
  			$limit_count = 0;
			foreach ( $tabInfo['products'] as $product ) {
				if ($limit_count++ == $limit) break;
				$results[$product['product_id']] = $this->model_catalog_product->getProduct($product['product_id']);
			}
		}

		return $results;
  	}

  	//Obtiene los productos segun los datos del custom query
  	private function getCustomQuery( $tabInfo , $limit){
  		$results = array();

  		if ( $tabInfo['sort'] == 'rating' || $tabInfo['sort'] == 'p.date_added') {
  			$order = 'DESC';
  		}else{
  			$order = 'ASC';
  		}

  		$data = array(
  			'filter_category_id' => $tabInfo['filter_category']=='ALL' ? '' : $tabInfo['filter_category'], 
  			'filter_manufacturer_id' => $tabInfo['filter_manufacturer']=='ALL' ? '' : $tabInfo['filter_manufacturer'], 
  			'sort' => $tabInfo['sort'], 
  			'order' => $order,
  			'start' => 0,
  			'limit' => $limit
  		);

  		$results = $this->model_catalog_product->getProducts($data);

		return $results;
  	}

}
