<?php

namespace GSWPTS\Includes;

use GSWPTS\Includes\Classes\Controller\Admin_Menus;
use GSWPTS\Includes\Classes\Controller\Ajax_Handler;
use GSWPTS\Includes\Classes\Elementor\Elementor_Init;
use GSWPTS\Includes\Classes\Enqueue_Files;
use GSWPTS\Includes\Classes\Global_Class;
use GSWPTS\Includes\Classes\Settings_API;
use GSWPTS\Includes\Classes\Sortcode;

if (!defined('ABSPATH')) {
    die('you cant access this plugin directly');
}


class Plugin {
    public function __construct() {
        $this->includes();
        $this->global_functions();
    }
    public function includes() {
        new Enqueue_Files;
        new Admin_Menus;
        new Ajax_Handler;
        new Sortcode;
        new Settings_API;
        // new Elementor_Init;
    }
    public function global_functions() {
        global $gswpts;
        $gswpts = new Global_Class;
    }
}
