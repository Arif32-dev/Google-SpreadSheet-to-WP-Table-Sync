<?php

/**
 * Google SpreadSheet to WP table Sync
 *
 * @package           spreadsheet-to-wp-table-sync
 * @author            Arifur Rahman Arif
 * @copyright         2019 Your Name or Company Name
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Google SpreadSheet to WP table Sync
 * Plugin URI:        https://example.com/plugin-name
 * Description:      This is a WordPress plugin to synchronize google spreadsheet data into wordpress table.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.0.0
 * Author:             Arifur Rahman Arif
 * Author URI:        https://example.com
 * Text Domain:       swpts
 * License:           GPL v2 or later
 * License URI:       
 */

/* if accessed directly exit from plugin */
if (!defined('ABSPATH')) {
    die('you cant access this plugin directly');
}

if (!defined('SWPTS_VERSION')) {
    define('SWPTS_VERSION', '1.0.0');
}

if (!defined('SWPTS_BASE_PATH')) {
    define('SWPTS_BASE_PATH', plugin_dir_path(__FILE__));
}

if (!defined('SWPTS_BASE_URL')) {
    define('SWPTS_BASE_URL', plugin_dir_url(__FILE__));
}

if (!file_exists(SWPTS_BASE_PATH . 'vendor/autoload.php')) {
    return;
}

require_once SWPTS_BASE_PATH . 'vendor/autoload.php';

final class SWPTS_Plugin {

    public function __construct() {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        if ($this->version_check() == 'version_low')
            return;
        $this->register_active_deactive_hooks();
        $this->plugins_check();
    }

    public function version_check() {
        if (version_compare(PHP_VERSION, '7.0.0') < 0) {
            if (is_plugin_active(plugin_basename(__FILE__))) {
                deactivate_plugins(plugin_basename(__FILE__));
                add_action('admin_notices', [$this, 'show_notice']);
            }
            return 'version_low';
        }
    }

    public function show_notice() {
        echo '<div class="notice notice-error is-dismissible"><h3><strong>Plugin </strong></h3><p> cannot be activated - requires at least  PHP 7.0.0 Plugin automatically deactivated.</p></div>';
        return;
    }

    public function plugins_check() {
        if (is_plugin_active(plugin_basename(__FILE__))) {
            $this->include_file();
            add_filter('plugin_action_links_' . plugin_basename(__FILE__), [__CLASS__, 'add_action_links']);
        }
    }

    /**
     * registering activation and deactivation Hooks
     * @return void
     */
    public function register_active_deactive_hooks() {
        register_activation_hook(__FILE__, function () {
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
        new SWPTS\Includes\Plugin;
    }
    public static function add_action_links($links) {
        $mylinks = array(
            '<a href="' . admin_url('options-general.php?page=wp_user_control') . '">Settigns Page</a>',
        );
        return array_merge($links, $mylinks);
    }
}

if (!class_exists('SWPTS_Plugin')) {
    return;
}

if (!function_exists('SWPTS_plugin')) {
    function SWPTS_plugin() {
        return new SWPTS_Plugin;
    }
}
SWPTS_plugin();
