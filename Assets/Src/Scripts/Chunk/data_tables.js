jQuery(document).ready(function($) {
    class Data_Tables {
        constructor() {
            this.mange_tables = $('#manage_tables');
            this.create_table = $('#create_tables');
            this.events();
        }
        events() {
            this.show_manage_tables();
        }
        show_manage_tables() {
            this.mange_tables.DataTable({
                "columnDefs": [{
                    "targets": [0, 5],
                    "orderable": false,
                }],
                "order": []
            });
        }
    }
    new Data_Tables;
})