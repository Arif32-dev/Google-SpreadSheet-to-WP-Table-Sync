import Base_Class from './../Base/base_class';

jQuery(document).ready(function($) {
    class Fetch_Sheet_Data extends Base_Class {
        constructor() {
            super($);
            this.events();
        }
        events() {
            this.fetch_data_by_id();
        }
        fetch_data_by_id() {
            if (!this.get_slug_parameter('id')) {
                return;
            }
            $.ajax({
                url: file_url.admin_ajax,

                data: {
                    action: 'gswpts_sheet_fetch',
                    id: this.get_slug_parameter('id')
                },
                type: 'post',

                success: res => {
                    if (JSON.parse(res).response_type == 'invalid_action' || JSON.parse(res).response_type == 'invalid_request') {
                        this.sheet_container.html('');
                        this.call_alert('Error &#128683;', JSON.parse(res).output, 'error', 4)
                    }

                    if (JSON.parse(res).response_type == 'success') {

                        this.sheet_details.addClass('mt-4 p-0');
                        setTimeout(() => {
                            $('.edit_table_name').siblings('input[name=table_name]').val(JSON.parse(res).table_data.table_name);
                            $('.edit_table_name').parent().transition('fade up');
                            $("#table_type").dropdown('set selected', JSON.parse(res).table_data.source_type);
                            this.sheet_form.find('input[name=file_input]').val(JSON.parse(res).table_data.source_url)
                            this.sheet_details.html(this.sheet_details_html(JSON.parse(res)));
                            this.sheet_details.transition('scale');
                            this.show_shortcode(this.get_slug_parameter('id'));
                        }, 400);

                        this.sheet_container.html(JSON.parse(res).output);

                    }
                },

                error: err => {
                    this.call_alert('Error &#128683;', '<b>Something went wrong</b>', 'error', 3)
                    this.sheet_container.html('');
                },

                complete: (res) => {

                    if (JSON.parse(res.responseText).response_type == 'success') {

                        let table_settings = JSON.parse(JSON.parse(res.responseText).table_data.table_settings)

                        let table_name = JSON.parse(res.responseText).table_data.table_name;
                        let dom = `B<"#filtering_input"${table_settings.show_x_entries == 'true' ? 'l' : ''}${table_settings.search_bar == 'true' ? 'f' : ''}>rt<"#bottom_options"${table_settings.show_info_block == 'true' ? 'i' : ''}p>`;

                        let swap_filter_inputs = table_settings.swap_filter_inputs == 'true' ? true : false;
                        let swap_bottom_options = table_settings.swap_bottom_options == 'true' ? true : false;

                        /* This will trigger the change event and its related functionality in table_changes.js  */
                        this.reconfigure_input_fields(JSON.parse(JSON.parse(res.responseText).table_data.table_settings));

                        setTimeout(() => {

                            $('#create_tables').DataTable(
                                this.table_object(
                                    table_name,
                                    table_settings.default_rows_per_page,
                                    table_settings.allow_sorting == 'true' ? true : false,
                                    dom
                                )
                            );

                            this.swap_filter_inputs(swap_filter_inputs);

                            this.swap_bottom_options(swap_bottom_options);

                            if (table_settings.table_export != 'empty') {
                                this.export_buttons_row_revealer(table_settings.table_export);
                            }

                            this.call_alert('Successfull &#128077;', '<b>Google Sheet data fetched successfully</b>', 'success', 3)

                        }, 700);
                    }

                },
            })
        }

    }
    new Fetch_Sheet_Data;
})