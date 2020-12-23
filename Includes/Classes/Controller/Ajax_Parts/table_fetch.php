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
        $table = '<table id="manage_tables" class="ui celled table">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        <input data-show="false" type="checkbox" name="manage_tables_main_checkbox" id="manage_tables_checkbox">
                                    </th>
                                    <th class="text-center">Table ID</th>
                                    <th class="text-center">Table Name</th>
                                    <th class="text-center">Type</th>
                                    <th class="text-center">Shortcode</th>
                                    <th class="text-center">Update</th>
                                    <th class="text-center">Delete</th>
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
                                    <td class="text-center">' . $table_data->id . '</td>
                                    <td class="text-center d-flex justify-content-around align-items-center">
                                        <a
    
                                            style="width: 100%; height: 35px; padding-top: 15px; margin-right: 5px;" 
                                            class="table_name" 
                                            href="' . admin_url('admin.php?page=gswpts-create-tables&id=' . $table_data->id . '') . '">
                                            ' . $table_data->table_name . '
                                        </a>
                                        <button type="button" value="edit" class="copyToken ui right icon button gswpts_edit_table ml-1">
                                            <i class="edit icon"></i>
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        ' . self::table_type($table_data->source_type) . '
                                    </td>
                                    <td class="d-flex justify-content-around align-items-center">
                                        <input type="hidden" class="table_copy_sortcode" value="[gswpts_table id=' . $table_data->id . ']">
                                        <span>[gswpts_table id=' . $table_data->id . ']</span>
                                        <button type="button" name="copyToken" value="copy" class="copyToken ui right icon button gswpts_sortcode_copy">
                                            <i class="clone icon"></i>
                                        </button>
                                    </td>
                                    <td class="text-center"><button id="' . $table_data->id . '"  class="ui yellow button gswpts_table_update_btn">Update</button></td>
                                    <td class="text-center"><button id="' . $table_data->id . '" class="negative ui button gswpts_table_delete_btn">Delete</button></td>
                                </tr>';
            }
        }

        $table .= '
                        </tbody>
                </table>
        ';
        self::$output['response_type'] = 'success';
        if (!$fetched_tables) {
            self::$output['no_data'] = 'true';
        }
        self::$output['output'] = "" . $table . "";
        return self::$output;
    }
    public static function table_type($type) {
        if ($type == 'spreadsheet') {
            return 'Spreadsheet';
        } elseif ($type == 'csv') {
            return 'CSV';
        } else {
            return 'No type';
        }
    }
}
