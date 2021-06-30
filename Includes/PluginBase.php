<?php

namespace GSWPTS_PRO\Includes;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheetstowptable-pro'));

use GSWPTS_PRO\Includes\Classes\ActionCallbacks;
use GSWPTS_PRO\Includes\Classes\ActionHooks;
use GSWPTS_PRO\Includes\Classes\Elementor\ElementorBase;
use GSWPTS_PRO\Includes\Classes\FilterCallbacks;
use GSWPTS_PRO\Includes\Classes\FilterHooks;
use GSWPTS_PRO\Includes\Classes\GlobalClass;

class PluginBase {
    public function __construct() {
        $this->includeClasses();
    }

    public function includeClasses() {
        new ActionHooks();
        new ActionCallbacks();
        new FilterHooks();
        new FilterCallbacks();
        new ElementorBase();
        global $gswptsPro;
        $gswptsPro = new GlobalClass();
    }
}