<?php
class ModelExtensionModuleCruduser extends Model {
	public function install() {
		$this->db->query("
			CREATE TABLE `" . DB_PREFIX . "cruduser` (
				`cruduser_id` INT(11) NOT NULL AUTO_INCREMENT,
				`cruduser_name` varchar(255) NOT NULL,
				`phone_number` varchar(15) NOT NULL,
				`sort_order` INT(11) NOT NULL,
				`date_added` timestamp NOT NULL DEFAULT current_timestamp(),
				PRIMARY KEY (`cruduser_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "cruduser`");
	}

	public function addCruduser($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "cruduser SET sort_order = '" . (int)$data['sort_order'] . "', cruduser_name = '" . $data['cruduser_name'] . "', phone_number = '" . $data['phone_number'] . "'");
	}

	public function editCruduser($cruduser_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "cruduser SET cruduser_name = '" . $data['cruduser_name'] . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE cruduser_id = '" . (int)$cruduser_id . "'");
	}

	public function deleteCruduser($cruduser_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "cruduser WHERE cruduser_id = '" . (int)$cruduser_id . "'");
	}

	public function getCruduserInfo($cruduser_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cruduser WHERE cruduser_id = '" . (int)$cruduser_id . "'");

		return $query->row;
	}

	public function getCruduser($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "cruduser";

		$sort_data = array(
			'cruduser_name',
			'date_added',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY cruduser_name, date_added";
		}
		

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalCruduser() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "cruduser");

		return $query->row['total'];
	}
}