<?php

namespace SWPTS\Includes;

use SWPTS\Includes\Classes\Class_Enqueue_Files as Enqueue_Files;
use SWPTS\Includes\Classes\Controller\Admin_Menus;

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
