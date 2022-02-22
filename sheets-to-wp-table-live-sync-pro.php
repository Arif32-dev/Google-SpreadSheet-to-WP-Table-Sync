<?php

/**
 * Plugin Name:       Sheets To WP Table Live Sync Pro
 * Plugin URI:        https://wppool.dev/sheets-to-wp-table-live-sync/
 * Description:       Display Google Spreadsheet data to WordPress table in just a few clicks and keep the data always synced. Organize and display all your spreadsheet data in your WordPress quickly and effortlessly.
 * Version:           2.12.1
 * Requires at least: 5.0
 * Requires PHP:      5.4
 * Author:            WPPOOL
 * Author URI:        https://wppool.dev/
 * Text Domain:       sheetstowptable-pro
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

/* if accessed directly exit from plugin */
defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheetstowptable-pro'));

if (!defined('GSWPTS_PRO_VERSION')) {
    // define('GSWPTS_PRO_VERSION', '2.12.1');
    define('GSWPTS_PRO_VERSION', time());
}

if (!defined('GSWPTS_PRO_BASE_PATH')) {
    define('GSWPTS_PRO_BASE_PATH', plugin_dir_path(__FILE__));
}

if (!defined('GSWPTS_PRO_BASE_URL')) {
    define('GSWPTS_PRO_BASE_URL', plugin_dir_url(__FILE__));
}

if (!file_exists(GSWPTS_PRO_BASE_PATH . 'vendor/autoload.php')) {
    return;
}

require_once GSWPTS_PRO_BASE_PATH . 'vendor/autoload.php';

final class SheetsToWPTableLiveSyncPro {
    /**
     * @return null
     */
    public function __construct() {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        if ($this->version_check() == 'version_low') {
            return;
        }
        add_action('plugins_loaded', [$this, 'initializeProPlugin']);

        $this->register_active_deactive_hooks();
    }

    public function initializeProPlugin() {

        if (!$this->isBasePluginActive()) {
            deactivate_plugins(plugin_basename(__FILE__));
            return false;
        }

        $this->redirectUser();

        if (!$this->appseroInit()) {
            if (get_option('is-sheets-to-wp-table-pro-active')) {
                delete_option('is-sheets-to-wp-table-pro-active');
            }
            return false;
        }

        if (is_plugin_active(plugin_basename(__FILE__))) {
            add_option('is-sheets-to-wp-table-pro-active', true);
            $this->include_file();
        }
    }

    public function redirectUser() {
        if (get_option('gswpts_activation_pro_redirect', false)) {
            delete_option('gswpts_activation_pro_redirect');
            wp_redirect(admin_url('admin.php?page=gswpts-dashboard'));
        }
    }

    public function include_file() {

        new GSWPTS_PRO\Includes\PluginBase();
    }

    public function register_active_deactive_hooks() {
        register_activation_hook(__FILE__, function () {
            add_option('gswpts_activation_pro_redirect', true);
            flush_rewrite_rules();
        });
        register_deactivation_hook(__FILE__, function () {
            flush_rewrite_rules();
            update_option('custom_css', false);
        });
    }

    /**
     * @return boolean
     */
    public function appseroInit(): bool {
        if (!class_exists('Appsero\Client')) {
            require_once __DIR__ . '/appsero/src/Client.php';
        }

        $client = new Appsero\Client('b82905c5-b807-47a0-a2cf-a7d3792f362f', 'Sheets to WP Table Live Sync Premium', __FILE__);

        // Hide the notice
        $client->insights()
            ->hide_notice()
            ->init();

        // Active automatic updater
        $client->updater();

        // Active license page and checker
        $args = array(
            'type'        => 'submenu',
            'menu_title'  => 'Pro License',
            'page_title'  => 'Sheets to WP Table Live Sync License',
            'menu_slug'   => 'sheets_to_wp_table_live_sync_pro_settings',
            'parent_slug' => 'gswpts-dashboard'
        );
        $client->license()->add_settings_page($args);

        // IF license is not valid then show notice or elese return true
        if (!$client->license()->is_valid()) {
            add_action('admin_notices', [$this, 'licenseNotice']);
            update_option('custom_css', false);
            return false;
        } else {
            return true;
        }

    }

    /**
     * @return null
     */
    public function licenseNotice() {
        printf('
                <div class="notice notice-info">
                    <p><strong>%s</strong></p>
                    <p>%s</p>
                </div>',
            esc_html('Sheets To WP Table Live Sync Pro License is not activated.'),
            __('Please activate the Pro plugin by entering a valid license
                    <strong style="font-size: 15px;">
                        <a href="' . esc_url(admin_url('admin.php?page=sheets_to_wp_table_live_sync_pro_settings')) . '">Here</a>
                    </strong>', 'sheetstowptable-pro'));
        return;
    }

    /**
     * @return null
     */
    public function show_notice() {
        printf('<div class="notice notice-error is-dismissible"><h3><strong>%s %s </strong></h3><p>%s</p></div>', esc_html('Sheets To WP Table Live Sync Pro'), __('Plugin', 'sheetstowptable-pro'), __('cannot be activated - requires at least PHP 5.4. Plugin automatically deactivated.', 'sheetstowptable-pro'));
        return;
    }

    public function version_check() {
        if (version_compare(PHP_VERSION, '5.4') < 0) {
            if (is_plugin_active(plugin_basename(__FILE__))) {
                deactivate_plugins(plugin_basename(__FILE__));
                add_action('admin_notices', [$this, 'show_notice']);
            }
            return 'version_low';
        }
    }

    /**
     * @return boolean
     */
    public function isBasePluginActive(): bool {
        $returnValue = false;

        if (!class_exists('SheetsToWPTableLiveSync')) {

            if (is_plugin_active(plugin_basename(__FILE__))) {
                add_action('admin_notices', function () {
                    printf(
                        '<div class="notice notice-error is-dismissible"><h3><strong>%s %s </strong></h3><p>%s</p></div>',
                        esc_html('Sheets To WP Table Live Sync Pro'),
                        __('Plugin', 'sheetstowptable-pro'),
                        __('cannot be activated - requires the base plugin
                            <b><a href="' . esc_url(self_admin_url('plugin-install.php?s=Sheets+to+WP+Table+Live+Sync+WPPOOL&tab=search&type=term')) . '">Sheets to WP Table Live Sync</a></b>
                            to be activated.', 'sheetstowptable-pro'
                        )
                    );
                });
            }

            $returnValue = false;
        } else {
            $returnValue = true;
        }
        return $returnValue;
    }
}

if (!class_exists('SheetsToWPTableLiveSyncPro')) {
    return;
}

if (!function_exists('sheetsToWPTableLiveSyncPro')) {
    function sheetsToWPTableLiveSyncPro() {
        return new SheetsToWPTableLiveSyncPro();
    }
}
sheetsToWPTableLiveSyncPro();