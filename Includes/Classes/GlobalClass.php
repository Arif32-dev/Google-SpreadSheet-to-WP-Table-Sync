<?php

namespace GSWPTS\Includes\Classes;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheetstowptable'));

class GlobalClass {
    public function data_table_styles() {
        wp_enqueue_style('GSWPTS-semanticui-css', GSWPTS_BASE_URL.'Assets/Public/Common/Semantic-UI-CSS-master/semantic.min.css', [], GSWPTS_VERSION, 'all');
        wp_enqueue_style('GSWPTS-dataTable-semanticui-css', GSWPTS_BASE_URL.'Assets/Public/Common/DataTables/Tables/css/dataTables.semanticui.min.css', [], GSWPTS_VERSION, 'all');
    }

    public function data_table_scripts() {
        wp_enqueue_script('GSWPTS-jquery-dataTable-js', GSWPTS_BASE_URL.'Assets/Public/Common/DataTables/Tables/js/jquery.dataTables.min.js', ['jquery'], GSWPTS_VERSION, true);
        wp_enqueue_script('GSWPTS-dataTable-semanticui-js', GSWPTS_BASE_URL.'Assets/Public/Common/DataTables/Tables/js/dataTables.semanticui.min.js', ['jquery'], GSWPTS_VERSION, true);
    }

    /**
     * @param $nonce_action
     * @param $nonce_name
     */
    public function nonce_field(
        $nonce_action,
        $nonce_name
    ) {
        wp_nonce_field($nonce_action, $nonce_name);
    }

    public function semantic_files() {
        wp_enqueue_style('GSWPTS-semanticui-css', GSWPTS_BASE_URL.'Assets/Public/Common/Semantic-UI-CSS-master/semantic.min.css', [], GSWPTS_VERSION, 'all');
        wp_enqueue_script('GSWPTS-semantic-js', GSWPTS_BASE_URL.'Assets/Public/Common/Semantic-UI-CSS-master/semantic.min.js', ['jquery'], GSWPTS_VERSION, false);
    }

    public function frontend_tables_assets() {
        wp_enqueue_script('GSWPTS-frontend-table', GSWPTS_BASE_URL.'Assets/Public/Common/DataTables/Tables/js/jquery.dataTables.min.js', ['jquery'], GSWPTS_VERSION, false);
        wp_enqueue_script('GSWPTS-frontend-semantic', GSWPTS_BASE_URL.'Assets/Public/Common/DataTables/Tables/js/dataTables.semanticui.min.js', ['jquery'], GSWPTS_VERSION, false);
    }

    /**
     * @param  string  $string
     * @return mixed
     */
    public function get_sheet_id(string $string) {
        $pattern = "/\//";
        $components = preg_split($pattern, $string);
        if ($components) {
            if (array_key_exists(5, $components)) {
                return $components[5];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @param  $url
     * @return array
     */
    public function get_json_data($url) {
        $sheet_id = $this->get_sheet_id($url);
        if (!$sheet_id) {
            return;
        }
        $sheet_url = "https://spreadsheets.google.com/feeds/cells/".$sheet_id."/1/public/full?alt=json";

        return json_decode(wp_remote_get($sheet_url)['body'], true)['feed'];
    }

    /**
     * @param  string   $url
     * @return string
     */
    public function get_csv_data(string $url) {
        $sheet_id = $this->get_sheet_id($url);
        if (!$sheet_id) {
            return;
        }

        $response = wp_remote_get("https://docs.google.com/spreadsheets/d/".$sheet_id."/export?format=csv&id=".$sheet_id."")['body'];

        if (preg_match_all("/((<!DOCTYPE html>)|(<head>))/i", $response)) {
            return false;
        }

        return $response;
    }

    /**
     * @param  $ajax_req
     * @param  false        $sheet_response
     * @param  null         $table_id
     * @return mixed
     */
    public function get_table(
        $ajax_req = false,
        $sheet_response = null,
        $table_id = null
    ) {
        if ($ajax_req && $sheet_response) {
            return $this->the_table($sheet_response);
        }
        if (isset($table_id) && $table_id !== '') {
            $db_result = $this->fetch_db_by_id($table_id);
            if ($db_result) {

                $json_response = $this->get_json_data($db_result[0]->source_url);

                if (!$json_response) {
                    return false;
                }

                $sheet_response = $this->get_csv_data($db_result[0]->source_url);

                if (!$sheet_response) {
                    return false;
                }

                $table = $this->the_table($sheet_response);
                $output = [
                    'id'                 => $table_id,
                    'table'              => $table,
                    'table_settings'     => unserialize($db_result[0]->table_settings),
                    'table_name'         => $db_result[0]->table_name,
                    'sheet_name'         => $json_response['title']['$t'],
                    'author_info'        => $json_response['author'],
                    'sheet_total_result' => $json_response['openSearch$totalResults']['$t'],
                    'total_rows'         => $table['count']
                ];
                return $output;
            }
        }
        return false;
    }

    /**
     * @param  $sheet_response
     * @return mixed
     */
    public function the_table($sheet_response) {

        $table = '<table id="create_tables" class="ui celled display table gswpts_tables" style="width:100%">';
        $i = 0;

        $row = explode(PHP_EOL, $sheet_response);

        $length = count($row);

        if (!$length) {
            return false;
        }

        for ($i; $i < $length; $i++) {
            $data = str_getcsv($row[$i], ",");

            if ($i == 0) {
                $table .= '<thead><tr>';
                foreach ($data as $cell_value) {

                    if ($cell_value) {
                        $table .= '<th>'.esc_html__($cell_value, 'sheetstowptable').'</th>';
                    } else {
                        $table .= '<th>&nbsp;</th>';
                    }
                }
                $table .= '</tr></thead>';
            } else {
                $allowRowFetching = apply_filters('gswpts_allow_sheet_rows_fetching', [
                    'unlimited' => false,
                    'totalRows' => 21
                ]);

                if (!$allowRowFetching['unlimited']) {
                    if ($i == $allowRowFetching['totalRows']) {
                        break;
                    }
                }
                $table .= '<tr>';
                foreach ($data as $cell_value) {
                    if ($cell_value) {
                        $table .= '<td>'.esc_html__($cell_value, 'sheetstowptable').'</td>';
                    } else {
                        $table .= '<td>&nbsp;</td>';
                    }
                }
                $table .= '</tr>';
            }
        }

        $table .= '</table>';

        $response = [
            'table' => $table,
            'count' => $i
        ];
        return $response;
    }

    /**
     * @param  $id
     * @return mixed
     */
    public function fetch_db_by_id($id) {
        global $wpdb;
        $table = $wpdb->prefix.'gswpts_tables';

        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$table." WHERE id=%d", sanitize_text_field($id)));
        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function fetch_gswpts_tables() {
        global $wpdb;
        $table = $wpdb->prefix.'gswpts_tables';
        $result = $wpdb->get_results("SELECT * FROM ".$table."");
        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * @param  $data
     * @return mixed
     */
    public function sheet_details($data) {
        $sheet_details = false;
        if (isset($_GET['id']) && !empty($_GET['id'])) {

            $sheet_details = '
                    <div id="sheet_ui_card" class="ui card" style="width: 60%; min-width: 400px;">
                            <div class="content">
                                <div class="row">
                                    <div class="col-12 d-flex align-items-center justify-content-start mb-3">
                                        <h6 class="m-0">Sheet Name: </h6>
                                        <h6 class="m-0 ml-2">'.esc_html__($data['sheet_name'], 'sheetstowptable').'</h6>
                                    </div>
                                    <div class="col-12 d-flex align-items-center justify-content-start mb-3">
                                        <h6 class="m-0">Total Rows: </h6>
                                        <h6 class="m-0 ml-2">'.esc_html__($data['total_rows'], 'sheetstowptable').'</h6>
                                    </div>
                                    <div class="col-12 d-flex align-items-center justify-content-start mb-3">
                                        <h6 class="m-0">Total Result: </h6>
                                        <h6 class="m-0 ml-2">'.esc_html__($data['sheet_total_result'], 'sheetstowptable').'</h6>
                                    </div>
                                    <div class="col-12 d-flex align-items-center justify-content-start">
                                        <h6 class="m-0">Author Mail: </h6>
                                        <h6 class="m-0 ml-2">'.esc_html__($data['author_info'][0]['email']['$t'], 'sheetstowptable').'</h6>
                                    </div>
                                    <div id="shortcode_container" class="col-12 d-flex mt-3 align-items-center justify-content-start">
                                        <h6 class="m-0">Table Shortcode: </h6>
                                        <h6 class="m-0 ml-2">
                                            <div class="ui action input">
                                                <input id="sortcode_value" type="text" class="copyInput" value="[gswpts_table id='.esc_html__(esc_attr($data['id']), 'sheetstowptable').']">
                                                <button id="sortcode_copy" type="button" name="copyToken" value="copy" class="copyToken ui right icon button">
                                                    <i class="clone icon"></i>
                                                </button>
                                            </div>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                    </div>
            ';
            return $sheet_details;
        }
    }

    /**
     * @param  int     $id
     * @return mixed
     */
    public function input_values(int $id) {
        $db_result = $this->fetch_db_by_id($id);
        if ($db_result) {
            $input_values = [
                'source_url' => $db_result[0]->source_url,
                'table_name' => $this->output_table_by_condition($db_result)
            ];
            return $input_values;
        } else {
            return false;
        }
        return false;
    }

    /**
     * @param $db_result
     */
    public function output_table_by_condition($db_result) {
        $table_settings = unserialize($db_result[0]->table_settings);
        if ($table_settings['table_title'] == 'true') {
            return '<h3>'.esc_html__($db_result[0]->table_name, 'sheetstowptable').'</h3>';
        } else {
            return null;
        }
    }

    /**
     * @return mixed
     */
    public function latest_table_details() {
        $db_result = $this->fetch_gswpts_tables();
        $last_table_id = $this->get_last_table_id($db_result);
        $latest_table_info = [
            'total_table_count' => $db_result != false ? count($db_result) : 0,
            'last_table_name'   => $db_result != false ? $db_result[(count($db_result) - 1)]->table_name : null,
            'last_table_id'     => $last_table_id
        ];
        return $latest_table_info;
    }

    /**
     * @param  $db_result
     * @return int|null
     */
    public function get_last_table_id($db_result) {
        $last_table = null;
        if ($db_result != false) {
            $last_table = $db_result[(count($db_result) - 1)]->id;
        }
        return $last_table;
    }

    /**
     * @return int|null
     */
    public function get_first_table_details() {
        $table_details = null;
        $db_result = $this->fetch_gswpts_tables();
        if ($db_result) {
            $table_details = [
                'id'             => $db_result[0]->id,
                'table_name'     => $db_result[0]->table_name,
                'source_url'     => $db_result[0]->source_url,
                'source_type'    => $db_result[0]->source_type,
                'table_settings' => $db_result[0]->table_settings
            ];
        }
        return $table_details;
    }

    /**
     * @return mixed
     */
    public function changeLogs() {

        $changeLogs = [
            '1.1.2' => [
                __('Fix: Fixed spreadsheet data fetching issue for different server', 'sheetstowptable'),
                __('Fix: Fixed tutorial video linking in dashboard', 'sheetstowptable'),
                __('Improvement: Added video documention in dashboard page', 'sheetstowptable')
            ],
            '1.2.2' => [
                __('Fix: Fixed admin page css issue', 'sheetstowptable'),
                __('Fix: Fixed tabe broken issue', 'sheetstowptable'),
                __('Added: Add cell formating feature as upcoming feature', 'sheetstowptable').
                __('Added: Changed non-developed feature as upcoming feature', 'sheetstowptable'),
                __('Improvement: UI/UX improved for users', 'sheetstowptable'),
                __('Improvement: Added 20 row fetching from google sheet', 'sheetstowptable'),
                __('Improvement: Plugins code structure updated for pro version', 'sheetstowptable')
            ]
        ];

        krsort($changeLogs);

        $html = '';

        foreach ($changeLogs as $key => $logs) {

            $html .= "<div class='col-12 mt-4 flex-column'>
                        <strong>Version: ".esc_html__($key)."</strong>
                        ".$this->innerLogs($logs)."
                </div>";
        }

        return $html;
    }

    /**
     * @param  $logs
     * @return mixed
     */
    public function innerLogs($logs) {
        $logsHtml = '';
        foreach ($logs as $log) {
            $logsHtml .= "<div class='d-flex mt-2'>
                            <div class='col-1 p-0 info_circle text-center'>
                            <svg class='svg_icons' width='15px' height='15px' aria-hidden='true' focusable='false' data-prefix='fas' data-icon='info-circle' class='svg-inline--fa fa-info-circle fa-w-16' role='img' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'><path fill='currentColor' d='M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z'></path></svg>
                            </div>
                            <div class='col-11 p-0'>
                                <ul class='p-0 m-0'>
                                    ".sprintf('<li>%s</li>', $log)."
                                </ul>
                            </div>
                        </div>";
        }
        return $logsHtml;
    }

    /**
     * @return boolean
     */
    public function isProActive(): bool {
        $returnValue = false;
        if (class_exists('SheetsToWPTableLiveSyncPro')) {
            $returnValue = true;
        } else {
            $returnValue = false;
        }
        return $returnValue;
    }

    /**
     * @return array
     */
    public function rowsPerPage(): array{
        $rowsPerPage = [
            '1'   => [
                'val'   => 1,
                'isPro' => false
            ],
            '5'   => [
                'val'   => 5,
                'isPro' => false
            ],
            '10'  => [
                'val'   => 10,
                'isPro' => false
            ],
            '15'  => [
                'val'   => 15,
                'isPro' => false
            ],
            '25'  => [
                'val'   => 25,
                'isPro' => true
            ],
            '50'  => [
                'val'   => 50,
                'isPro' => true
            ],
            '100' => [
                'val'   => 100,
                'isPro' => true
            ],
            'all' => [
                'val'   => 'All',
                'isPro' => true
            ]
        ];

        $rowsPerPage = apply_filters('gswpts_rows_per_page', $rowsPerPage);

        return $rowsPerPage;
    }

    /**
     * @param  array  $values
     * @return null
     */
    public function selectFieldHTML(array $values) {
        if (!$values) {
            return;
        }
        load_template(GSWPTS_BASE_PATH.'Includes/Templates/Parts/select_values.php', false, $values);
    }

    /**
     * @return array
     */
    public function scrollHeightArray(): array{
        $scrollHeights = [
            '200'  => [
                'val'   => '200px',
                'isPro' => true
            ],
            '400'  => [
                'val'   => '400px',
                'isPro' => true
            ],
            '500'  => [
                'val'   => '500px',
                'isPro' => true
            ],
            '600'  => [
                'val'   => '600px',
                'isPro' => true
            ],
            '700'  => [
                'val'   => '700px',
                'isPro' => true
            ],
            '800'  => [
                'val'   => '800px',
                'isPro' => true
            ],
            '900'  => [
                'val'   => '900px',
                'isPro' => true
            ],
            '1000' => [
                'val'   => '1000px',
                'isPro' => true
            ]
        ];

        $scrollHeights = apply_filters('gswpts_table_scorll_height', $scrollHeights);

        return $scrollHeights;
    }

    /**
     * @return array
     */
    public function displaySettingsArray(): array{
        $settingsArray = [
            'table_title'          => [
                'feature_title' => __('Table Title', 'sheetstowptable'),
                'feature_desc'  => __('Enable this to show the table title in <i>h3</i> tag above the table in your website front-end', 'sheetstowptable'),
                'input_name'    => 'show_title',
                'checked'       => false,
                'type'          => 'checkbox'
            ],
            'rows_per_page'        => [
                'feature_title' => __('Default rows per page', 'sheetstowptable'),
                'feature_desc'  => __('This will show rows per page in the frontend', 'sheetstowptable'),
                'input_name'    => 'rows_per_page',
                'type'          => 'select',
                'values'        => $this->rowsPerPage(),
                'default_text'  => 'Rows Per Page'
            ],
            'show_info_block'      => [
                'feature_title' => __('Show info block', 'sheetstowptable'),
                'feature_desc'  => __('Show <i>Showing X to Y of Z entries</i>block below the table', 'sheetstowptable'),
                'input_name'    => 'info_block',
                'checked'       => true,
                'type'          => 'checkbox'
            ],
            'responsive_table'     => [
                'feature_title' => __('Resposive Table', 'sheetstowptable'),
                'feature_desc'  => __('Allow collapsing on mobile and tablet screen', 'sheetstowptable'),
                'input_name'    => 'responsive',
                'checked'       => false,
                'is_pro'        => true,
                'type'          => 'checkbox'
            ],
            'show_x_entries'       => [
                'feature_title' => __('Show X entries', 'sheetstowptable'),
                'feature_desc'  => __('<i>Show X entries</i> per page dropdown', 'sheetstowptable'),
                'input_name'    => 'show_entries',
                'checked'       => true,
                'type'          => 'checkbox'
            ],
            'swap_filters'         => [
                'feature_title' => __('Swap Filters', 'sheetstowptable'),
                'feature_desc'  => __('Swap the places of <i> X entries</i> dropdown & search filter input', 'sheetstowptable'),
                'input_name'    => 'swap_filter_inputs',
                'checked'       => false,
                'type'          => 'checkbox'

            ],
            'swap_bottom_elements' => [
                'feature_title' => __('Swap Bottom Elements', 'sheetstowptable'),
                'feature_desc'  => __('Swap the places of <i>Showing X to Y of Z entries</i> with table pagination filter', 'sheetstowptable'),
                'input_name'    => 'swap_bottom_options',
                'checked'       => false,
                'type'          => 'checkbox'
            ],
            'vertical_scrolling'   => [
                'feature_title' => __('Vertical Scroll/Sticky Header', 'sheetstowptable'),
                'feature_desc'  => __('Choose the height of the table to scroll vertically. Activating this feature will allow the table to behave as sticky header', 'sheetstowptable'),
                'input_name'    => 'vertical_scrolling',
                'checked'       => false,
                'is_pro'        => true,
                'type'          => 'select',
                'values'        => $this->scrollHeightArray(),
                'default_text'  => 'Choose Height'
            ],
            'cell_format'          => [
                'feature_title' => __('Format Table Cell', 'sheetstowptable'),
                'feature_desc'  => __('Format the table cell as like google sheet cell formatting. Format your cell as Wrap or Clip or Expanded style', 'sheetstowptable'),
                'input_name'    => 'cell_format',
                'checked'       => false,
                // 'is_pro'        => true,
                'is_upcoming'   => true,
                'type'          => 'select',
                'values'        => $this->cellFormattingArray(),
                'default_text'  => 'Cell Format'
            ]
        ];

        $settingsArray = apply_filters('gswpts_display_settings_arr', $settingsArray);

        return $settingsArray;
    }

    /**
     * @return mixed
     */
    public function cellFormattingArray(): array{
        $cellFormats = [
            'clip'   => [
                'val'   => 'Clip Style',
                'isPro' => true
            ],
            'wrap'   => [
                'val'   => 'Wrap Style',
                'isPro' => true
            ],
            'expand' => [
                'val'   => 'Expanded Style',
                'isPro' => true
            ]
        ];

        $cellFormats = apply_filters('gswpts_cell_format', $cellFormats);

        return $cellFormats;
    }

    /**
     * @return null
     */
    public function displaySettingsHTML() {
        $settingsArray = $this->displaySettingsArray();
        if (!$settingsArray) {
            return;
        }

        foreach ($settingsArray as $key => $setting) {
            load_template(GSWPTS_BASE_PATH.'Includes/Templates/Parts/indiviual_feature.php', false, $setting);
        }
    }

    /**
     * @return array
     */
    public function sortAndFilterSettingsArray(): array
    {
        $settingsArray = [
            'allow_sorting'    => [
                'feature_title' => __('Allow Sorting', 'sheetstowptable'),
                'feature_desc'  => __('Enable this feature to sort table data for frontend.', 'sheetstowptable'),
                'input_name'    => 'sorting',
                'checked'       => true,
                'type'          => 'checkbox'
            ],
            'search_bar'       => [
                'feature_title' => __('Search Bar', 'sheetstowptable'),
                'feature_desc'  => __('Enable this feature to show a search bar in for the table. It will help user to search data in the table', 'sheetstowptable'),
                'input_name'    => 'search_table',
                'checked'       => true,
                'type'          => 'checkbox'
            ],
            'rows_higlighting' => [
                'feature_title' => __('Rows Highlight', 'sheetstowptable'),
                'feature_desc'  => __('Enable this feature to show highlighted rows of the table in the frontend selected by admin/user', 'sheetstowptable'),
                'input_name'    => 'rows_highlight',
                'checked'       => false,
                // 'is_pro'        => true,
                'is_upcoming'   => true,
                'type'          => 'checkbox'
            ]
        ];

        $settingsArray = apply_filters('gswpts_sortfilter_settings_arr', $settingsArray);

        return $settingsArray;
    }

    /**
     * @return null
     */
    public function sortAndFilterHTML() {
        $settingsArray = $this->sortAndFilterSettingsArray();

        if (!$settingsArray) {
            return;
        }

        foreach ($settingsArray as $key => $setting) {
            load_template(GSWPTS_BASE_PATH.'Includes/Templates/Parts/indiviual_feature.php', false, $setting);
        }
    }

    /**
     * @return array
     */
    public function docButtonsArray(): array
    {
        $docArray = [
            'displayDocButton'       => [
                'btnText' => 'Display Documention',
                'iconURL' => GSWPTS_BASE_PATH.'Assets/Public/Icons/cogs-solid.svg'
            ],
            'sortAndFilterDocButton' => [
                'btnText' => 'Sorting Documention',
                'iconURL' => GSWPTS_BASE_PATH.'Assets/Public/Icons/sort-numeric-up-solid.svg'
            ],
            'tableToolsDocButton'    => [
                'btnText' => 'Table Tools Doc',
                'iconURL' => GSWPTS_BASE_PATH.'Assets/Public/Icons/tools-solid.svg'
            ]
        ];
        return $docArray;
    }

    /**
     * @param  null   $key
     * @return null
     */
    public function tabDocButtons($key = null) {
        $docArray = $this->docButtonsArray();
        if (!$docArray) {
            return;
        }

        if ($key == null) {
            return;
        }
        load_template(GSWPTS_BASE_PATH.'Includes/Templates/Parts/tab_page_button.php', false, $docArray[$key]);
    }

    /**
     * @return array
     */
    public function tableToolsArray(): array{
        $settingsArray = [
            'table_export' => [
                'feature_title' => __('Table Exporting', 'sheetstowptable'),
                'feature_desc'  => __('Enable this feature in order to allow your user to download your table content as various format.', 'sheetstowptable'),
                'input_name'    => 'table_exporting',
                'is_pro'        => true,
                'type'          => 'multi-select',
                'values'        => $this->tableExportValues(),
                'default_text'  => 'Choose Type'
            ]
        ];

        $settingsArray = apply_filters('gswpts_table_tools_settings_arr', $settingsArray);

        return $settingsArray;
    }

    /**
     * @return array
     */
    public function tableExportValues(): array{
        $exportValues = [
            'json'  => [
                'val'   => 'JSON',
                'isPro' => true
            ],
            'pdf'   => [
                'val'   => 'PDF',
                'isPro' => true
            ],
            'csv'   => [
                'val'   => 'CSV',
                'isPro' => true
            ],
            'excel' => [
                'val'   => 'Excel',
                'isPro' => true
            ],
            'print' => [
                'val'   => 'Print',
                'isPro' => true
            ],
            'copy'  => [
                'val'   => 'Copy',
                'isPro' => true
            ]
        ];

        $exportValues = apply_filters('gswpts_table_export_values', $exportValues);

        return $exportValues;
    }

    /**
     * @return null
     */
    public function tableToolsHTML() {
        $settingsArray = $this->tableToolsArray();

        if (!$settingsArray) {
            return;
        }

        foreach ($settingsArray as $key => $setting) {
            load_template(GSWPTS_BASE_PATH.'Includes/Templates/Parts/indiviual_feature.php', false, $setting);
        }
    }
}