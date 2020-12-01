<?php
if (!defined('ABSPATH')) {
    die('you cant access this plugin directly');
}

class Plugin_Uninstall {
    public function __construct() {
        $all_sql = $this->all_delete_sql();
        if (!empty($all_sql)) {
            foreach ($all_sql as $sql) {
                $this->delete_tables($sql);
            }
        }
    }
    public function db_connection() {
        $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        return $connection;
    }
    public function delete_tables($sql) {
        $connection = $this->db_connection();
        if ($connection) {
            $connection->query($sql);
        }
    }
    public function db_tables() {
        global $wpdb;
        $tables = [
            $wpdb->prefix . 'gswpts_spreadsheet',
        ];
        return $tables;
    }
    public function all_delete_sql() {
        $sql = [];
        $db_tables = $this->db_tables();
        if (!empty($db_tables)) {
            foreach ($db_tables as $table) {
                $single_sql = "DROP TABLE IF EXISTS `{$table}`";
                array_push($sql, $single_sql);
            }
            return $sql;
        }
    }
}
new Plugin_Uninstall;
