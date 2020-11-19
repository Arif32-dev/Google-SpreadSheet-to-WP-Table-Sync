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
            self::$output['output'] = 'Action is invalid';
            echo json_encode($this->output);
            wp_die();
        }

        parse_str($_POST['form_data'], $parsed_data);

        if (!isset($parsed_data['gswpts_sheet_nonce']) || !wp_verify_nonce($parsed_data['gswpts_sheet_nonce'],  'gswpts_sheet_nonce_action')) {
            wp_die();
        }
        echo json_encode(self::sheet_html($parsed_data['sheet_url']));
        wp_die();
    }


    public static function sheet_html($url) {
        global $gswpts;
        $table = '<table id="create_tables" class="ui celled table">';
        $sheet_response = $gswpts->get_csv_data($url);
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
        self::$output['response_type'] = 'success';
        self::$output['output'] = "" . $table . "";
        return self::$output;
    }
}
