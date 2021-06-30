<?php

namespace GSWPTS\Includes\Classes\Controller\Ajax_Parts;

class RemoteClass {
    /**
     * @var array
     */
    private $output = [];
    /**
     * @var array
     */
    private $formData = [];

    public function subscriptionRequest() {

        if (sanitize_text_field($_POST['action']) != 'gswpts_user_subscribe') {
            $this->output['response_type'] = esc_html('invalid_action');
            $this->output['output'] = sprintf('<b>%s</b>', esc_html__('Action is invalid', 'sheetstowptable'));
            echo json_encode($this->output);
            wp_die();
        }

        parse_str($_POST['form_data'], $formData);

        $this->formData = array_map(function ($data) {
            return sanitize_text_field($data);
        }, $formData);

        if (sanitize_text_field($_POST['action']) != 'gswpts_user_subscribe') {
            $this->output['response_type'] = esc_html('invalid_action');
            $this->output['output'] = sprintf('<b>%s</b>', esc_html__('Action is invalid', 'sheetstowptable'));
            echo json_encode($this->output);
            wp_die();
        }

        if (!wp_verify_nonce($this->formData['user_subscription'], 'user_subscription_action')) {
            $this->output['response_type'] = esc_html('invalid_action');
            $this->output['output'] = sprintf('<b>%s</b>', esc_html__('Nonce is invalid', 'sheetstowptable'));
            echo json_encode($this->output);
            wp_die();
        }

        $this->userSubscription();

        echo json_encode($this->output);

        wp_die();
    }

    public function userSubscription() {

        $arg = [
            'body' => [
                'email'     => $this->formData['email'] ? $this->formData['email'] : null,
                'full_name' => $this->formData['full_name'] ? $this->formData['full_name'] : null
            ]
        ];

        $error = [];

        if (!$arg['body']['email']) {
            array_push($error, esc_html__('Email is required', 'sheetstowptable'));
        }

        if (!$arg['body']['full_name']) {
            array_push($error, esc_html__('User full name is required', 'sheetstowptable'));
        }

        if (!$this->formData['url']) {
            array_push($error, esc_html__('Form URL is missing', 'sheetstowptable'));
        }

        if ($error) {
            $this->output['response_type'] = esc_html('empty');
            $this->output['output'] = sprintf('<b>%s</b>', join("<br/>", $error));
        }

        $subscribe = wp_remote_post($this->formData['url'], $arg);

        if (is_wp_error($subscribe)) {
            $this->output['response_type'] = esc_html('error');
            $this->output['output'] = sprintf('<b>%s</b>', esc_html__('Subscription can\'t be completed', 'sheetstowptable'));
        } else {
            if ($subscribe['response']['code'] == 200 && json_decode($subscribe['body'])->data->message == 'updated') {
                $this->output['response_type'] = esc_html('success');
                $this->output['output'] = sprintf('<b>%s</b>', esc_html__('Subscription mail updated', 'sheetstowptable'));
            } elseif ($subscribe['response']['code'] == 200 && json_decode($subscribe['body'])->data->message == 'created') {
                $this->output['response_type'] = esc_html('success');
                $this->output['output'] = sprintf('<b>%s</b>', esc_html__('Thank you for being a subscriber', 'sheetstowptable'));
            } else {
                $this->output['response_type'] = esc_html('success');
                $this->output['output'] = sprintf('<b>%s</b>', esc_html__('Thank you for being a subscriber', 'sheetstowptable'));
            }

            $responseMessage = json_decode($subscribe['body'])->data->message;

            if ($subscribe['response']['code'] == 200) {
                switch ($responseMessage) {
                case 'updated':
                    $this->output['response_type'] = esc_html('success');
                    $this->output['output'] = sprintf('<b>%s</b>', esc_html__('Subscription mail updated', 'sheetstowptable'));
                    break;

                case 'created':
                    $this->output['response_type'] = esc_html('success');
                    $this->output['output'] = sprintf('<b>%s</b>', esc_html__('Thank you for being a subscriber', 'sheetstowptable'));
                    break;

                default:
                    $this->output['response_type'] = esc_html('success');
                    $this->output['output'] = sprintf('<b>%s</b>', esc_html__($responseMessage, 'sheetstowptable'));
                    break;
                }
            } else {
                $this->output['response_type'] = esc_html('error');
                $this->output['output'] = sprintf('<b>%s</b>', esc_html__($responseMessage, 'sheetstowptable'));
            }

        }

    }

    public function getPostRequest() {

        if ($_SERVER['REQUEST_METHOD'] != 'GET') {
            $this->output['response_type'] = esc_html('invalid_action');
            $this->output['output'] = sprintf('<b>%s</b>', esc_html__('Request is invalid', 'sheetstowptable'));
            echo json_encode($this->output);
            wp_die();
        }

        if (sanitize_text_field($_GET['action']) != 'gswpts_get_posts') {
            $this->output['response_type'] = esc_html('invalid_action');
            $this->output['output'] = '<b>'.esc_html__('Action is invalid', 'sheetstowptable').'</b>';
            echo json_encode($this->output);
            wp_die();
        }

        $this->retrivePosts();

        echo json_encode($this->output);

        wp_die();
    }

    public function retrivePosts() {

        $post = wp_remote_get('https://wppool.dev/wp-json/wp/v2/posts?per_page=10');

        if (is_wp_error($post)) {
            $this->output['response_type'] = esc_html('error');
            $this->output['output'] = sprintf('<b>%s</b>', esc_html__('WPPOOL Blogs can\'t be loaded. Try again', 'sheetstowptable'));
        } else {
            if ($post['response']['code'] == 200 && $post['response']['message'] == 'OK') {
                $this->output['response_type'] = esc_html('success');
                $this->output['output'] = json_decode($post['body']);
            }
        }

    }

}