<?php

namespace GSWPTS\Includes\Classes\Controller\Ajax_Parts;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheetstowptable'));

class TabNameToggle {

    /**
     * @var array
     */
    public $output = [];

    public function tabNameToggle() {

        if (sanitize_text_field($_POST['action']) != 'gswpts_manage_tab_toggle') {
            $this->output['response_type'] = esc_html('invalid_action');
            $this->output['output'] = '<b>' . esc_html__('Action is invalid', 'sheetstowptable') . '</b>';
            echo json_encode($this->output);
            wp_die();
        }

        if (!isset($_POST['show_name'])) {
            $this->output['response_type'] = esc_html('invalid_action');
            $this->output['output'] = '<b>' . esc_html__('Show name parameter is missing', 'sheetstowptable') . '</b>';
            echo json_encode($this->output);
            wp_die();
        }

        $this->updateToggleValue();

        wp_die();
    }

    public function updateToggleValue() {
        $showName = rest_sanitize_boolean($_POST['show_name']);
        $tabID = sanitize_text_field($_POST['tabID']);

        global $wpdb;
        $table = $wpdb->prefix . 'gswpts_tabs';

        try {

            $respond = $wpdb->update(
                $table,
                [
                    'show_name' => $showName
                ],
                [
                    'id' => $tabID
                ],
                [
                    '%d'
                ],
                [
                    '%d'
                ]
            );

            if ($respond) {
                $this->output['response_type'] = esc_html('success');
                $this->output['output'] = '<b>' . esc_html__('Tab updated successfully', 'sheetstowptable') . '</b>';
                echo json_encode($this->output);
                wp_die();
            } else {
                $this->output['response_type'] = esc_html('error');
                $this->output['output'] = '<b>' . esc_html__('Tab could not be updated. Try again', 'sheetstowptable') . '</b>';
                echo json_encode($this->output);
                wp_die();
            }

        } catch (\Throwable $error) {
            throw $error;
            $this->output['response_type'] = esc_html('error');
            $this->output['output'] = '<b>' . esc_html__('Database update error', 'sheetstowptable') . '</b>';
            echo json_encode($this->output);
            wp_die();
        }

    }

}