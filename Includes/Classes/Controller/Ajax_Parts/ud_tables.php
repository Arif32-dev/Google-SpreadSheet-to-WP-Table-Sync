<?php

namespace GSWPTS\Includes\Classes\Controller\Ajax_Parts;

class UD_Tables {
    private static $output = [];

    public function ud_tables() {
        if ($_POST['action'] != 'gswpts_ud_table') {
            self::$output['response_type'] = 'invalid_action';
            self::$output['output'] = '<b>Action is invalid</b>';
            echo json_encode(self::$output);
            wp_die();
        }
        if ($_POST['data']['reqType'] == 'update') {
            $sanitized_data = [
                'id' => sanitize_text_field($_POST['data']['table_id']),
                'table_name' => sanitize_text_field($_POST['data']['table_name'])
            ];
            echo json_encode(self::update_table($sanitized_data));
            wp_die();
        }

        if ($_POST['data']['reqType'] == 'delete') {
            $sanitized_data = [
                'id' => sanitize_text_field($_POST['data']['table_id']),
            ];
            echo json_encode(self::delete_table($sanitized_data));
            wp_die();
        }

        if ($_POST['data']['reqType'] == 'deleteAll') {
            $delete_respose = false;
            $sanitized_data = [
                'ids' => $_POST['data']['table_ids'],
            ];
            foreach ($sanitized_data['ids'] as $key =>  $value) {
                $return = self::delete_table(['id' => sanitize_text_field($value)]);
                if ($return['response_type'] != 'deleted') {
                    self::$output['response_type'] = 'invalid_request';
                    self::$output['output'] = '<b>Request is invalid</b>';
                    echo json_encode(self::$output);
                    wp_die();
                } else {
                    $delete_respose = true;
                }
            }
            if ($delete_respose) {
                self::$output['response_type'] = 'deleted_All';
                self::$output['output'] = '<b>Selected tables deleted successfully</b>';
                echo json_encode(self::$output);
                wp_die();
            } else {
                self::$output['response_type'] = 'invalid_request';
                self::$output['output'] = '<b>Request is invalid</b>';
                echo json_encode(self::$output);
                wp_die();
            }
        }

        self::$output['response_type'] = 'invalid_request';
        self::$output['output'] = '<b>Request is invalid</b>';
        echo json_encode(self::$output);
        wp_die();
    }
    public static function update_table(array $sanitized_data) {
        global $wpdb;
        $table = $wpdb->prefix . 'gswpts_tables';

        $update_response = $wpdb->update(
            $table,
            [
                'table_name' => $sanitized_data['table_name']
            ],
            [
                'id' => $sanitized_data['id']
            ],
            [
                '%s'
            ],
            [
                '%d'
            ]
        );
        if (is_int($update_response)) {
            self::$output['response_type'] = 'updated';
            self::$output['output'] = '<b>Table name updated successfully</b>';
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
            self::$output['response_type'] = 'deleted';
            self::$output['output'] = '<b>Table deleted successfully</b>';
            return self::$output;
        } else {
            self::$output['response_type'] = 'invalid_request';
            self::$output['output'] = '<b>Request is invalid</b>';
            return self::$output;
        }
    }
}
