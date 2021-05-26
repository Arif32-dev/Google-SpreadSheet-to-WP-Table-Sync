<?php
    namespace GSWPTS_PRO\Includes\Classes\Elementor\Template;

    class TemplateContent {
    public function render_template_js() {?>

<# jQuery(document).ready(function($) { $.ajax({ type: "post" , url: "<?php echo admin_url('admin-ajax.php') ?>" , data:
    { action: 'gswpts_sheet_fetch' , id: settings.choose_table }, success: (res)=> {
    let sheet_container =
    $($('#elementor-preview-iframe')[0].contentWindow.document.getElementById(settings.choose_table).querySelector("#spreadsheet_container"));
    let response = JSON.parse(res);
    console.log(response);
    if (response.response_type == 'invalid_action' || response.response_type == 'invalid_request') {

    sheet_container.html(`
    <div class="gswpts_create_table_container" style="margin-right: 0px;">
        <div class="block_initializer">
            <div class="ui green message" style="width:70%; margin: 0 auto;text-align: center; font-weight: bold;">
                {{{response.output}}}</div>
        </div>
    </div>
    `);

    return;
    }
    if (response.response_type == 'success') {
    sheet_container.html(response.output);
    return;
    }
    },
    complete: (res) => {
    if(!res) return;
    let table_settings = JSON.parse(JSON.parse(res.responseText).table_data.table_settings);
    let table_name = JSON.parse(res.responseText).table_data.table_name;
    let dom = `<"filtering_input"${table_settings.show_x_entries=='true' ? 'l' : '' }${table_settings.search_bar=='true'
        ? 'f' : '' }>rt<"bottom_options"${table_settings.show_info_block=='true' ? 'i' : '' }p>`;
            let swap_filter_inputs = table_settings.swap_filter_inputs == 'true' ? true : false;
            let swap_bottom_options = table_settings.swap_bottom_options == 'true' ? true : false;

            let table =
            $($('#elementor-preview-iframe')[0].contentWindow.document.getElementById(settings.choose_table).querySelector('#create_tables'));

            table.DataTable({
            dom: dom,
            "order": [],
            "responsive": true,

            "lengthMenu": [
            [1, 5, 10, 15, 25, 50, 100, -1],
            [1, 5, 10, 15, 25, 50, 100, "All"],
            ],
            "pageLength": parseInt(table_settings.default_rows_per_page),
            "lengthChange": true,
            "ordering": table_settings.allow_sorting == 'true' ? true : false,
            "destroy": true,
            "scrollX": true,
            "scrollY": table_settings.vertical_scroll ? `${table_settings.vertical_scroll}px`: null
            });

            },
            error: (err) => {
            console.log(err);
            alert('Table data fetching could not be completed');
            let sheet_container =
            $($('#elementor-preview-iframe')[0].contentWindow.document.getElementById(settings.choose_table).querySelector("#spreadsheet_container"));
            sheet_container.html('');
            }
            });
            })
            #>
            <?php }

                    public function table_container() {
                    ?>
            <div class="gswpts_create_table_container" id="{{{ settings.choose_table }}}"
                class="col-12 d-flex justify-content-center align-content-center p-relative p-0 position-relative">
                <div id="spreadsheet_container">
                    <div class="ui segment gswpts_table_loader" style="z-index: -1;">
                        <div class="ui active inverted dimmer">
                            <div class="ui large text loader">Loading</div>
                        </div>
                        <p></p>
                        <p></p>
                        <p></p>
                    </div>
                </div>
            </div>
            <?php
                }

                    public function init_content() {
                    ?>
            <div class="gswpts_create_table_container" style="margin-right: 0px;">
                <div class="block_initializer">
                    <div class="ui green message"
                        style="width:70%; margin: 0 auto;text-align: center; font-weight: bold;">Choose any saved table
                        to load data</div>
                </div>
            </div>
            <?php
            }
            }