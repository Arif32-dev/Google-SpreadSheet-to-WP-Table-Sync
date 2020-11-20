jQuery(document).ready(function ($) {
    class Google_Sheets_Creation {
        constructor() {
            this.sheet_form = $('#gswpts_create_table');
            this.events();
        }

        events() {
            this.sheet_form.on('submit', this.handle_submit)
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
                    submit_button.html(`
                        Fetch Data &nbsp;
                        <div class="ui active mini inline loader"></div>
                    `);
                },

                success: res => {
                    console.log(res);
                    if (JSON.parse(res).response_type == 'invalid_action' || JSON.parse(res).response_type == 'invalid_request') {
                        $.suiAlert({
                            title: 'Error &#128683;',
                            description: JSON.parse(res).output,
                            type: 'error',
                            time: '4',
                            position: 'bottom-right',
                        });

                        submit_button.html(`
                            Fetch Data
                        `);
                        submit_button.attr('req-type', 'fetch')

                    }

                    if (JSON.parse(res).response_type == 'empty_field') {

                        submit_button.html(`
                            Fetch Data
                        `);
                        submit_button.attr('req-type', 'fetch')

                        $.suiAlert({
                            title: 'Warning &#9888;&#65039;',
                            description: JSON.parse(res).output,
                            type: 'warning',
                            time: '3',
                            position: 'bottom-right',
                        });
                    }

                    if (JSON.parse(res).response_type == 'success') {
                        $('#spreadsheet_container').html(JSON.parse(res).output)
                    }
                },

                complete: (res) => {

                    if (JSON.parse(res.responseText).response_type == 'success') {
                        $('#create_tables').DataTable({
                            "order": []
                        });

                        submit_button.html(`
                            Save Table
                        `);

                        submit_button.attr('req-type', 'save')

                        let sheet_res = JSON.parse(res.responseText);

                        if (table_name.val() == "" || !table_name.val()) {
                            table_name.val(sheet_res.sheet_data.sheet_name)
                        }

                        setTimeout(() => {
                            $.suiAlert({
                                title: 'Successfull &#128077;',
                                description: '<b>Google Sheet data fetched successfully</b>',
                                type: 'success',
                                time: '3',
                                position: 'bottom-right',
                            });
                        }, 700);
                    }

                },

                error: err => {
                    submit_button.html(`
                            Fetch Data
                        `);

                    submit_button.attr('req-type', 'fetch')

                    $.suiAlert({
                        title: 'Error &#128683;',
                        description: "Something went wrong",
                        type: 'error',
                        time: '3',
                        position: 'bottom-right',
                    });
                }
            })
        }
    }
    new Google_Sheets_Creation;
    // const Google_Sheets_Creation = {
    //     sheet_form: $('#gswpts_create_table'),

    //     init: () => {
    //         app.events();
    //     },
    //     events: () => {
    //         app.userCreationForm.on('submit', app.form_submit);
    //         app.login_form.on('submit', app.user_login);
    //     },
    // }
})