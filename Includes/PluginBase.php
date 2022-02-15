<?php

namespace GSWPTS\includes;

use GSWPTS\includes\classes\ClassSortcode;
use GSWPTS\includes\classes\controller\AdminMenus;
use GSWPTS\includes\classes\controller\AjaxHandler;
use GSWPTS\includes\classes\EnqueueFiles;
use GSWPTS\includes\classes\GlobalClass;
use GSWPTS\includes\classes\Hooks;
use GSWPTS\includes\classes\SettingsApi;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheetstowptable'));

class PluginBase {
    public function __construct() {
        $this->global_functions();
        $this->includes();
        $this->includesFunctions();
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

    public function includesFunctions() {
        require_once GSWPTS_BASE_PATH . 'includes/functions/tab_functions.php';

        global $gswptsTabFunctions;

        $gswptsTabFunctions = new \TabFunctions();
    }
}