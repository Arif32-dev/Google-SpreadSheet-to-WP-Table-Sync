<?php

namespace GSWPTS\Includes\Classes\Controller\Ajax_Parts;

class Table_Fetch {

    private static $output = [];

    public function table_fetch() {
        if ($_POST['action'] != 'gswpts_table_fetch') {
            self::$output['response_type'] = 'invalid_action';
            self::$output['output'] = '<b>Action is invalid</b>';
            echo json_encode(self::$output);
            wp_die();
        }
        if (empty($_POST['page_slug']) && $_POST['page_slug'] == null && $_POST['page_slug'] == "") {
            self::$output['response_type'] = 'invalid_request';
            self::$output['output'] = '<b>Request is invalid</b>';
            echo json_encode(self::$output);
            wp_die();
        }

        echo json_encode(self::table_html());

        wp_die();
    }
    public static function table_html() {
        global $gswpts;
        $table = '<table id="manage_tables" class="ui celled table" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        <input type="checkbox" name="manage_tables_checkbox" class="manage_tables_checkbox">
                                    </th>
                                    <th>Table ID</th>
                                    <th>Table Name</th>
                                    <th>Type</th>
                                    <th>Shortcode</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        <tbody>
        ';

        $fetched_tables = $gswpts->fetch_gswpts_tables();
        if ($fetched_tables) {
            foreach ($fetched_tables as $table_data) {

                $table .= '<tr>
                                    <td class="text-center">
                                        <input type="checkbox" value="' . $table_data->id . '" name="manage_tables_checkbox" class="manage_tables_checkbox">
                                    </td>
                                    <td>' . $table_data->id . '</td>
                                    <td><a href="' . admin_url('admin.php?page=gswpts-create-tables&id=' . $table_data->id . '') . '">' . $table_data->table_name . '</a></td>
                                    <td>SpreadSheet</td>
                                    <td class="d-flex justify-content-around align-items-center">
                                        [gswpts_table id=' . $table_data->id . ']
                                        <button type="button" name="copyToken" value="copy" class="copyToken ui right icon button gswpts_sortcode_copy">
                                            <i class="clone icon"></i>
                                        </button>
                                    </td>
                                    <td class="text-center"><button id="' . $table_data->id . '" class="negative ui button">Delete</button></td>
                                </tr>';
            }
        }

        $table .= '
                        </tbody>
                </table>
        ';
        self::$output['response_type'] = 'success';
        self::$output['output'] = "" . $table . "";
        return self::$output;
    }
}
