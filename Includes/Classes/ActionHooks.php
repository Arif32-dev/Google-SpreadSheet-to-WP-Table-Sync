<?php

namespace GSWPTS_PRO\Includes\Classes;

use GSWPTS_PRO\Includes\Classes\ActionCallbacks;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheetstowptable-pro'));

class ActionHooks extends ActionCallbacks {
    public function __construct() {
        add_action('gswpts_export_dependency_backend', [$this, 'tableExportDependencies']);
        add_action('gswpts_export_dependency_frontend', [$this, 'tableExportDependencies']);
        add_action('gswpts_export_dependency_frontend', [$this, 'addResponsiveCss']);
    }
}