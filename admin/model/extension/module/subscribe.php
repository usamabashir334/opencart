<?php
class ModelExtensionModuleSubscribe extends Model {
	public function install() {
		$this->db->query("
			CREATE TABLE `" . DB_PREFIX . "subscribe` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`email` varchar(255) NOT NULL,
				`date_added` timestamp NOT NULL DEFAULT current_timestamp(),
				PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "subscribe`");
	}
}
