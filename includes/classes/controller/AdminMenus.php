<?php

namespace GSWPTS\includes\classes\controller;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheetstowptable'));

class AdminMenus {
    public function __construct() {
        add_action('admin_menu', [$this, 'admin_menus']);
    }

    public function admin_menus() {
        add_menu_page(
            __('Sheets To Table', 'sheetstowptable'),
            __('Sheets To Table', 'sheetstowptable'),
            'manage_options',
            'gswpts-dashboard',
            [$this, 'dashboardPage'],
            GSWPTS_BASE_URL . 'assets/public/images/logo_20_20.svg'
        );
        add_submenu_page(
            'gswpts-dashboard',
            __('Dashboard', 'sheetstowptable'),
            __('Dashboard', 'sheetstowptable'),
            'manage_options',
            'gswpts-dashboard',
            [$this, 'dashboardPage']
        );

        global $gswpts;

        if ($this->checkProPluginExists() && $gswpts->isProActive()) {
            add_submenu_page(
                'gswpts-dashboard',
                __('Manage Tab', 'sheetstowptable'),
                __('Manage Tab', 'sheetstowptable'),
                'manage_options',
                'gswpts-manage-tab',
                [$this, 'tabPage']
            );
        }

        add_submenu_page(
            'gswpts-dashboard',
            __('General Settings', 'sheetstowptable'),
            __('General Settings', 'sheetstowptable'),
            'manage_options',
            'gswpts-general-settings',
            [$this, 'generalSettingsPage']
        );
        add_submenu_page(
            'gswpts-dashboard',
            __('Documentation', 'sheetstowptable'),
            __('Documentation', 'sheetstowptable'),
            'manage_options',
            'gswpts-documentation',
            [$this, 'documentationPage']
        );
        add_submenu_page(
            'gswpts-dashboard',
            __('Recommended Plugins', 'sheetstowptable'),
            __('Recommended Plugins', 'sheetstowptable'),
            'manage_options',
            'gswpts-recommendation',
            [$this, 'pluginRecommendationPage']
        );

        if (!$this->checkProPluginExists()) {
            add_submenu_page(
                'gswpts-dashboard',
                __('Sheets To WP Table Live Sync Pro', 'sheetstowptable'),
                __('<span style="color: #ff3b00; font-weight: 900; font-size: 14px; letter-spacing: 1.2px">Upgrade To Pro</span>', 'sheetstowptable'),
                'manage_options',
                'https://go.wppool.dev/fu4'
            );
        }
    }

    /**
     * @return boolean
     */
    public function checkProPluginExists(): bool {
        $isProExits = false;
        $plugins = get_plugins();
        if (!$plugins) {
            return false;
        }

        foreach ($plugins as $plugin) {
            if ($plugin['TextDomain'] == 'sheetstowptable-pro') {
                $isProExits = true;
                break;
            }
        }
        return $isProExits;
    }

    public static function dashboardPage() {
        load_template(GSWPTS_BASE_PATH . 'includes/templates/manage_tables.php', true);
    }

    public static function tabPage() {
        load_template(GSWPTS_BASE_PATH . 'includes/templates/manage_tab.php', true);
    }

    public static function generalSettingsPage() {
        load_template(GSWPTS_BASE_PATH . 'includes/templates/general_settings.php', true);
    }

    public static function documentationPage() {
        load_template(GSWPTS_BASE_PATH . 'includes/templates/documentation_page.php', true);
    }

    public static function pluginRecommendationPage() {
        load_template(GSWPTS_BASE_PATH . 'includes/templates/recommendation_page.php', true);
    }
}