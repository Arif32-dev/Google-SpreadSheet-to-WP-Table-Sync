<?php

namespace GSWPTS\Includes\Classes\Controller\Ajax_Parts;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheetstowptable'));

class UdTables {
    /**
     * @var array
     */
    private static $output = [];

    public function ud_tables() {
        if (sanitize_text_field($_POST['action']) != 'gswpts_ud_table') {
            self::$output['response_type'] = esc_html('invalid_action');
            self::$output['output'] = '<b>' . esc_html__('Action is invalid', 'sheetstowptable') . '</b>';
            echo json_encode(self::$output);
            wp_die();
        }
        if (sanitize_text_field($_POST['data']['reqType']) == 'update') {
            $sanitized_data = [
                'id'         => sanitize_text_field($_POST['data']['table_id']),
                'table_name' => sanitize_text_field($_POST['data']['table_name'])
            ];
            echo json_encode(self::update_table($sanitized_data));
            wp_die();
        }

        if (sanitize_text_field($_POST['data']['reqType']) == 'delete') {
            $sanitized_data = [
                'id' => sanitize_text_field($_POST['data']['table_id'])
            ];
            echo json_encode(self::delete_table($sanitized_data));
            wp_die();
        }

        if (sanitize_text_field($_POST['data']['reqType']) == 'deleteAll') {
            $delete_respose = false;
            $sanitized_data = [
                'ids' => array_map(function ($id) {
                    return sanitize_text_field($id);
                }, $_POST['data']['table_ids'])
            ];
            foreach ($sanitized_data['ids'] as $key => $value) {
                $return = self::delete_table(['id' => sanitize_text_field($value)]);
                if ($return['response_type'] != 'deleted') {
                    self::$output['response_type'] = esc_html('invalid_request');
                    self::$output['output'] = '<b>' . esc_html__('Request is invalid', 'sheetstowptable') . '</b>';
                    echo json_encode(self::$output);
                    wp_die();
                } else {
                    $delete_respose = true;
                }
            }
            if ($delete_respose) {
                self::$output['response_type'] = esc_html('deleted_All');
                self::$output['output'] = '<b>' . esc_html__('Selected tables deleted successfully', 'sheetstowptable') . '</b>';
                echo json_encode(self::$output);
                wp_die();
            } else {
                self::$output['response_type'] = esc_html('invalid_request');
                self::$output['output'] = '<b>' . esc_html__('Request is invalid', 'sheetstowptable') . '</b>';
                echo json_encode(self::$output);
                wp_die();
            }
        }

        self::$output['response_type'] = esc_html('invalid_request');
        self::$output['output'] = '<b>' . esc_html__('Request is invalid', 'sheetstowptable') . '</b>';
        echo json_encode(self::$output);
        wp_die();
    }

    /**
     * @param array $sanitized_data
     */
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
            self::$output['output'] = '<b>' . esc_html__('Table name updated successfully', 'sheetstowptable') . '</b>';
            return self::$output;
        }
    }

    /**
     * @param array $sanitized_data
     */
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
            // delete caching related transient of this table
            delete_transient('gswpts_sheet_data_' . $sanitized_data['id'] . '');
            delete_transient('gswpts_sheet_styles_' . $sanitized_data['id'] . '');
            delete_transient('gswpts_sheet_images_' . $sanitized_data['id'] . '');

            delete_option('gswpts_sheet_updated_time_' . $sanitized_data['id'] . '');

            self::$output['response_type'] = esc_html('deleted');
            self::$output['output'] = '<b>' . esc_html__('Table deleted successfully', 'sheetstowptable') . '</b>';
            return self::$output;
        } else {
            self::$output['response_type'] = esc_html('invalid_request');
            self::$output['output'] = '<b>' . esc_html__('Request is invalid', 'sheetstowptable') . '</b>';
            return self::$output;
        }
    }
}