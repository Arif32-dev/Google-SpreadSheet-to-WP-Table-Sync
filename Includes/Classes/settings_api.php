<?php

namespace GSWPTS\Includes\Classes;

defined('ABSPATH') || die('you cant access this plugin directly');

class Settings_API {

    public function __construct() {
        add_action('admin_init', [$this, 'add_settings']);
    }
    public function add_settings() {
        $settings_options = [
            'asynchronous_loading',
            'multiple_sheet_tab',
            'sheet_tab_connection'
        ];

        foreach ($settings_options as $setting) {
            register_setting(
                'gswpts_general_setting',
                $setting,
                [
                    'default' => $setting == 'gutenberg_support' || $setting == 'elementor_support'  ? 'on' : false
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
    public static function fields() { ?>

        <?php $option_values = self::get_option_values() ?>

        <?php load_template(GSWPTS_BASE_PATH . 'Includes/Templates/Parts/general_settings.php', false, [
            'setting_title' => 'Asynchronous Loading',
            'setting_tooltip' => 'Enable this feature for loading table asynchronously',
            'is_checked' => $option_values['asynchronous_loading'],
            'input_name' => 'asynchronous_loading',
            'setting_desc' => "Enable this feauture to load the table in the frontend after loading all content with a pre-loader.
                                            This will help your website load fast.
                                            If you don't want to enable this feature than the table will load with the reloading of browser every time."
        ]); ?>

        <?php load_template(GSWPTS_BASE_PATH . 'Includes/Templates/Parts/general_settings.php', false, [
            'setting_title' => 'Multiple Spreadsheet Tab',
            'setting_tooltip' => 'This feature will let you to choose & save multiple spreadsheet tab',
            'is_checked' => $option_values['multiple_sheet_tab'],
            'input_name' => 'multiple_sheet_tab',
            'setting_desc' => "Enabling this feature will allow user/admin to choose & save multiple spreadsheet tab from a Google spreadsheet.
                                            In this free plugin user/admin can select 1 spreadsheet tab from a single Google spreadsheet.
                                            To know more about this feature <a href=''>Click Here</a>",
            'is_pro' => true
        ]); ?>


     <?php load_template(GSWPTS_BASE_PATH . 'Includes/Templates/Parts/general_settings.php', false, [
            'setting_title' => 'Table Connection',
            'setting_tooltip' => 'This feature will let you connect multiple table in a single page with Tabs/Acordian',
            'is_checked' => $option_values['sheet_tab_connection'],
            'input_name' => 'sheet_tab_connection',
            'setting_desc' => "Enabling this feature will allow user/admin to connect multiple created table in a single page.
                                            Each individual table will be shown as like bootstrap tab or accordian design
                                            To know more about this feature <a href=''>Click Here</a>",
            'is_pro' => true
        ]); ?>

<?php
    }

    public static function get_option_values() {
        $options_values = [
            'asynchronous_loading' => get_option('asynchronous_loading') ? 'checked' : '',
            'gutenberg_support' => get_option('gutenberg_support') ? 'checked' : '',
            'elementor_support' => get_option('elementor_support') ? 'checked' : '',
            'multiple_sheet_tab' => get_option('multiple_sheet_tab') ? 'checked' : '',
            'sheet_tab_connection' => get_option('sheet_tab_connection') ? 'checked' : '',
        ];
        return $options_values;
    }
}
