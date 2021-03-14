jQuery(document).ready(function ($) {

    class Elementor_table_global {

        set_elementor_table(selector, table_settings, dom) {
            selector.DataTable(
                this.table_obj(table_settings, dom)
            );
        }

        table_obj(table_settings, dom) {

            let table_object = {
                dom: dom,
                "order": [],
                "responsive": true,
                "lengthMenu": [
                    [1, 5, 10, 15],
                    [1, 5, 10, 15]
                ],
                "pageLength": parseInt(table_settings.default_rows_per_page),
                "lengthChange": true,
                "ordering": table_settings.allow_sorting == 'true' ? true : false,
                "destroy": true,
                "scrollX": true
            }
            return table_object;
        }


        swap_input_filter(table_id, filter_state) {
            /* If checkbox is checked then swap filter */
            if (filter_state) {
                $('#' + table_id + ' #filtering_input').css('flex-direction', 'row-reverse');
                $('#' + table_id + ' #create_tables_length').css({
                    'margin-right': '0',
                    'margin-left': 'auto'
                });
                $('#' + table_id + ' #create_tables_filter').css({
                    'margin-left': '0',
                    'margin-right': 'auto',
                });
            } else {
                /* Set back to default position */
                $('#' + table_id + ' #filtering_input').css('flex-direction', 'row');
                $('#' + table_id + ' #create_tables_length').css({
                    'margin-right': 'auto',
                    'margin-left': '0'
                });
                $('#' + table_id + ' #create_tables_filter').css({
                    'margin-left': 'auto',
                    'margin-right': '0',
                });
            }
        }

        swap_bottom_options(table_id, bottom_state) {
            if (bottom_state) {
                $('#' + table_id + ' #bottom_options').css('flex-direction', 'row-reverse');
                $('#' + table_id + ' #create_tables_info').css({
                    'margin-right': '0',
                    'margin-left': 'auto'
                });
                $('#' + table_id + ' #create_tables_paginate').css({
                    'margin-left': '0',
                    'margin-right': 'auto',
                });
            } else {
                $('#' + table_id + ' #bottom_options').css('flex-direction', 'row');
                $('#' + table_id + ' #create_tables_info').css({
                    'margin-right': 'auto',
                    'margin-left': '0'
                });
                $('#' + table_id + ' #create_tables_paginate').css({
                    'margin-left': 'auto',
                    'margin-right': '0',
                });
            }
        }

    }
    window.gswpts_elementor_global = new Elementor_table_global()
});

