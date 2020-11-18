<?php

namespace GSWPTS\Includes;

use GSWPTS\Includes\Classes\Controller\Admin_Menus;
use GSWPTS\Includes\Classes\Controller\Ajax_Handler;
use GSWPTS\Includes\Classes\Enqueue_Files;
use GSWPTS\Includes\Classes\Global_Class;

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
    }
    public function global_functions() {
        global $gswpts;
        $gswpts = new Global_Class;
    }
}
