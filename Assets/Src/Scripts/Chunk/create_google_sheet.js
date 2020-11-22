jQuery(document).ready(function ($) {
    class Google_Sheets_Creation {
        constructor() {
            this.sheet_form = $('#gswpts_create_table');
            this.sheet_container = $('#spreadsheet_container');
            this.sheet_details = $('#sheet_details');
            this.events();
        }

        events() {
            this.sheet_form.on('submit', (e) => {
                this.handle_submit(e);
            })
        }

        handle_submit(e) {

            e.preventDefault();

            let submit_button = $(e.currentTarget).find('button');
            let table_name = $(e.currentTarget).find('input[name=table_name]');
            let form_data = $(e.currentTarget).serialize();

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
                        let html = `
                            Saving Table &nbsp;
                            <div class="ui active mini inline loader"></div>
                        `
                        this.btn_changer(submit_button, html, 'save')
                        // this.sheet_container.html(this.html_loader)
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
                <div class="ui card" style="width: 60%; min-width: 400px;">
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
                            <div class="col-12 d-flex align-items-center justify-content-start mb-3">
                                <h4 class="m-0">Author Mail: </h4>
                                <h5 class="m-0 ml-2">${res.sheet_data.author_info[0].email.$t}</h5>
                            </div>
                            <div class="col-12 d-flex align-items-center justify-content-start">
                                <h4 class="m-0">Sheet Shortcode: </h4>
                                <h5 class="m-0 ml-2">
                                    <div class="ui action input">
                                        <input type="text" class="copyInput" value="">
                                        <button type="button" name="copyToken" value="copy" class="copyToken ui right icon button">
                                            <i class="clipboard icon"></i>
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
    }
    new Google_Sheets_Creation;
})