<?php

/**
 * Live Google Sheets to WordPress Tables
 *
 * @package           google-sheets-to-wp-tables
 * @author            WPPOOL
 * @copyright         2019 Your Name or Company Name
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Live Google Sheets to WordPress Tables
 * Plugin URI:       https://wordpress.org/plugins/google-sheets-to-wp-tables/
 * Description:      This is a WordPress plugin to synchronize google spreadsheet data into wordpress table.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.0.0
 * Author:             WPPOOL
 * Author URI:        https://wppool.dev/
 * Text Domain:       gswpts
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

/* if accessed directly exit from plugin */
if (!defined('ABSPATH')) {
    die('you cant access this plugin directly');
}

if (!defined('GSWPTS_VERSION')) {
    define('GSWPTS_VERSION', '1.0.0');
    // define('GSWPTS_VERSION', time());
}

if (!defined('GSWPTS_BASE_PATH')) {
    define('GSWPTS_BASE_PATH', plugin_dir_path(__FILE__));
}

if (!defined('GSWPTS_BASE_URL')) {
    define('GSWPTS_BASE_URL', plugin_dir_url(__FILE__));
}

if (!defined('PlUGIN_NAME')) {
    define('PlUGIN_NAME', 'Live Google Sheets to WordPress Tables');
}


if (!file_exists(GSWPTS_BASE_PATH . 'vendor/autoload.php')) {
    return;
}

require_once GSWPTS_BASE_PATH . 'vendor/autoload.php';

final class GSWPTS_Plugin {

    public function __construct() {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        if ($this->version_check() == 'version_low') return;
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
            new GSWPTS\Includes\Classes\DB_Tables;
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
        new GSWPTS\Includes\Plugin;

        if (get_option('gswpts_activation_redirect', false)) {
            delete_option('gswpts_activation_redirect');
            wp_redirect(admin_url('admin.php?page=gswpts-dashboard'));
        }
    }
    public static function add_action_links($links) {
        $mylinks = array(
            '<a href="' . admin_url('admin.php?page=gswpts-dashboard') . '">Dashboard</a>',
            '<a href="' . admin_url('admin.php?page=gswpts-create-tables') . '">Create Table</a>',
            '<a href="' . admin_url('admin.php?page=gswpts-general-settings') . '">General Settings</a>',
        );
        return array_merge($links, $mylinks);
    }
}

if (!class_exists('GSWPTS_Plugin')) {
    return;
}

if (!function_exists('GSWPTS_plugin')) {
    function GSWPTS_plugin() {
        return new GSWPTS_Plugin;
    }
}
GSWPTS_plugin();
