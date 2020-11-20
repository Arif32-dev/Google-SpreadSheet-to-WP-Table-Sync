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
}
