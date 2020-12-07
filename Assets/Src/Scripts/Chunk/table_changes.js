import Base_Class from './../Base/base_class';

jQuery(document).ready(function ($) {
    class Table_Changes extends Base_Class {

        constructor() {
            super();
            this.table_settings = $('.tables_settings');
            this.settings = $('#show_title, #rows_per_page, #info_block, #responsive, #show_entries, #sorting, #search_table, #table_exporting')
            this.events();
        }

        events() {
            this.table_settings.on('click', (e) => {
                this.change_btn_text(e);
            })
            this.add_select_box_style()

            this.settings.on('change', (e) => {
                this.update_table_by_changes(e)
            })
        }

        change_btn_text(e) {
            let btn_text_value = $(e.currentTarget).attr('data-btn-text');
            let btn_attr_value = $(e.currentTarget).attr('data-attr-text');
            $('#fetch_save_btn').html(btn_text_value);
            $('#fetch_save_btn').attr('req-type', btn_attr_value);
        }

        add_select_box_style() {
            $('#rows_per_page').dropdown();
            $('#table_exporting').dropdown();
        }

        update_table_by_changes(e) {

            if ($(e.currentTarget).attr('id') == 'table_exporting') {
                let export_btn = ['json', 'pdf', 'csv', 'excel', 'print', 'copy'];

                export_btn.forEach(btn => {
                    this.button_reavealer(e, btn)
                });
            }

            if ($(e.currentTarget).attr('id') == 'search_table') {
                $('#create_tables_filter').transition('scale');
            }

            if ($(e.currentTarget).attr('id') == 'sorting') {

                if ($(e.currentTarget).prop('checked') == false) {
                    $('#create_tables').DataTable().destroy();
                    $('#create_tables').DataTable({
                        dom: 'B<"#filtering_input"lf>rt<"#botton_options"ip>',
                        buttons: [{
                            text: 'JSON { }',
                            className: 'ui inverted yellow button transition hidden json_btn',
                            action: function (e, dt, button, config) {
                                var data = dt.buttons.exportData();

                                $.fn.dataTable.fileSave(
                                    new Blob([JSON.stringify(data)]),
                                    `${$('.edit_table_name').siblings('input[name=table_name]').val()}.json`
                                );
                            }
                        },
                        {
                            text: 'PDF &nbsp;<i class="fas fa-file-pdf"></i>',
                            extend: 'pdf',
                            className: 'ui inverted red button transition hidden pdf_btn',
                            title: `${$('.edit_table_name').siblings('input[name=table_name]').val()}`
                        },
                        {
                            text: 'CSV &nbsp; <i class="fas fa-file-csv"></i>',
                            extend: 'csv',
                            className: 'ui inverted green button transition hidden csv_btn',
                            title: `${$('.edit_table_name').siblings('input[name=table_name]').val()}`
                        },
                        {
                            text: 'Excel &nbsp; <i class="fas fa-file-excel"></i>',
                            extend: 'excel',
                            className: 'ui inverted green button transition hidden excel_btn',
                            title: `${$('.edit_table_name').siblings('input[name=table_name]').val()}`
                        },
                        {
                            text: 'Print &nbsp; <i class="fas fa-print"></i>',
                            extend: 'print',
                            className: 'ui inverted secondary button transition hidden print_btn',
                            title: `${$('.edit_table_name').siblings('input[name=table_name]').val()}`
                        },
                        {
                            text: 'Copy &nbsp; <i class="fas fa-copy"></i>',
                            extend: 'copy',
                            className: 'ui inverted violet button transition hidden copy_btn',
                            title: `${$('.edit_table_name').siblings('input[name=table_name]').val()}`
                        }

                        ],
                        "order": [],
                        "responsive": true,
                        "lengthMenu": [
                            [1, 5, 10, 15, 25, 50, 100, -1],
                            [1, 5, 10, 15, 25, 50, 100, "All"]
                        ],
                        "pageLength": 10,
                        "ordering": false
                    });
                } else {
                    $('#create_tables').DataTable().destroy();
                    $('#create_tables').DataTable({
                        dom: 'B<"#filtering_input"lf>rt<"#botton_options"ip>',
                        buttons: [{
                            text: 'JSON { }',
                            className: 'ui inverted yellow button transition hidden json_btn',
                            action: function (e, dt, button, config) {
                                var data = dt.buttons.exportData();

                                $.fn.dataTable.fileSave(
                                    new Blob([JSON.stringify(data)]),
                                    `${$('.edit_table_name').siblings('input[name=table_name]').val()}.json`
                                );
                            }
                        },
                        {
                            text: 'PDF &nbsp;<i class="fas fa-file-pdf"></i>',
                            extend: 'pdf',
                            className: 'ui inverted red button transition hidden pdf_btn',
                            title: `${$('.edit_table_name').siblings('input[name=table_name]').val()}`
                        },
                        {
                            text: 'CSV &nbsp; <i class="fas fa-file-csv"></i>',
                            extend: 'csv',
                            className: 'ui inverted green button transition hidden csv_btn',
                            title: `${$('.edit_table_name').siblings('input[name=table_name]').val()}`
                        },
                        {
                            text: 'Excel &nbsp; <i class="fas fa-file-excel"></i>',
                            extend: 'excel',
                            className: 'ui inverted green button transition hidden excel_btn',
                            title: `${$('.edit_table_name').siblings('input[name=table_name]').val()}`
                        },
                        {
                            text: 'Print &nbsp; <i class="fas fa-print"></i>',
                            extend: 'print',
                            className: 'ui inverted secondary button transition hidden print_btn',
                            title: `${$('.edit_table_name').siblings('input[name=table_name]').val()}`
                        },
                        {
                            text: 'Copy &nbsp; <i class="fas fa-copy"></i>',
                            extend: 'copy',
                            className: 'ui inverted violet button transition hidden copy_btn',
                            title: `${$('.edit_table_name').siblings('input[name=table_name]').val()}`
                        }

                        ],
                        "order": [],
                        "responsive": true,
                        "lengthMenu": [
                            [1, 5, 10, 15, 25, 50, 100, -1],
                            [1, 5, 10, 15, 25, 50, 100, "All"]
                        ],
                        "pageLength": 10,
                        "ordering": true
                    });
                }
            }


        }

        button_reavealer(e, target) {
            if ($(e.currentTarget).val().includes(target)) {
                if ($('.' + target + '_btn').hasClass('hidden')) {
                    $('.' + target + '_btn').transition('scale');
                    return;
                }
            } else {
                if ($('.' + target + '_btn').hasClass('visible')) {
                    $('.' + target + '_btn').transition('scale');
                    return;
                }
            }
        }

    }
    new Table_Changes;
})