<?php

namespace GSWPTS\Includes\Classes;

class Sortcode {
    public function __construct() {
        add_shortcode('gswpts_table', [$this, 'gswpts_sortcodes']);
    }
    public function gswpts_sortcodes($atts) {
        // $return_value = $atts['id'] . 'hello';
        // return $return_value;
    }
}
