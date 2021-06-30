<?php

namespace GSWPTS\Includes\Classes;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheetstowptable'));

class ClassSortcode {
    public function __construct() {
        if (get_option('asynchronous_loading') == 'on') {
            add_shortcode('gswpts_table', [$this, 'gswpts_sortcodes_asynchronous']);
        } else {
            add_shortcode('gswpts_table', [$this, 'gswpts_sortcodes']);
        }
    }

    /**
     * @param  $atts
     * @return HTML
     */
    public function gswpts_sortcodes_asynchronous($atts) {

        if (defined('ELEMENTOR_VERSION') && \Elementor\Plugin::$instance->editor->is_edit_mode()) {
            return $this->gswpts_sortcodes($atts);
        } else {
            $output = "<h5><b>".__('No table data found', 'sheetstowptable')."</b></h5> <br>";
            if (!is_int(intval($atts['id']))) {
                return $output;
            }

            $output = '<div class="gswpts_tables_container" id="'.esc_attr($atts['id']).'">';
            $output .= '<h3></h3>';

            $output .= '<div class="gswpts_tables_content">';

            $output .= '
                 <div class="ui segment gswpts_table_loader">
                            <div class="ui active inverted dimmer">
                                <div class="ui large text loader">'.__('Loading', 'sheetstowptable').'</div>
                            </div>
                            <p></p>
                            <p></p>
                            <p></p>
                    </div>
            ';

            $output .= '</div>';
            $output .= '</div>';
            $output .= '<br><br>';

            return $output;
        }
    }

    /**
     * @param  $atts
     * @return HTML
     */
    public function gswpts_sortcodes($atts) {
        $output = "<h5><b>".__('No table data found', 'sheetstowptable')."</b></h5><br>";
        global $gswpts;
        if (!is_int(intval($atts['id']))) {
            return $output;
        }

        $respond = $gswpts->get_table(false, null, $atts['id']);

        if ($respond === false) {
            return $output;
        }

        $table_name = $gswpts->input_values($atts['id'])['table_name'];
        $table = $respond['table']['table'];

        $table_settings = $respond['table_settings'];
        $responsive = isset($table_settings['responsive_table']) && $table_settings['responsive_table'] == 'true' ? 'gswpts_responsive' : null;
        $tableStyle = isset($table_settings['table_style']) && $table_settings['table_style'] ? 'gswpts_'.$table_settings['table_style'].'' : null;

        $output = '<div
                                    class="gswpts_tables_container '.esc_attr($responsive).' '.esc_attr($tableStyle).'" id="'.esc_attr($atts['id']).'"
                                    data-table_name="'.esc_attr($respond['table_name']).'"
                                    data-table_settings='.json_encode($table_settings).'>';

        $output .= $table_name;

        $output .= '<div class="gswpts_tables_content">';

        $output .= $table;

        $output .= '</div>';
        $output .= '</div>';
        $output .= '<br><br>';

        return $output;
    }
}