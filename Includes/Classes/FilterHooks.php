<?php

namespace GSWPTS_PRO\Includes\Classes;

use GSWPTS_PRO\Includes\Classes\FilterCallbacks;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheetstowptable-pro'));

class FilterHooks extends FilterCallbacks {
    public function __construct() {
        add_filter('export_buttons_logo_backend', [$this, 'loadIconsUrl']);
        add_filter('export_buttons_logo_frontend', [$this, 'loadIconsUrl']);
        add_filter('gswpts_rows_per_page', [$this, 'rowsPerPage']);
        add_filter('gswpts_allow_sheet_rows_fetching', [$this, 'sheetsRowFetching']);
        add_filter('gswpts_display_settings_arr', [$this, 'displaySettingsArray']);
        add_filter('gswpts_table_tools_settings_arr', [$this, 'tableToolsArray']);
        add_filter('gswpts_table_settings', [$this, 'getTableSettings'], 10, 2);
    }
}