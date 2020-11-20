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
        /* CSS Files */

        /* Javascript Files */
        wp_enqueue_script('jquery');
        wp_enqueue_script('GSWPTS-admin-js', GSWPTS_BASE_URL . 'Assets/Public/Backend/Scripts/admin.min.js', ['jquery'], GSWPTS_VERSION, true);
        wp_localize_script('GSWPTS-admin-js', 'file_url', [
            'admin_ajax' => admin_url('admin-ajax.php'),
        ]);
    }
}
