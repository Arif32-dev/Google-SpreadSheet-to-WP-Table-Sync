<?php

namespace GSWPTS\Includes\Classes;

defined('ABSPATH') || wp_die(__('You can\'t access this page', 'sheetstowptable'));

class SettingsApi {

    public function __construct() {
        add_action('admin_init', [$this, 'add_settings']);
    }

    public function add_settings() {

        global $gswpts;

        $settings_options = $gswpts->generalSettingsOptions();

        foreach ($settings_options as $setting) {
            register_setting(
                'gswpts_general_setting',
                $setting,
                [
                    'default' => $setting == 'gutenberg_support' || $setting == 'elementor_support' ? 'on' : false
                ]
            );
            if ($setting == 'gutenberg_support' || $setting == 'elementor_support') {
                add_option($setting, 'on');
            }
        }
        self::add_section_and_fields();
    }

    public static function add_section_and_fields() {
        add_settings_section(
            'gswpts_general_section_id',
            '',
            null,
            'gswpts-general-settings'
        );
        add_settings_field(
            'gswpts_general_settings_field_id',
            "",
            [get_called_class(), 'fields'],
            'gswpts-general-settings',
            'gswpts_general_section_id'
        );
    }

    /**
     * @return null
     */
    public static function fields() {

        global $gswpts;

        $settingsArray = $gswpts->generalSettingsArray();

        if (!$settingsArray) {
            return;
        }

        foreach ($settingsArray as $setting) {
            load_template($setting['template_path'], false, $setting);
        }

    }
}