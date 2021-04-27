<?php

namespace GSWPTS\Includes\Classes\Controller\Ajax_Parts;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheets-to-wp-table-live-sync'));

class SheetFetching {
    private static $output = [];

    public function sheet_fetch() {
        if (sanitize_text_field($_POST['action']) != 'gswpts_sheet_fetch') {
            self::$output['response_type'] = esc_html('invalid_action');
            self::$output['output'] = '<b>' . esc_html('Action is invalid') . '</b>';
            echo json_encode(self::$output);
            wp_die();
        }
        $ID = sanitize_text_field($_POST['id']);
        if (empty($ID) || $ID == null || $ID == "") {
            self::$output['response_type'] = esc_html('invalid_request');
            self::$output['output'] = '<b>' . esc_html('Request is invalid') . '</b>';
            echo json_encode(self::$output);
            wp_die();
        }

        echo json_encode(self::output_html(sanitize_text_field($_POST['id'])));
        wp_die();
    }
    public static function output_html(int $id) {
        global $gswpts;

        $db_result = $gswpts->fetch_db_by_id($id);
        if (!$db_result) {
            self::$output['response_type'] = esc_html('invalid_request');
            self::$output['output'] = '<b>' . esc_html('Request is invalid') . '</b>';
            return self::$output;
        }
        $source_url = $db_result[0]->source_url;
        $table_name = $db_result[0]->table_name;
        $source_type = $db_result[0]->source_type;
        $table_settings = unserialize($db_result[0]->table_settings);
        $table_id = $db_result[0]->id;

        return self::sheet_html($source_url, $table_name, $source_type, $table_settings, $table_id);
    }

    public static function sheet_html($url, $table_name, $source_type, $table_settings, $table_id) {
        global $gswpts;

        $json_res = $gswpts->get_json_data($url);

        if (!$json_res) {
            self::$output['response_type'] = esc_html('invalid_request');
            self::$output['output'] = '<b>' . esc_html('The spreadsheet is not publicly available. Publish it to the web.') . '</b>';
            return self::$output;
        }

        $sheet_response = $gswpts->get_csv_data($url);

        if (count(fgetcsv($sheet_response)) < 2) {
            self::$output['response_type'] = esc_html('invalid_request');
            self::$output['output'] = '<b>' . esc_html('The spreadsheet is restricted.') . '<br/>' . esc_html(' Please make it public by clicking on share button at the top of spreadsheet') . '</b>';
            return self::$output;
        }

        $sheet_response = $gswpts->get_csv_data($url);

        if (!$sheet_response || empty($sheet_response) || $sheet_response == null) {
            self::$output['response_type'] = esc_html('invalid_request');
            self::$output['output'] = '<b>' . esc_html('Request is invalid') . '</b>';
            return self::$output;
        }


        $response = $gswpts->get_table(true, $sheet_response);

        self::$output['response_type'] = esc_html('success');
        self::$output['sheet_data'] = [
            'sheet_name' => $json_res['title']['$t'],
            'author_info' => $json_res['author'],
            'sheet_total_result' => esc_html($json_res['openSearch$totalResults']['$t']),
            'total_rows' => esc_html($response['count'])
        ];

        self::$output['table_data'] = [
            'table_id' => esc_html($table_id),
            'source_url' => esc_url($url),
            'table_name' => esc_html($table_name),
            'source_type' => esc_html($source_type),
            'table_settings' => json_encode(array_map(function ($setting) {
                return esc_html($setting);
            }, $table_settings))
        ];
        self::$output['output'] = "" . $response['table'] . "";
        return self::$output;
    }
}
