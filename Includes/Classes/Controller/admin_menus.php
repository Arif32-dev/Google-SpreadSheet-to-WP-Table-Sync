<?php

namespace GSWPTS\Includes\Classes\Controller;

use GSWPTS\Includes\Templates\Mange_Tables;

class Admin_Menus {
    public function __construct() {
        add_action('admin_menu', [$this, 'admin_menus']);
        add_action('admin_menu', [$this, 'add_logo']);
    }
    public function admin_menus() {
        add_menu_page(
            _('G SpreadSheet to WP Table', 'gswpts'),
            _('G SpreadSheet to WP Table', 'gswpts'),
            'manage_options',
            'gswpts-dashboard',
            [get_called_class(), 'gswpts_dashboard'],
            'dashicons-media-spreadsheet',
            101
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
    public function add_logo() {
        global $menu;
        $menu[101][6] = GSWPTS_BASE_URL . 'Assets/Public/Backend/Images/Google_Sheets_logo.svg';
    }
    public static function gswpts_dashboard() {
    }
    public static function gswpts_tables() {
        load_template(GSWPTS_BASE_PATH . 'Includes/Templates/manage_tables.php', true);
    }
    public static function gswpts_create_tables() {
        load_template(GSWPTS_BASE_PATH . 'Includes/Templates/create_tables.php', true);
    }
}
