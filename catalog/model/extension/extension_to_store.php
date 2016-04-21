<?php
class ModelExtensionExtensionToStore extends Model {
	public function getExtensions($store_id, $type) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension_to_store WHERE store_id = '" . (int)$store_id . "' AND type = '". $type ."'");
		return $query->rows;
	}
}