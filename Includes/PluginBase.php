<?php

namespace GSWPTS\Includes;

defined('ABSPATH') || die('you cant access this plugin directly');

use GSWPTS\Includes\Classes\ClassSortcode;
use GSWPTS\Includes\Classes\Controller\AdminMenus;
use GSWPTS\Includes\Classes\Controller\AjaxHandler;
use GSWPTS\Includes\Classes\EnqueueFiles;
use GSWPTS\Includes\Classes\GlobalClass;
use GSWPTS\Includes\Classes\SettingsAPI;

if (!defined('ABSPATH')) {
    die('you cant access this plugin directly');
}



if (!file_exists(GSWPTS_BASE_PATH . 'vendor/autoload.php')) {
    return;
}


class PluginBase {
    public function __construct() {
        $this->includes();
        $this->global_functions();
    }
    public function includes() {
        require GSWPTS_BASE_PATH . 'vendor/autoload.php';
        new EnqueueFiles;
        new AdminMenus;
        new AjaxHandler;
        new ClassSortcode;
        new SettingsAPI;
    }
    public function global_functions() {
        global $gswpts;
        $gswpts = new GlobalClass;
    }
}
