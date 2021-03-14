<?php

namespace GSWPTS\Includes\Classes\Elementor;

use GSWPTS\Includes\Classes\Elementor\Plugins_Widget;

if (!defined('ABSPATH')) die("Can't access directly");

class Elementor_Init {


    /**
     * Minimum Elementor Version
     *
     * @since 1.0.0
     *
     * @var string Minimum Elementor version required to run the plugin.
     */
    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';


    /**
     * Constructor
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function __construct() {
        $this->initalize_elemenetor_functionality();
    }


    /**
     * On Plugins Loaded
     *
     * Checks if Elementor has loaded, and performs some compatibility checks.
     * If All checks pass, inits the plugin.
     *
     * Fired by `plugins_loaded` action hook.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function initalize_elemenetor_functionality() {

        if ($this->is_compatible()) {
            add_action('elementor/init', [$this, 'init']);
        }
    }

    /**
     * Compatibility Checks
     *
     * Checks if the installed version of Elementor meets the plugin's minimum requirement.
     * Checks if the installed PHP version meets the plugin's minimum requirement.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function is_compatible() {

        // Check if Elementor installed and activated
        if (!did_action('elementor/loaded')) {
            return false;
        }

        // Check for required Elementor version
        if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
            return false;
        }

        return true;
    }

    /**
     * Initialize the plugin
     *
     * Load the files required to run the plugin.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function init() {
        // Add Plugin actions
        add_action('elementor/widgets/widgets_registered', [$this, 'init_widgets']);

        add_action('elementor/editor/before_enqueue_scripts', [$this, 'include_elementor_assets']);
    }

    public function include_elementor_assets() {
        wp_enqueue_style('GSWPTS-elementor-css', GSWPTS_BASE_URL . 'Assets/Public/Styles/elementor.min.css');
        wp_enqueue_script('GSWPTS-elementor-js', GSWPTS_BASE_URL . 'Assets/Public/Scripts/Backend/elementor.min.js', ['elementor-editor', 'jquery'], GSWPTS_VERSION, true);
    }

    /**
     * Init Widgets
     *
     * Include widgets files and register them
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function init_widgets() {

        // Register widget
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Plugins_Widget());
    }


    /**
     * Admin notice
     *
     * Warning when the site doesn't have Elementor installed or activated.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function admin_notice_missing_main_plugin() {

        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            '"%1$s" requires "%2$s" to be installed and activated.',
            '<strong>Google Spreadsheet to WP Table Sync</strong>',
            '<strong>Elementor</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required Elementor version.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function admin_notice_minimum_elementor_version() {

        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            '"%1$s" requires "%2$s" version %3$s or greater.',
            '<strong>Google Spreadsheet to WP Table Sync</strong>',
            '<strong>Elementor</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }
}
