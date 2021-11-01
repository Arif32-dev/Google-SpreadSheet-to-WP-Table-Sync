<?php

namespace GSWPTS\Includes\Classes\Controller\Ajax_Parts;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheetstowptable'));

class TableFetch {

    /**
     * @var array
     */
    private static $output = [];

    public function table_fetch() {
        if (sanitize_text_field($_POST['action']) != 'gswpts_table_fetch') {
            self::$output['response_type'] = esc_html('invalid_action');
            self::$output['output'] = '<b>' . esc_html__('Action is invalid', 'sheetstowptable') . '</b>';
            echo json_encode(self::$output);
            wp_die();
        }
        $page_slug = sanitize_text_field($_POST['page_slug']);
        if (empty($page_slug) && $page_slug == null && $page_slug == "") {
            self::$output['response_type'] = esc_html('invalid_request');
            self::$output['output'] = '<b>' . esc_html__('Request is invalid', 'sheetstowptable') . '</b>';
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
                                    <th class="text-center">' . esc_html__('Table ID', 'sheetstowptable') . '</th>
                                    <th class="text-center">' . esc_html__('Type', 'sheetstowptable') . '</th>
                                    <th class="text-center">' . esc_html__('Shortcode', 'sheetstowptable') . '</th>
                                    <th class="text-center">' . esc_html__('Table Name', 'sheetstowptable') . '</th>
                                    <th class="text-center">' . esc_html__('Delete', 'sheetstowptable') . '</th>
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
                                    ' . esc_html__(self::table_type($table_data->source_type), 'sheetstowptable') . '
                                </td>
                                <td class="text-center" style="display: flex; justify-content: center; align-items: center; height: 35px;">
                                        <input type="hidden" class="table_copy_sortcode" value="[gswpts_table id=' . esc_attr($table_data->id) . ']">
                                        <span class="gswpts_sortcode_copy" style="display: flex; align-items: center; white-space: nowrap; margin-right: 12px">[gswpts_table id=' . esc_attr($table_data->id) . ']</span>
                                        <i class="fas fa-copy gswpts_sortcode_copy" style="font-size: 20px;color: #b7b8ba; cursor: copy"></i>
                                </td>
                                 <td class="text-center">
                                    <div style="line-height: 38px;">
                                        <a
                                        style="margin-right: 5px; padding: 5px 15px;white-space: nowrap;"
                                        class="table_name"
                                        href="' . esc_url(admin_url('admin.php?page=gswpts-dashboard&subpage=create-table&id=' . esc_attr($table_data->id) . '')) . '">
                                        ' . esc_html__($table_data->table_name, 'sheetstowptable') . '
                                        </a>
                                        <button type="button" value="edit" class="copyToken ui right icon button gswpts_edit_table ml-1" id="' . esc_attr($table_data->id) . '" style="width: 50px;height: 38px;">
                                            <img src="' . GSWPTS_BASE_URL . 'Assets/Public/Icons/rename.svg' . '" width="24px" height="15px" alt="rename-icon"/>
                                        </button>
                                    </div>
                                </td>
                                <td class="text-center"><button data-id="' . esc_attr($table_data->id) . '" id="table-' . esc_attr($table_data->id) . '" class="negative ui button gswpts_table_delete_btn">' . esc_html__('Delete', 'sheetstowptable') . ' &nbsp; <i class="fas fa-trash"></i></button></td>
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

    /**
     * @param $type
     */
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