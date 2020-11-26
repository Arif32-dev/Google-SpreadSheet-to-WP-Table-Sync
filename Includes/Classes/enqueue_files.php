<?php

namespace GSWPTS\Includes\Classes;

if (!defined('ABSPATH')) {
    die('you cant access this plugin directly');
}

class Enqueue_Files {
    public function __construct() {
        add_action('admin_enqueue_scripts', [$this, 'backend_files']);
        add_action('wp_enqueue_scripts', [$this, 'frontend_files']);
    }
    public function backend_files() {
        /* CSS Files */
        wp_enqueue_style('GSWPTS-alert-css', GSWPTS_BASE_URL . 'Assets/Public/Package/alert.min.css', [], GSWPTS_VERSION, 'all');

        /* Javascript Files */
        wp_enqueue_script('jquery');
        wp_enqueue_script('GSWPTS-admin-js', GSWPTS_BASE_URL . 'Assets/Public/Scripts/admin.min.js', ['jquery'], GSWPTS_VERSION, true);
        wp_localize_script('GSWPTS-admin-js', 'file_url', [
            'admin_ajax' => admin_url('admin-ajax.php'),
        ]);
        wp_enqueue_script('GSWPTS-jquery-js', '//code.jquery.com/jquery-3.5.1.min.js');
        wp_enqueue_script('GSWPTS-alert-js', GSWPTS_BASE_URL . 'Assets/Public/Package/alert.min.js', ['GSWPTS-jquery-js'], GSWPTS_VERSION, true);
    }

    public function frontend_files() {
        global $gswpts;
        wp_enqueue_script('jquery');
        wp_enqueue_script('GSWPTS-frontend-js', GSWPTS_BASE_URL . 'Assets/Public/Scripts/frontend.min.js', ['jquery'], GSWPTS_VERSION, true);

        $gswpts->data_table_styles();
        $gswpts->data_table_scripts();
    }
}
