<?php

namespace GSWPTS\Includes\Classes\Controller;

use GSWPTS\Includes\Templates\Mange_Tables;

class Admin_Menus {
    public function __construct() {
        add_action('admin_menu', [$this, 'admin_menus']);
    }
    public function admin_menus() {
        add_menu_page(
            _('G SpreadSheet to WP Table', 'gswpts'),
            _('G SpreadSheet to WP Table', 'gswpts'),
            'manage_options',
            'gswpts-dashboard',
            [get_called_class(), 'gswpts_dashboard'],
            'dashicons-media-spreadsheet'
        );
        add_submenu_page(
            'gswpts-dashboard',
            _('DashBoard', 'gswpts'),
            _('DashBoard', 'gswpts'),
            'manage_options',
            'gswpts-dashboard',
            [get_called_class(), 'gswpts_dashboard']
        );
        add_submenu_page(
            'gswpts-dashboard',
            _('Manage Tables', 'gswpts'),
            _('Manage Tables', 'gswpts'),
            'manage_options',
            'gswpts-tables',
            [get_called_class(), 'gswpts_tables']
        );
        add_submenu_page(
            'gswpts-dashboard',
            _('Create Table', 'gswpts'),
            _('Create Table', 'gswpts'),
            'manage_options',
            'gswpts-create-tables',
            [get_called_class(), 'gswpts_create_tables']
        );
    }
    public static function gswpts_dashboard() {
    }
    public static function gswpts_tables() {
        wp_enqueue_style('GSWPTS-bootstap-css', GSWPTS_BASE_URL . 'Assets/Public/Common/bootstrap-dist/css/bootstrap.min.css', [], GSWPTS_VERSION, 'all');
        wp_enqueue_script('GSWPTS-bootstap-js', GSWPTS_BASE_URL . 'Assets/Public/Common/bootstrap-dist/js/bootstrap.min.js', ['jquery'], GSWPTS_VERSION, true);
        load_template(GSWPTS_BASE_PATH . 'Includes/Templates/manage_tables.php', true);
    }
    public static function gswpts_create_tables() {
        wp_enqueue_style('GSWPTS-bootstap-css', GSWPTS_BASE_URL . 'Assets/Public/Common/bootstrap-dist/css/bootstrap.min.css', [], GSWPTS_VERSION, 'all');
        wp_enqueue_script('GSWPTS-bootstap-js', GSWPTS_BASE_URL . 'Assets/Public/Common/bootstrap-dist/js/bootstrap.min.js', ['jquery'], GSWPTS_VERSION, true);
        load_template(GSWPTS_BASE_PATH . 'Includes/Templates/create_tables.php', true);
    }
}
