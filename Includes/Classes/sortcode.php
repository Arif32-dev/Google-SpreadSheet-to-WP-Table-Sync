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
        // $output .= '<div class="export_buttons" style="margin-bottom: 15px; display: flex;justify-content: flex-end;">
        //     <button id="gswpts_export_json" style="margin: 0 8px;" class="ui inverted yellow button">To JSON</button>
        //     <button id="gswpts_export_csv" style="margin: 0 8px;" class="ui inverted green button">To CSV</button>
        //     <button id="gswpts_export_pdf" style="margin: 0 0 0 8px;" class="ui inverted red button">To PDF</button>
        // </div>';
        $output .= $respond['table']['table'];
        return $output;
    }
}
