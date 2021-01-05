<?php

namespace GSWPTS\Includes\Classes\Elementor;

if (!defined('ABSPATH')) die("Can't access directly");


class Plugins_Widget extends \Elementor\Widget_Base {

    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);

        wp_register_style('GSWPTS-elementor-semantic-css', GSWPTS_BASE_URL . 'Assets/Public/Common/Semantic-UI-CSS-master/semantic.min.css');

        wp_register_style('GSWPTS-elementor-css', GSWPTS_BASE_URL . 'Assets/Public/Styles/elementor.min.css');

        wp_register_style('GSWPTS-elementor-table-css', GSWPTS_BASE_URL . 'Assets/Public/Styles/gutenberg.min.css');

        wp_register_script('GSWPTS-elementor-js', GSWPTS_BASE_URL . 'Assets/Public/Scripts/Backend/elementor.min.js', ['jquery'], GSWPTS_VERSION, true);

        wp_register_script('GSWPTS-elementor-table', GSWPTS_BASE_URL . 'Assets/Public/Scripts/Backend/elementor.min.js', ['jquery'], GSWPTS_VERSION, true);

        global $gswpts;

        $gswpts->data_table_scripts();

        wp_localize_script('GSWPTS-elementor-js', 'gswpts_elementor', [
            'admin_ajax' => admin_url('admin-ajax.php'),
            'table_details' => $gswpts->fetch_gswpts_tables()
        ]);
    }

    public function get_style_depends() {
        return ['GSWPTS-elementor-css', 'GSWPTS-elementor-semantic-css', 'GSWPTS-elementor-table-css'];
    }

    public function get_script_depends() {
        return ['GSWPTS-elementor-js'];
    }

    public function get_name() {
        return 'spreadsheet-to-wp-table-sync';
    }

    public function get_title() {
        return __('Spreadsheet to WP Table Sync', 'gswpts');
    }

    public function get_icon() {
        return 'fa fa-code';
    }

    public function get_categories() {
        return ['basic'];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'table_section',
            [
                'label' => 'Tables',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );


        $this->add_control(
            'choose_table',
            [
                'label' => __('Choose Table', 'gswpts'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'select',
                'options' => $this->tables_info()
            ]
        );

        $this->end_controls_section();



        $this->start_controls_section(
            'display_settings',
            [
                'label' => 'Display Settings',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label' => __('Show Title', 'gswpts'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'gswpts'),
                'label_off' => __('Hide', 'gswpts'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'default_rows_per_page',
            [
                'label' => __('Default rows per page', 'gswpts'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => $this->default_rows_per_page_selector()
            ]
        );

        $this->add_control(
            'show_info_block',
            [
                'label' => __('Show info block', 'gswpts'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'gswpts'),
                'label_off' => __('Hide', 'gswpts'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'responsive_table',
            [
                'label' => __('Resposive table', 'gswpts'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'gswpts'),
                'label_off' => __('Hide', 'gswpts'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_x_entries',
            [
                'label' => __('Show X entries', 'gswpts'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'gswpts'),
                'label_off' => __('Hide', 'gswpts'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'swap_filter_inputs',
            [
                'label' => __('Swap Filters', 'gswpts'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'gswpts'),
                'label_off' => __('Hide', 'gswpts'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'swap_bottom_options',
            [
                'label' => __('Swap Bottom Elements', 'gswpts'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'gswpts'),
                'label_off' => __('Hide', 'gswpts'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'sort_and_filter',
            [
                'label' => 'Sort & Filter',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );


        $this->add_control(
            'allow_sorting',
            [
                'label' => __('Allow Sorting', 'gswpts'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'gswpts'),
                'label_off' => __('Hide', 'gswpts'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'search_bar',
            [
                'label' => __('Search Bar', 'gswpts'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'gswpts'),
                'label_off' => __('Hide', 'gswpts'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    protected function tables_info() {
        $options = [
            'select' => 'Select a table'
        ];
        global $gswpts;
        $details = $gswpts->fetch_gswpts_tables();
        if ($details) {
            foreach ($details as $table) {
                $options[$table->id] = $table->table_name;
            }
        }
        return $options;
    }

    protected function default_rows_per_page_selector() {
        $options = [
            '1' => '1',
            '5' => '5',
            '10' => '10',
            '15' => '15',
            '25' => '25',
            '50' => '50',
            '100' => '100',
            'all' => 'All'
        ];

        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        // if (do_shortcode('[gswpts_table id=12]')) {
        // echo do_shortcode('[gswpts_table id=12]'); 
?>
        <!-- <script>
                gswpts_elementor_global.set_elementor_table()
            </script> -->

        <?php // } 
        ?>

    <?php
    }
    protected function _content_template() {

    ?>
        <div class="gswpts_create_table_container">
            <div class="block_initializer">
                <button id="create_button" class="positive ui button">
                    Create New
                </button>
                <button id="choose_table" class="ui violet button" type="button">
                    Choose Table
                </button>
            </div>
        </div>
<?php
    }
}
