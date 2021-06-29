<?php

    namespace GSWPTS_PRO\Includes\Classes\Elementor;

    use GSWPTS_PRO\Includes\Classes\Elementor\Includes\TableSettings;
    use GSWPTS_PRO\Includes\Classes\Elementor\Template\TemplateContent;

    if (!defined('ABSPATH')) {
        die("Can't access directly");
    }

    class ElementorWidget extends \Elementor\Widget_Base {

        /**
         * @param array   $data
         * @param $args
         */

        public $tableSettingsClass;

        /**
         * @param array   $data
         * @param $args
         */
        public function __construct(
            $data = [],
            $args = null
        ) {
            parent::__construct($data, $args);

            wp_enqueue_style('GSWPTS-elementor-table', GSWPTS_PRO_BASE_URL.'Assets/Public/Styles/elementor.min.css');

            global $gswpts;
            $gswpts->data_table_scripts();

            $this->tableSettingsClass = new TableSettings();

        }

        public function get_script_depends() {
            return ['script-handle', 'GSWPTS-buttons', 'GSWPTS-buttons-flash', 'GSWPTS-jszip', 'GSWPTS-pdfmake', 'GSWPTS-vfs_fonts', 'GSWPTS-buttons-html5', 'GSWPTS-buttons-print'];
        }

        public function get_name() {
            return 'sheets-to-wp-table-sync-live';
        }

        public function get_title() {
            return __('Sheets to Table', 'sheetstowptable-pro');
        }

        public function get_icon() {
            return 'fas fa-sync';
        }

        public function get_categories() {
            return ['basic'];
        }

        protected function _register_controls() {

            // Table choose section
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
                    'label'   => __('Choose Table', 'sheetstowptable-pro'),
                    'type'    => \Elementor\Controls_Manager::SELECT,
                    'default' => 'select',
                    'options' => $this->tables_info()
                ]
            );

            $this->end_controls_section();
            // End table choose section

            // wp_console_log($this->get_settings_for_display());
            // If table is choosen than show the table settings
            // if ($this->get_settings_for_display() != 'select') {
            //     // Display settings sectionffffffffffffffff
            //     $this->start_controls_section(
            //         'display_settings',
            //         [
            //             'label' => 'Display Settings',
            //             'tab'   => \Elementor\Controls_Manager::TAB_CONTENT
            //         ]
            //     );

            //     $displaySettings = $this->tableSettingsClass->displaySettings();

            //     if ($displaySettings) {
            //         foreach ($displaySettings as $key => $setting) {
            //             $this->add_control(
            //                 $key,
            //                 $setting
            //             );
            //         }
            //     }

            //     $this->end_controls_section();
            //     // End of display settings

            //     // Sort & Filter settings section
            //     $this->start_controls_section(
            //         'sort_and_filter',
            //         [
            //             'label' => 'Sort & Filter',
            //             'tab'   => \Elementor\Controls_Manager::TAB_CONTENT
            //         ]
            //     );

            //     $sortFilterSettings = $this->tableSettingsClass->sortFilterSettings();

            //     if ($sortFilterSettings) {
            //         foreach ($sortFilterSettings as $key => $setting) {
            //             $this->add_control(
            //                 $key,
            //                 $setting
            //             );
            //         }
            //     }

            //     $this->end_controls_section();
            //     // End of sort & filter settings
            // }
        }

        /**
         * @return array
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