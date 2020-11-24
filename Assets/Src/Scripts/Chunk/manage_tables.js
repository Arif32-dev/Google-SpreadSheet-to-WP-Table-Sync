import Base_Class from './../Base/base_class';

jQuery(document).ready(function ($) {
    class Manage_Tables extends Base_Class {
        constructor() {
            super();
            this.table_container = $('#gswpts_tables_container');
            this.events();
        }
        events() {
            this.fetch_data_by_page();
        }
        fetch_data_by_page() {
            if (!this.get_slug_parameter('page') || this.get_slug_parameter('page') !== 'gswpts-tables') {
                return;
            }
            $.ajax({
                url: file_url.admin_ajax,

                data: {
                    action: 'gswpts_table_fetch',
                    page_slug: this.get_slug_parameter('page')
                },
                type: 'post',

                success: res => {
                    console.log(res);
                    if (JSON.parse(res).response_type == 'invalid_action' || JSON.parse(res).response_type == 'invalid_request') {
                        this.table_container.html('');
                        this.call_alert('Error &#128683;', JSON.parse(res).output, 'error', 4)
                    }
                    if (JSON.parse(res).response_type == 'success') {
                        this.table_container.html(JSON.parse(res).output);
                    }
                },

                error: err => {
                    this.call_alert('Error &#128683;', '<b>Something went wrong</b>', 'error', 3)
                    this.sheet_container.html('');
                },

                complete: (res) => {

                    if (JSON.parse(res.responseText).response_type == 'success') {
                        $('#manage_tables').DataTable({
                            "columnDefs": [{
                                "targets": [0, 5],
                                "orderable": false,
                            }],
                            "order": []
                        });

                        setTimeout(() => {

                            this.call_alert('Successfull &#128077;', '<b>All SpreadSheet Tables Fetched Successfully</b>', 'success', 3)

                        }, 700);
                    }

                },
            })
        }
    }
    new Manage_Tables;
})
