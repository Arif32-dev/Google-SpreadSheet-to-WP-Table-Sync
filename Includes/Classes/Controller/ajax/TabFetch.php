<?php

namespace GSWPTS\includes\classes\controller\ajax;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheetstowptable'));

class TabFetch {

    /**
     * @var array
     */
    public $output = [];
    /**
     * @var mixed
     */
    public $tabData = null;

    public function manageTabs() {
        if (sanitize_text_field($_POST['action']) != 'gswpts_manage_tab') {
            $this->output['response_type'] = esc_html('invalid_action');
            $this->output['output'] = '<b>' . esc_html__('Action is invalid', 'sheetstowptable') . '</b>';
            echo json_encode($this->output);
            wp_die();
        }

        $page_slug = sanitize_text_field($_POST['page_slug']);

        if (empty($page_slug) && $page_slug == null && $page_slug == "") {
            $this->output['response_type'] = esc_html('invalid_request');
            $this->output['output'] = '<b>' . esc_html__('Request is invalid', 'sheetstowptable') . '</b>';
            echo json_encode($this->output);
            wp_die();
        }

        $this->tabTableData();

        echo json_encode($this->table_html());

        wp_die();
    }

    /**
     * @return mixed
     */
    public function table_html() {

        if (!$this->tabData) {
            $this->output['no_data'] = 'true';
        }

        $table = '<table id="manage_tabs" class="ui celled table">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        <input data-show="false" type="checkbox" name="manage_tab_main_checkbox" id="manage_tab_checkbox">
                                    </th>
                                    <th class="text-center">' . esc_html__('Show Name', 'sheetstowptable') . '</th>
                                    <th class="text-center">' . esc_html__('Shortcode', 'sheetstowptable') . '</th>
                                    <th class="text-center">' . esc_html__('Tab Name', 'sheetstowptable') . '</th>
                                    <th class="text-center">' . esc_html__('Delete', 'sheetstowptable') . '</th>
                                </tr>
                            </thead>
                        <tbody>

        ';

        if ($this->tabData) {
            foreach ($this->tabData as $key => $data) {

                $checked = $data->show_name ? "checked" : "";

                $table .= '
                <tr>
                    <td class="text-center" style="vertical-align: middle">
                        <input type="checkbox" value="' . esc_attr($data->id) . '" name="manage_tab_checkbox" class="manage_tab_checkbox">
                    </td>

                    <td class="text-center" style="vertical-align: middle">
                        <div class="ui toggle checkbox mt-2 manage_tab_name_toggle">
                            <input type="checkbox" name="public" ' . $checked . ' data-id="' . esc_attr($data->id) . '">
                            <label style="margin-bottom: 0"></label>
                        </div>
                    </td>

                    <td class="text-center" style="display: flex; justify-content: center; align-items: center; height: 35px;">
                            <input type="hidden" class="tab_copy_sortcode" value="[gswpts_tab id=' . esc_attr($data->id) . ']">
                            <span class="gswpts_tab_sortcode_copy" style="display: flex; align-items: center; white-space: nowrap; margin-right: 12px">[gswpts_tab id=' . esc_attr($data->id) . ']</span>
                            <i class="fas fa-copy gswpts_sortcode_copy" style="font-size: 20px;color: #b7b8ba; cursor: copy"></i>
                    </td>

                    <td class="text-center">
                        <div style="line-height: 38px;">

                            <div class="ui input tab_name_hidden">
                                <input type="text" class="tab_name_hidden_input" value="' . esc_attr($data->tab_name) . '" />
                            </div>

                            <a
                            style="margin-right: 5px; padding: 5px 15px;white-space: nowrap;"
                            class="tab_name"
                            href="' . esc_url(admin_url('admin.php?page=gswpts-manage-tab&subpage=create-tab&id=' . esc_attr($data->id) . '')) . '">
                            ' . esc_html__($data->tab_name, 'sheetstowptable') . '
                            </a>
                            <button type="button" value="edit" class="copyToken ui right icon button gswpts_edit_tab ml-1" id="' . esc_attr($data->id) . '" style="width: 50px;height: 38px;">
                                <img src="' . GSWPTS_BASE_URL . 'Assets/Public/Icons/rename.svg' . '" width="24px" height="15px" alt="rename-icon"/>
                            </button>
                        </div>
                    </td>

                    <td class="text-center"><button data-id="' . esc_attr($data->id) . '" id="tab-' . esc_attr($data->id) . '" class="negative ui button gswpts_tab_delete_btn">' . esc_html__('Delete', 'sheetstowptable') . ' &nbsp; <i class="fas fa-trash"></i></button></td>
                </tr>';
            }
        }

        $table .= '
                    </tbody>
                </table>
        ';
        $this->output['response_type'] = esc_html('success');
        $this->output['output'] = "" . $table . "";
        return $this->output;
    }

    public function tabTableData() {
        global $wpdb;
        $table = $wpdb->prefix . 'gswpts_tabs';

        $result = $wpdb->get_results("SELECT * FROM " . $table . "");
        if (!empty($result)) {
            $this->tabData = $result;
        }
    }

}