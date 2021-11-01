<?php

defined('WP_UNINSTALL_PLUGIN') || wp_die(__('You can\'t access this page', 'sheetstowptable'));

class SheetsToWPTableLiveSyncUninstall {
    public function __construct() {
        $all_sql = $this->all_delete_sql();
        if (!empty($all_sql)) {
            foreach ($all_sql as $sql) {
                $this->delete_tables($sql);
            }
        }
        $this->unregister_options();
    }

    /**
     * @return mixed
     */
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

    /**
     * @return mixed
     */
    public function db_connection() {
        $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        return $connection;
    }

    /**
     * @return mixed
     */
    public function db_tables() {
        global $wpdb;
        $tables = [
            $wpdb->prefix . 'gswpts_tables'
        ];
        return $tables;
    }

    /**
     * @param $sql
     */
    public function delete_tables($sql) {
        $connection = $this->db_connection();
        if ($connection) {
            $connection->query($sql);
        }
    }

    public function unregister_options() {
        $savedOptions = [
            'gswptsActivationTime',
            'gswptsReviewNotice',
            'deafaultNoticeInterval',
            'gswptsAffiliateNotice',
            'deafaultAffiliateInterval',
            'asynchronous_loading',
            'multiple_sheet_tab',
            'custom_css',
            'css_code_value'
        ];

        foreach ($savedOptions as $option) {
            unregister_setting('gswpts_general_setting', $option);
            delete_option($option);
        }
    }
}

if (!class_exists('SheetsToWPTableLiveSyncUninstall')) {
    return;
}

new SheetsToWPTableLiveSyncUninstall();