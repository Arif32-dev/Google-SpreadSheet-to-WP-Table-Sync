<?php

namespace SWPTS\Includes\Classes;

if (!defined('ABSPATH')) {
    die('you cant access this plugin directly');
}

class Class_Enqueue_Files {
    public function __construct() {
        add_action('admin_enqueue_scripts', [$this, 'backend_files']);
    }
    public function backend_files() {
        // wp_enqueue_style('swpts-bootstrap-css', '//cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css');
        // wp_enqueue_script('swpts-bootstrap-js', '//cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js');
    }
}
