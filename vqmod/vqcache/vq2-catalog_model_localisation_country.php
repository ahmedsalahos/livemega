<?php
class ModelLocalisationCountry extends Model {
	public function getCountry($country_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "' AND status = '1'");

		return $query->row;
	}

	public function getCountries() {
		
				//$country_data = $this->cache->get('country.status');
			

		if (!$country_data) {
			
				if($this->config->get('config_store_id') == 1) {
					$countries = array();
					$countries = array_map('intval', explode('-', GULF_COUNTRIES));
					$countries = implode("','",$countries);
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id IN ('".$countries."') AND status = '1' ORDER BY name ASC");
				} else {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE status = '1' ORDER BY name ASC");
				}
			

			$country_data = $query->rows;

			$this->cache->set('country.status', $country_data);
		}

		return $country_data;
	}
}