<?php

namespace GSWPTS\Includes\Classes\Controller\Ajax_Parts;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheets-to-wp-table-live-sync'));

class TableFetch {

    private static $output = [];

    public function table_fetch() {
        if (sanitize_text_field($_POST['action']) != 'gswpts_table_fetch') {
            self::$output['response_type'] = esc_html('invalid_action');
            self::$output['output'] = '<b>' . esc_html('Action is invalid') . '</b>';
            echo json_encode(self::$output);
            wp_die();
        }
        $page_slug = sanitize_text_field($_POST['page_slug']);
        if (empty($page_slug) && $page_slug == null && $page_slug == "") {
            self::$output['response_type'] = esc_html('invalid_request');
            self::$output['output'] = '<b>' . esc_html('Request is invalid') . '</b>';
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
                                    <th class="text-center">' . esc_html('Table ID') . '</th>
                                    <th class="text-center">' . esc_html('Table Name') . '</th>
                                    <th class="text-center">' . esc_html('Type') . '</th>
                                    <th class="text-center">' . esc_html('Shortcode') . '</th>
                                    <th class="text-center">' . esc_html('Update') . '</th>
                                    <th class="text-center">' . esc_html('Delete') . '</th>
                                </tr>
                            </thead>
                        <tbody>
        ';

        $fetched_tables = $gswpts->fetch_gswpts_tables();
        if ($fetched_tables) {
            foreach ($fetched_tables as $table_data) {

                $table .= '<tr>
                                    <td class="text-center">
                                        <input type="checkbox" value="' . esc_attr($table_data->id) . '" name="manage_tables_checkbox" class="manage_tables_checkbox">
                                    </td>
                                    <td class="text-center">' . esc_attr($table_data->id) . '</td>
                                    <td class="text-center">
                                        <div style="height: 100%;" class="d-flex align-content-center">
                                            <a
                                            style="width: 100%; height: 35px; padding-top: 8px; margin-right: 5px;     white-space: nowrap;" 
                                            class="table_name" 
                                            href="' . esc_url(admin_url('admin.php?page=gswpts-create-tables&id=' . esc_attr($table_data->id) . '')) . '">
                                            ' . esc_html($table_data->table_name) . '
                                            </a>
                                            <button type="button" value="edit" class="copyToken ui right icon button gswpts_edit_table ml-1">
                                                <i class="edit icon"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        ' . esc_html(self::table_type($table_data->source_type)) . '
                                    </td>
                                    <td class="text-center" style="display: flex; justify-content: space-around; align-items: center;">
                                            <input type="hidden" class="table_copy_sortcode" value="[gswpts_table id=' . esc_attr($table_data->id) . ']">
                                            <span style="display: flex; align-items: center; white-space: nowrap; margin-right: 5px">[gswpts_table id=' . esc_attr($table_data->id) . ']</span>
                                            <button type="button" name="copyToken" value="copy" class="copyToken ui right icon button gswpts_sortcode_copy">
                                                <i class="clone icon"></i>
                                            </button>
                                    </td>
                                    <td class="text-center"><button id="' . esc_attr($table_data->id) . '"  class="ui yellow button gswpts_table_update_btn">' . esc_html('Update') . '</button></td>
                                    <td class="text-center"><button id="' . esc_attr($table_data->id) . '" class="negative ui button gswpts_table_delete_btn">' . esc_html('Delete') . '</button></td>
                                </tr>';
            }
        }

        $table .= '
                        </tbody>
                </table>
        ';
        self::$output['response_type'] = esc_html('success');
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
