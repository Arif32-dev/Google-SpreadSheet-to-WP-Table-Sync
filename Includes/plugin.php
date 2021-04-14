<?php

namespace GSWPTS\Includes;

defined('ABSPATH') || die('you cant access this plugin directly');

use GSWPTS\Includes\Classes\Controller\AdminMenus;
use GSWPTS\Includes\Classes\Controller\AjaxHandler;
use GSWPTS\Includes\Classes\EnqueueFiles;
use GSWPTS\Includes\Classes\GlobalClass;
use GSWPTS\Includes\Classes\SettingsAPI;
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
        new EnqueueFiles;
        new AdminMenus;
        new AjaxHandler;
        new Sortcode;
        new SettingsAPI;
    }
    public function global_functions() {
        global $gswpts;
        $gswpts = new GlobalClass;
    }
}
