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
        echo 'fetching is working';
        wp_die();
    }
}
