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
        return __('Spreadsheet Sync', 'gswpts');
    }

    public function get_icon() {
        return 'fas fa-sync';
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


    protected function render() {
        $settings = $this->get_settings_for_display();
        if ($settings['choose_table'] == 'select') {
            return;
        }
        $table_id = intval($settings['choose_table']);
        if (do_shortcode('[gswpts_table id=' . $table_id . ']')) {
            echo do_shortcode('[gswpts_table id=' . $table_id . ']');
?>
            <script>
                let main_table_container = $('#<?php echo $table_id ?>');
                let table_settings = JSON.parse($(main_table_container).attr('data-table_settings'));

                let table_name = $(main_table_container).attr('data-table_name')
                let dom = `<"filtering_input"${table_settings.show_x_entries == 'true' ? 'l' : ''}${table_settings.search_bar == 'true' ? 'f' : ''}>rt<"bottom_options"${table_settings.show_info_block == 'true' ? 'i' : ''}p>`;
                gswpts_elementor_global.set_elementor_table($('#<?php echo $table_id ?> #create_tables'), table_settings, dom);

                let swap_filter = table_settings.swap_filter_inputs == 'true' ? true : false;
                let swap_bottom = table_settings.swap_bottom_options == 'true' ? true : false;

                gswpts_elementor_global.swap_input_filter(<?php echo $table_id ?>, swap_filter)
                gswpts_elementor_global.swap_bottom_options(<?php echo $table_id ?>, swap_bottom)
            </script>

        <?php }
        ?>

<?php
    }
}
