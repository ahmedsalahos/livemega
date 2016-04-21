<?php
class ModelCatalogCommission extends Model {

	public function addCategoryCommission($data) {
		$this->event->trigger('pre.admin.category.commission.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "category_commission SET category_id = '" . (int)$data['category_id'] . "' ,company_commission = '" . (float)$data['company_commission'] . "', users_commission = '" . (float)$data['users_commission'] . "' , commission_type = '" . $data['commission_type'] . "'");

		$commission_id = $this->db->getLastId();
		
		$this->event->trigger('post.admin.category.commission.add', $commission_id);
	}

	public function editCategoryCommission($category_id, $data = array()) {
		if($this->getCategoryCommission($category_id)) {
			$this->event->trigger('pre.admin.category.commission.edit', $data);
		
			$this->db->query("UPDATE " . DB_PREFIX . "category_commission SET company_commission = '" . (float)$data['company_commission'] . "', users_commission = '" . (float)$data['users_commission'] . "', commission_type = '". $data['commission_type'] ."' WHERE category_id = '" . (int)$category_id . "'");

			$this->event->trigger('post.admin.category.commission.edit', $category_id);
		} else {
			$this->event->trigger('pre.admin.category.commission.add', $data);
			$this->addCategoryCommission($data);
		}
	}

	public function getCategoryCommission($category_id) {
		$query = $this->db->query("SELECT DISTINCT cc.company_commission, cc.users_commission, cc.commission_type FROM " . DB_PREFIX . "category_commission cc WHERE cc.category_id=" . (int)$category_id);

		return $query->row;
	}

	public function getCategoryCommissionPercentage($category_id) {
		$query = $this->db->query("SELECT DISTINCT cc.company_commission, cc.users_commission FROM " . DB_PREFIX . "category_commission cc WHERE cc.commission_type = 'percentage' AND cc.category_id=" . (int)$category_id);

		return $query->row;
	}

	public function deleteCategoryCommission($category_id) {

		$this->db->query("DELETE FROM " . DB_PREFIX . "category_commission WHERE category_id = '" . (int)$category_id . "'");
		$this->cache->delete('category_commission');
	}

	public function addProductCommission($category_id, $data) {
		$this->event->trigger('pre.admin.product.commission.add', $data);
		if(!empty($data['company_commission'])) {
			$company_commission = $data['company_commission'];
		} else {
			$category_commission = $this->getCategoryCommissionPercentage($category_id);
			if($category_commission) {
				$company_commission = ($category_commission['company_commission']/100)*$data['price'];
			} else {
				$company_commission = 0;
			}
		}

		if(!empty($data['users_commission'])) {
			$users_commission = $data['users_commission'];
		} else {
			$category_commission = $this->getCategoryCommissionPercentage($category_id);
			if($category_commission) {
				$users_commission = ($category_commission['users_commission']/100)*$data['price'];
			} else {
				$users_commission = 0;
			}
		}


		$this->db->query("INSERT INTO " . DB_PREFIX . "product_commission SET product_id = '" . (int)$data['product_id'] . "' ,company_commission = '" . (float)$company_commission . "', users_commission = '" . (float)$users_commission . "'");

		$this->event->trigger('post.admin.product.commission.add', $data);
		$commission_id = $this->db->getLastId();
	}

	public function editProductCommission($product_id, $data) {
		
		if($this->getProductCommission($product_id)) { 
			$this->event->trigger('pre.admin.product.commission.edit', $data);
			$this->db->query("UPDATE " . DB_PREFIX . "product_commission SET company_commission = '" . (float)$data['company_commission'] . "', users_commission = '" . (float)$data['users_commission'] . "' WHERE product_id = '" . (int)$product_id . "'");
			$this->event->trigger('post.admin.product.commission.edit', $product_id);
		} else {
			$this->event->trigger('pre.admin.product.commission.add', $data);
			$this->addProductCommission($data['category_id'], $data);
			$this->event->trigger('post.admin.product.commission.add', $data);
		}
	}

	public function getProductCommission($product_id) {
		$query = $this->db->query("SELECT DISTINCT pc.company_commission, pc.users_commission FROM " . DB_PREFIX . "product_commission pc WHERE pc.product_id=" . (int)$product_id);

		return $query->row;
	}

	public function deleteProductCommission($product_id) {
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_commission WHERE product_id = '" . (int)$product_id . "'");
		$this->cache->delete('product_commission');
	}

}