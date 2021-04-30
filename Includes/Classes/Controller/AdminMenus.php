<?php

namespace GSWPTS\Includes\Classes\Controller;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheets-to-wp-table-live-sync'));

class AdminMenus {
    public function __construct() {
        add_action('admin_menu', [$this, 'admin_menus']);
    }
    public function admin_menus() {
        add_menu_page(
            __('Sheets To Table', 'sheets-to-wp-table-live-sync'),
            __('Sheets To Table', 'sheets-to-wp-table-live-sync'),
            'manage_options',
            'gswpts-dashboard',
            [get_called_class(), 'gswpts_dashboard'],
            GSWPTS_BASE_URL . 'Assets/Public/Images/logo_20_20.svg',
            10
        );
        add_submenu_page(
            'gswpts-dashboard',
            __('DashBoard', 'sheets-to-wp-table-live-sync'),
            __('DashBoard', 'sheets-to-wp-table-live-sync'),
            'manage_options',
            'gswpts-dashboard',
            [get_called_class(), 'gswpts_dashboard']
        );
        add_submenu_page(
            'gswpts-dashboard',
            __('Manage Tables', 'sheets-to-wp-table-live-sync'),
            __('Manage Tables', 'sheets-to-wp-table-live-sync'),
            'manage_options',
            'gswpts-tables',
            [get_called_class(), 'gswpts_tables']
        );
        add_submenu_page(
            'gswpts-dashboard',
            __('Create Table', 'sheets-to-wp-table-live-sync'),
            __('Create Table', 'sheets-to-wp-table-live-sync'),
            'manage_options',
            'gswpts-create-tables',
            [get_called_class(), 'gswpts_create_tables']
        );
        add_submenu_page(
            'gswpts-dashboard',
            __('General Settings', 'sheets-to-wp-table-live-sync'),
            __('General Settings', 'sheets-to-wp-table-live-sync'),
            'manage_options',
            'gswpts-general-settings',
            [get_called_class(), 'general_settings']
        );
    }
    public static function gswpts_dashboard() {
        load_template(GSWPTS_BASE_PATH . 'Includes/Templates/dashboard.php', true);
    }
    public static function gswpts_tables() {
        load_template(GSWPTS_BASE_PATH . 'Includes/Templates/manage_tables.php', true);
    }
    public static function gswpts_create_tables() {
        load_template(GSWPTS_BASE_PATH . 'Includes/Templates/create_tables.php', true);
    }
    public static function general_settings() {
        load_template(GSWPTS_BASE_PATH . 'Includes/Templates/general_settings.php', true);
    }
}
