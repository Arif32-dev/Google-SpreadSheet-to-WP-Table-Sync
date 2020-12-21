import Base_Class from '../Base/base_class';

jQuery(document).ready(function ($) {
    class General_settings extends Base_Class {
        constructor() {
            super($);
            this.settings_checkbox = $('.settings_row input[type=checkbox]');
            this.save_changes_btn = $('#save_changes');
            this.events();
        }
        events() {
            this.settings_checkbox.on('change', (e) => {
                this.show_settings_desc(e)
            })
        }

        show_settings_desc(e) {
            let desc_box = $(e.currentTarget).parents('.card').find('.settings_desc');
            desc_box.transition('fade down');
        }
    }
    new General_settings;
})
