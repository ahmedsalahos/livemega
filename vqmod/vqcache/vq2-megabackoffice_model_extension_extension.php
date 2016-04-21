<?php
class ModelExtensionExtension extends Model {
	public function getInstalled($type) {
		$extension_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "' ORDER BY code");

		foreach ($query->rows as $result) {
			$extension_data[] = $result['code'];
		}

		return $extension_data;
	}


				function getExtensions($type) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'");
					return $query->rows;
				}
			
	public function install($type, $code) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "extension SET `type` = '" . $this->db->escape($type) . "', `code` = '" . $this->db->escape($code) . "'");
	}


				function getExtensionById($extension_id) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `extension_id` = '" . (int)$extension_id . "'");
					return $query->row;
				}
			
	public function uninstall($type, $code) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "' AND `code` = '" . $this->db->escape($code) . "'");
	}
}