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
        $this->unregister_options();
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
            $wpdb->prefix . 'gswpts_tables',
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
    public function unregister_options() {
        $settings_options = [
            'asynchronous_loading',
            'gutenberg_support',
            'elementor_support',
        ];
        foreach ($settings_options as  $option) {
            unregister_setting('gswpts_general_setting', $option);
            delete_option($option);
        }
    }
}
new Plugin_Uninstall;
