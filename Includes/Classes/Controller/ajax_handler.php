<?php

namespace GSWPTS\Includes\Classes\Controller;

use GSWPTS\Includes\Classes\Controller\Ajax_Parts\Sheet_Creation;
use GSWPTS\Includes\Classes\Controller\Ajax_Parts\Sheet_Fetching;

if (!defined('ABSPATH')) {
    die('you cant access this plugin directly');
}

class Ajax_Handler {
    private static $output = [];

    public function __construct() {
        $this->events();
    }
    public function events() {
        /* Sheet Creation */
        add_action('wp_ajax_gswpts_sheet_create', [$this, 'sheet_creation']);
        add_action('wp_ajax_nopriv_gswpts_sheet_create', [$this, 'sheet_creation']);
        /* Sheet Fetching */
        add_action('wp_ajax_gswpts_sheet_fetch', [$this, 'sheet_fetch']);
        add_action('wp_ajax_nopriv_gswpts_sheet_fetch', [$this, 'sheet_fetch']);
    }
    public function sheet_creation() {
        $sheet_creation = new Sheet_Creation;
        $sheet_creation->sheet_creation();
    }

    public function sheet_fetch() {
        $sheet_creation = new Sheet_Fetching;
        $sheet_creation->sheet_fetch();
    }
}
