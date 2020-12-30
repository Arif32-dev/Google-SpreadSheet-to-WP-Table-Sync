import Base_Class from './../Base/base_class';

jQuery(document).ready(function ($) {
    class Table_Changes extends Base_Class {

        constructor() {
            super($);
            this.table_settings = $('.tables_settings');
            this.events();
        }

        events() {
            this.table_settings.on('click', (e) => {
                this.change_btn_text(e);
            })
            this.add_select_box_style()

            this.settings_field.on('change', (e) => {
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
            if ($('#rows_per_page').length) {
                $('#rows_per_page').dropdown();
            }
            if ($('#table_exporting').length) {
                $('#table_exporting').dropdown();
            }
        }

        update_table_by_changes(e) {

            let table_name = $('.edit_table_name').siblings('input[name=table_name]').val();
            let table_settings = this.table_settings_obj();

            if ($(e.currentTarget).attr('id') == 'table_exporting') {
                let export_btn = ['json', 'pdf', 'csv', 'excel', 'print', 'copy'];
                export_btn.forEach(btn => {
                    this.button_reavealer(e, btn)
                });
            }

            if ($(e.currentTarget).attr('id') == 'show_title' || 'responsive' || 'search_table' || 'rows_per_page' || 'sorting' || 'show_entries' || 'info_block') {
                let dom = `B<"#filtering_input"${table_settings.showXEntries ? 'l' : ''}${table_settings.searchBar ? 'f' : ''}>rt<"#bottom_options"${table_settings.showInfoBlock ? 'i' : ''}p>`;
                this.export_buttons_row_revealer(table_settings.tableExport);
                this.table_changer(table_name, table_settings, dom)
                this.swap_filter_inputs(table_settings.swapFilterInputs);
                this.swap_bottom_options(table_settings.swapBottomOptions);
            }

            /* Swaping Filter Inputs */
            if ($(e.currentTarget).attr('id') == 'swap_filter_inputs') {
                this.swap_filter_inputs($(e.currentTarget).prop('checked'));
            }

            /* Swaping bottom elemts */

            if ($(e.currentTarget).attr('id') == 'swap_bottom_options') {
                this.swap_bottom_options($(e.currentTarget).prop('checked'));
            }

        }

        /* Show the export buttons based on user selection */
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


        set_default() {
            let default_settings = this.default_settings();
            $.each(this.settings, function (index, value) {
                if ($(value).attr('id') == 'show_title') {
                    $(value).prop('checked', default_settings.table_title);
                }
                if ($(value).attr('id') == 'responsive') {
                    $(value).prop('checked', default_settings.responsiveTable);
                }
                if ($(value).attr('id') == 'search_table') {
                    $(value).prop('checked', default_settings.searchBar);
                }
                if ($(value).attr('id') == 'show_entries') {
                    $(value).prop('checked', default_settings.showXEntries);
                }
                if ($(value).attr('id') == 'info_block') {
                    $(value).prop('checked', default_settings.showInfoBlock);
                }
                if ($(value).attr('id') == 'swap_filter_inputs') {
                    $(value).prop('checked', default_settings.swapFilterInputs);
                }
                if ($(value).attr('id') == 'swap_bottom_options') {
                    $(value).prop('checked', default_settings.swapBottomOptions);
                }
            });
        }

    }

    new Table_Changes;
})