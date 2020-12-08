import Base_Class from './../Base/base_class';

jQuery(document).ready(function ($) {
    class Google_Sheets_Creation extends Base_Class {

        constructor() {
            super($);
            this.fetch_and_save_button = $('#fetch_save_btn')
            this.sheet_url = '';
            this.dropdown_select = $('#table_type')
            this.events();
            $('#table_type').dropdown();
        }

        events() {
            this.dropdown_select.on('change', (e) => {
                this.show_fetch_btn(e);
                this.show_hidden_input(e)
            })
            this.fetch_and_save_button.on('click', (e) => {
                this.handle_submit(e);
            })
            $(document).on('click', '#sortcode_copy', (e) => {
                this.copy_shorcode(e);
            })
            $(document).on('click', '#create_button', (e) => {
                this.clear_fields();
            })
            $('.edit_table_name').on('click', (e) => {
                this.edit_table_name(e)
            })
        }
        edit_table_name(e) {
            $(e.currentTarget).siblings('input').select();
        }

        show_fetch_btn(e) {
            if ($(e.currentTarget).find('input[name=source_type]').val()) {
                if ($('#fetch_save_btn').hasClass('hidden')) {
                    $('#fetch_save_btn').transition('scale');
                }
            }
        }

        show_hidden_input(e) {
            if ($(e.currentTarget).find('input[name=source_type]').val() == 'spreadsheet') {
                this.show_file_input()
                if ($('.browse_input').hasClass('visible')) {
                    $('.browse_input').transition('scale');
                }
            }
            if ($(e.currentTarget).find('input[name=source_type]').val() == 'csv') {
                this.show_file_input()
                this.show_browser_input()
            }
        }

        show_file_input() {
            if ($('.file_input').hasClass('hidden')) {
                $('.file_input').transition('scale');
            }
        }

        show_browser_input() {
            if ($('.browse_input').hasClass('hidden')) {
                $('.browse_input').transition('scale');
            }
        }

        handle_submit(e) {

            e.preventDefault();
            let submit_button = $(e.currentTarget);
            let table_name = $('#table_name').val();
            let form_data = this.sheet_form.serialize();
            if (this.sheet_form.find('input[name=file_input]').val() == "") {
                this.call_alert('Warning &#9888;&#65039;', "<b>Form field is empty. Please fill out the field</b>", 'warning', 3)
                return;
            }

            if (this.sheet_url == this.sheet_form.find('input[name=file_input]').val()) {
                this.call_alert('Warning &#9888;&#65039;', "<b>This SpreadSheet already exists. Try new one</b>", 'warning', 3)
                return;
            }


            $.ajax({

                url: file_url.admin_ajax,

                data: {
                    action: 'gswpts_sheet_create',
                    form_data: form_data,
                    table_name: table_name,
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
                        this.btn_changer(submit_button, html, 'save')

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
                        this.sheet_details.addClass('mt-4 p-0');
                        this.sheet_details.html(this.sheet_details_html(JSON.parse(res)));
                        this.sheet_details.transition('scale');
                        this.sheet_container.parent().removeClass('mt-4').addClass('mt-3');
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
                        let table_name = $('#table_name').val();
                        $('#create_tables').DataTable({
                            dom: 'B<"#filtering_input"lf>rt<"#bottom_options"ip>',
                            buttons: [{
                                text: 'JSON { }',
                                className: 'ui inverted yellow button',
                                action: function (e, dt, button, config) {
                                    var data = dt.buttons.exportData();

                                    $.fn.dataTable.fileSave(
                                        new Blob([JSON.stringify(data)]),
                                        `${table_name}.json`
                                    );
                                }
                            },
                            {
                                text: 'PDF &nbsp;<i class="fas fa-file-pdf"></i>',
                                extend: 'pdf',
                                className: 'ui inverted red button',
                                title: `${table_name}`
                            },
                            {
                                text: 'CSV &nbsp; <i class="fas fa-file-csv"></i>',
                                extend: 'csv',
                                className: 'ui inverted green button',
                                title: `${table_name}`
                            },
                            {
                                text: 'Excel &nbsp; <i class="fas fa-file-excel"></i>',
                                extend: 'excel',
                                className: 'ui inverted green button',
                                title: `${table_name}`
                            },
                            {
                                text: 'Print &nbsp; <i class="fas fa-print"></i>',
                                extend: 'print',
                                className: 'ui inverted secondary button',
                                title: `${table_name}`
                            },
                            {
                                text: 'Copy &nbsp; <i class="fas fa-copy"></i>',
                                extend: 'copy',
                                className: 'ui inverted violet button',
                                title: `${table_name}`
                            }

                            ],
                            "order": [],
                            "responsive": true,
                            "lengthMenu": [
                                [1, 5, 10, 15, 25, 50, 100, -1],
                                [1, 5, 10, 15, 25, 50, 100, "All"]
                            ],
                            "pageLength": 10,
                        });

                        this.btn_changer(submit_button, 'Save Table', 'save')

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
            if (!$('#create_button').hasClass('visible')) {
                $('#create_button').transition('scale');
            }
        }

        clear_fields() {
            this.sheet_form.find('input[name=file_input]').val('');
            $('.edit_table_name').attr('disabled', false);
            $('#table_name').val('');
            $('#table_name').attr('disabled', false);
            $('#tab1').prop('checked', true)
            $('#sheet_ui_card').transition('scale');
            $('#create_tables_wrapper').transition('scale');
            this.btn_changer(this.fetch_and_save_button, 'Fetch Data', 'fetch');
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