<?php
namespace GSWPTS_PRO\Includes\Classes\Elementor;

use GSWPTS_PRO\Includes\Classes\Elementor\ElementorWidget;

if (!defined('ABSPATH')) {
    die("Can't access directly");
}

class ElementorBase {

    /**
     * Minimum Elementor Version
     *
     * @var string Minimum Elementor version required to run the plugin.
     * @since 1.0.0
     */
    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

    /**
     * Constructor
     *
     * @access public
     * @since 1.0.0
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
     * @access public
     * @since 1.0.0
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
     * @access public
     * @since 1.0.0
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
     * @access public
     * @since 1.0.0
     */
    public function init() {
        // Add Plugin actions
        add_action('elementor/widgets/widgets_registered', [$this, 'init_widgets']);
    }

    /**
     * Init Widgets
     *
     * Include widgets files and register them
     *
     * @access public
     * @since 1.0.0
     */
    public function init_widgets() {

        // Register widget
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new ElementorWidget());
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required Elementor version.
     *
     * @access public
     * @since 1.0.0
     */
    public function admin_notice_minimum_elementor_version() {

        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

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