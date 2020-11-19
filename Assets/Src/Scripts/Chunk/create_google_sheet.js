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
            let form_data = $(e.currentTarget).serialize();
            $.ajax({
                url: file_url.admin_ajax,
                data: {
                    action: 'gswpts_sheet_create',
                    form_data: form_data
                },
                type: 'post',
                success: res => {
                    console.log(JSON.parse(res))
                    $('#spreadsheet_container').html(JSON.parse(res).output)
                },
                complete: () => {
                    $('#create_tables').DataTable({
                        "order": []
                    });
                },
                error: err => {
                    alert('Wrong');
                }
            })
        }
    }
    new Google_Sheets_Creation;
})