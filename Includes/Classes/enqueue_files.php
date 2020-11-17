<?php

namespace GSWPTS\Includes\Classes;

if (!defined('ABSPATH')) {
    die('you cant access this plugin directly');
}

class Enqueue_Files {
    public function __construct() {
        add_action('admin_enqueue_scripts', [$this, 'backend_files']);
    }
    public function backend_files() {
        wp_enqueue_style('GSWPTS-dataTable-css', GSWPTS_BASE_URL . 'Assets/Public/Common/DataTables/datatables.min.css', [], GSWPTS_VERSION, 'all');
        wp_enqueue_script('GSWPTS-dataTable-js', GSWPTS_BASE_URL . 'Assets/Public/Common/DataTables/datatables.min.js', [], GSWPTS_VERSION, true);
        wp_enqueue_script('GSWPTS-admin-js', GSWPTS_BASE_URL . 'Assets/Public/Backend/Scripts/admin.min.js', ['jquery'], GSWPTS_VERSION, true);
    }
}
