<?php

/**
 * Plugin Name:       Sheets To WP Table Live Sync
 * Plugin URI:        https://wppool.dev/sheets-to-wp-table-live-sync/
 * Description:       Display Google Spreadsheet data to WordPress table in just a few clicks and keep the data always synced. Organize and display all your spreadsheet data in your WordPress quickly and effortlessly.
 * Version:           1.0.0
 * Requires at least: 5.0
 * Requires PHP:      5.4
 * Author:            WPPOOL
 * Author URI:        https://wppool.dev/
 * Text Domain:       sheetstowptable
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

/* if accessed directly exit from plugin */
defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheetstowptable'));

if (!defined('GSWPTS_VERSION')) {
    define('GSWPTS_VERSION', time());
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
        $this->appseroInit();
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
        printf('<div class="notice notice-error is-dismissible"><h3><strong>%s </strong></h3><p>%s</p></div>', __('Plugin', 'sheetstowptable'), __('cannot be activated - requires at least PHP 5.4. Plugin automatically deactivated.', 'sheetstowptable'));
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
            sprintf('<a href="%s">%s</a>', esc_url(admin_url('admin.php?page=gswpts-dashboard')), esc_html__('Dashboard', 'sheetstowptable')),
            sprintf('<a href="%s">%s</a>', esc_url(admin_url('admin.php?page=gswpts-create-tables')), esc_html__('Create Table', 'sheetstowptable')),
            sprintf('<a href="%s">%s</a>', esc_url(admin_url('admin.php?page=gswpts-general-settings')), esc_html__('General Settings', 'sheetstowptable')),
        );
        return array_merge($links, $mylinks);
    }
    public function appseroInit(){
        if( ! class_exists( 'Appsero\Client' ) ) {
            require_once __DIR__ . '/appsero/src/Client.php';
        }
      
        $client = new Appsero\Client( 'e8bb9069-1a77-457b-b1e3-a961ce950e2f', 'Sheets To WP Table Live Sync', __FILE__ );
      
          // Active insights
        $client->insights()->init();
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
