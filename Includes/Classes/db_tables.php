<?php

namespace GSWPTS\Includes\Classes;

final class DB_Tables {
    private $sql;
    public function __construct() {
        global $wpdb;
        $collate = $wpdb->get_charset_collate();
        $table = $wpdb->prefix . 'gswpts_spreadsheet';
        $this->sql = "CREATE TABLE {$table} (
                                  id INT(11) NOT NULL AUTO_INCREMENT,
                                  table_name VARCHAR(255) NOT NULL,
                                  sheet_url VARCHAR(255) NOT NULL,
                                  table_sortcode VARCHAR(255) NOT NULL,
                                  UNIQUE KEY id (id)
                                ) DEFAULT CHARSET=$collate";
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        $this->create_tables();
    }
    public function create_tables() {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($this->sql);
    }
}
