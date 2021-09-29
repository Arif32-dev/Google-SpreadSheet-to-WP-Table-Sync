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
            $output = "<h5><b>" . __('Table maybe deleted or can\'t be loaded.', 'sheetstowptable') . "</b></h5> <br>";
            if (!is_int(intval($atts['id']))) {
                return $output;
            }

            $output = '<div class="gswpts_tables_container gswpts_table_' . esc_attr($atts['id']) . '" id="' . esc_attr($atts['id']) . '">';
            $output .= '<h3></h3>';

            $output .= '<div class="gswpts_tables_content">';

            $output .= '
                 <div class="ui segment gswpts_table_loader">
                            <div class="ui active inverted dimmer">
                                <div class="ui large text loader">' . __('Loading', 'sheetstowptable') . '</div>
                            </div>
                            <p></p>
                            <p></p>
                            <p></p>
                    </div>
            ';

            $output .= '</div>';
            $output .= $this->editTableLink(intval($atts['id']));
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
        $output = "<h5><b>" . __('Table maybe deleted or can\'t be loaded.', 'sheetstowptable') . "</b></h5><br>";
        global $gswpts;
        if (!is_int(intval($atts['id']))) {
            return $output;
        }

        $dbResult = $gswpts->fetch_db_by_id($atts['id']);

        if (!$dbResult) {
            return $output;
        }

        $table_settings = unserialize($dbResult[0]->table_settings);
        $hiddenRows = $table_settings['hide_rows'] ? $table_settings['hide_rows'] : [];

        $respond = $gswpts->get_table(false, null, $atts['id'], $hiddenRows, $dbResult);

        if ($respond === false) {
            return $output;
        }

        $table_name = $gswpts->input_values($atts['id'])['table_name'];
        $table = $respond['table']['table'];

        $responsive = isset($table_settings['responsive_style']) && $table_settings['responsive_style'] ? $table_settings['responsive_style'] : null;
        $tableStyle = isset($table_settings['table_style']) && $table_settings['table_style'] ? 'gswpts_' . $table_settings['table_style'] . '' : null;

        $output = '<div class="gswpts_tables_container gswpts_table_' . esc_attr($atts['id']) . ' ' . esc_attr($responsive) . ' ' . esc_attr($tableStyle) . '" id="' . esc_attr($atts['id']) . '"
                        data-table_name="' . esc_attr($respond['table_name']) . '"
                        data-table_settings=' . json_encode($table_settings) . '>';

        $output .= $table_name;

        $output .= '<div class="gswpts_tables_content">';

        $output .= $table;

        $output .= '</div>';
        $output .= '<br/>';
        $output .= $this->editTableLink(intval($atts['id']));
        $output .= '</div>';
        $output .= '<br><br>';

        return $output;
    }

    /**
     * @param int $tableID
     */
    public function editTableLink(int $tableID) {

        if (current_user_can('manage_options')) {
            return '<a style="position: relative; top: 20px;" href="' . esc_url(admin_url('admin.php?page=gswpts-dashboard&subpage=create-table&id=' . $tableID . '')) . '" target="_blank">Customize Table</a>';
        } else {
            return null;
        }
    }
}