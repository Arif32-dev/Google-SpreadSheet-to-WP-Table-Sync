<?php

namespace GSWPTS\includes\classes\controller\ajax;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheetstowptable'));

class UdTables {
    /**
     * @var array
     */
    public $output = [];
    /**
     * @var mixed
     */
    public $action;

    /**
     * @return null
     */
    public function ud_tables() {

        $this->action = sanitize_text_field($_POST['action']);

        if ($this->action != 'gswpts_ud_table' && $this->action != 'gswpts_ud_tab') {

            $this->output['response_type'] = esc_html('invalid_action');
            $this->output['output'] = '<b>' . esc_html__('Action is invalid', 'sheetstowptable') . '</b>';
            echo json_encode($this->output);
            wp_die();
        }

        if (sanitize_text_field($_POST['data']['reqType']) == 'update') {

            $sanitized_data = [
                'id'   => sanitize_text_field($_POST['data']['id']),
                'name' => sanitize_text_field($_POST['data']['name'])
            ];

            echo json_encode($this->updateData($sanitized_data));

            wp_die();
        }

        if (sanitize_text_field($_POST['data']['reqType']) == 'delete') {

            $sanitized_data = [
                'id'             => sanitize_text_field($_POST['data']['id']),
                'dataActionType' => sanitize_text_field($_POST['data']['dataActionType'])
            ];

            echo json_encode($this->deleteData($sanitized_data));

            wp_die();
        }

        if (sanitize_text_field($_POST['data']['reqType']) == 'deleteAll') {
            $delete_respose = false;

            $sanitized_data = [
                'ids'            => array_map(function ($id) {
                    return sanitize_text_field($id);
                }, $_POST['data']['ids']),

                'dataActionType' => sanitize_text_field($_POST['data']['dataActionType'])
            ];

            foreach ($sanitized_data['ids'] as $key => $value) {

                $return = $this->deleteData([
                    'id'             => sanitize_text_field($value),
                    'dataActionType' => $sanitized_data['dataActionType']
                ]);

                if ($return['response_type'] != 'deleted') {
                    $this->output['response_type'] = esc_html('invalid_request');
                    $this->output['output'] = '<b>' . esc_html__('Request is invalid', 'sheetstowptable') . '</b>';
                    echo json_encode($this->output);
                    wp_die();
                } else {
                    $delete_respose = true;
                }
            }

            if ($delete_respose) {
                $this->output['response_type'] = esc_html('deleted_All');

                if ($sanitized_data['dataActionType'] == 'gswpts_ud_table') {
                    $this->output['output'] = '<b>' . esc_html__('Selected tables deleted successfully', 'sheetstowptable') . '</b>';
                }

                if ($sanitized_data['dataActionType'] == 'gswpts_ud_tab') {
                    $this->output['output'] = '<b>' . esc_html__('Selected tabs deleted successfully', 'sheetstowptable') . '</b>';
                }

                echo json_encode($this->output);
                wp_die();
            } else {
                $this->output['response_type'] = esc_html('invalid_request');
                $this->output['output'] = '<b>' . esc_html__('Request is invalid', 'sheetstowptable') . '</b>';
                echo json_encode($this->output);
                wp_die();
            }
        }

        $this->output['response_type'] = esc_html('invalid_request');
        $this->output['output'] = '<b>' . esc_html__('Request is invalid', 'sheetstowptable') . '</b>';
        echo json_encode($this->output);
        wp_die();
    }

    /**
     * @param array $sanitized_data
     */
    public function updateData(array $sanitized_data) {
        global $wpdb;
        $table = null;

        $columField = [];

        if ($this->action == 'gswpts_ud_table') {
            $table = $wpdb->prefix . 'gswpts_tables';
            $columField['table_name'] = sanitize_text_field($sanitized_data['name']);
            $this->output['output'] = '<b>' . esc_html__('Table name updated successfully', 'sheetstowptable') . '</b>';
        }

        if ($this->action == 'gswpts_ud_tab') {
            $table = $wpdb->prefix . 'gswpts_tabs';
            $columField['tab_name'] = sanitize_text_field($sanitized_data['name']);
            $this->output['output'] = '<b>' . esc_html__('Tab name updated successfully', 'sheetstowptable') . '</b>';
        }

        $update_response = $wpdb->update(
            $table,
            $columField,
            [
                'id' => intval(sanitize_text_field($sanitized_data['id']))
            ],
            [
                '%s'
            ],
            [
                '%d'
            ]
        );

        if (is_int($update_response)) {
            $this->output['response_type'] = esc_html('updated');
            return $this->output;
        }
    }

    /**
     * @param array $sanitized_data
     */
    public function deleteData(array $sanitized_data) {
        global $wpdb;
        $table = null;

        if ($sanitized_data['dataActionType'] == 'gswpts_ud_table') {
            $table = $wpdb->prefix . 'gswpts_tables';
            $this->output['output'] = '<b>' . esc_html__('Table deleted successfully', 'sheetstowptable') . '</b>';
        }

        if ($sanitized_data['dataActionType'] == 'gswpts_ud_tab') {
            $table = $wpdb->prefix . 'gswpts_tabs';
            $this->output['output'] = '<b>' . esc_html__('Tab deleted successfully', 'sheetstowptable') . '</b>';
        }

        $update_response = $wpdb->delete(
            $table,
            [
                'id' => $sanitized_data['id']
            ],
            [
                '%d'
            ]
        );

        if (is_int($update_response)) {

            if ($sanitized_data['dataActionType'] == 'gswpts_ud_table') {
                // delete caching related transient of this table
                delete_transient('gswpts_sheet_data_' . $sanitized_data['id'] . '');
                delete_transient('gswpts_sheet_styles_' . $sanitized_data['id'] . '');
                delete_transient('gswpts_sheet_images_' . $sanitized_data['id'] . '');

                delete_option('gswpts_sheet_updated_time_' . $sanitized_data['id'] . '');
            }

            $this->output['response_type'] = esc_html('deleted');
            return $this->output;
        } else {
            $this->output['response_type'] = esc_html('invalid_request');
            $this->output['output'] = '<b>' . esc_html__('Request is invalid', 'sheetstowptable') . '</b>';
            return $this->output;
        }
    }
}