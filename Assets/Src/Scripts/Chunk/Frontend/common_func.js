export default class Global_Table_Config {
    table_configuration($, i, elem, table_name, table_settings) {

        let dom = `B<"filtering_input filtering_input_${i}"${table_settings.show_x_entries == 'true' ? 'l' : ''}${table_settings.search_bar == 'true' ? 'f' : ''}>rt<"bottom_options bottom_options_${i}"${table_settings.show_info_block == 'true' ? 'i' : ''}p>`;
        $(elem).find('#create_tables').DataTable(
            this.table_obj($, table_name, table_settings, dom)
        );

        this.reveal_export_btns($, elem, table_settings);

        this.swap_input_filter($, i, table_settings);

        this.swap_bottom_options($, i, table_settings);

    }

    table_obj($, table_name, table_settings, dom) {

        let table_object = {
            dom: dom,
            buttons: [{
                    text: `JSON &nbsp;<img src="${front_end_data.iconsURL.curlyBrackets}" />`,
                    className: 'ui inverted yellow button export_btns json_btn',
                    action: function(e, dt, button, config) {
                        var data = dt.buttons.exportData();
                        $.fn.dataTable.fileSave(
                            new Blob([JSON.stringify(data)]),
                            `${table_name}.json`
                        );
                    }
                },
                {
                    text: `PDF &nbsp;<img src="${front_end_data.iconsURL.filePdf}" />`,
                    extend: 'pdf',
                    className: 'ui inverted red button export_btns pdf_btn',
                    title: `${table_name}`
                },
                {
                    text: `CSV &nbsp;<img src="${front_end_data.iconsURL.fileCSV}" />`,
                    extend: 'csv',
                    className: 'ui inverted green button export_btns csv_btn',
                    title: `${table_name}`
                },
                {
                    text: `Excel &nbsp;<img src="${front_end_data.iconsURL.fileExcel}" />`,
                    extend: 'excel',
                    className: 'ui inverted green button export_btns excel_btn',
                    title: `${table_name}`
                },
                {
                    text: `Print &nbsp;<img src="${front_end_data.iconsURL.printIcon}" />`,
                    extend: 'print',
                    className: 'ui inverted secondary button export_btns print_btn',
                    title: `${table_name}`
                },
                {
                    text: `Copy &nbsp;<img src="${front_end_data.iconsURL.copySolid}" />`,
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

    reveal_export_btns($, elem, table_settings) {
        if (!table_settings) return;

        let export_btns = table_settings.table_export;

        if (export_btns != 'empty' && export_btns) {
            export_btns.forEach(btn => {
                $(elem).find('.' + btn + '_btn').removeClass('export_btns');
            });
        }
    }

    swap_input_filter($, i, table_settings) {
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

    swap_bottom_options($, i, table_settings) {

        let pagination_menu = $('.bottom_options_' + i + ' .pagination.menu')


        let style = {
            flex_direction: 'row-reverse',
            table_info_style: {
                margin_left: 'auto',
                margin_right: 0
            },
            table_paginate_style: {
                margin_left: 0,
                margin_right: 'auto'
            }
        }

        if (table_settings.swap_bottom_options == 'true') {

            if (pagination_menu.children().length > 5) {
                this.overflow_menu_style($)
            } else {
                this.bottom_option_style($, style, i)
            }

        } else {

            if (pagination_menu.children().length > 5) {
                this.overflow_menu_style($, i)
            } else {

                style['flex_direction'] = 'row'

                style.table_info_style['margin_left'] = 0
                style.table_info_style['margin_right'] = 'auto'

                style.table_paginate_style['margin_left'] = 'auto'
                style.table_paginate_style['margin_right'] = 0

                this.bottom_option_style($, style, i)

            }
        }
    }

    overflow_menu_style($, i) {
        $('.bottom_options_' + i + '').css('flex-direction', 'column');
        $('.bottom_options_' + i + ' > #create_tables_info').css({
            'margin': '5px auto',
        });
        $('.bottom_options_' + i + ' > #create_tables_paginate').css({
            'margin': '5px auto',
        });
    }

    bottom_option_style($, $arg, i) {

        $('.bottom_options_' + i + '').css('flex-direction', $arg['flex_direction']);
        $('.bottom_options_' + i + ' > #create_tables_info').css({
            'margin-left': $arg['table_info_style']['margin_left'],
            'margin-right': $arg['table_info_style']['margin_right']
        });
        $('.bottom_options_' + i + ' > #create_tables_paginate').css({
            'margin-left': $arg['table_paginate_style']['margin_left'],
            'margin-right': $arg['table_paginate_style']['margin_right'],
        });

    }

    html_loader() {
        let loader = `
               <div class="ui segment gswpts_table_loader">
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
}