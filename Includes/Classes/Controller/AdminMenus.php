<?php

namespace GSWPTS\Includes\Classes\Controller;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheetstowptable'));

class AdminMenus {
    public function __construct() {
        add_action('admin_menu', [$this, 'admin_menus']);
    }
    public function admin_menus() {
        add_menu_page(
            __('Sheets To Table', 'sheetstowptable'),
            __('Sheets To Table', 'sheetstowptable'),
            'manage_options',
            'gswpts-dashboard',
            [get_called_class(), 'gswpts_dashboard'],
            GSWPTS_BASE_URL . 'Assets/Public/Images/logo_20_20.svg',
            10
        );
        add_submenu_page(
            'gswpts-dashboard',
            __('DashBoard', 'sheetstowptable'),
            __('DashBoard', 'sheetstowptable'),
            'manage_options',
            'gswpts-dashboard',
            [get_called_class(), 'gswpts_dashboard']
        );
        add_submenu_page(
            'gswpts-dashboard',
            __('Manage Tables', 'sheetstowptable'),
            __('Manage Tables', 'sheetstowptable'),
            'manage_options',
            'gswpts-tables',
            [get_called_class(), 'gswpts_tables']
        );
        add_submenu_page(
            'gswpts-dashboard',
            __('Create Table', 'sheetstowptable'),
            __('Create Table', 'sheetstowptable'),
            'manage_options',
            'gswpts-create-tables',
            [get_called_class(), 'gswpts_create_tables']
        );
        add_submenu_page(
            'gswpts-dashboard',
            __('General Settings', 'sheetstowptable'),
            __('General Settings', 'sheetstowptable'),
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
