
$(document).ready(function () {
    class Data_Tables_Frontend {
        constructor() {
            this.frontend_table = $('.gswpts_tables_container');
            this.events();
        }
        events() {
            this.get_frontend_table();
        }

        get_frontend_table() {
            $.each(this.frontend_table, function (i, elem) {
                let id = $(elem).attr('id');
                $.ajax({
                    url: file_url.admin_ajax,

                    data: {
                        action: 'gswpts_sheet_fetch',
                        id: id
                    },
                    type: 'post',

                    success: res => {
                        console.log(res)

                        if (JSON.parse(res).response_type == 'success') {

                            let table_settings = JSON.parse(JSON.parse(res).table_data.table_settings);

                            if (table_settings.table_title == 'true') {
                                $(elem).find('h3').html(JSON.parse(res).table_data.table_name);
                            }

                            $(elem).find('.gswpts_tables').html(JSON.parse(res).output);

                            let table_name = JSON.parse(res).table_data.table_name

                            let dom = `B<"#filtering_input"${table_settings.show_x_entries == 'true' ? 'l' : ''}${table_settings.search_bar == 'true' ? 'f' : ''}>rt<"#bottom_options"${table_settings.show_info_block == 'true' ? 'i' : ''}p>`;

                            $(elem).find('table').DataTable(
                                {
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
                                    "ordering": table_settings.allow_sorting,
                                    "destroy": true,
                                }
                            );


                            let export_btns = table_settings.table_export;
                            if (export_btns) {
                                export_btns.forEach(btn => {
                                    $('.' + btn + '_btn').removeClass('export_btns');
                                });
                            }



                        }
                    },

                    error: err => {
                        alert('Something went wrong')
                    },

                })
            });

        }

        table_changes(export_btns, swap_filter_inputs, swap_bottom_options) {
            if (export_btns) {
                export_btns.forEach(btn => {
                    this.button_reavealer(btn);
                });
            }
            // this.swap_filter_inputs(swap_filter_inputs);
            // this.swap_bottom_options(swap_bottom_options);
        }

        table_object(table_name, pageLength, ordering, dom) {
            let obj =
            {
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
                "scrollX": true,
                "lengthMenu": [
                    [1, 5, 10, 15, 25, 50, 100, -1],
                    [1, 5, 10, 15, 25, 50, 100, "All"]
                ],
                "pageLength": parseInt(pageLength),
                "lengthChange": true,
                "ordering": ordering,
                "destroy": true,

            }
            return obj;
        }

        button_reavealer(target) {
            $('.' + target + '_btn').removeClass('export_btns');
        }

        swap_filter_inputs(state) {
            /* If checkbox is checked then swap filter */
            if (state == 'true') {
                $('#filtering_input').css('flex-direction', 'row-reverse');
                $('#create_tables_length').css({
                    'margin-right': '0',
                    'margin-left': 'auto'
                });
                $('#create_tables_filter').css({
                    'margin-left': '0',
                    'margin-right': 'auto',
                });
            } else {
                /* Set back to default position */
                $('#filtering_input').css('flex-direction', 'row');
                $('#create_tables_length').css({
                    'margin-right': 'auto',
                    'margin-left': '0'
                });
                $('#create_tables_filter').css({
                    'margin-left': 'auto',
                    'margin-right': '0',
                });
            }
        }

        swap_bottom_options(state) {
            if (state == 'true') {
                $('#bottom_options').css('flex-direction', 'row-reverse');
                $('#create_tables_info').css({
                    'margin-right': '0',
                    'margin-left': 'auto'
                });
                $('#create_tables_paginate').css({
                    'margin-left': '0',
                    'margin-right': 'auto',
                });
            } else {
                $('#bottom_options').css('flex-direction', 'row');
                $('#create_tables_info').css({
                    'margin-right': 'auto',
                    'margin-left': '0'
                });
                $('#create_tables_paginate').css({
                    'margin-left': 'auto',
                    'margin-right': '0',
                });
            }
        }

    }
    new Data_Tables_Frontend;
})