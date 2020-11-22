jQuery(document).ready(function ($) {
    class Google_Sheets_Creation {
        constructor() {
            this.sheet_form = $('#gswpts_create_table');
            this.sheet_container = $('#spreadsheet_container');
            this.sheet_details = $('#sheet_details');
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
                        this.sheet_container.html(JSON.parse(res).output)
                    }

                    if (JSON.parse(res).response_type == 'saved') {
                        this.btn_changer(submit_button, 'Table Saved', 'saved');
                        this.call_alert('Successfull &#128077;', JSON.parse(res).output, 'success', 3)
                        this.show_shortcode(JSON.parse(res).id);
                        this.sheet_url = JSON.parse(res).sheet_url;
                        this.show_create_btn();
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

        btn_changer(submit_button, text, reqType) {
            submit_button.html(`
                            ${text}
                        `);

            submit_button.attr('req-type', reqType)
        }

        call_alert(title, description, type, time, pos = 'bottom-right') {
            $.suiAlert({
                title: title,
                description: description,
                type: type,
                time: time,
                position: pos,
            });
        }
        html_loader() {
            let loader = `
                <div class="ui segment" style="width: 100%; min-height: 400px; margin-left: -18px;">
                    <div class="ui active inverted dimmer">
                        <div class="ui large text loader">Loading</div>
                    </div>
                    <p></p>
                    <p></p>
                    <p></p>
                </div>
            `
            return loader;
        }
        sheet_details_html(res) {
            let details = `
                <div id="sheet_ui_card" class="ui card" style="width: 60%; min-width: 400px;">
                    <div class="content">
                        <div class="row">
                            <div class="col-12 d-flex align-items-center justify-content-start mb-3">
                                <h4 class="m-0">Sheet Name: </h4>
                                <h5 class="m-0 ml-2">${res.sheet_data.sheet_name}</h5>
                            </div>
                            <div class="col-12 d-flex align-items-center justify-content-start mb-3">
                                <h4 class="m-0">Total Rows: </h4>
                                <h5 class="m-0 ml-2">${res.sheet_data.total_rows}</h5>
                            </div>
                            <div class="col-12 d-flex align-items-center justify-content-start mb-3">
                                <h4 class="m-0">Total Result: </h4>
                                <h5 class="m-0 ml-2">${res.sheet_data.sheet_total_result}</h5>
                            </div>
                            <div class="col-12 d-flex align-items-center justify-content-start">
                                <h4 class="m-0">Author Mail: </h4>
                                <h5 class="m-0 ml-2">${res.sheet_data.author_info[0].email.$t}</h5>
                            </div>
                            <div id="shortcode_container" style="display: none !important;" class="col-12 d-flex mt-3 align-items-center justify-content-start transition hidden">
                                <h4 class="m-0">Table Shortcode: </h4>
                                <h5 class="m-0 ml-2">
                                    <div class="ui action input">
                                        <input id="sortcode_value" type="text" class="copyInput" value="">
                                        <button id="sortcode_copy" type="button" name="copyToken" value="copy" class="copyToken ui right icon button">
                                            <i class="clone icon"></i>
                                        </button>
                                    </div>
                                </h5>
                            </div>
                        </div>
                    </div>
            </div>
            `
            return details;
        }

        show_shortcode(shortcode_id) {
            $('#shortcode_container').removeAttr("style");
            $('#shortcode_container').transition('scale');
            $('#sortcode_value').val(`[gswpts_table id=${shortcode_id}]`)
        }

        copy_shorcode(e) {
            let input = $(e.currentTarget).siblings('input')
            input.focus();
            input.select();
            document.execCommand("copy");
            this.call_alert('Copied &#128077;', 'Sortcode copied successfully', 'info', 2)
        }

        show_create_btn() {
            $('#create_button_container').addClass('mt-4');
            $('#create_button').transition('scale');
        }
        clear_fields() {
            this.sheet_form.find('input').val('');
            $('#sheet_ui_card').transition('scale');
            this.sheet_container.transition('scale');
            this.btn_changer(this.sheet_form.find('button'), 'Fetch Data', 'fetch');
            this.sheet_url = '';
        }
    }
    new Google_Sheets_Creation;
})