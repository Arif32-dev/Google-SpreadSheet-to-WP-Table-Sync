<?php

namespace GSWPTS\Includes\Classes;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheetstowptable'));

if (!defined('ABSPATH')) {
    die('you cant access this plugin directly');
}

class EnqueueFiles {
    public function __construct() {
        add_action('admin_enqueue_scripts', [$this, 'backend_files']);
        add_action('wp_enqueue_scripts', [$this, 'frontend_files']);
        add_action('enqueue_block_editor_assets', [$this, 'gutenberg_files']);
    }

    public function backend_files() {
        $current_screen = get_current_screen();
        $get_page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : null;

        if (($get_page == 'gswpts-dashboard') ||
            ($get_page == 'gswpts-tables') ||
            ($get_page == 'gswpts-create-tables') ||
            ($get_page == 'gswpts-general-settings') ||
            ($current_screen->is_block_editor())
        ) {

            global $gswpts;
            $gswpts->semantic_files();

            $gswpts->data_table_styles();
            $gswpts->data_table_scripts();

            /* CSS Files */
            wp_enqueue_style('GSWPTS-alert-css', GSWPTS_BASE_URL . 'Assets/Public/Package/alert.min.css', [], GSWPTS_VERSION, 'all');
            wp_enqueue_style('GSWPTS-admin-css', GSWPTS_BASE_URL . 'Assets/Public/Styles/admin.min.css', [], GSWPTS_VERSION, 'all');

            /* Javascript Files */
            wp_enqueue_script('jquery');
            wp_enqueue_script('GSWPTS-alert-js', GSWPTS_BASE_URL . 'Assets/Public/Package/alert.min.js', ['jquery'], GSWPTS_VERSION, true);
            wp_enqueue_script('GSWPTS-admin-js', GSWPTS_BASE_URL . 'Assets/Public/Scripts/Backend/admin.min.js', ['jquery'], GSWPTS_VERSION, true);
            wp_localize_script('GSWPTS-admin-js', 'file_url', ['admin_ajax' => admin_url('admin-ajax.php'),]);
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
            ['wp-blocks', 'wp-i18n', 'wp-editor', 'wp-element', 'wp-components', 'jquery'],
            GSWPTS_VERSION,
            true
        );

        register_block_type(
            'gswpts/google-sheets-to-wp-tables',
            array(
                'editor_script' => 'gswpts-gutenberg',
            )
        );
        global $gswpts;
        $gswpts->semantic_files();
        $gswpts->data_table_styles();
        $gswpts->data_table_scripts();

        wp_localize_script('gswpts-gutenberg', 'gswpts_gutenberg_block', [
            'admin_ajax' => esc_url(admin_url('admin-ajax.php')),
            'table_details' => $gswpts->fetch_gswpts_tables()
        ]);
    }
}
