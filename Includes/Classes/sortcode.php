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

        $respond = $gswpts->get_table(false, null, $atts['id']);
        $output = '<h3>' . $gswpts->input_values($atts['id'])['table_name'] . '</h3>';
        $output .= $respond['table']['table'];

        return $output;
    }
}
