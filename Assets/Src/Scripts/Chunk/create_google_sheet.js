import Base_Class from './../Base/base_class';

jQuery(document).ready(function ($) {
    class Google_Sheets_Creation extends Base_Class {

        constructor() {
            super();
            this.sheet_url = '';
            this.events();
        }

        events() {
            this.sheet_form.on('submit', (e) => {
                this.handle_submit(e);
            })
            $(document).on('click', '#sortcode_copy', (e) => {
                this.copy_shorcode(e);
            })
            $(document).on('click', '#create_button', (e) => {
                this.clear_fields();
            })
        }

        handle_submit(e) {

            e.preventDefault();

            let submit_button = $(e.currentTarget).find('button');
            let table_name = $(e.currentTarget).find('input[name=table_name]');
            let form_data = $(e.currentTarget).serialize();

            if (this.sheet_url == $(e.currentTarget).find('input[name=sheet_url]').val()) {
                this.call_alert('Warning &#9888;&#65039;', "<b>This SpreadSheet already exists. Try new one</b>", 'warning', 3)
                return;
            }

            $.ajax({

                url: file_url.admin_ajax,

                data: {
                    action: 'gswpts_sheet_create',
                    form_data: form_data,
                    type: submit_button.attr('req-type')
                },

                type: 'post',

                beforeSend: () => {
                    if (submit_button.attr('req-type') == 'fetch') {
                        let html = `
                            Fetching Data &nbsp;
                            <div class="ui active mini inline loader"></div>
                        `
                        this.btn_changer(submit_button, html, 'fetch')
                        this.sheet_container.html(this.html_loader)
                    } else {
                        if (submit_button.attr('req-type') == 'saved') {
                            $('#shortcode_container').transition('scale');
                        }
                        let html = `
                                Saving Table &nbsp;
                                <div class="ui active mini inline loader"></div>
                            `
                        this.btn_changer(submit_button, html, 'fetch')
                    }
                },

                success: res => {
                    console.log(res);
                    if (JSON.parse(res).response_type == 'invalid_action' || JSON.parse(res).response_type == 'invalid_request') {
                        this.call_alert('Error &#128683;', JSON.parse(res).output, 'error', 4)
                        this.btn_changer(submit_button, 'Fetch Data', 'fetch')
                        this.clear_fields();
                        this.sheet_container.html('');
                    }

                    if (JSON.parse(res).response_type == 'empty_field') {
                        this.call_alert('Warning &#9888;&#65039;', JSON.parse(res).output, 'warning', 3)
                        this.btn_changer(submit_button, 'Fetch Data', 'fetch')
                        this.sheet_container.html('');
                    }

                    if (JSON.parse(res).response_type == 'success') {
                        this.sheet_details.addClass('mt-5 p-3');
                        this.sheet_details.html(this.sheet_details_html(JSON.parse(res)));
                        this.sheet_details.transition('scale');
                        this.sheet_container.html(JSON.parse(res).output);
                    }

                    if (JSON.parse(res).response_type == 'saved') {
                        this.btn_changer(submit_button, 'Table Saved', 'saved');
                        this.call_alert('Successfull &#128077;', JSON.parse(res).output, 'success', 3)
                        let id = (Object.values(JSON.parse(res).id)[0]);
                        this.show_shortcode(id);
                        this.sheet_url = JSON.parse(res).sheet_url;
                        this.show_create_btn();
                        this.push_parameter(id);
                    }

                    if (JSON.parse(res).response_type == 'sheet_exists') {
                        this.call_alert('Warning &#9888;&#65039;', JSON.parse(res).output, 'warning', 3)
                        this.btn_changer(submit_button, 'Save Table', 'save');
                    }
                },

                complete: (res) => {

                    if (JSON.parse(res.responseText).response_type == 'success') {
                        $('#create_tables').DataTable({
                            "order": []
                        });

                        this.btn_changer(submit_button, 'Save Table', 'save')

                        let sheet_res = JSON.parse(res.responseText);

                        if (table_name.val() == "" || !table_name.val()) {
                            table_name.val(sheet_res.sheet_data.sheet_name)
                        }

                        setTimeout(() => {

                            this.call_alert('Successfull &#128077;', '<b>Google Sheet data fetched successfully</b>', 'success', 3)

                        }, 700);
                    }

                },

                error: err => {
                    this.call_alert('Error &#128683;', '<b>Something went wrong</b>', 'error', 3)
                    this.btn_changer(submit_button, 'Fetch Data', 'fetch')
                    this.sheet_container.html('');
                }
            })
        }

        copy_shorcode(e) {
            let input = $(e.currentTarget).siblings('input')
            input.focus();
            input.select();
            document.execCommand("copy");
            this.call_alert('Copied &#128077;', 'Sortcode copied successfully', 'info', 2)
        }

        show_create_btn() {
            if (!$('#create_button_container').hasClass('mt-4') || !$('#create_button').hasClass('visible')) {
                $('#create_button_container').addClass('mt-4');
                $('#create_button').transition('scale');
            }
        }

        clear_fields() {
            this.sheet_form.find('input[name=sheet_url], input[name=table_name]').val('');
            $('#sheet_ui_card').transition('scale');
            $('#create_tables_wrapper').transition('scale');
            this.btn_changer(this.sheet_form.find('button'), 'Fetch Data', 'fetch');
            this.sheet_url = '';
            setTimeout(() => {
                this.sheet_details.transition('scale');
                $('#sheet_ui_card').remove();
                $('#create_tables_wrapper').remove();
            }, 300);
        }
        push_parameter(id) {
            const url = new URL(window.location);
            url.searchParams.set('id', id);
            window.history.pushState({}, '', url);
        }
    }
    new Google_Sheets_Creation;
})