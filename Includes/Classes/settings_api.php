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
            'gutenberg_support',
            'elementor_support',
        ];

        foreach ($settings_options as $setting) {
            register_setting(
                'gswpts_general_setting',
                $setting,
                [
                    'default' => $setting == 'gutenberg_support' || $setting == 'elementor_support'  ? 'on' : false
                ]
            );
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


        <div class="ui cards settings_row">


            <div class="card">
                <div class="content">
                    <div class="description d-flex justify-content-between align-items-center">
                        <h3 class="m-0">
                            Asynchronous Loading
                            <span class="ui icon button p-0 m-0" data-tooltip="Enable this feature for loading table asynchronously" data-position="right center" data-inverted="">
                                <i class="fas fa-info-circle"></i>
                            </span>
                        </h3>
                        <div class="ui toggle checkbox m-0">
                            <input type="checkbox" <?php echo $option_values['asynchronous_loading'] ?> name="asynchronous_loading" id="asynchronous_loading">
                            <label class="m-0" for="asynchronous_loading"></label>
                        </div>
                    </div>
                </div>
                <div class="settings_desc <?php echo $option_values['asynchronous_loading'] == 'checked' ? '' : 'transition hidden' ?>">
                    <p>
                        Enable this feauture to load the table in the frontend after loading all content with a pre-loader.
                        This will help your website load fast.
                        If you don't want to enable this feature than the table will load with the reloading of browser every time.
                    </p>
                </div>
            </div>

        </div>

        <div class="ui cards settings_row">


            <div class="card">
                <div class="content">
                    <div class="description d-flex justify-content-between align-items-center">
                        <h3 class="m-0">
                            Gutenberg Block Support
                            <span class="ui icon button p-0 m-0" data-tooltip="Enable this feature for gutenberg block" data-position="right center" data-inverted="">
                                <i class="fas fa-info-circle"></i>
                            </span>
                        </h3>
                        <div class="ui toggle checkbox m-0">
                            <input type="checkbox" <?php echo $option_values['gutenberg_support'] ?> name="gutenberg_support" id="gutenberg_support">
                            <label class="m-0" for="gutenberg_support"></label>
                        </div>
                    </div>
                </div>
                <div class="settings_desc <?php echo $option_values['gutenberg_support'] == 'checked' ? '' : 'transition hidden' ?>">
                    <p>
                        Enabling this feature will enable a custom block in gutenberg. The Google spreadsheet to WP Table Sync block will allow you
                        to create and choose table previously created from plugins <a href="<?php echo admin_url('admin.php?page=gswpts-create-tables') ?>">Create Table</a> page.
                        The changes or new new table created from gutenberg will effect everywhere in this plugin.
                    </p>
                </div>
            </div>

        </div>

        <div class="ui cards settings_row">

            <div class="card">
                <div class="content">
                    <div class="description d-flex justify-content-between align-items-center">
                        <h3 class="m-0">
                            Elementor Widget Support
                            <span class="ui icon button p-0 m-0" data-tooltip="Enable this feature for elemetor widget support" data-position="right center" data-inverted="">
                                <i class="fas fa-info-circle"></i>
                            </span>
                        </h3>
                        <div class="ui toggle checkbox m-0">
                            <input type="checkbox" <?php echo $option_values['elementor_support'] ?> name="elementor_support" id="elementor_support">
                            <label class="m-0" for="elementor_support"></label>
                        </div>
                    </div>
                </div>
                <div class="settings_desc <?php echo $option_values['elementor_support'] == 'checked' ? '' : 'transition hidden' ?>">
                    <p>
                        Enabling this feature will enable a custom elementor widget in elementor basic area.
                        By doing this you will be able to choose previously created table in elementor widget.
                    </p>
                </div>
            </div>

        </div>


<?php
    }

    public static function get_option_values() {
        $options_values = [
            'asynchronous_loading' => get_option('asynchronous_loading') ? 'checked' : '',
            'gutenberg_support' => get_option('gutenberg_support') ? 'checked' : '',
            'elementor_support' => get_option('elementor_support') ? 'checked' : '',
        ];
        return $options_values;
    }
}
