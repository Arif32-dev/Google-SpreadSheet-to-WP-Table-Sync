import Base_Class from '../Base/base_class';

jQuery(document).ready(function ($) {
    class UD_tables extends Base_Class {
        constructor() {
            super();
            this.events();
        }
        events() {
            $(document).on('click', '.gswpts_table_delete_btn', (e) => {
                alert('hello');
            })
            $(document).on('click', '.gswpts_edit_table', (e) => {
                this.edit_table_name(e)
            })
            $(document).on('click', '.gswpts_popup_edit', (e) => {
                this.edit_tag_value(e)
            })
        }
        delete_table() {

            $.ajax({
                url: file_url.admin_ajax,

                data: {
                    action: 'gswpts_ud_table',
                    id: null
                },
                type: 'post',

                success: res => {
                    console.log(JSON.parse(res));
                },

                error: err => {
                    this.call_alert('Error &#128683;', '<b>Something went wrong</b>', 'error', 3)
                },
            })
        }
        edit_table_name(e) {
            if ($('.gswpts_modal').hasClass('hidden')) {
                $('.gswpts_input_table_name').val(this.get_table_name(e));
                $('.gswpts_modal').transition('fade up');
                this.set_target_class(e)
            } else {
                $('.gswpts_input_table_name').val(this.get_table_name(e));
                this.set_target_class(e)
            }
        }
        get_table_name(e) {
            let table_name = $(e.currentTarget).siblings('a').text();
            return table_name;
        }
        set_target_class(e) {
            $('.table_name').removeClass('edit_tag')
            $(e.currentTarget).siblings('a').addClass('edit_tag');
        }
        edit_tag_value(e) {
            let input_value = $(e.currentTarget).siblings('input').val();
            $('.edit_tag').html(input_value);
            $(e.currentTarget).parent().parent().transition('fade up');
        }
    }
    new UD_tables;
})
