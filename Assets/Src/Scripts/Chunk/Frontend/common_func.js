
export default class Global_Table_Config {
    table_configuration(i, elem, table_name, table_settings) {

        let dom = `B<"filtering_input filtering_input_${i}"${table_settings.show_x_entries == 'true' ? 'l' : ''}${table_settings.search_bar == 'true' ? 'f' : ''}>rt<"bottom_options bottom_options_${i}"${table_settings.show_info_block == 'true' ? 'i' : ''}p>`;
        $(elem).find('#create_tables').DataTable(
            this.table_obj(table_name, table_settings, dom)
        );

        this.reveal_export_btns(elem, table_settings);

        this.swap_input_filter(i, table_settings);

        this.swap_bottom_options(i, table_settings);

    }

    table_obj(table_name, table_settings, dom) {

        let table_object = {
            dom: dom,
            buttons: [{
                text: 'JSON { }',
                className: 'ui inverted yellow button export_btns json_btn',
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
                className: 'ui inverted red button export_btns pdf_btn',
                title: `${table_name}`
            },
            {
                text: 'CSV &nbsp; <i class="fas fa-file-csv"></i>',
                extend: 'csv',
                className: 'ui inverted green button export_btns csv_btn',
                title: `${table_name}`
            },
            {
                text: 'Excel &nbsp; <i class="fas fa-file-excel"></i>',
                extend: 'excel',
                className: 'ui inverted green button export_btns excel_btn',
                title: `${table_name}`
            },
            {
                text: 'Print &nbsp; <i class="fas fa-print"></i>',
                extend: 'print',
                className: 'ui inverted secondary button export_btns print_btn',
                title: `${table_name}`
            },
            {
                text: 'Copy &nbsp; <i class="fas fa-copy"></i>',
                extend: 'copy',
                className: 'ui inverted violet button export_btns copy_btn',
                title: `${table_name}`
            }

            ],

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

    reveal_export_btns(elem, table_settings) {
        let export_btns = table_settings.table_export;
        if (export_btns) {
            export_btns.forEach(btn => {
                $(elem).find('.' + btn + '_btn').removeClass('export_btns');
            });
        }
    }

    swap_input_filter(i, table_settings) {
        /* If checkbox is checked then swap filter */
        if (table_settings.swap_filter_inputs == 'true') {
            $('.filtering_input_' + i + '').css('flex-direction', 'row-reverse');
            $('.filtering_input_' + i + ' > #create_tables_length').css({
                'margin-right': '0',
                'margin-left': 'auto'
            });
            $('.filtering_input_' + i + ' > #create_tables_filter').css({
                'margin-left': '0',
                'margin-right': 'auto',
            });
        } else {
            /* Set back to default position */
            $('.filtering_input_' + i + '').css('flex-direction', 'row');
            $('.filtering_input_' + i + ' > #create_tables_length').css({
                'margin-right': 'auto',
                'margin-left': '0'
            });
            $('.filtering_input_' + i + ' > #create_tables_filter').css({
                'margin-left': 'auto',
                'margin-right': '0',
            });
        }
    }

    swap_bottom_options(i, table_settings) {
        if (table_settings.swap_bottom_options == 'true') {
            $('.bottom_options_' + i + '').css('flex-direction', 'row-reverse');
            $('.bottom_options_' + i + ' > #create_tables_info').css({
                'margin-right': '0',
                'margin-left': 'auto'
            });
            $('.bottom_options_' + i + ' > #create_tables_paginate').css({
                'margin-left': '0',
                'margin-right': 'auto',
            });
        } else {
            $('.bottom_options_' + i + '').css('flex-direction', 'row');
            $('.bottom_options_' + i + ' > #create_tables_info').css({
                'margin-right': 'auto',
                'margin-left': '0'
            });
            $('.bottom_options_' + i + ' > #create_tables_paginate').css({
                'margin-left': 'auto',
                'margin-right': '0',
            });
        }
    }
}