<?php

namespace GSWPTS\Includes\Classes;

class Sortcode {
    public function __construct() {
        if (get_option('asynchronous_loading') == 'on') {
            add_shortcode('gswpts_table', [$this, 'gswpts_sortcodes_asynchronous']);
        } else {
            add_shortcode('gswpts_table', [$this, 'gswpts_sortcodes']);
        }
    }
    public function gswpts_sortcodes_asynchronous($atts) {

        if (defined('ELEMENTOR_VERSION') && \Elementor\Plugin::$instance->editor->is_edit_mode()) {
            return $this->gswpts_sortcodes($atts);
        } else {
            $output = "<h5><b>No table data found</b></h5> <br>";
            if (!is_int(intval($atts['id']))) {
                return $output;
            }


            $output = '<div class="gswpts_tables_container" id="' . $atts['id'] . '">';
            $output .= '<h3></h3>';

            $output .= '<div class="gswpts_tables_content">';

            $output .= '
                 <div class="ui segment gswpts_table_loader">
                            <div class="ui active inverted dimmer">
                                <div class="ui large text loader">Loading</div>
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

    public function gswpts_sortcodes($atts) {
        $output = "<h5><b>No table data found</b></h5><br>";
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
        $responsive = $table_settings['responsive_table'] == 'true' ? 'gswpts_resposive' : null;

        $output = '<div 
                                    class="gswpts_tables_container' . $responsive . '" id="' . $atts['id'] . '"
                                    data-table_name="' . $respond['table_name'] . '"
                                    data-table_settings=' . json_encode($table_settings) . '>';

        $output .= $table_name;

        $output .= '<div class="gswpts_tables_content">';

        $output .= $table;

        $output .= '</div>';
        $output .= '</div>';
        $output .= '<br><br>';

        return $output;
    }
}
