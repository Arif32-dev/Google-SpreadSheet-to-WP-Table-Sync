jQuery(document).ready(function ($) {

    class Elementor_table_global {

        set_elementor_table(selector) {
            $('.gswpts_tables_container #create_tables').DataTable();
        }

        table_obj(table_settings, dom) {

            let table_object = {
                dom: dom,
                "order": [],
                "responsive": true,
                "lengthMenu": [
                    [1, 5, 10, 15, 25, 50, 100, -1],
                    [1, 5, 10, 15, 25, 50, 100, "All"]
                ],
                "pageLength": parseInt(table_settings.default_rows_per_page),
                "lengthChange": true,
                "ordering": table_settings.allow_sorting == 'true' ? true : false,
                "destroy": true,
                "scrollX": true
            }
            return table_object;
        }
    }
    window.gswpts_elementor_global = new Elementor_table_global()
});

