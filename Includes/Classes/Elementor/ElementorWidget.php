<?php

    namespace GSWPTS_PRO\Includes\Classes\Elementor;

    use GSWPTS_PRO\Includes\Classes\Elementor\Template\TemplateContent;

    if (!defined('ABSPATH')) {
        die("Can't access directly");
    }

    class ElementorWidget extends \Elementor\Widget_Base {

        /**
         * @param array   $data
         * @param $args
         */
        public function __construct(
            $data = [],
            $args = null
        ) {
            parent::__construct($data, $args);

            wp_enqueue_style('GSWPTS-elementor-table_css', GSWPTS_PRO_BASE_URL.'Assets/Public/Styles/elementor.min.css');

            global $gswpts;
            $gswpts->data_table_scripts();
        }

        public function get_name() {
            return 'sheets-to-wp-table-sync-live';
        }

        public function get_title() {
            return __('Sheets to Table', 'gswpts');
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
                    'tab'   => \Elementor\Controls_Manager::TAB_CONTENT
                ]
            );

            $this->add_control(
                'choose_table',
                [
                    'label'   => __('Choose Table', 'gswpts'),
                    'type'    => \Elementor\Controls_Manager::SELECT,
                    'default' => 'select',
                    'options' => $this->tables_info()
                ]
            );

            $this->end_controls_section();
        }

        /**
         * @return mixed
         */
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

        /**
         * @return null
         */
        protected function render() {
            $settings = $this->get_settings_for_display();
            if ($settings['choose_table'] == 'select') {
                return;
            }
            $table_id = intval($settings['choose_table']);
            echo do_shortcode('[gswpts_table id='.$table_id.']');
        }

        protected function _content_template() {
        $template = new TemplateContent()?>
<# if ( settings.choose_table !='select' ) { #>
    <?php $template->table_container()?>
    <?php $template->render_template_js()?>

    <# } else{ #>

        <?php $template->init_content()?>

        <# } #>

            <?php }
            }