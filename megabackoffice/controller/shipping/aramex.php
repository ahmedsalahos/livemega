<?php
class ControllerShippingAramex extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('shipping/aramex');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('aramex', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_regular_pickup'] = $this->language->get('text_regular_pickup');
		$data['text_request_courier'] = $this->language->get('text_request_courier');
		$data['text_drop_box'] = $this->language->get('text_drop_box');
		$data['text_business_service_center'] = $this->language->get('text_business_service_center');
		$data['text_station'] = $this->language->get('text_station');

		$data['text_aramex_envelope'] = $this->language->get('text_aramex_envelope');
		$data['text_aramex_pak'] = $this->language->get('text_aramex_pak');
		$data['text_aramex_box'] = $this->language->get('text_aramex_box');
		$data['text_aramex_tube'] = $this->language->get('text_aramex_tube');
		$data['text_aramex_10kg_box'] = $this->language->get('text_aramex_10kg_box');
		$data['text_aramex_25kg_box'] = $this->language->get('text_aramex_25kg_box');
		$data['text_your_packaging'] = $this->language->get('text_your_packaging');
		$data['text_list_rate'] = $this->language->get('text_list_rate');
		$data['text_account_rate'] = $this->language->get('text_account_rate');

//		$data['entry_key'] = $this->language->get('entry_key');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_account'] = $this->language->get('entry_account');
                $data['entry_pin'] = $this->language->get('entry_pin');
                $data['entry_username'] = $this->language->get('entry_username');
                $data['entry_version'] = $this->language->get('entry_version');
                $data['entry_countrycode'] = $this->language->get('entry_countrycode');
                $data['entry_entity'] = $this->language->get('entry_entity');
//		$data['entry_meter'] = $this->language->get('entry_meter');
//		$data['entry_postcode'] = $this->language->get('entry_postcode');
//		$data['entry_test'] = $this->language->get('entry_test');
//		$data['entry_service'] = $this->language->get('entry_service');
//		$data['entry_dimension'] = $this->language->get('entry_dimension');
//		$data['entry_length_class'] = $this->language->get('entry_length_class');
//		$data['entry_length'] = $this->language->get('entry_length');
//		$data['entry_width'] = $this->language->get('entry_width');
//		$data['entry_height'] = $this->language->get('entry_height');
//		$data['entry_dropoff_type'] = $this->language->get('entry_dropoff_type');
//		$data['entry_packaging_type'] = $this->language->get('entry_packaging_type');
//		$data['entry_rate_type'] = $this->language->get('entry_rate_type');
//		$data['entry_display_time'] = $this->language->get('entry_display_time');
//		$data['entry_display_weight'] = $this->language->get('entry_display_weight');
//		$data['entry_weight_class'] = $this->language->get('entry_weight_class');
//		$data['entry_weight_class'] = $this->language->get('entry_weight_class');
//		$data['entry_tax_class'] = $this->language->get('entry_tax_class');
//		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_display_time'] = $this->language->get('help_display_time');
		$data['help_length_class'] = $this->language->get('help_length_class');
		$data['help_display_weight'] = $this->language->get('help_display_weight');
		$data['help_weight_class'] = $this->language->get('help_weight_class');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

//		if (isset($this->error['key'])) {
//			$data['error_key'] = $this->error['key'];
//		} else {
//			$data['error_key'] = '';
//		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['account'])) {
			$data['error_account'] = $this->error['account'];
		} else {
			$data['error_account'] = '';
		}

		if (isset($this->error['pin'])) {
			$data['error_pin'] = $this->error['pin'];
		} else {
			$data['error_pin'] = '';
		}
                
                if (isset($this->error['username'])) {
			$data['error_username'] = $this->error['username'];
		} else {
			$data['error_username'] = '';
		}
                
                if (isset($this->error['countrycode'])) {
			$data['error_countrycode'] = $this->error['countrycode'];
		} else {
			$data['error_countrycode'] = '';
		}
               
                if (isset($this->error['entity'])) {
			$data['error_entity'] = $this->error['entity'];
		} else {
			$data['error_entity'] = '';
		}
                
                if (isset($this->error['version'])) {
			$data['error_version'] = $this->error['version'];
		} else {
			$data['error_version'] = '';
		}
                
//		if (isset($this->error['meter'])) {
//			$data['error_meter'] = $this->error['meter'];
//		} else {
//			$data['error_meter'] = '';
//		}
//
//		if (isset($this->error['postcode'])) {
//			$data['error_postcode'] = $this->error['postcode'];
//		} else {
//			$data['error_postcode'] = '';
//		}
//		
//		if (isset($this->error['postcode'])) {
//			$data['error_postcode'] = $this->error['postcode'];
//		} else {
//			$data['error_postcode'] = '';
//		}
//		
//		if (isset($this->error['dimension'])) {
//			$data['error_dimension'] = $this->error['dimension'];
//		} else {
//			$data['error_dimension'] = '';
//		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_shipping'),
			'href' => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('shipping/aramex', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('shipping/aramex', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');

//		if (isset($this->request->post['aramex_key'])) {
//			$data['aramex_key'] = $this->request->post['aramex_key'];
//		} else {
//			$data['aramex_key'] = $this->config->get('aramex_key');
//		}

		if (isset($this->request->post['aramex_password'])) {
			$data['aramex_password'] = $this->request->post['aramex_password'];
		} else {
			$data['aramex_password'] = $this->config->get('aramex_password');
		}

		if (isset($this->request->post['aramex_account'])) {
			$data['aramex_account'] = $this->request->post['aramex_account'];
		} else {
			$data['aramex_account'] = $this->config->get('aramex_account');
		}
                if (isset($this->request->post['aramex_pin'])) {
			$data['aramex_pin'] = $this->request->post['aramex_pin'];
		} else {
			$data['aramex_pin'] = $this->config->get('aramex_pin');
		}
                if (isset($this->request->post['aramex_username'])) {
			$data['aramex_username'] = $this->request->post['aramex_username'];
		} else {
			$data['aramex_username'] = $this->config->get('aramex_username');
		}
                if (isset($this->request->post['aramex_countrycode'])) {
			$data['aramex_countrycode'] = $this->request->post['aramex_countrycode'];
		} else {
			$data['aramex_countrycode'] = $this->config->get('aramex_countrycode');
		}
                if (isset($this->request->post['aramex_entity'])) {
			$data['aramex_entity'] = $this->request->post['aramex_entity'];
		} else {
			$data['aramex_entity'] = $this->config->get('aramex_entity');
		}
                if (isset($this->request->post['aramex_version'])) {
			$data['aramex_version'] = $this->request->post['aramex_version'];
		} else {
			$data['aramex_version'] = $this->config->get('aramex_version');
		}
//		if (isset($this->request->post['aramex_meter'])) {
//			$data['aramex_meter'] = $this->request->post['aramex_meter'];
//		} else {
//			$data['aramex_meter'] = $this->config->get('aramex_meter');
//		}
//
//		if (isset($this->request->post['aramex_postcode'])) {
//			$data['aramex_postcode'] = $this->request->post['aramex_postcode'];
//		} else {
//			$data['aramex_postcode'] = $this->config->get('aramex_postcode');
//		}
//
//		if (isset($this->request->post['aramex_test'])) {
//			$data['aramex_test'] = $this->request->post['aramex_test'];
//		} else {
//			$data['aramex_test'] = $this->config->get('aramex_test');
//		}
//
//		if (isset($this->request->post['aramex_service'])) {
//			$data['aramex_service'] = $this->request->post['aramex_service'];
//		} elseif ($this->config->has('aramex_service')) {
//			$data['aramex_service'] = $this->config->get('aramex_service');
//		} else {
//			$data['aramex_service'] = array();
//		}
//
//		$data['services'] = array();
//
//		$data['services'][] = array(
//			'text'  => $this->language->get('text_europe_first_international_priority'),
//			'value' => 'EUROPE_FIRST_INTERNATIONAL_PRIORITY'
//		);
//
//		$data['services'][] = array(
//			'text'  => $this->language->get('text_aramex_1_day_freight'),
//			'value' => 'ARAMEX_1_DAY_FREIGHT'
//		);
//
//		$data['services'][] = array(
//			'text'  => $this->language->get('text_aramex_2_day'),
//			'value' => 'ARAMEX_2_DAY'
//		);
//
//		$data['services'][] = array(
//			'text'  => $this->language->get('text_aramex_2_day_am'),
//			'value' => 'ARAMEX_2_DAY_AM'
//		);
//
//		$data['services'][] = array(
//			'text'  => $this->language->get('text_aramex_2_day_freight'),
//			'value' => 'ARAMEX_2_DAY_FREIGHT'
//		);
//
//		$data['services'][] = array(
//			'text'  => $this->language->get('text_aramex_3_day_freight'),
//			'value' => 'ARAMEX_3_DAY_FREIGHT'
//		);
//
//		$data['services'][] = array(
//			'text'  => $this->language->get('text_aramex_express_saver'),
//			'value' => 'ARAMEX_EXPRESS_SAVER'
//		);
//
//		$data['services'][] = array(
//			'text'  => $this->language->get('text_aramex_first_freight'),
//			'value' => 'ARAMEX_FIRST_FREIGHT'
//		);
//
//		$data['services'][] = array(
//			'text'  => $this->language->get('text_aramex_freight_economy'),
//			'value' => 'ARAMEX_FREIGHT_ECONOMY'
//		);
//
//		$data['services'][] = array(
//			'text'  => $this->language->get('text_aramex_freight_priority'),
//			'value' => 'ARAMEX_FREIGHT_PRIORITY'
//		);
//
//		$data['services'][] = array(
//			'text'  => $this->language->get('text_aramex_ground'),
//			'value' => 'ARAMEX_GROUND'
//		);
//
//		$data['services'][] = array(
//			'text'  => $this->language->get('text_first_overnight'),
//			'value' => 'FIRST_OVERNIGHT'
//		);
//
//		$data['services'][] = array(
//			'text'  => $this->language->get('text_ground_home_delivery'),
//			'value' => 'GROUND_HOME_DELIVERY'
//		);
//
//		$data['services'][] = array(
//			'text'  => $this->language->get('text_international_economy'),
//			'value' => 'INTERNATIONAL_ECONOMY'
//		);
//
//		$data['services'][] = array(
//			'text'  => $this->language->get('text_international_economy_freight'),
//			'value' => 'INTERNATIONAL_ECONOMY_FREIGHT'
//		);
//
//		$data['services'][] = array(
//			'text'  => $this->language->get('text_international_first'),
//			'value' => 'INTERNATIONAL_FIRST'
//		);
//
//		$data['services'][] = array(
//			'text'  => $this->language->get('text_international_priority'),
//			'value' => 'INTERNATIONAL_PRIORITY'
//		);
//
//		$data['services'][] = array(
//			'text'  => $this->language->get('text_international_priority_freight'),
//			'value' => 'INTERNATIONAL_PRIORITY_FREIGHT'
//		);
//
//		$data['services'][] = array(
//			'text'  => $this->language->get('text_priority_overnight'),
//			'value' => 'PRIORITY_OVERNIGHT'
//		);
//
//		$data['services'][] = array(
//			'text'  => $this->language->get('text_smart_post'),
//			'value' => 'SMART_POST'
//		);
//
//		$data['services'][] = array(
//			'text'  => $this->language->get('text_standard_overnight'),
//			'value' => 'STANDARD_OVERNIGHT'
//		);
//		
//		
//		if (isset($this->request->post['aramex_length'])) {
//			$data['aramex_length'] = $this->request->post['aramex_length'];
//		} else {
//			$data['aramex_length'] = $this->config->get('aramex_length');
//		}
//		
//		if (isset($this->request->post['aramex_width'])) {
//			$data['aramex_width'] = $this->request->post['aramex_width'];
//		} else {
//			$data['aramex_width'] = $this->config->get('aramex_width');
//		}
//		
//		if (isset($this->request->post['aramex_height'])) {
//			$data['aramex_height'] = $this->request->post['aramex_height'];
//		} else {
//			$data['aramex_height'] = $this->config->get('aramex_height');
//		}
//		
//		if (isset($this->request->post['aramex_length_class_id'])) {
//			$data['aramex_length_class_id'] = $this->request->post['aramex_length_class_id'];
//		} else {
//			$data['aramex_length_class_id'] = $this->config->get('aramex_length_class_id');
//		}
//
//		$this->load->model('localisation/length_class');
//
//		$data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();
//		
//		if (isset($this->request->post['aramex_dropoff_type'])) {
//			$data['aramex_dropoff_type'] = $this->request->post['aramex_dropoff_type'];
//		} else {
//			$data['aramex_dropoff_type'] = $this->config->get('aramex_dropoff_type');
//		}
//
//		if (isset($this->request->post['aramex_packaging_type'])) {
//			$data['aramex_packaging_type'] = $this->request->post['aramex_packaging_type'];
//		} else {
//			$data['aramex_packaging_type'] = $this->config->get('aramex_packaging_type');
//		}
//
//		if (isset($this->request->post['aramex_rate_type'])) {
//			$data['aramex_rate_type'] = $this->request->post['aramex_rate_type'];
//		} else {
//			$data['aramex_rate_type'] = $this->config->get('aramex_rate_type');
//		}
//
//		if (isset($this->request->post['aramex_destination_type'])) {
//			$data['aramex_destination_type'] = $this->request->post['aramex_destination_type'];
//		} else {
//			$data['aramex_destination_type'] = $this->config->get('aramex_destination_type');
//		}
//
//		if (isset($this->request->post['aramex_display_time'])) {
//			$data['aramex_display_time'] = $this->request->post['aramex_display_time'];
//		} else {
//			$data['aramex_display_time'] = $this->config->get('aramex_display_time');
//		}
//
//		if (isset($this->request->post['aramex_display_weight'])) {
//			$data['aramex_display_weight'] = $this->request->post['aramex_display_weight'];
//		} else {
//			$data['aramex_display_weight'] = $this->config->get('aramex_display_weight');
//		}
//
//		if (isset($this->request->post['aramex_weight_class_id'])) {
//			$data['aramex_weight_class_id'] = $this->request->post['aramex_weight_class_id'];
//		} else {
//			$data['aramex_weight_class_id'] = $this->config->get('aramex_weight_class_id');
//		}
//
//		$this->load->model('localisation/weight_class');
//
//		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();
//
//		if (isset($this->request->post['aramex_tax_class_id'])) {
//			$data['aramex_tax_class_id'] = $this->request->post['aramex_tax_class_id'];
//		} else {
//			$data['aramex_tax_class_id'] = $this->config->get('aramex_tax_class_id');
//		}
//
//		$this->load->model('localisation/tax_class');
//
//		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
//
//		if (isset($this->request->post['aramex_geo_zone_id'])) {
//			$data['aramex_geo_zone_id'] = $this->request->post['aramex_geo_zone_id'];
//		} else {
//			$data['aramex_geo_zone_id'] = $this->config->get('aramex_geo_zone_id');
//		}
//
//		$this->load->model('localisation/geo_zone');
//
//		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
//
		if (isset($this->request->post['aramex_status'])) {
			$data['aramex_status'] = $this->request->post['aramex_status'];
		} else {
			$data['aramex_status'] = $this->config->get('aramex_status');
		}

		if (isset($this->request->post['aramex_sort_order'])) {
			$data['aramex_sort_order'] = $this->request->post['aramex_sort_order'];
		} else {
			$data['aramex_sort_order'] = $this->config->get('aramex_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('shipping/aramex.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/aramex')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

//		if (!$this->request->post['aramex_key']) {
//			$this->error['key'] = $this->language->get('error_key');
//		}

		if (!$this->request->post['aramex_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if (!$this->request->post['aramex_account']) {
			$this->error['account'] = $this->language->get('error_account');
		}
                
                if (!$this->request->post['aramex_pin']) {
                    $this->error['pin'] = $this->language->get('error_pin');
		}
                
                if (!$this->request->post['aramex_countrycode']) {
                    $this->error['countrycode'] = $this->language->get('error_countrycode');
		}
                 
                if (!$this->request->post['aramex_entity']) {
                    $this->error['entity'] = $this->language->get('error_entity');
		}
                if (!$this->request->post['aramex_version']) {
                    $this->error['version'] = $this->language->get('error_version');
		}
                
                if (!$this->request->post['aramex_username']) {
			$this->error['username'] = $this->language->get('error_username');
		}
                
//		if (!$this->request->post['aramex_meter']) {
//			$this->error['meter'] = $this->language->get('error_meter');
//		}
//
//		if (!$this->request->post['aramex_postcode']) {
//			$this->error['postcode'] = $this->language->get('error_postcode');
//		}
//		
//		if (!$this->request->post['aramex_length'] || !$this->request->post['aramex_width'] || !$this->request->post['aramex_width']) {
//			$this->error['dimension'] = $this->language->get('error_dimension');
//		}		

		return !$this->error;
	}
}