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
        add_filter('gswpts_table_scorll_height', [$this, 'scrollHeightArray']);
        add_filter('gswpts_table_export_values', [$this, 'tableExportValues']);
        add_filter('gswpts_cell_format', [$this, 'cellFormattingArray']);
        add_filter('gswpts_general_settings', [$this, 'generalSettingsArray']);
        add_filter('gswpts_redirection_types', [$this, 'redirectionTypeArray']);
        add_filter('gswpts_url_constructor', [$this, 'sheetURLConstructor'], 10, 2);
        add_filter('gswpts_table_styles', [$this, 'tableStylesArray'], 10);
        add_filter('gswpts_table_styles', [$this, 'tableStylesArray'], 10);
        add_filter('gswpts_table_styles_path', [$this, 'tableStylesCssFile'], 10);
        add_filter('gswpts_responsive_styles', [$this, 'responsiveStyle'], 10);
    }
}