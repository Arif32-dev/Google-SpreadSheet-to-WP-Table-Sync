<?php

namespace GSWPTS\Includes\Classes;

class Sortcode {
    public function __construct() {
        add_shortcode('gswpts_table', [$this, 'gswpts_sortcodes']);
    }
    public function gswpts_sortcodes($atts) {
        $output = "<b>No table data found</b>";
        global $gswpts;
        if (!is_int(intval($atts['id']))) {
            return $output;
        }

        $output = '<div class="gswpts_tables_container" id="' . $atts['id'] . '">';
        $output .= '<h3></h3>';

        $output .= '<div class="gswpts_tables">';

        $output .= '
             <div class="ui segment gswpts_table_loader">
                        <div class="ui active inverted dimmer">
                            <div class="ui large text loader"></div>
                        </div>
                        <p></p>
                        <p></p>
                        <p></p>
                </div>
        ';

        $output .= '</div>';
        $output .= '</div>';


        return $output;
    }
}
