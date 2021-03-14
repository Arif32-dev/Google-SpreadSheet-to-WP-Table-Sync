export default class Base_Class {
    constructor($) {
        this.sheet_form = $('#gswpts_create_table');
        this.sheet_details = $('#sheet_details');
        this.sheet_container = $('#spreadsheet_container');
        this.settings_field = $('#show_title, #rows_per_page, #info_block, #show_entries, #swap_filter_inputs, #swap_bottom_options, #sorting, #search_table');
    }

    call_alert(title, description, type, time, pos = 'bottom-right') {
        $.suiAlert({
            title: title,
            description: description,
            type: type,
            time: time,
            position: pos,
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

    sheet_details_html(res) {
        let details = `
                <div id="sheet_ui_card" class="ui card" style="width: 60%; min-width: 400px;">
                    <div class="content">
                        <div class="row">
                            <div class="col-12 d-flex align-items-center justify-content-start mb-3">
                                <h4 class="m-0">Sheet Name: </h4>
                                <h5 class="m-0 ml-2">${res.sheet_data.sheet_name}</h5>
                            </div>
                            <div class="col-12 d-flex align-items-center justify-content-start mb-3">
                                <h4 class="m-0">Total Rows: </h4>
                                <h5 class="m-0 ml-2">${res.sheet_data.total_rows}</h5>
                            </div>
                            <div class="col-12 d-flex align-items-center justify-content-start mb-3">
                                <h4 class="m-0">Total Result: </h4>
                                <h5 class="m-0 ml-2">${res.sheet_data.sheet_total_result}</h5>
                            </div>
                            <div class="col-12 d-flex align-items-center justify-content-start">
                                <h4 class="m-0">Author Mail: </h4>
                                <h5 class="m-0 ml-2">${res.sheet_data.author_info[0].email.$t}</h5>
                            </div>
                            <div id="shortcode_container" style="display: none !important;" class="col-12 d-flex mt-3 align-items-center justify-content-start transition hidden">
                                <h4 class="m-0">Table Shortcode: </h4>
                                <h5 class="m-0 ml-2">
                                    <div class="ui action input">
                                        <input id="sortcode_value" type="text" class="copyInput" value="">
                                        <button id="sortcode_copy" type="button" name="copyToken" value="copy" class="copyToken ui right icon button">
                                            <i class="clone icon"></i>
                                        </button>
                                    </div>
                                </h5>
                            </div>
                        </div>
                    </div>
            </div>
            `
        return details;
    }

    show_shortcode(shortcode_id) {
        $('#shortcode_container').removeAttr("style");
        $('#shortcode_container').transition('scale');
        $('#sortcode_value').val(`[gswpts_table id=${shortcode_id}]`)
    }

    copy_shorcode(e) {
        let input = $(e.currentTarget).siblings('input');
        input.attr('type', 'text')
        input.focus();
        input.select();
        document.execCommand("copy");
        input.attr('type', 'hidden')
        this.call_alert('Copied &#128077;', '<b>Sortcode copied successfully</b>', 'info', 2)
    }

    btn_changer(submit_button, text, reqType = false) {
        submit_button.html(`
                            ${text}
                        `);

        if (reqType !== false) {
            submit_button.attr('req-type', reqType)
        }
    }

    get_slug_parameter(slug) {
        let url = new URL(window.location);
        let params = new URLSearchParams(url.search);
        let retrieve_param = params.get(slug);
        if (retrieve_param) {
            return retrieve_param
        } else {
            return false;
        }
    }
    export_to_json(e, target) {
        target.tableHTMLExport({ type: 'json', filename: 'sample.json' });
    }
    export_to_csv(e, target) {
        target.tableHTMLExport({ type: 'csv', filename: 'sample.csv' });
    }
    export_to_pdf(e, target) {
        target.tableHTMLExport({ type: 'pdf', filename: 'sample.pdf' });
    }

    table_settings_obj() {
        let settings = {
            table_title: $('#show_title').prop('checked'),
            defaultRowsPerPage: $('#rows_per_page').find('input[name=rows_per_page]').val() == 'all' ? -1 : $('#rows_per_page').find('input[name=rows_per_page]').val(),
            showInfoBlock: $('#info_block').prop('checked'),
            // responsiveTable: $('#responsive').prop('checked'),
            showXEntries: $('#show_entries').prop('checked'),
            swapFilterInputs: $('#swap_filter_inputs').prop('checked'),
            swapBottomOptions: $('#swap_bottom_options').prop('checked'),
            allowSorting: $('#sorting').prop('checked'),
            searchBar: $('#search_table').prop('checked'),
            // tableExport: $('#table_exporting').val() || null
        }
        return settings;
    }

    default_settings() {
        let default_settings = {
            table_title: false,
            defaultRowsPerPage: 10,
            showInfoBlock: true,
            responsiveTable: false,
            showXEntries: true,
            swapFilterInputs: false,
            swapBottomOptions: false,
            allowSorting: true,
            searchBar: true,
            // tableExport: null
        }
        return default_settings;
    }

    table_changer(table_name, table_settings, dom) {
        $('#create_tables').DataTable(
            this.table_object(
                table_name,
                table_settings.defaultRowsPerPage,
                table_settings.allowSorting,
                dom
            )
        );
    }

    swap_filter_inputs(state) {
        /* If checkbox is checked then swap filter */
        if (state) {
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
        let pagination_menu = $('#bottom_options .pagination.menu')
        let style = {
            flex_direction: 'row-reverse',
            table_info_style: {
                margin_right: 0,
                margin_left: 'auto'
            },
            table_paginate_style: {
                margin_right: 'auto',
                margin_left: 0
            }
        }
        if (state) {

            this.bottom_option_style(style)

            if (pagination_menu.children().length > 5) {
                this.overflow_menu_style()
            }
            else {
                this.bottom_option_style(style)
            }

        } else {

            if (pagination_menu.children().length > 5) {
                this.overflow_menu_style()
            }
            else {

                style['flex_direction'] = 'row'

                style.table_info_style['margin_left'] = 0
                style.table_info_style['margin_right'] = 'auto'

                style.table_paginate_style['margin_left'] = 'auto'
                style.table_paginate_style['margin_right'] = 0

                this.bottom_option_style(style)

            }
        }
    }

    overflow_menu_style() {
        $('#bottom_options').css('flex-direction', 'column');
        $('#create_tables_info').css({
            'margin': '5px auto',
        });
        $('#create_tables_paginate').css({
            'margin': '5px auto',
        });
    }

    bottom_option_style($arg) {
        $('#bottom_options').css('flex-direction', $arg['flex_direction']);
        $('#create_tables_info').css({
            'margin-left': $arg['table_info_style']['margin_left'],
            'margin-right': $arg['table_info_style']['margin_right']
        });
        $('#create_tables_paginate').css({
            'margin-left': $arg['table_paginate_style']['margin_left'],
            'margin-right': $arg['table_paginate_style']['margin_right'],
        });
    }

    export_buttons_row_revealer(export_btns) {
        export_btns.forEach(btn => {
            setTimeout(() => {
                this.export_button_revealer_by_other_input(btn)
            }, 300);
        });
    }

    export_button_revealer_by_other_input(btn) {
        if ($('.' + btn + '_btn').hasClass('hidden')) {
            $('.' + btn + '_btn').transition('scale');
        }
    }

    table_object(table_name, pageLength, ordering, dom) {
        let obj = {
            dom: dom,
            // buttons: [{
            //     text: 'JSON { }',
            //     className: 'ui inverted yellow button transition hidden json_btn',
            //     action: function (e, dt, button, config) {
            //         var data = dt.buttons.exportData();

            //         $.fn.dataTable.fileSave(
            //             new Blob([JSON.stringify(data)]),
            //             `${table_name}.json`
            //         );
            //     }
            // },
            // {
            //     text: 'PDF &nbsp;<i class="fas fa-file-pdf"></i>',
            //     extend: 'pdf',
            //     className: 'ui inverted red button transition hidden pdf_btn',
            //     title: `${table_name}`
            // },
            // {
            //     text: 'CSV &nbsp; <i class="fas fa-file-csv"></i>',
            //     extend: 'csv',
            //     className: 'ui inverted green button transition hidden csv_btn',
            //     title: `${table_name}`
            // },
            // {
            //     text: 'Excel &nbsp; <i class="fas fa-file-excel"></i>',
            //     extend: 'excel',
            //     className: 'ui inverted green button transition hidden excel_btn',
            //     title: `${table_name}`
            // },
            // {
            //     text: 'Print &nbsp; <i class="fas fa-print"></i>',
            //     extend: 'print',
            //     className: 'ui inverted secondary button transition hidden print_btn',
            //     title: `${table_name}`
            // },
            // {
            //     text: 'Copy &nbsp; <i class="fas fa-copy"></i>',
            //     extend: 'copy',
            //     className: 'ui inverted violet button transition hidden copy_btn',
            //     title: `${table_name}`
            // }

            // ],
            "order": [],
            "responsive": true,
            // "lengthMenu": [
            //     [1, 5, 10, 15, 25, 50, 100, -1],
            //     [1, 5, 10, 15, 25, 50, 100, "All"]
            // ],
            "lengthMenu": [
                [1, 5, 10, 15],
                [1, 5, 10, 15]
            ],
            "pageLength": parseInt(pageLength),
            "lengthChange": true,
            "ordering": ordering,
            "destroy": true,
            "scrollX": true

        }
        return obj;
    }

    /* This function will reconfigure tables fields based on data */
    reconfigure_input_fields(settings) {
        $('#show_title').prop('checked', settings.table_title == 'true' ? true : false);
        $("#rows_per_page").dropdown('set selected', settings.default_rows_per_page == '-1' ? 'all' : settings.default_rows_per_page);
        $('#info_block').prop('checked', settings.show_info_block == 'true' ? true : false);
        // $('#responsive').prop('checked', settings.responsive_table == 'true' ? true : false);
        $('#show_entries').prop('checked', settings.show_x_entries == 'true' ? true : false);
        $('#swap_filter_inputs').prop('checked', settings.swap_filter_inputs == 'true' ? true : false);
        $('#swap_bottom_options').prop('checked', settings.swap_bottom_options == 'true' ? true : false);
        $('#sorting').prop('checked', settings.allow_sorting == 'true' ? true : false);
        $('#search_table').prop('checked', settings.search_bar == 'true' ? true : false);

        // if (settings.table_export != 'empty') {
        //     settings.table_export.forEach(export_type => {
        //         $('#table_exporting').dropdown('set selected', export_type)
        //     });
        // }

    }
}
