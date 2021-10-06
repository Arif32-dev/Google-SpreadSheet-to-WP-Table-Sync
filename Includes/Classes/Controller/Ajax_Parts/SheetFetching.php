<?php

namespace GSWPTS\Includes\Classes\Controller\Ajax_Parts;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheetstowptable'));

class SheetFetching {
    /**
     * @var array
     */
    private static $output = [];

    public function sheet_fetch() {
        if (sanitize_text_field($_POST['action']) != 'gswpts_sheet_fetch') {
            self::$output['response_type'] = esc_html('invalid_action');
            self::$output['output'] = '<b>' . esc_html__('Action is invalid', 'sheetstowptable') . '</b>';
            echo json_encode(self::$output);
            wp_die();
        }
        $ID = sanitize_text_field($_POST['id']);
        if (empty($ID) || $ID == null || $ID == "") {
            self::$output['response_type'] = esc_html('invalid_request');
            self::$output['output'] = '<b>' . esc_html__('Request is invalid', 'sheetstowptable') . '</b>';
            echo json_encode(self::$output);
            wp_die();
        }

        echo json_encode(self::output_html(sanitize_text_field($_POST['id'])));
        wp_die();
    }

    /**
     * @param int $id
     */
    public static function output_html(int $id) {
        global $gswpts;

        $db_result = $gswpts->fetch_db_by_id($id);
        if (!$db_result) {
            self::$output['response_type'] = esc_html('invalid_request');
            self::$output['output'] = '<b>' . esc_html__('Request is invalid', 'sheetstowptable') . '</b>';
            return self::$output;
        }
        $source_url = $db_result[0]->source_url;
        $table_name = $db_result[0]->table_name;
        $source_type = $db_result[0]->source_type;
        $table_settings = unserialize($db_result[0]->table_settings);
        $table_id = $db_result[0]->id;

        return self::sheet_html($source_url, $table_name, $source_type, $table_settings, $table_id);
    }

    /**
     * @param $url
     * @param $table_name
     * @param $source_type
     * @param $table_settings
     * @param $table_id
     */
    public static function sheet_html(
        $url,
        $table_name,
        $source_type,
        $table_settings,
        $table_id
    ) {
        global $gswpts;

        $tableCache = false;

        if (isset($table_settings['table_cache']) && $table_settings['table_cache'] == 'true' && $gswpts->isProActive()) {
            $tableCache = true;
        }

        $sheet_response = $gswpts->loadDataByCondition($table_id, $url, $tableCache);

        if (!$sheet_response || empty($sheet_response) || $sheet_response == null) {
            self::$output['response_type'] = esc_html('invalid_request');
            self::$output['output'] = '<b>' . esc_html__('The spreadsheet is restricted.', 'sheetstowptable') . '<br/>' . esc_html__('Please make it public by clicking on share button at the top of spreadsheet', 'sheetstowptable') . '</b>';
            return self::$output;
        }

        $hiddenValues = [
            'hiddenRows'  => $table_settings['hide_rows'] ? $table_settings['hide_rows'] : [],
            'hiddenCells' => $table_settings['hide_cell'] ? $table_settings['hide_cell'] : []
        ];

        $response = $gswpts->get_table(true, $sheet_response, null, $hiddenValues);

        self::$output['response_type'] = esc_html('success');

        self::$output['table_data'] = [
            'table_id'       => esc_html__($table_id, 'sheetstowptable'),
            'source_url'     => esc_url($url),
            'table_name'     => esc_html__($table_name, 'sheetstowptable'),
            'source_type'    => esc_html__($source_type, 'sheetstowptable'),
            'table_settings' => json_encode(self::escapeData($table_settings))
        ];
        self::$output['output'] = "" . $response['table'] . "";
        self::$output['tableColumns'] = $response['tableColumns'];
        return self::$output;
    }

    /**
     * @param  array   $tableSettings
     * @return array
     */
    public static function escapeData(array $tableSettings) {
        $escapedData = null;

        $escapedData = array_map(function ($setting) {
            if (gettype($setting) == 'array') {
                return self::escapeData($setting);
            } else {
                return esc_html__($setting);
            }
        }, $tableSettings);

        return $escapedData;
    }

}