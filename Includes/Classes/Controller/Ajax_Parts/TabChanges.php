<?php

namespace GSWPTS\Includes\Classes\Controller\Ajax_Parts;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheetstowptable'));

class TabChanges {

    /**
     * @var array
     */
    public $output = [];
    /**
     * @var mixed
     */
    public $data;

    public function tabChanges() {
        if (sanitize_text_field($_POST['action']) != 'gswpts_tab_changes') {
            $this->output['response_type'] = esc_html('invalid_action');
            $this->output['output'] = '<b>' . esc_html__('Action is invalid', 'sheetstowptable') . '</b>';
            echo json_encode($this->output);
            wp_die();
        }

        // Sanitize the incoming data
        $this->data = $this->sanitizeData($_POST['data']);

        switch (sanitize_text_field($_POST['type'])) {

        case 'create':
            $this->saveChanges();
            break;

        case 'update':
            $this->updateChanges();
            break;

        default:
            wp_die();
            break;
        }

        wp_die();
    }

    /**
     * @return null
     */
    public function saveChanges() {
        global $wpdb;
        $table = $wpdb->prefix . 'gswpts_tabs';

        $insertionValues = [];

        if (!$this->data) {
            return;
        }

        foreach ($this->data as $key => $singleTabData) {
            $reverseMode = $singleTabData['reverseMode'] == 'true' ? true : false;
            $insertionValues[] = "('" . $singleTabData['tabName'] . "','" . $reverseMode . "','" . serialize($singleTabData['tabSettings']) . "')";
        }

        $insertionString = implode(",", $insertionValues);

        try {
            $sql = "INSERT INTO " . $table . "(tab_name, reverse_mode, tab_settings) VALUES ${insertionString}";
            $result = $wpdb->query($sql);

            if ($result) {
                $this->output['response_type'] = esc_html('success');
                $this->output['output'] = '<b>' . esc_html__('Data saved successfully', 'sheetstowptable') . '</b>';
                echo json_encode($this->output);
                wp_die();
            }
        } catch (\Throwable $error) {
            throw $error;
            $this->output['response_type'] = esc_html('error');
            $this->output['output'] = '<b>' . esc_html__('Database insertion error', 'sheetstowptable') . '</b>';
            echo json_encode($this->output);
            wp_die();
        }

    }

    public function updateChanges() {
        global $wpdb;
        $table = $wpdb->prefix . 'gswpts_tabs';

        try {

            $respond = $wpdb->update(
                $table,
                [
                    'tab_settings' => serialize($this->data[0]['tabSettings']),
                    'reverse_mode' => $this->data[0]['reverseMode'] == 'true' ? true : false
                ],
                [
                    'id' => intval($this->data[0]['tabID'])
                ],
                [
                    '%s',
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
                $this->output['response_type'] = esc_html('success');
                $this->output['output'] = '<b>' . esc_html__('Nothing changed to update.', 'sheetstowptable') . '</b>';
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

    /**
     * @param  array   $unSanitizedData
     * @return mixed
     */
    public function sanitizeData(array $unSanitizedData) {
        $sanitizedData = null;

        $sanitizedData = array_map(function ($data) {
            if (gettype($data) == 'array') {
                return $this->sanitizeData($data);
            } else {
                return sanitize_text_field($data);
            }
        }, $unSanitizedData);

        return $sanitizedData;
    }

}