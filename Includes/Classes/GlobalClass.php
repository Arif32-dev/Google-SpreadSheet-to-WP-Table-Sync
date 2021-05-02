<?php

namespace GSWPTS\Includes\Classes;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheetstowptable'));

class GlobalClass {
    public function data_table_styles() {
        wp_enqueue_style('GSWPTS-semanticui-css', GSWPTS_BASE_URL . 'Assets/Public/Common/Semantic-UI-CSS-master/semantic.min.css', [], GSWPTS_VERSION, 'all');
        wp_enqueue_style('GSWPTS-dataTable-semanticui-css', GSWPTS_BASE_URL . 'Assets/Public/Common/DataTables/Tables/css/dataTables.semanticui.min.css', [], GSWPTS_VERSION, 'all');
    }
    public function data_table_scripts() {
        wp_enqueue_script('GSWPTS-jquery-dataTable-js', GSWPTS_BASE_URL . 'Assets/Public/Common/DataTables/Tables/js/jquery.dataTables.min.js', ['jquery'], GSWPTS_VERSION, true);
        wp_enqueue_script('GSWPTS-dataTable-semanticui-js', GSWPTS_BASE_URL . 'Assets/Public/Common/DataTables/Tables/js/dataTables.semanticui.min.js', ['jquery'], GSWPTS_VERSION, true);
    }
    public function nonce_field($nonce_action, $nonce_name) {
        wp_nonce_field($nonce_action, $nonce_name);
    }

    public function semantic_files() {
        wp_enqueue_style('GSWPTS-semanticui-css', GSWPTS_BASE_URL . 'Assets/Public/Common/Semantic-UI-CSS-master/semantic.min.css', [], GSWPTS_VERSION, 'all');
        wp_enqueue_script('GSWPTS-semantic-js', GSWPTS_BASE_URL . 'Assets/Public/Common/Semantic-UI-CSS-master/semantic.min.js', ['jquery'], GSWPTS_VERSION, false);
    }

    public function frontend_tables_assets() {
        wp_enqueue_script('GSWPTS-frontend-table', GSWPTS_BASE_URL . 'Assets/Public/Common/DataTables/Tables/js/jquery.dataTables.min.js', ['jquery'], GSWPTS_VERSION, false);
        wp_enqueue_script('GSWPTS-frontend-semantic', GSWPTS_BASE_URL . 'Assets/Public/Common/DataTables/Tables/js/dataTables.semanticui.min.js', ['jquery'], GSWPTS_VERSION, false);
    }

    public function get_sheet_id(string $string) {
        $pattern = "/\//";
        $components = preg_split($pattern, $string);
        if ($components) {
            if (array_key_exists(5, $components)) {
                return $components[5];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function get_json_data($url) {
        $sheet_id = $this->get_sheet_id($url);
        if (!$sheet_id) {
            return;
        }
        $sheet_url = "https://spreadsheets.google.com/feeds/cells/" . $sheet_id . "/1/public/full?alt=json";
       
        $res = file_get_contents($sheet_url);
        return json_decode($res, true)['feed'];
    }
    public function get_csv_data($url) {
        $sheet_id = $this->get_sheet_id($url);
        if (!$sheet_id) {
            return;
        }
        return fopen("https://docs.google.com/spreadsheets/d/" . $sheet_id . "/export?format=csv&id=" . $sheet_id . "", "r");
    }
    public function get_table($ajax_req = false, $sheet_response = null, $table_id = null) {
        if ($ajax_req && $sheet_response) {
            return $this->the_table($sheet_response);
        }
        if (isset($table_id) && $table_id !== '') {
            $db_result = $this->fetch_db_by_id($table_id);
            if ($db_result) {

                $json_response = $this->get_json_data($db_result[0]->source_url);

                if (!$json_response) {
                    return false;
                }

                $sheet_response = $this->get_csv_data($db_result[0]->source_url);

                if (count(fgetcsv($sheet_response)) < 2) {
                    return false;
                }

                $sheet_response = $this->get_csv_data($db_result[0]->source_url);

                $table = $this->the_table($sheet_response);
                $output = [
                    'id' => $table_id,
                    'table' => $table,
                    'table_settings' => unserialize($db_result[0]->table_settings),
                    'table_name' => $db_result[0]->table_name,
                    'sheet_name' => $json_response['title']['$t'],
                    'author_info' => $json_response['author'],
                    'sheet_total_result' => $json_response['openSearch$totalResults']['$t'],
                    'total_rows' => $table['count'],
                ];
                return $output;
            }
        }
        return false;
    }
    public function the_table($sheet_response) {

        $table = '<table id="create_tables" class="ui celled display table gswpts_tables" style="width:100%">';
        $i = 0;
        while (!feof($sheet_response)) {

            if ($i == 0) {
                $table .= '<thead><tr>';
                foreach (fgetcsv($sheet_response) as $cell_value) {

                    if ($cell_value) {
                        $table .= '<th>' . esc_html__($cell_value, 'sheetstowptable') . '</th>';
                    } else {
                        $table .= '<th>&nbsp;</th>';
                    }
                }
                $table .= '</tr></thead>';
            } else {
                if ($i == 16) {
                    break;
                }
                $table .= '<tr>';
                foreach (fgetcsv($sheet_response) as $cell_value) {
                    if ($cell_value) {
                        $table .= '<td>' . esc_html__($cell_value, 'sheetstowptable') . '</td>';
                    } else {
                        $table .= '<td>&nbsp;</td>';
                    }
                }
                $table .= '</tr>';
            }
            $i++;
        }
        fclose($sheet_response);

        $table .= '</table>';

        $response = [
            'table' => $table,
            'count' => $i
        ];
        return $response;
    }

    public function fetch_db_by_id($id) {
        global $wpdb;
        $table = $wpdb->prefix . 'gswpts_tables';

        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $table . " WHERE id=%d", sanitize_text_field($id)));
        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
    }
    public function fetch_gswpts_tables() {
        global $wpdb;
        $table = $wpdb->prefix . 'gswpts_tables';
        $result = $wpdb->get_results("SELECT * FROM " . $table . "");
        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
    }

    public function sheet_details($data) {
        $sheet_details = false;
        if (isset($_GET['id']) && !empty($_GET['id'])) {

            $sheet_details = '
                    <div id="sheet_ui_card" class="ui card" style="width: 60%; min-width: 400px;">
                            <div class="content">
                                <div class="row">
                                    <div class="col-12 d-flex align-items-center justify-content-start mb-3">
                                        <h6 class="m-0">Sheet Name: </h6>
                                        <h6 class="m-0 ml-2">' . esc_html__($data['sheet_name'], 'sheetstowptable') . '</h6>
                                    </div>
                                    <div class="col-12 d-flex align-items-center justify-content-start mb-3">
                                        <h6 class="m-0">Total Rows: </h6>
                                        <h6 class="m-0 ml-2">' . esc_html__($data['total_rows'], 'sheetstowptable') . '</h6>
                                    </div>
                                    <div class="col-12 d-flex align-items-center justify-content-start mb-3">
                                        <h6 class="m-0">Total Result: </h6>
                                        <h6 class="m-0 ml-2">' . esc_html__($data['sheet_total_result'], 'sheetstowptable') . '</h6>
                                    </div>
                                    <div class="col-12 d-flex align-items-center justify-content-start">
                                        <h6 class="m-0">Author Mail: </h6>
                                        <h6 class="m-0 ml-2">' . esc_html__($data['author_info'][0]['email']['$t'], 'sheetstowptable') . '</h6>
                                    </div>
                                    <div id="shortcode_container" class="col-12 d-flex mt-3 align-items-center justify-content-start">
                                        <h6 class="m-0">Table Shortcode: </h6>
                                        <h6 class="m-0 ml-2">
                                            <div class="ui action input">
                                                <input id="sortcode_value" type="text" class="copyInput" value="[gswpts_table id=' . esc_html__(esc_attr($data['id']), 'sheetstowptable') . ']">
                                                <button id="sortcode_copy" type="button" name="copyToken" value="copy" class="copyToken ui right icon button">
                                                    <i class="clone icon"></i>
                                                </button>
                                            </div>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                    </div>
            ';
            return $sheet_details;
        }
    }
    public function input_values(int $id) {
        $db_result = $this->fetch_db_by_id($id);
        if ($db_result) {
            $input_values = [
                'source_url' => $db_result[0]->source_url,
                'table_name' => $this->output_table_by_condition($db_result)
            ];
            return $input_values;
        } else {
            return false;
        }
        return false;
    }
    public function output_table_by_condition($db_result) {
        $table_settings = unserialize($db_result[0]->table_settings);
        if ($table_settings['table_title'] == 'true') {
            return '<h3>' . esc_html__($db_result[0]->table_name, 'sheetstowptable') . '</h3>';
        } else {
            return null;
        }
    }

    public function latest_table_details() {
        $db_result = $this->fetch_gswpts_tables();
        $last_table_id =  $this->get_last_table_id($db_result);
        $latest_table_info = [
            'total_table_count' => $db_result != false ? count($db_result) : 0,
            'last_table_name' => $db_result != false ?  $db_result[(count($db_result) - 1)]->table_name : null,
            'last_table_id' => $last_table_id
        ];
        return $latest_table_info;
    }
    public function get_last_table_id($db_result) {
        $last_table = null;
        if ($db_result != false) {
            $last_table = $db_result[(count($db_result) - 1)]->id;
        }
        return $last_table;
    }
    public function get_first_table_details() {
        $table_details = null;
        $db_result = $this->fetch_gswpts_tables();
        if ($db_result) {
            $table_details = [
                'id' => $db_result[0]->id,
                'table_name' => $db_result[0]->table_name,
                'source_url' => $db_result[0]->source_url,
                'source_type' => $db_result[0]->source_type,
                'table_settings' => $db_result[0]->table_settings,
            ];
        }
        return $table_details;
    }
}
