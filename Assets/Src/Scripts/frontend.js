jQuery(document).ready(function ($) {
    class Data_Tables_Frontend {
        constructor() {
            this.frontend_table = $('#create_tables');
            this.events();
        }
        events() {
            this.show_manage_tables();
        }
        show_manage_tables() {
            this.frontend_table.DataTable({
                "order": []
            });
        }
    }
    new Data_Tables_Frontend;
})