<?php

namespace GSWPTS\Includes\Classes\Controller;

if (!defined('ABSPATH')) {
    die('you cant access this plugin directly');
}

class Ajax_Handler {
    private $output = [];

    public function __construct() {
        $this->events();
    }
    public function events() {
        add_action('wp_ajax_gswpts_sheet_create', [$this, 'sheet_creation']);
        add_action('wp_ajax_nopriv_gswpts_sheet_create', [$this, 'sheet_creation']);
    }
    public function sheet_creation() {

        if ($_POST['action'] != 'gswpts_sheet_create') {
            $this->output['error_type'] = 'invalid_action';
            $this->output['error_message'] = 'Action is invalid';
            echo json_encode($this->output);
            wp_die();
        }

        parse_str($_POST['form_data'], $parsed_data);

        if (!isset($parsed_data['gswpts_sheet_nonce']) || !wp_verify_nonce($parsed_data['gswpts_sheet_nonce'],  'gswpts_sheet_nonce_action')) {
            wp_die();
        }

        global $gswpts;

        // print_r($gswpts->get_sheet_id($parsed_data['sheet_url']));
        $client = new \GuzzleHttp\Client();
        $res = $client->request(
            'GET',
            'https://spreadsheets.google.com/feeds/cells/1t7MnIPlu_lU9srlftEvtSnSx3db3-hLctNXFao3wRVQ/1/public/full?alt=json'
        );
        print_r(json_decode($res->getBody(), true, 99)['feed']);
        wp_die();
    }
}
