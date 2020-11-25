import Base_Class from './../Base/base_class';

jQuery(document).ready(function ($) {
    class Fetch_Sheet_Data extends Base_Class {
        constructor() {
            super();
            this.sheet_skeleton = $('.gswpts_sheet_details_skeleton');
            this.input_skeleton = $('.gswpts_input_skeleton');
            this.input_form_container = $('.gswpts_form_container');
            this.events();
        }
        events() {
            this.fetch_data_by_id();
        }
        fetch_data_by_id() {
            if (!this.get_slug_parameter('id')) {
                return;
            }
            $.ajax({
                url: file_url.admin_ajax,

                data: {
                    action: 'gswpts_sheet_fetch',
                    id: this.get_slug_parameter('id')
                },
                type: 'post',

                success: res => {

                    if (JSON.parse(res).response_type == 'invalid_action' || JSON.parse(res).response_type == 'invalid_request') {
                        this.input_skeleton.transition('scale');
                        this.sheet_skeleton.transition('scale');
                        this.sheet_container.html('');
                        this.call_alert('Error &#128683;', JSON.parse(res).output, 'error', 4)
                    }

                    if (JSON.parse(res).response_type == 'success') {
                        $('#create_button').removeAttr('disabled');
                        this.input_skeleton.transition('scale');

                        this.sheet_skeleton.transition('scale');
                        this.sheet_details.addClass('mt-5 p-3');
                        setTimeout(() => {
                            this.input_form_container.transition('scale');
                            this.input_form_container.find('input[name=sheet_url]').val(JSON.parse(res).table_data.sheet_url)
                            this.input_form_container.find('input[name=table_name]').val(JSON.parse(res).table_data.table_name)
                            this.sheet_details.html(this.sheet_details_html(JSON.parse(res)));
                            this.sheet_details.transition('scale');
                            this.show_shortcode(this.get_slug_parameter('id'));
                        }, 400);
                        this.sheet_container.html(JSON.parse(res).output)
                    }
                },

                error: err => {
                    this.call_alert('Error &#128683;', '<b>Something went wrong</b>', 'error', 3)
                    this.sheet_container.html('');
                },

                complete: (res) => {

                    if (JSON.parse(res.responseText).response_type == 'success') {
                        $('#create_tables').DataTable({
                            "order": []
                        });

                        setTimeout(() => {

                            this.call_alert('Successfull &#128077;', '<b>Google Sheet data fetched successfully</b>', 'success', 3)

                        }, 700);
                    }

                },
            })
        }
    }
    new Fetch_Sheet_Data;
})