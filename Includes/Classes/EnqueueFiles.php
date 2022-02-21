<?php

namespace GSWPTS\includes\classes;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheetstowptable'));

if (!defined('ABSPATH')) {
    die('you cant access this plugin directly');
}

class EnqueueFiles {
    public function __construct() {
        add_action('admin_enqueue_scripts', [$this, 'backendFiles']);
        add_action('wp_enqueue_scripts', [$this, 'frontendFiles']);
        add_action('enqueue_block_editor_assets', [$this, 'gutenbergFiles']);
    }

    public function backendFiles() {
        $current_screen = get_current_screen();
        $get_page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : null;

        if (($get_page == 'gswpts-dashboard') ||
            ($get_page == 'gswpts-manage-tab') ||
            ($get_page == 'gswpts-general-settings') ||
            ($get_page == 'gswpts-documentation') ||
            ($get_page == 'gswpts-recommendation') ||
            ($get_page == 'sheets_to_wp_table_live_sync_pro_settings') ||
            ($current_screen->is_block_editor())
        ) {

            global $gswpts;
            $gswpts->semanticFiles();

            $gswpts->dataTableStyles();
            $gswpts->dataTableScripts();

            do_action('gswpts_export_dependency_backend', $get_page);

            /* CSS Files */
            wp_enqueue_style('GSWPTS-alert-css', GSWPTS_BASE_URL . 'assets/public/package/alert.min.css', [], GSWPTS_VERSION, 'all');
            wp_enqueue_style('GSWPTS-fontawesome', GSWPTS_BASE_URL . 'assets/public/icons/fontawesome/css/all.min.css', [], GSWPTS_VERSION, 'all');
            wp_enqueue_style('GSWPTS-admin-css', GSWPTS_BASE_URL . 'assets/public/styles/admin.min.css', [], GSWPTS_VERSION, 'all');
            $this->tableStylesCss();

            /* Javascript Files */
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-ui-draggable');
            wp_enqueue_script('jquery-ui-droppable');
            wp_enqueue_script('GSWPTS-fontawesome', GSWPTS_BASE_URL . 'assets/public/icons/fontawesome/css/all.min.js', [], GSWPTS_VERSION, true);
            wp_enqueue_script('GSWPTS-admin-js', GSWPTS_BASE_URL . 'assets/public/scripts/backend/admin.min.js', ['jquery', 'jquery-ui-draggable', 'jquery-ui-droppable'], GSWPTS_VERSION, true);

            $iconsURLs = apply_filters('export_buttons_logo_backend', false);

            wp_localize_script('GSWPTS-admin-js', 'file_url', [
                'admin_ajax'   => esc_url(admin_url('admin-ajax.php')),
                'iconsURL'     => $iconsURLs,
                'isProActive'  => $gswpts->isProActive(),
                'tableStyles'  => $gswpts->tableStylesArray(),
                'renameIcon'   => GSWPTS_BASE_URL . 'assets/public/icons/rename.svg',
                'dasboardURL'  => esc_url(admin_url('admin.php?page=gswpts-dashboard')),
                'manageTabURL' => esc_url(admin_url('admin.php?page=gswpts-manage-tab'))
            ]);
        }

        if ($get_page == 'gswpts-general-settings') {
            wp_enqueue_script('GSWPTS-cssCodeEditor', GSWPTS_BASE_URL . 'assets/public/common/editor/ace.js', [], GSWPTS_VERSION, true);
            wp_enqueue_script('GSWPTS-modeCSS', GSWPTS_BASE_URL . 'assets/public/common/editor/mode-css.js', [], GSWPTS_VERSION, true);
            wp_enqueue_script('GSWPTS-workerCSS', GSWPTS_BASE_URL . 'assets/public/common/editor/worker-css.js', [], GSWPTS_VERSION, true);
            wp_enqueue_script('GSWPTS-vibrantCSS', GSWPTS_BASE_URL . 'assets/public/common/editor/vibrant-ink.js', [], GSWPTS_VERSION, true);
        }
    }

    public function frontendFiles() {

        global $gswpts;
        wp_enqueue_script('jquery');

        $gswpts->frontendTablesAssets();

        do_action('gswpts_export_dependency_frontend');

        wp_enqueue_style('GSWPTS-frontend-css', GSWPTS_BASE_URL . 'assets/public/styles/frontend.min.css', [], GSWPTS_VERSION, 'all');
        $this->tableStylesCss();

        wp_enqueue_script(
            'GSWPTS-frontend-js',
            GSWPTS_BASE_URL . 'assets/public/scripts/frontend/frontend.min.js',
            ['jquery', 'jquery-ui-draggable'],
            GSWPTS_VERSION,
            true
        );

        $iconsURLs = apply_filters('export_buttons_logo_frontend', false);

        wp_localize_script('GSWPTS-frontend-js', 'front_end_data', [
            'admin_ajax'           => esc_url(admin_url('admin-ajax.php')),
            'asynchronous_loading' => get_option('asynchronous_loading') == 'on' ? 'on' : 'off',
            'isProActive'          => $gswpts->isProActive(),
            'iconsURL'             => $iconsURLs
        ]);
    }

    public function gutenbergFiles() {

        wp_enqueue_style('GSWPTS-gutenberg-css', GSWPTS_BASE_URL . 'assets/public/styles/gutenberg.min.css', [], GSWPTS_VERSION, 'all');

        wp_enqueue_script(
            'gswpts-gutenberg',
            GSWPTS_BASE_URL . 'assets/public/scripts/backend/gutenberg/gutenberg.min.js',
            ['wp-blocks', 'wp-i18n', 'wp-editor', 'wp-element', 'wp-components', 'jquery'],
            GSWPTS_VERSION,
            true
        );

        register_block_type(
            'gswpts/google-sheets-to-wp-tables',
            [
                'description'   => __('Display Google Spreadsheet data to WordPress table in just a few clicks and keep the data always synced. Organize and display all your spreadsheet data in your WordPress quickly and effortlessly.', 'sheetstowptable'),
                'title'         => 'Sheets To WP Table Live Sync',
                'editor_script' => 'gswpts-gutenberg',
                'editor_style'  => 'GSWPTS-gutenberg-css'
            ]
        );

        // register_block_type(GSWPTS_BASE_PATH . 'block.json');

        global $gswpts;
        $gswpts->semanticFiles();
        $gswpts->dataTableStyles();
        $gswpts->dataTableScripts();
        $this->tableStylesCss();

        wp_localize_script('gswpts-gutenberg', 'gswpts_gutenberg_block', [
            'admin_ajax'       => esc_url(admin_url('admin-ajax.php')),
            'table_details'    => $gswpts->fetchTables(),
            'isProActive'      => $gswpts->isProActive(),
            'tableStyles'      => $gswpts->tableStylesArray(),
            'scrollHeights'    => $gswpts->scrollHeightArray(),
            'responsiveStyles' => $gswpts->responsiveStyle()
        ]);
    }

    /**
     * @return null
     */
    public function tableStylesCss() {
        global $gswpts;

        $stylesArray = $gswpts->tableStylesArray();

        $stylesArray = apply_filters('gswpts_table_styles_path', $stylesArray);

        if (!$stylesArray) {
            return;
        }

        foreach ($stylesArray as $key => $style) {
            $tableStyleFileURL = isset($style['cssURL']) ? $style['cssURL'] : '';
            $tableStyleFilePath = isset($style['cssPath']) ? $style['cssPath'] : '';

            if (file_exists($tableStyleFilePath)) {
                wp_enqueue_style('gswptsProTable_' . $key . '', $tableStyleFileURL, [], GSWPTS_VERSION, 'all');
            }
        }
    }
}