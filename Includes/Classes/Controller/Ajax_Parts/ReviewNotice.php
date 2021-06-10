<?php

namespace GSWPTS\Includes\Classes\Controller\Ajax_Parts;

class ReviewNotice {
    /**
     * @var array
     */
    private $output = [];

    public function manageNotices() {

        if (sanitize_text_field($_POST['action']) != 'gswpts_review_notice') {
            $this->output['response_type'] = esc_html('invalid_action');
            echo json_encode($this->output);
            wp_die();
        }

        if (sanitize_text_field($_POST['info']['type']) == 'hide_notice') {
            $this->hideNotice();
            echo json_encode($this->output);
            wp_die();
        }

        if (sanitize_text_field($_POST['info']['type']) == 'reminder') {
            $this->setReminder();
            echo json_encode($this->output);
            wp_die();
        }

        wp_die();
    }

    public function hideNotice() {

        update_option('gswptsReviewNotice', [
            'isRated' => true
        ]);

        $this->output['response_type'] = esc_html('success');

    }

    public function setReminder() {

        $reminderValue = sanitize_text_field($_POST['info']['value']);

        if ($reminderValue == 'hide_notice') {
            $this->hideNotice();
            $this->output['response_type'] = esc_html('success');
        } else {

            update_option('gswptsActivationTime', (time() + (60 * 60 * 24 * intval($reminderValue))));
            update_option('deafaultNoticeInterval', intval($reminderValue));

            $this->output['response_type'] = esc_html('success');
        }
    }

}