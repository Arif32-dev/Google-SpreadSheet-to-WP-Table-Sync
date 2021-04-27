<?php

namespace GSWPTS\Includes\Classes\Controller\Ajax_Parts;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheets-to-wp-table-live-sync'));

class UdTables {
    private static $output = [];

    public function ud_tables() {
        if (sanitize_text_field($_POST['action']) != 'gswpts_ud_table') {
            self::$output['response_type'] = esc_html('invalid_action');
            self::$output['output'] = '<b>' . esc_html('Action is invalid') . '</b>';
            echo json_encode(self::$output);
            wp_die();
        }
        if (sanitize_text_field($_POST['data']['reqType']) == 'update') {
            $sanitized_data = [
                'id' => sanitize_text_field($_POST['data']['table_id']),
                'table_name' => sanitize_text_field($_POST['data']['table_name'])
            ];
            echo json_encode(self::update_table($sanitized_data));
            wp_die();
        }

        if (sanitize_text_field($_POST['data']['reqType']) == 'delete') {
            $sanitized_data = [
                'id' => sanitize_text_field($_POST['data']['table_id']),
            ];
            echo json_encode(self::delete_table($sanitized_data));
            wp_die();
        }

        if (sanitize_text_field($_POST['data']['reqType']) == 'deleteAll') {
            $delete_respose = false;
            $sanitized_data = [
                'ids' => array_map(function ($id) {
                    return sanitize_text_field($id);
                }, $_POST['data']['table_ids']),
            ];
            foreach ($sanitized_data['ids'] as $key =>  $value) {
                $return = self::delete_table(['id' => sanitize_text_field($value)]);
                if ($return['response_type'] != 'deleted') {
                    self::$output['response_type'] = esc_html('invalid_request');
                    self::$output['output'] = '<b>' . esc_html('Request is invalid') . '</b>';
                    echo json_encode(self::$output);
                    wp_die();
                } else {
                    $delete_respose = true;
                }
            }
            if ($delete_respose) {
                self::$output['response_type'] = esc_html('deleted_All');
                self::$output['output'] = '<b>' . esc_html('Selected tables deleted successfully') . '</b>';
                echo json_encode(self::$output);
                wp_die();
            } else {
                self::$output['response_type'] = esc_html('invalid_request');
                self::$output['output'] = '<b>' . esc_html('Request is invalid') . '</b>';
                echo json_encode(self::$output);
                wp_die();
            }
        }

        self::$output['response_type'] = esc_html('invalid_request');
        self::$output['output'] = '<b>' . esc_html('Request is invalid') . '</b>';
        echo json_encode(self::$output);
        wp_die();
    }
    public static function update_table(array $sanitized_data) {
        global $wpdb;
        $table = $wpdb->prefix . 'gswpts_tables';

        $update_response = $wpdb->update(
            $table,
            [
                'table_name' => sanitize_text_field($sanitized_data['table_name'])
            ],
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
            self::$output['response_type'] = esc_html('updated');
            self::$output['output'] = '<b>' . esc_html('Table name updated successfully') . '</b>';
            return self::$output;
        }
    }
    public static function delete_table(array $sanitized_data) {
        global $wpdb;
        $table = $wpdb->prefix . 'gswpts_tables';

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
            self::$output['response_type'] = esc_html('deleted');
            self::$output['output'] = '<b>' . esc_html('Table deleted successfully') . '</b>';
            return self::$output;
        } else {
            self::$output['response_type'] = esc_html('invalid_request');
            self::$output['output'] = '<b>' . esc_html('Request is invalid') . '</b>';
            return self::$output;
        }
    }
}
