<?php

namespace GSWPTS\Includes;

use GSWPTS\Includes\Classes\Controller\Admin_Menus;
use GSWPTS\Includes\Classes\Enqueue_Files;

if (!defined('ABSPATH')) {
    die('you cant access this plugin directly');
}


class Plugin {
    public function __construct() {
        $this->includes();
    }
    public function includes() {
        new Enqueue_Files;
        new Admin_Menus;
    }
}
