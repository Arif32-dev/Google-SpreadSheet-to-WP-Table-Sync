<?php

namespace GSWPTS\Includes\Classes;

class Global_Class {
    public function data_table_styles() {
        wp_enqueue_style('GSWPTS-semanticui-css', GSWPTS_BASE_URL . 'Assets/Public/Common/Semantic-UI-CSS-master/semantic.min.css', [], GSWPTS_VERSION, 'all');
        wp_enqueue_style('GSWPTS-dataTable-semanticui-css', GSWPTS_BASE_URL . 'Assets/Public/Common/DataTables/DataTables-1.10.22/css/dataTables.semanticui.min.css', [], GSWPTS_VERSION, 'all');
    }
    public function data_table_scripts() {
        wp_enqueue_script('GSWPTS-jquery-dataTable-js', GSWPTS_BASE_URL . 'Assets/Public/Common/DataTables/DataTables-1.10.22/js/jquery.dataTables.min.js', ['jquery'], GSWPTS_VERSION, true);
        wp_enqueue_script('GSWPTS-dataTable-semanticui-js', GSWPTS_BASE_URL . 'Assets/Public/Common/DataTables/DataTables-1.10.22/js/dataTables.semanticui.min.js', ['jquery'], GSWPTS_VERSION, true);
        wp_enqueue_script('GSWPTS-semantic-js', GSWPTS_BASE_URL . 'Assets/Public/Common/Semantic-UI-CSS-master/semantic.min.js', [], GSWPTS_VERSION, true);
    }
    public function nonce_field($nonce_action, $nonce_name) {
        wp_nonce_field($nonce_action, $nonce_name);
    }
    public function bootstrap_files() {
        wp_enqueue_style('GSWPTS-bootstap-css', GSWPTS_BASE_URL . 'Assets/Public/Common/bootstrap-dist/css/bootstrap.min.css', [], GSWPTS_VERSION, 'all');
        wp_enqueue_script('GSWPTS-bootstap-js', GSWPTS_BASE_URL . 'Assets/Public/Common/bootstrap-dist/js/bootstrap.min.js', ['jquery'], GSWPTS_VERSION, true);
    }

    public function alert_files() {
?>
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo GSWPTS_BASE_URL . 'Assets/Public/Backend/Package/alert.min.css' ?>">
        <script type="text/javascript" src="<?php echo GSWPTS_BASE_URL . 'Assets/Public/Backend/Package/alert.min.js' ?>"></script>
<?php
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
        $client = new \GuzzleHttp\Client();
        $res = $client->request(
            'GET',
            $sheet_url
        );
        return json_decode($res->getBody(), true, 99)['feed'];
    }
    public function get_csv_data($url) {
        $sheet_id = $this->get_sheet_id($url);
        if (!$sheet_id) {
            return;
        }
        return  fopen("https://docs.google.com/spreadsheets/d/" . $sheet_id . "/export?format=csv&id=" . $sheet_id . "", "r");
    }
    public function get_table($ajax_req = false, $sheet_response = null) {
        if ($ajax_req && $sheet_response) {
            return $this->the_table($sheet_response);
        }
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $db_result = $this->fetch_db_by_id($_GET['id']);
            if ($db_result) {
                $sheet_response = $this->get_csv_data($db_result[0]->sheet_url);
                $json_response = $this->get_json_data($db_result[0]->sheet_url);
                $table = $this->the_table($sheet_response);
                $output = [
                    'id' => $_GET['id'],
                    'table' => $table,
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
        $table = '<table id="create_tables" class="ui celled table">';
        $i = 0;
        while (!feof($sheet_response)) {
            if ($i == 0) {
                $table .= '<thead><tr>';
                foreach (fgetcsv($sheet_response) as $cell_value) {
                    if ($cell_value) {
                        $table .= '<th>' . $cell_value . '</th>';
                    } else {
                        $table .= '<th>&nbsp;</th>';
                    }
                }
                $table .= '</tr></thead>';
            } else {
                $table .= '<tr>';
                foreach (fgetcsv($sheet_response) as $cell_value) {
                    if ($cell_value) {
                        $table .= '<td>' . $cell_value . '</td>';
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
        $table = $wpdb->prefix . 'gswpts_spreadsheet';

        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $table . " WHERE id=%d", sanitize_text_field($id)));
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
                                        <h4 class="m-0">Sheet Name: </h4>
                                        <h5 class="m-0 ml-2">' . $data['sheet_name'] . '</h5>
                                    </div>
                                    <div class="col-12 d-flex align-items-center justify-content-start mb-3">
                                        <h4 class="m-0">Total Rows: </h4>
                                        <h5 class="m-0 ml-2">' . $data['total_rows'] . '</h5>
                                    </div>
                                    <div class="col-12 d-flex align-items-center justify-content-start mb-3">
                                        <h4 class="m-0">Total Result: </h4>
                                        <h5 class="m-0 ml-2">' . $data['sheet_total_result'] . '</h5>
                                    </div>
                                    <div class="col-12 d-flex align-items-center justify-content-start">
                                        <h4 class="m-0">Author Mail: </h4>
                                        <h5 class="m-0 ml-2">' . $data['author_info'][0]['email']['$t'] . '</h5>
                                    </div>
                                    <div id="shortcode_container" class="col-12 d-flex mt-3 align-items-center justify-content-start">
                                        <h4 class="m-0">Table Shortcode: </h4>
                                        <h5 class="m-0 ml-2">
                                            <div class="ui action input">
                                                <input id="sortcode_value" type="text" class="copyInput" value="[gswpts_table id=' . $data['id'] . ']">
                                                <button id="sortcode_copy" type="button" name="copyToken" value="copy" class="copyToken ui right icon button">
                                                    <i class="clone icon"></i>
                                                </button>
                                            </div>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                    </div>
            ';
            return $sheet_details;
        }
    }
    public function input_values() {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $db_result = $this->fetch_db_by_id($_GET['id']);
            if ($db_result) {
                $input_values = [
                    'sheet_url' => $db_result[0]->sheet_url,
                    'table_name' => $db_result[0]->table_name
                ];
                return $input_values;
            } else {
                return false;
            }
        }
        return false;
    }
}
