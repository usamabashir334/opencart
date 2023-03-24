<?php
class ModelCustomUser extends Model {
	public function add($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "usercrud` SET `crud_name` = '" . $this->db->escape($data['user_name']). "'");
	}
}