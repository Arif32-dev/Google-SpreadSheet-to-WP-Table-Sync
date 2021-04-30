<?php

/**
 * Sheets To WP Table Live Sync
 *
 * @package           sheets-to-wp-table-live-sync
 * @author            WPPOOL
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Sheets To WP Table Live Sync
 * Plugin URI:       https://wordpress.org/plugins/sheets-to-wp-table-live-sync/
 * Description:      This is a WordPress plugin to synchronize google spreadsheet data into wordpress table.
 * Version:           1.0.0
 * Requires at least: 5.0
 * Requires PHP:      5.4
 * Author:             WPPOOL
 * Author URI:        https://wppool.dev/
 * Text Domain:       sheets-to-wp-table-live-sync
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

/* if accessed directly exit from plugin */
defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheets-to-wp-table-live-sync'));

if (!defined('GSWPTS_VERSION')) {
    define('GSWPTS_VERSION', '1.0.0');
}

if (!defined('GSWPTS_BASE_PATH')) {
    define('GSWPTS_BASE_PATH', plugin_dir_path(__FILE__));
}

if (!defined('GSWPTS_BASE_URL')) {
    define('GSWPTS_BASE_URL', plugin_dir_url(__FILE__));
}

if (!defined('PlUGIN_NAME')) {
    define('PlUGIN_NAME', 'Sheets To WP Table Live Sync');
}


if (!file_exists(GSWPTS_BASE_PATH . 'vendor/autoload.php')) {
    return;
}

require_once GSWPTS_BASE_PATH . 'vendor/autoload.php';

final class SheetsToWPTableLiveSync {

    public function __construct() {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        if ($this->version_check() == 'version_low') return;
        $this->register_active_deactive_hooks();
        $this->plugins_check();
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

    public function show_notice() {
        printf('<div class="notice notice-error is-dismissible"><h3><strong>%s </strong></h3><p>%s</p></div>', __('Plugin', 'sheets-to-wp-table-live-sync'), __('cannot be activated - requires at least PHP 5.4. Plugin automatically deactivated.', 'sheets-to-wp-table-live-sync'));
        return;
    }

    public function plugins_check() {
        if (is_plugin_active(plugin_basename(__FILE__))) {
            add_action('plugins_loaded', [$this, 'include_file']);
            add_filter('plugin_action_links_' . plugin_basename(__FILE__), [__CLASS__, 'add_action_links']);
        }
    }

    /**
     * registering activation and deactivation Hooks
     * @return void
     */
    public function register_active_deactive_hooks() {
        register_activation_hook(__FILE__, function () {

            new GSWPTS\Includes\Classes\DbTables;
            add_option('gswpts_activation_redirect', true);
            flush_rewrite_rules();
        });
        register_activation_hook(__FILE__, function () {
            flush_rewrite_rules();
        });
    }

    /**
     * @requiring all the classes once
     * @return void
     */
    public function include_file() {

        new GSWPTS\Includes\PluginBase;

        if (get_option('gswpts_activation_redirect', false)) {
            delete_option('gswpts_activation_redirect');
            wp_redirect(admin_url('admin.php?page=gswpts-dashboard'));
        }
    }
    public static function add_action_links($links) {
        $mylinks = array(
            sprintf('<a href="%s">%s</a>', esc_url(admin_url('admin.php?page=gswpts-dashboard')), esc_html__('Dashboard', 'sheets-to-wp-table-live-sync')),
            sprintf('<a href="%s">%s</a>', esc_url(admin_url('admin.php?page=gswpts-create-tables')), esc_html__('Create Table', 'sheets-to-wp-table-live-sync')),
            sprintf('<a href="%s">%s</a>', esc_url(admin_url('admin.php?page=gswpts-general-settings')), esc_html__('General Settings', 'sheets-to-wp-table-live-sync')),
        );
        return array_merge($links, $mylinks);
    }
}

if (!class_exists('SheetsToWPTableLiveSync')) {
    return;
}

if (!function_exists('sheetsToWPTableLiveSync')) {
    function sheetsToWPTableLiveSync() {
        return new SheetsToWPTableLiveSync;
    }
}
sheetsToWPTableLiveSync();
