<?php

namespace GSWPTS\Includes\Classes\Controller\Ajax_Parts;

class Sheet_Creation {
    private static $output = [];

    public function sheet_creation() {
        if ($_POST['action'] != 'gswpts_sheet_create') {
            self::$output['response_type'] = 'invalid_action';
            self::$output['output'] = '<b>Action is invalid</b>';
            echo json_encode(self::$output);
            wp_die();
        }


        if (isset($_POST['gutenberg_req']) && $_POST['gutenberg_req']) {

            if ($_POST['type'] == 'fetch') {

                if (!$_POST['file_input'] || $_POST['file_input'] == "") {
                    self::$output['response_type'] = 'empty_field';
                    self::$output['output'] = '<b>Form field is empty. Please fill out the field</b>';
                    echo json_encode(self::$output);
                    wp_die();
                }

                echo json_encode(self::sheet_html($_POST['file_input']));
                wp_die();
            }

            if ($_POST['type'] == 'save' || $_POST['type'] == 'saved') {

                if (!$_POST['file_input'] || $_POST['file_input'] == "") {
                    self::$output['response_type'] = 'empty_field';
                    self::$output['output'] = '<b>Form field is empty. Please fill out the field</b>';
                    echo json_encode(self::$output);
                    wp_die();
                }

                $data = [
                    'file_input' => $_POST['file_input'],
                    'source_type' => $_POST['source_type']
                ];
                echo json_encode(self::save_table(
                    $data,
                    $_POST['table_name'],
                    $_POST['table_settings']
                ));
                wp_die();
            }

            if ($_POST['type'] == 'save_changes') {
                echo json_encode(self::update_changes(
                    $_POST['id'],
                    $_POST['table_settings']
                ));
                wp_die();
            }
        } else {

            parse_str($_POST['form_data'], $parsed_data);

            if (!isset($parsed_data['gswpts_sheet_nonce']) || !wp_verify_nonce($parsed_data['gswpts_sheet_nonce'],  'gswpts_sheet_nonce_action')) {
                self::$output['response_type'] = 'invalid_request';
                self::$output['output'] = '<b>Request is invalid</b>';
                echo json_encode(self::$output);
                wp_die();
            }

            if ($parsed_data['source_type'] === 'spreadsheet') {

                if (!$parsed_data['file_input'] || $parsed_data['file_input'] == "") {
                    self::$output['response_type'] = 'empty_field';
                    self::$output['output'] = '<b>Form field is empty. Please fill out the field</b>';
                    echo json_encode(self::$output);
                    wp_die();
                }

                if ($_POST['type'] == 'fetch') {
                    echo json_encode(self::sheet_html($parsed_data['file_input']));
                    wp_die();
                }

                if ($_POST['type'] == 'save' || $_POST['type'] == 'saved') {
                    echo json_encode(self::save_table(
                        $parsed_data,
                        $_POST['table_name'],
                        $_POST['table_settings']
                    ));
                    wp_die();
                }

                if ($_POST['type'] == 'save_changes') {
                    echo json_encode(self::update_changes(
                        $_POST['id'],
                        $_POST['table_settings']
                    ));
                    wp_die();
                }
            }
        }

        self::$output['response_type'] = 'invalid_request';
        self::$output['output'] = '<b>Request is invalid</b>';
        echo json_encode(self::$output);
        wp_die();
    }

    public static function sheet_html($url) {
        global $gswpts;

        $sheet_response = $gswpts->get_csv_data($url);
        if (!$sheet_response || empty($sheet_response) || $sheet_response == null) {
            self::$output['response_type'] = 'invalid_request';
            self::$output['output'] = '<b>The SpreadSheet url is invalid. Please enter a valid public sheet url</b>';
            return self::$output;
        }

        $response = $gswpts->get_table(true, $sheet_response);
        $json_res = $gswpts->get_json_data($url);

        self::$output['response_type'] = 'success';
        self::$output['sheet_data'] = [
            'sheet_name' => $json_res['title']['$t'],
            'author_info' => $json_res['author'],
            'sheet_total_result' => $json_res['openSearch$totalResults']['$t'],
            'total_rows' => $response['count'],
        ];
        self::$output['output'] = "" . $response['table'] . "";
        return self::$output;
    }

    public static function save_table(array $parsed_data, $table_name, array $table_settings) {
        global $wpdb;
        $table = $wpdb->prefix . 'gswpts_tables';

        if (self::check_sheet_url($parsed_data['file_input']) == false) {
            self::$output['response_type'] = 'sheet_exists';
            self::$output['output'] = "<b>This SpreadSheet already exists. Try new one</b>";
            return self::$output;
        }
        $settings = self::get_table_settings($table_settings);

        $data = [
            'table_name' => sanitize_text_field($table_name),
            'source_url' => esc_url($parsed_data['file_input']),
            'source_type' => sanitize_text_field($parsed_data['source_type']),
            'table_settings' => serialize($settings),
        ];

        $db_respond = $wpdb->insert($table, $data, [
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
        ]);

        if (is_int($db_respond)) {
            self::$output['response_type'] = 'saved';
            self::$output['id'] = $wpdb->get_results("SELECT LAST_INSERT_ID();")[0];
            self::$output['sheet_url'] = $parsed_data['file_input'];
            self::$output['output'] = '<b>Table saved successfully</b>';
        } else {
            self::$output['response_type'] = 'invalid_request';
            self::$output['output'] = "<b>Table couldn't be saved. Please try again</b>";
        }
        return self::$output;
    }

    public static function check_sheet_url(string $url) {
        $return_value = true;
        global $wpdb;
        global $gswpts;
        $table = $wpdb->prefix . 'gswpts_tables';

        $fetch_data = $wpdb->get_results("SELECT source_url FROM " . $table . "");
        if (!empty($fetch_data)) {
            foreach ($fetch_data as $data) {
                if ($gswpts->get_sheet_id($data->source_url) == $gswpts->get_sheet_id($url)) {
                    $return_value = false;
                    break;
                } else {
                    $return_value = true;
                }
            }
        } else {
            $return_value = true;
        }
        return $return_value;
    }

    public static function update_changes(int $table_id, array $settings) {
        global $wpdb;
        $table = $wpdb->prefix . 'gswpts_tables';

        $settings = self::get_table_settings($settings);

        $update_response = $wpdb->update(
            $table,
            [
                'table_settings' => serialize($settings),
            ],
            [
                'id' => $table_id
            ],
            [
                '%s',
            ],
            [
                '%d'
            ]
        );
        if (is_int($update_response)) {
            self::$output['response_type'] = 'updated';
            self::$output['output'] = '<b>Table changes updated successfully</b>';
            return self::$output;
        } else {
            self::$output['response_type'] = 'invalid_request';
            self::$output['output'] = '<b>Table changes could not be updated</b>';
            return self::$output;
        }
    }

    public static  function get_table_settings($table_settings) {
        $settings = [
            'table_title' => $table_settings['table_title'],
            'default_rows_per_page' => $table_settings['defaultRowsPerPage'],
            'show_info_block' => $table_settings['showInfoBlock'],
            'responsive_table' => $table_settings['responsiveTable'],
            'show_x_entries' => $table_settings['showXEntries'],
            'swap_filter_inputs' => $table_settings['swapFilterInputs'],
            'swap_bottom_options' => $table_settings['swapBottomOptions'],
            'allow_sorting' => $table_settings['allowSorting'],
            'search_bar' => $table_settings['searchBar'],
            'table_export' => isset($table_settings['tableExport']) && $table_settings['tableExport'] != null && $table_settings['tableExport'] != false || "" ? $table_settings['tableExport'] : 'empty',
        ];
        return $settings;
    }
}
