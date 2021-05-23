<?php

namespace GSWPTS_PRO\Includes\Classes;

use GSWPTS_PRO\Includes\Classes\FilterCallbacks;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheetstowptable-pro'));

class FilterHooks extends FilterCallbacks {
    public function __construct() {
        add_filter('export_buttons_logo_backend', [$this, 'loadIconsUrl']);
        add_filter('export_buttons_logo_frontend', [$this, 'loadIconsUrl']);
        add_filter('gswpts_rows_per_page', [$this, 'rowsPerPage']);
    }
}