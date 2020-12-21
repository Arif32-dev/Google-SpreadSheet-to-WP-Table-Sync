<?php

namespace GSWPTS\Includes\Classes\Controller\Ajax_Parts;

class Sheet_Fetching {
    private static $output = [];

    public function sheet_fetch() {
        if ($_POST['action'] != 'gswpts_sheet_fetch') {
            self::$output['response_type'] = 'invalid_action';
            self::$output['output'] = '<b>Action is invalid</b>';
            echo json_encode(self::$output);
            wp_die();
        }
        if (empty($_POST['id']) && $_POST['id'] == null && $_POST['id'] == "") {
            self::$output['response_type'] = 'invalid_request';
            self::$output['output'] = '<b>Request is invalid</b>';
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
            self::$output['response_type'] = 'invalid_request';
            self::$output['output'] = '<b>Request is invalid</b>';
            return self::$output;
        }
        $source_url = $db_result[0]->source_url;
        $table_name = $db_result[0]->table_name;
        $source_type = $db_result[0]->source_type;
        $table_settings = unserialize($db_result[0]->table_settings);
        $table_dom = $db_result[0]->table_dom;

        return self::sheet_html($source_url, $table_name, $source_type, $table_settings, $table_dom);
    }

    public static function sheet_html($url, $table_name, $source_type, $table_settings, $table_dom) {
        global $gswpts;

        $sheet_response = $gswpts->get_csv_data($url);
        if (!$sheet_response || empty($sheet_response) || $sheet_response == null) {
            self::$output['response_type'] = 'invalid_request';
            self::$output['output'] = '<b>Request is invalid</b>';
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
        self::$output['table_data'] = [
            'source_url' => $url,
            'table_name' => $table_name,
            'source_type' => $source_type,
            'table_settings' => json_encode($table_settings),
            'table_dom' => $table_dom
        ];
        self::$output['output'] = "" . $response['table'] . "";
        return self::$output;
    }
}
