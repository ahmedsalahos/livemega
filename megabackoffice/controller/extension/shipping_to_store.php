<?php
class ControllerExtensionShippingToStore extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/shipping_to_store');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/shipping_to_store');
		$this->load->model('setting/setting');

		//var_dump($this->model_extension_payment_to_store->getAllPaymentStores('payment'));die;
		$this->getList();
	}

	public function add() {
		$this->load->language('extension/shipping_to_store');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/shipping_to_store');


		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_shipping_to_store->addShippingToStore($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			$this->response->redirect($this->url->link('extension/shipping_to_store', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('extension/shipping_to_store');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/shipping_to_store');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_shipping_to_store->editShippingToStore($this->request->get['extension_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			$this->response->redirect($this->url->link('extension/shipping_to_store', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('extension/shipping_to_store');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/shipping_to_store');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $extension_id) {
				$this->model_extension_shipping_to_store->deleteShippingToStore($extension_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/shipping_to_store', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		$url = "";
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/shipping_to_store', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		$data['add'] = $this->url->link('extension/shipping_to_store/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('extension/shipping_to_store/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['shippings'] = array();

		$this->load->model('setting/store');
		$results = $this->model_extension_shipping_to_store->getAllShippingStores('shipping');
		
		foreach ($results as $result) {
			$stores = array();
			foreach ($result['stores'] as $store) {
				if($store == 0) {
					$storeName = 'Default';
				} else {
					$storeData = $this->model_setting_store->getStore($store);
					$storeName = $storeData['name']; 
				}
				$stores[] = $storeName;
			}			
			$data['shippings'][] = array(
				'extension_id'=> $result['extension_id'],
				'code'        => $result['code'],
				'stores'  	  => $stores,
				'edit'        => $this->url->link('extension/shipping_to_store/edit', 'token=' . $this->session->data['token'] . '&extension_id=' . $result['extension_id'] . $url, 'SSL'),
				'delete'      => $this->url->link('extension/shipping_to_store/delete', 'token=' . $this->session->data['token'] . '&extension_id=' . $result['extension_id'] . $url, 'SSL')
			);
		}
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_shippings'] = $this->language->get('column_shippings');
		$data['column_stores'] = $this->language->get('column_stores');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/shipping_to_store_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_form'] = !isset($this->request->get['extension_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_shippings'] = $this->language->get('entry_shippings');
		$data['entry_stores'] = $this->language->get('entry_stores');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		$url = '';
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/shipping_to_store', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		if (!isset($this->request->get['extension_id'])) {
			$data['action'] = $this->url->link('extension/shipping_to_store/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/shipping_to_store/edit', 'token=' . $this->session->data['token'] . '&extension_id=' . $this->request->get['extension_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('extension/shipping_to_store', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['extension_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$shippings_info = $this->model_extension_shipping_to_store->getShippingStores($this->request->get['extension_id']);
		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['shipping_stores'])) {
			$data['shipping_store'] = $this->request->post['shipping_store'];
		} elseif (isset($this->request->get['extension_id'])) {
			$data['shipping_store'] = $this->model_extension_shipping_to_store->getShippingStores($this->request->get['extension_id']);
			$data['extension_id'] = $this->request->get['extension_id'];
		} else {
			$data['shipping_store'] = array(0);
		}

		$this->load->model('extension/extension');

		$data['shippings'] = $this->model_extension_extension->getExtensions('shipping');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/shipping_to_store_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/shipping_to_store')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}