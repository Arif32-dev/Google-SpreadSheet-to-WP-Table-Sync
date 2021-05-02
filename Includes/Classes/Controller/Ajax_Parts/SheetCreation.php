<?php

namespace GSWPTS\Includes\Classes\Controller\Ajax_Parts;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheetstowptable'));

class SheetCreation {
    private static $output = [];

    public function sheet_creation() {
        if (sanitize_text_field($_POST['action']) != 'gswpts_sheet_create') {
            self::$output['response_type'] = esc_html('invalid_action');
            self::$output['output'] = '<b>' . esc_html__('Action is invalid', 'sheetstowptable') . '</b>';
            echo json_encode(self::$output);
            wp_die();
        }


        if (isset($_POST['gutenberg_req']) && sanitize_text_field($_POST['gutenberg_req'])) {

            $file_input = sanitize_text_field($_POST['file_input']);

            if (sanitize_text_field($_POST['type']) == 'fetch') {

                if (!$file_input || $file_input == "") {
                    self::$output['response_type'] = esc_html('empty_field');
                    self::$output['output'] = '<b>' . esc_html__('Form field is empty. Please fill out the field', 'sheetstowptable') . '</b>';
                    echo json_encode(self::$output);
                    wp_die();
                }

                echo json_encode(self::sheet_html($file_input));
                wp_die();
            }

            if (sanitize_text_field($_POST['type']) == 'save' || sanitize_text_field($_POST['type']) == 'saved') {

                if (!$file_input || $file_input == "") {
                    self::$output['response_type'] = esc_html('empty_field');
                    self::$output['output'] = '<b>' . esc_html__('Form field is empty. Please fill out the field', 'sheetstowptable') . '</b>';
                    echo json_encode(self::$output);
                    wp_die();
                }

                $data = [
                    'file_input' => $file_input,
                    'source_type' => sanitize_text_field($_POST['source_type'])
                ];

                $table_settings = array_map(function ($setting) {
                    return sanitize_text_field($setting);
                }, $_POST['table_settings']);

                echo json_encode(self::save_table(
                    $data,
                    sanitize_text_field($_POST['table_name']),
                    $table_settings
                ));
                wp_die();
            }

            if (sanitize_text_field($_POST['type']) == 'save_changes') {
                echo json_encode(self::update_changes(
                    sanitize_text_field($_POST['id']),
                    $table_settings
                ));
                wp_die();
            }
        } else {

            parse_str($_POST['form_data'], $parsed_data);

            $parsed_data = array_map(function ($data) {
                return sanitize_text_field($data);
            }, $parsed_data);

            $file_input = sanitize_text_field($parsed_data['file_input']);

            $table_settings = array_map(function ($setting) {
                return sanitize_text_field($setting);
            }, $_POST['table_settings']);

            if (!isset($parsed_data['gswpts_sheet_nonce']) || !wp_verify_nonce($parsed_data['gswpts_sheet_nonce'],  'gswpts_sheet_nonce_action')) {
                self::$output['response_type'] = esc_html('invalid_request');
                self::$output['output'] = '<b>' . esc_html__('Request is invalid', 'sheetstowptable') . '</b>';
                echo json_encode(self::$output);
                wp_die();
            }

            if ($parsed_data['source_type'] === 'spreadsheet') {

                if (!$file_input || $file_input == "") {
                    self::$output['response_type'] = esc_html('empty_field');
                    self::$output['output'] = '<b>' . esc_html__('Form field is empty. Please fill out the field', 'sheetstowptable') . '</b>';
                    echo json_encode(self::$output);
                    wp_die();
                }

                if (sanitize_text_field($_POST['type']) == 'fetch') {
                    echo json_encode(self::sheet_html($file_input));
                    wp_die();
                }

                if (sanitize_text_field($_POST['type']) == 'save' || sanitize_text_field($_POST['type']) == 'saved') {
                    echo json_encode(self::save_table(
                        $parsed_data,
                        sanitize_text_field($_POST['table_name']),
                        $table_settings
                    ));
                    wp_die();
                }

                if (sanitize_text_field($_POST['type']) == 'save_changes') {
                    echo json_encode(self::update_changes(
                        sanitize_text_field($_POST['id']),
                        $table_settings
                    ));
                    wp_die();
                }
            }
        }

        self::$output['response_type'] = esc_html('invalid_request');
        self::$output['output'] = '<b>' . esc_html__('Request is invalid', 'sheetstowptable') . '</b>';
        echo json_encode(self::$output);
        wp_die();
    }

    public static function sheet_html($url) {
        global $gswpts;

        $json_res = $gswpts->get_json_data($url);

        if (!$json_res) {
            self::$output['response_type'] = esc_html('invalid_request');
            self::$output['output'] = '<b>' . esc_html__('The spreadsheet is not publicly available. Publish it to the web', 'sheetstowptable') . '</b>';
            return self::$output;
        }

        $sheet_response = $gswpts->get_csv_data($url);

        if (count(fgetcsv($sheet_response)) < 2) {
            self::$output['response_type'] = esc_html('invalid_request');
            self::$output['output'] = sprintf('<b>%s<br/>%s</b>', __('The spreadsheet is restricted.', 'sheetstowptable'), __('Please make it public by clicking on share button at the top of spreadsheet', 'sheetstowptable'));
            return self::$output;
        }

        $sheet_response = $gswpts->get_csv_data($url);

        if (!$sheet_response || empty($sheet_response) || $sheet_response == null) {
            self::$output['response_type'] = esc_html('invalid_request');
            self::$output['output'] = '<b>' . esc_html__('The SpreadSheet url is invalid. Please enter a valid public sheet url', 'sheetstowptable') . '</b>';
            return self::$output;
        }

        $response = $gswpts->get_table(true, $sheet_response);
        self::$output['response_type'] = esc_html('success');
        self::$output['sheet_data'] = [
            'sheet_name' => esc_html__($json_res['title']['$t'], 'sheetstowptable'),
            'author_info' => (array) $json_res['author'],
            'sheet_total_result' => esc_html__($json_res['openSearch$totalResults']['$t'], 'sheetstowptable'),
            'total_rows' => esc_html__($response['count'], 'sheetstowptable')
        ];
        self::$output['output'] = "" . $response['table'] . "";
        return self::$output;
    }

    public static function save_table(array $parsed_data, $table_name, array $table_settings) {
        global $wpdb;
        $table = $wpdb->prefix . 'gswpts_tables';

        if (self::check_sheet_url($parsed_data['file_input']) == false) {
            self::$output['response_type'] = esc_html('sheet_exists');
            self::$output['output'] = "<b>" . esc_html__('This SpreadSheet already exists. Try new one', 'sheetstowptable') . "</b>";
            return self::$output;
        }
        $settings = self::get_table_settings($table_settings);

        $data = [
            'table_name' => sanitize_text_field($table_name),
            'source_url' => esc_url_raw($parsed_data['file_input']),
            'source_type' => sanitize_text_field($parsed_data['source_type']),
            'table_settings' => serialize($settings)
        ];

        $db_respond = $wpdb->insert($table, $data, [
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
        ]);

        if (is_int($db_respond)) {
            self::$output['response_type'] = esc_html('saved');
            self::$output['id'] = $wpdb->get_results("SELECT LAST_INSERT_ID();")[0];
            self::$output['sheet_url'] = esc_url($parsed_data['file_input']);
            self::$output['output'] = '<b>' . esc_html__('Table saved successfully', 'sheetstowptable') . '</b>';
        } else {
            self::$output['response_type'] = esc_html('invalid_request');
            self::$output['output'] = "<b>" . esc_html__("Table couldn't be saved. Please try again", 'sheetstowptable') . "</b>";
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
                'id' => intval(sanitize_text_field($table_id))
            ],
            [
                '%s',
            ],
            [
                '%d'
            ]
        );
        if (is_int($update_response)) {
            self::$output['response_type'] = esc_html('updated');
            self::$output['output'] = '<b>' . esc_html__('Table changes updated successfully', 'sheetstowptable') . '</b>';
            return self::$output;
        } else {
            self::$output['response_type'] = esc_html('invalid_request');
            self::$output['output'] = '<b>' . esc_html__('Table changes could not be updated', 'sheetstowptable') . '</b>';
            return self::$output;
        }
    }

    public static  function get_table_settings($table_settings) {
        $settings = [
            'table_title' => $table_settings['table_title'],
            'default_rows_per_page' => $table_settings['defaultRowsPerPage'],
            'show_info_block' => $table_settings['showInfoBlock'],
            'show_x_entries' => $table_settings['showXEntries'],
            'swap_filter_inputs' => $table_settings['swapFilterInputs'],
            'swap_bottom_options' => $table_settings['swapBottomOptions'],
            'allow_sorting' => $table_settings['allowSorting'],
            'search_bar' => $table_settings['searchBar'],
        ];
        return $settings;
    }
}
