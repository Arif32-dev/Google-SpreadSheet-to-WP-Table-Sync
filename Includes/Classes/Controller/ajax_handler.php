<?php

namespace GSWPTS\Includes\Classes\Controller;


if (!defined('ABSPATH')) {
    die('you cant access this plugin directly');
}

class Ajax_Handler {
    private static $output = [];

    public function __construct() {
        $this->events();
    }
    public function events() {
        add_action('wp_ajax_gswpts_sheet_create', [$this, 'sheet_creation']);
        add_action('wp_ajax_nopriv_gswpts_sheet_create', [$this, 'sheet_creation']);
    }
    public function sheet_creation() {

        if ($_POST['action'] != 'gswpts_sheet_create') {
            self::$output['response_type'] = 'invalid_action';
            self::$output['output'] = '<b>Action is invalid</b>';
            echo json_encode(self::$output);
            wp_die();
        }

        parse_str($_POST['form_data'], $parsed_data);

        if (!isset($parsed_data['gswpts_sheet_nonce']) || !wp_verify_nonce($parsed_data['gswpts_sheet_nonce'],  'gswpts_sheet_nonce_action')) {
            self::$output['response_type'] = 'invalid_request';
            self::$output['output'] = '<b>Request is invalid</b>';
            echo json_encode(self::$output);
            wp_die();
        }

        if (!$parsed_data['sheet_url'] && $parsed_data['sheet_url'] == "") {
            self::$output['response_type'] = 'empty_field';
            self::$output['output'] = '<b>Form field is empty. Please fill out the sheet url</b>';
            echo json_encode(self::$output);
            wp_die();
        }


        if ($_POST['type'] == 'fetch') {
            echo json_encode(self::sheet_html($parsed_data['sheet_url']));
            wp_die();
        }

        if ($_POST['type'] == 'save' || $_POST['type'] == 'saved') {
            echo json_encode(self::save_table($parsed_data));
            wp_die();
        }

        self::$output['response_type'] = 'invalid_request';
        self::$output['output'] = '<b>Request is invalid</b>';
        echo json_encode(self::$output);
        wp_die();
    }


    public static function sheet_html($url) {
        global $gswpts;
        $table = '<table id="create_tables" class="ui celled table">';
        $sheet_response = $gswpts->get_csv_data($url);
        if (!$sheet_response || empty($sheet_response) || $sheet_response == null) {
            self::$output['response_type'] = 'invalid_request';
            self::$output['output'] = '<b>The SpreadSheet url is invalid. Please enter a valid public sheet url</b>';
            return self::$output;
        }
        $i = 0;
        while (!feof($sheet_response)) {
            if ($i == 0) {
                $table .= '<thead><tr>';
                foreach (fgetcsv($sheet_response) as $cell_value) {
                    if ($cell_value) {
                        $table .= '<th>' . $cell_value . '</th>';
                    } else {
                        $table .= '<th>&nbsp;</th>';
                    }
                }
                $table .= '</tr></thead>';
            } else {
                $table .= '<tr>';
                foreach (fgetcsv($sheet_response) as $cell_value) {
                    if ($cell_value) {
                        $table .= '<td>' . $cell_value . '</td>';
                    } else {
                        $table .= '<td>&nbsp;</td>';
                    }
                }
                $table .= '</tr>';
            }
            $i++;
        }
        fclose($sheet_response);

        $table .= '</table>';

        $json_res = $gswpts->get_json_data($url);

        self::$output['response_type'] = 'success';
        self::$output['sheet_data'] = [
            'sheet_name' => $json_res['title']['$t'],
            'author_info' => $json_res['author'],
            'sheet_total_result' => $json_res['openSearch$totalResults']['$t'],
            'total_rows' => $i,
        ];
        self::$output['output'] = "" . $table . "";
        return self::$output;
    }

    public static function save_table(array $parsed_data) {
        global $wpdb;
        $table = $wpdb->prefix . 'gswpts_spreadsheet';

        if (self::check_sheet_url($parsed_data['sheet_url']) == false) {
            self::$output['response_type'] = 'sheet_exists';
            self::$output['output'] = "<b>This SpreadSheet already exists. Try new one</b>";
            return self::$output;
        }

        $data = [
            'table_name' => sanitize_text_field($parsed_data['table_name']),
            'sheet_url' => esc_url($parsed_data['sheet_url']),
        ];
        $db_respond = $wpdb->insert($table, $data, [
            '%s',
            '%s',
        ]);
        if (is_int($db_respond)) {
            $last_record = "SELECT id FROM {$table} ORDER BY id DESC LIMIT 1";
            $last_row = $wpdb->get_results($wpdb->prepare($last_record));
            if ($last_row[0]->id) {
                self::$output['response_type'] = 'saved';
                self::$output['id'] = $last_row[0]->id;
                self::$output['sheet_url'] = $parsed_data['sheet_url'];
                self::$output['output'] = '<b>Table saved successfully</b>';
            } else {
                self::$output['response_type'] = 'invalid_request';
                self::$output['output'] = "<b>Table couldn't be saved. Please try again</b>";
            }
            return self::$output;
        }
    }

    public static function check_sheet_url(string $url) {
        global $wpdb;
        global $gswpts;
        $table = $wpdb->prefix . 'gswpts_spreadsheet';

        $fetch_data = $wpdb->get_results($wpdb->prepare("SELECT sheet_url FROM " . $table . ""));
        if (!empty($fetch_data)) {
            foreach ($fetch_data as $sheet_url) {
                if ($gswpts->get_sheet_id($sheet_url->sheet_url) == $gswpts->get_sheet_id($url)) {
                    return false;
                } else {
                    return true;
                }
            }
        } else {
            return true;
        }
    }
}
