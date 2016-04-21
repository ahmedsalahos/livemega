<?php
class ModelLiveshopProduct extends Model {
    public function getUserInfo($user_id) {
        $customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$user_id . "' AND status = '1'");

		if ($customer_query->num_rows) {
		    return $customer_query->rows;
		}
		else    return false;
		   
    }
    public function insert_user_reward_points($order_info) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$order_info['customer_id'] . "' AND order_id = '".(int)$order_info['order_id']."'");

		if ($query->num_rows) {
            $this->db->query("UPDATE " . DB_PREFIX . "customer_reward SET points = (points + ".$order_info['points'].") WHERE order_id = '" . (int)$order_info['order_id'] . "'");		    
		}
		else  {    
            $this->db->query("INSERT INTO " . DB_PREFIX . "customer_reward SET customer_id = '" . (int)$order_info['customer_id'] . "', order_id = '" . (int)$order_info['order_id'] . "', description = '" . $order_info['description'] . "', points = '" . (int)$order_info['points'] . "', date_added = NOW()");  
        }  
    }
}
