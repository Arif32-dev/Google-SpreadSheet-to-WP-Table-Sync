<?php

namespace SWPTS\Includes\Classes\Controller;

class Admin_Menus {
    public function __construct() {
        add_action('admin_menu', [$this, 'admin_menus']);
    }
    public function admin_menus() {
        add_menu_page(
            _('SpreadSheet to wpTable', 'swpts'),
            _('SpreadSheet to wpTable', 'swpts'),
            'manage_options',
            'swpts-dashboard',
            [get_called_class(), 'swpts_dashboard'],
            'dashicons-media-spreadsheet'
        );
        add_submenu_page(
            'swpts-dashboard',
            _('DashBoard', 'swpts'),
            _('DashBoard', 'swpts'),
            'manage_options',
            'swpts-dashboard',
            [get_called_class(), 'swpts_dashboard']
        );
        add_submenu_page(
            'swpts-dashboard',
            _('Manage Tables', 'swpts'),
            _('Manage Tables', 'swpts'),
            'manage_options',
            'swpts-tables',
            [get_called_class(), 'swpts_tables']
        );
        add_submenu_page(
            'swpts-dashboard',
            _('Create Table', 'swpts'),
            _('Create Table', 'swpts'),
            'manage_options',
            'swpts-create-tables',
            [get_called_class(), 'swpts_create_tables']
        );
    }
    public static function swpts_dashboard() {
    }
    public static function swpts_tables() {
    }
    public static function swpts_create_tables() {
    }
}
