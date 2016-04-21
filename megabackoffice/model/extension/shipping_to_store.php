<?php
class ModelExtensionShippingToStore extends Model {
	public function addShippingToStore($data) {
		if (isset($data['shipping_store'])) {
			$extension_id = $data['shipping'];
			$this->load->model('extension/extension');
			$extension_data = $this->model_extension_extension->getExtensionById($extension_id);
			foreach ($data['shipping_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "extension_to_store SET extension_id = '" . (int)$extension_id . "', store_id = '" . (int)$store_id . "', type = '" . $extension_data['type'] . "',code = '". $extension_data['code'] . "'");
			}
		}
	}

	public function editShippingToStore($extension_id, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "extension_to_store WHERE extension_id = '" . (int)$extension_id . "'");

		$this->addShippingToStore($data);
	}

	public function deleteShippingToStore($extension_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "extension_to_store WHERE extension_id = '" . (int)$extension_id . "'");
	}

	public function getShippingStores($extension_id) {
		$shipping_to_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension_to_store WHERE extension_id = '" . (int)$extension_id . "'");

		foreach ($query->rows as $result) {
			$shipping_to_store_data[] = $result['store_id'];
		}

		return $shipping_to_store_data;
	}

	public function getAllShippingStores($type) {
		$shipping_to_store_data = array();
		$stores = array();
		$shipping_all_data = array();

		$query = $this->db->query("SELECT DISTINCT extension_id , code FROM " . DB_PREFIX . "extension_to_store WHERE type = '" . $type . "'" );

		foreach ($query->rows as $result) {
			$stores = $this->getShippingStores($result['extension_id']);
			$shipping_to_store_data['extension_id'] = $result['extension_id'];
			$shipping_to_store_data['code'] = $result['code']; 
			$shipping_to_store_data['stores'] = $stores;
			$shipping_all_data[] = $shipping_to_store_data; 
		}

		return $shipping_all_data;
	}
}