<?php
class ModelExtensionPaymentToStore extends Model {
	public function addPaymentToStore($data) {
		if (isset($data['payment_store'])) {
			$extension_id = $data['payment'];
			$this->load->model('extension/extension');
			$extension_data = $this->model_extension_extension->getExtensionById($extension_id);
			foreach ($data['payment_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "extension_to_store SET extension_id = '" . (int)$extension_id . "', store_id = '" . (int)$store_id . "', type = '" . $extension_data['type'] . "',code = '". $extension_data['code'] . "'");
			}
		}
	}

	public function editPaymentToStore($extension_id, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "extension_to_store WHERE extension_id = '" . (int)$extension_id . "'");

		$this->addPaymentToStore($data);
	}

	public function deletePaymentToStore($extension_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "extension_to_store WHERE extension_id = '" . (int)$extension_id . "'");
	}

	public function getPaymentStores($extension_id) {
		$payment_to_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension_to_store WHERE extension_id = '" . (int)$extension_id . "'");

		foreach ($query->rows as $result) {
			$payment_to_store_data[] = $result['store_id'];
		}

		return $payment_to_store_data;
	}

	public function getAllPaymentStores($type) {
		$payment_to_store_data = array();
		$stores = array();
		$payment_all_data = array();

		$query = $this->db->query("SELECT DISTINCT extension_id , code FROM " . DB_PREFIX . "extension_to_store WHERE type = '" . $type . "'" );

		foreach ($query->rows as $result) {
			$stores = $this->getPaymentStores($result['extension_id']);
			$payment_to_store_data['extension_id'] = $result['extension_id'];
			$payment_to_store_data['code'] = $result['code']; 
			$payment_to_store_data['stores'] = $stores;
			$payment_all_data[] = $payment_to_store_data; 
		}

		return $payment_all_data;
	}
}