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
            'gutenberg_rich_editing',
            'elementor_support',
            'elementor_rich_editing',
        ];

        foreach ($settings_options as $setting) {
            register_setting(
                'gswpts_general_setting',
                $setting
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
                            <span class="ui icon button p-0 m-0" data-tooltip="Enable this feature for gutenberg sortcode support" data-position="right center" data-inverted="">
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
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Corrupti omnis vitae aperiam ex.
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Corrupti omnis vitae aperiam ex.
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Corrupti omnis vitae aperiam ex.
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Corrupti omnis vitae aperiam ex.
                    </p>
                </div>
            </div>

        </div>

        <div class="ui cards settings_row">


            <div class="card">
                <div class="content">
                    <div class="description d-flex justify-content-between align-items-center">
                        <h3 class="m-0">
                            Gutenberg Support
                            <span class="ui icon button p-0 m-0" data-tooltip="Enable this feature for gutenberg sortcode support" data-position="right center" data-inverted="">
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
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Corrupti omnis vitae aperiam ex.
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Corrupti omnis vitae aperiam ex.
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Corrupti omnis vitae aperiam ex.
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Corrupti omnis vitae aperiam ex.
                    </p>
                </div>
            </div>

        </div>


        <div class="ui cards settings_row">

            <div class="card">
                <div class="content">
                    <div class="description d-flex justify-content-between align-items-center">
                        <h3 class="m-0">
                            Gutenberg Rich Table Editing
                            <span class="ui icon button p-0 m-0" data-tooltip="This feature will enable rich table editing in gutenberg" data-position="right center" data-inverted="">
                                <i class="fas fa-info-circle"></i>
                            </span>
                        </h3>
                        <div class="ui toggle checkbox m-0">
                            <input type="checkbox" <?php echo $option_values['gutenberg_rich_editing'] ?> name="gutenberg_rich_editing" id="gutenberg_rich_editing">
                            <label class="m-0" for="gutenberg_rich_editing"></label>
                        </div>
                    </div>
                </div>
                <div class="settings_desc <?php echo $option_values['gutenberg_rich_editing'] == 'checked' ? '' : 'transition hidden' ?>">
                    <p>
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Corrupti omnis vitae aperiam ex.
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Corrupti omnis vitae aperiam ex.
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Corrupti omnis vitae aperiam ex.
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Corrupti omnis vitae aperiam ex.
                    </p>
                </div>
            </div>

        </div>

        <div class="ui cards settings_row">

            <div class="card">
                <div class="content">
                    <div class="description d-flex justify-content-between align-items-center">
                        <h3 class="m-0">
                            Elementor Support
                            <span class="ui icon button p-0 m-0" data-tooltip="Enable this feature for elemetor sortcode type support" data-position="right center" data-inverted="">
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
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Corrupti omnis vitae aperiam ex.
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Corrupti omnis vitae aperiam ex.
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Corrupti omnis vitae aperiam ex.
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Corrupti omnis vitae aperiam ex.
                    </p>
                </div>
            </div>

        </div>

        <div class="ui cards settings_row">

            <div class="card">
                <div class="content">
                    <div class="description d-flex justify-content-between align-items-center">
                        <h3 class="m-0">
                            Elementor Rich Table Editing
                            <span class="ui icon button p-0 m-0" data-tooltip="This feature will enable rich table editing in elementor" data-position="right center" data-inverted="">
                                <i class="fas fa-info-circle"></i>
                            </span>
                        </h3>
                        <div class="ui toggle checkbox m-0">
                            <input type="checkbox" <?php echo $option_values['elementor_rich_editing'] ?> name="elementor_rich_editing" id="elementor_rich_editing">
                            <label class="m-0" for="elementor_rich_editing"></label>
                        </div>
                    </div>
                </div>
                <div class="settings_desc <?php echo $option_values['elementor_rich_editing'] == 'checked' ? '' : 'transition hidden' ?>">
                    <p>
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Corrupti omnis vitae aperiam ex.
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Corrupti omnis vitae aperiam ex.
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Corrupti omnis vitae aperiam ex.
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Corrupti omnis vitae aperiam ex.
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
            'gutenberg_rich_editing' => get_option('gutenberg_rich_editing') ? 'checked' : '',
            'elementor_support' => get_option('elementor_support') ? 'checked' : '',
            'elementor_rich_editing' => get_option('elementor_rich_editing') ? 'checked' : '',
        ];
        return $options_values;
    }
}
