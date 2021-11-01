<?php

namespace GSWPTS\Includes;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheetstowptable'));

use GSWPTS\Includes\Classes\ClassSortcode;
use GSWPTS\Includes\Classes\Controller\AdminMenus;
use GSWPTS\Includes\Classes\Controller\AjaxHandler;
use GSWPTS\Includes\Classes\EnqueueFiles;
use GSWPTS\Includes\Classes\GlobalClass;
use GSWPTS\Includes\Classes\Hooks;
use GSWPTS\Includes\Classes\SettingsApi;

class PluginBase {
    public function __construct() {
        $this->includes();
        $this->global_functions();
    }

    public function global_functions() {
        global $gswpts;
        $gswpts = new GlobalClass();
    }

    public function includes() {
        new EnqueueFiles();
        new AdminMenus();
        new AjaxHandler();
        new ClassSortcode();
        new SettingsApi();
        new Hooks();
    }
}