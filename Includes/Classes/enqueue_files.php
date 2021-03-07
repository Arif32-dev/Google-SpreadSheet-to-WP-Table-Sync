<?php

namespace GSWPTS\Includes\Classes;

if (!defined('ABSPATH')) {
    die('you cant access this plugin directly');
}

class Enqueue_Files {
    public function __construct() {
        add_action('admin_enqueue_scripts', [$this, 'backend_files']);
        add_action('wp_enqueue_scripts', [$this, 'frontend_files']);
        if (get_option('gutenberg_support') == 'on') {
            add_action('enqueue_block_editor_assets', [$this, 'gutenberg_files']);
        }
    }

    public function backend_files() {
        if ((isset($_GET['page']) && $_GET['page'] == 'gswpts-dashboard') ||
            (isset($_GET['page']) && $_GET['page'] == 'gswpts-tables') ||
            (isset($_GET['page']) && $_GET['page'] == 'gswpts-create-tables') ||
            (isset($_GET['page']) && $_GET['page'] == 'gswpts-general-settings')
        ) {

            /* CSS Files */
            wp_enqueue_style('GSWPTS-alert-css', GSWPTS_BASE_URL . 'Assets/Public/Package/alert.min.css', [], GSWPTS_VERSION, 'all');
            wp_enqueue_style('GSWPTS-admin-css', GSWPTS_BASE_URL . 'Assets/Public/Styles/admin.min.css', [], time(), 'all');
            wp_enqueue_style('GSWPTS-fontawesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css');

            /* Javascript Files */
            wp_enqueue_script('jquery');
            wp_enqueue_script('GSWPTS-admin-js', GSWPTS_BASE_URL . 'Assets/Public/Scripts/Backend/admin.min.js', ['jquery'], GSWPTS_VERSION, true);
            wp_localize_script('GSWPTS-admin-js', 'file_url', ['admin_ajax' => admin_url('admin-ajax.php'),]);
            wp_enqueue_script('GSWPTS-jquery-js', '//code.jquery.com/jquery-3.5.1.min.js');
            wp_enqueue_script('GSWPTS-alert-js', GSWPTS_BASE_URL . 'Assets/Public/Package/alert.min.js', ['GSWPTS-jquery-js'], GSWPTS_VERSION, true);
        }
    }

    public function frontend_files() {

        global $gswpts;
        wp_enqueue_script('jquery');
        $gswpts->frontend_tables_assets();

        wp_enqueue_style('GSWPTS-frontend-css', GSWPTS_BASE_URL . 'Assets/Public/Styles/frontend.min.css', [], GSWPTS_VERSION, 'all');

        wp_enqueue_script(
            'GSWPTS-frontend-js',
            GSWPTS_BASE_URL . 'Assets/Public/Scripts/Frontend/frontend.min.js',
            ['jquery'],
            GSWPTS_VERSION,
            true
        );

        wp_localize_script('GSWPTS-frontend-js', 'front_end_data', [
            'admin_ajax' => admin_url('admin-ajax.php'),
            'asynchronous_loading' => get_option('asynchronous_loading') == 'on' ? 'on' : 'off'
        ]);
    }

    public function gutenberg_files() {

        wp_enqueue_style('GSWPTS-gutenberg-css', GSWPTS_BASE_URL . 'Assets/Public/Styles/gutenberg.min.css', [], GSWPTS_VERSION, 'all');

        wp_enqueue_script(
            'gswpts-gutenberg',
            GSWPTS_BASE_URL . 'Assets/Public/Scripts/Backend/Gutenberg/gutenberg.min.js',
            ['wp-blocks', 'wp-i18n', 'wp-editor', 'wp-element', 'wp-components', 'GSWPTS-jquery-js'],
            GSWPTS_VERSION,
            true
        );

        register_block_type(
            'gswpts/spreadsheet-to-wp-table-sync',
            array(
                'editor_script' => 'gswpts-gutenberg',
            )
        );
        global $gswpts;
        $gswpts->semantic_files();
        $gswpts->data_table_styles();
        $gswpts->data_table_scripts();

        wp_localize_script('gswpts-gutenberg', 'gswpts_gutenberg_block', [
            'admin_ajax' => admin_url('admin-ajax.php'),
            'table_details' => $gswpts->fetch_gswpts_tables()
        ]);
    }
}
