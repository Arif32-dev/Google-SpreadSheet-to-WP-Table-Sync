<?php

namespace GSWPTS\Includes\Classes\Controller;

use GSWPTS\Includes\Classes\Controller\Ajax_Parts\Fetch_Products;
use GSWPTS\Includes\Classes\Controller\Ajax_Parts\Sheet_Creation;
use GSWPTS\Includes\Classes\Controller\Ajax_Parts\Sheet_Fetching;
use GSWPTS\Includes\Classes\Controller\Ajax_Parts\Table_Fetch;
use GSWPTS\Includes\Classes\Controller\Ajax_Parts\UD_Tables;

if (!defined('ABSPATH')) {
    die('you cant access this plugin directly');
}

class Ajax_Handler {

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
        /* Table Fetching */
        add_action('wp_ajax_gswpts_table_fetch', [$this, 'table_fetch']);
        add_action('wp_ajax_nopriv_gswpts_table_fetch', [$this, 'table_fetch']);
        /* Table Fetching */
        add_action('wp_ajax_gswpts_ud_table', [$this, 'ud_tables']);
        add_action('wp_ajax_nopriv_gswpts_ud_table', [$this, 'ud_tables']);
        /* Other product fetching */
        add_action('wp_ajax_gswpts_product_fetch', [$this, 'fetch_products']);
        add_action('wp_ajax_nopriv_gswpts_product_fetch', [$this, 'fetch_products']);
    }
    public function sheet_creation() {
        $sheet_creation = new Sheet_Creation;
        $sheet_creation->sheet_creation();
    }

    public function sheet_fetch() {
        $sheet_fetching = new Sheet_Fetching;
        $sheet_fetching->sheet_fetch();
    }

    public function table_fetch() {
        $table_fetching = new Table_Fetch;
        $table_fetching->table_fetch();
    }

    public function ud_tables() {
        $ud_tables = new UD_Tables;
        $ud_tables->ud_tables();
    }

    public function fetch_products() {
        $ud_tables = new Fetch_Products;
        $ud_tables->fetch_products();
    }
}
