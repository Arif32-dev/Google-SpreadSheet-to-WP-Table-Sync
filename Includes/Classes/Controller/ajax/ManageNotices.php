<?php

namespace GSWPTS\includes\classes\controller\ajax;

class ManageNotices {
    /**
     * @var array
     */
    private $output = [];

    public function manageNotices() {

        if (sanitize_text_field($_POST['action']) != 'gswpts_notice_action') {
            $this->output['response_type'] = esc_html('invalid_action');
            echo json_encode($this->output);
            wp_die();
        }

        $args = [
            'actionType' => sanitize_text_field($_POST['actionType'])
        ];

        if (sanitize_text_field($_POST['info']['type']) == 'hide_notice') {
            $this->hideNotice($args);
            echo json_encode($this->output);
            wp_die();
        }

        if (sanitize_text_field($_POST['info']['type']) == 'reminder') {
            $this->setReminder($args);
            echo json_encode($this->output);
            wp_die();
        }

        wp_die();
    }

    /**
     * @param $args
     */
    public function hideNotice($args) {

        if ($args['actionType'] == 'review_notice') {
            update_option('gswptsReviewNotice', true);
        }

        if ($args['actionType'] == 'affiliate_notice') {
            update_option('gswptsAffiliateNotice', true);
        }

        $this->output['response_type'] = esc_html('success');

    }

    /**
     * @param $args
     */
    public function setReminder($args) {

        $reminderValue = sanitize_text_field($_POST['info']['value']);

        if ($reminderValue == 'hide_notice') {
            $this->hideNotice($args);
            $this->output['response_type'] = esc_html('success');
        } else {

            if ($args['actionType'] == 'review_notice') {
                update_option('deafaultNoticeInterval', (time() + intval($reminderValue) * 24 * 60 * 60));
            }

            if ($args['actionType'] == 'affiliate_notice') {
                update_option('deafaultAffiliateInterval', (time() + intval($reminderValue) * 24 * 60 * 60));
            }

            $this->output['response_type'] = esc_html('success');
        }
    }

}