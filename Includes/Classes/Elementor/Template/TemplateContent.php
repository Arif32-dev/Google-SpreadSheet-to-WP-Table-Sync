<?php
namespace GSWPTS_PRO\Includes\Classes\Elementor\Template;

use GSWPTS_PRO\Includes\Classes\Elementor\Includes\TableSettings;

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

    let mainContainer =
    $($('#elementor-preview-iframe')[0].contentWindow.document.querySelector(`.gswpts_table_${settings.choose_table}`));

    mainContainer.append(`
    <div class="gswpts_table_settings" data-id="${settings.choose_table}">
        <button>Table Settings</button>
    </div>
    `);
    <!-- Append the settings modal -->
    mainContainer.append(settingsModal());
    <!-- Append the table data into the container -->
    sheet_container.html(response.output);
    openTableSettings();
    changeSettingsValues(mainContainer, JSON.parse(response.table_data.table_settings));
    return;
    }
    },
    complete: (res) => {
    if(!res) return;
    let table_settings = JSON.parse(JSON.parse(res.responseText).table_data.table_settings);
    let table_name = JSON.parse(res.responseText).table_data.table_name;
    let dom = `B<"filtering_input"${table_settings.show_x_entries=='true' ? 'l' : ''
        }${table_settings.search_bar=='true' ? 'f' : '' }>rt<"bottom_options"${table_settings.show_info_block=='true'
            ? 'i' : '' }p>`;
            let swap_filter_inputs = table_settings.swap_filter_inputs == 'true' ? true : false;
            let swap_bottom_options = table_settings.swap_bottom_options == 'true' ? true : false;

            let table =
            $($('#elementor-preview-iframe')[0].contentWindow.document.getElementById(settings.choose_table).querySelector('#create_tables'));

            table.DataTable({
            dom: dom,
            "order": [],
            "responsive": true,
            buttons: ['copy', 'csv'],

            "lengthMenu": [
            [1, 5, 10, 15, 25, 50, 100, -1],
            [1, 5, 10, 15, 25, 50, 100, "All"],
            ],
            "pageLength": parseInt(table_settings.default_rows_per_page),
            "lengthChange": true,
            "ordering": table_settings.allow_sorting == 'true' ? true : false,
            "destroy": true,
            "scrollX": true,
            "scrollY": table_settings.vertical_scroll != 'default' ? `${table_settings.vertical_scroll}px`: null
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

            <!-- Open settings popup -->
            function openTableSettings(){
            let settingsBtn =
            $($('#elementor-preview-iframe')[0].contentWindow.document.querySelector(".gswpts_table_settings"));
            let promoCloseBtn =
            $($('#elementor-preview-iframe')[0].contentWindow.document.querySelector(".large_promo_close"));
            $(settingsBtn).on('click', (e) => {
            let target = $(e.currentTarget);
            let tableID = target.attr("data-id");
            target.siblings('.modal_wrapper').addClass('active');
            })
            $(promoCloseBtn).on('click', (e) => {
            let target = $(e.currentTarget);
            target.parents('.modal_wrapper').removeClass('active');
            })
            }

            <!-- Settings modal -->
            function settingsModal(){

            <?php $settingsClass = new TableSettings();?>
            return `
            <div class="modal_wrapper">
                <div class="modal_container">

                    <div class="settings_container">
                        <span class="large_promo_close">
                            <?php require GSWPTS_BASE_PATH . 'Assets/Public/Images/promo-close.svg'?>
                        </span>
                        <div class="tabs">

                            <input type="radio" id="tab1" name="tab-control" checked>
                            <input type="radio" id="tab2" name="tab-control">
                            <input type="radio" id="tab3" name="tab-control">
                            <ul>
                                <li title="Display Settings">
                                    <label for="tab1" role="button">
                                        <span>
                                            <?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/cogs-solid.svg';?>
                                        </span>
                                        <span><?php _e('Display Settings', 'sheetstowptable-pro');?></span>
                                    </label>
                                </li>
                                <li title="Sort & Filter">
                                    <label for="tab2" role="button">
                                        <span>
                                            <?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/sort-numeric-up-solid.svg';?>
                                        </span>
                                        <span><?php _e('Sort & Filter', 'sheetstowptable');?></span>
                                    </label>
                                </li>
                                <li title="Table Tools">
                                    <label for="tab3" role="button">
                                        <span>
                                            <?php require GSWPTS_BASE_PATH . 'Assets/Public/Icons/tools-solid.svg';?>
                                        </span>
                                        <span><?php _e('Table Tools', 'sheetstowptable');?></span>
                                    </label>
                                </li>
                            </ul>

                            <div class="slider">
                                <div class="indicator"></div>
                            </div>
                            <div class="content">
                                <section class="display_settings">
                                    <div class="feature-container">
                                        <?php $settingsClass->displaySettings()?>
                                    </div>
                                </section>
                                <section class="sort_filter">
                                    <div class="feature-container">
                                        <?php $settingsClass->sortAndFilterSettings()?>
                                    </div>
                                </section>
                                <section class="table_tools">
                                    <div class="feature-container">
                                        <?php $settingsClass->tableToolsSettings()?>
                                    </div>
                                </section>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
            `;
            }

            function changeSettingsValues(mainSelector, settings){

            let tableTitle = mainSelector.find('#show_title');
            infoBlock = mainSelector.find('#info_block'),
            showEntries = mainSelector.find('#show_entries'),
            swapFilters = mainSelector.find('#swap_filter_inputs'),
            swapBottomElements = mainSelector.find('#swap_bottom_options'),
            responsiveStyles = mainSelector.find('#responsive_style'),
            rowsPerPage = mainSelector.find('#rows_per_page'),
            tableHeight = mainSelector.find('#vertical_scrolling'),
            cellFormat = mainSelector.find('#cell_format'),
            redirectionType = mainSelector.find('#redirection_type'),
            allowSorting = mainSelector.find('#sorting'),
            searchTable = mainSelector.find('#search_table'),
            tableCache = mainSelector.find('#table_cache');

            <!-- Change the input values according to saved values from databse -->
            tableTitle.prop("checked", settings.table_title == "true" ? true : false);
            infoBlock.prop("checked", settings.show_info_block == "true" ? true : false);
            showEntries.prop("checked", settings.show_x_entries == "true" ? true : false);
            swapFilters.prop("checked", settings.swap_filter_inputs == "true" ? true : false);
            swapBottomElements.prop("checked",settings.swap_bottom_options == "true" ? true : false);
            responsiveStyles.val(settings.responsive_style).change();
            rowsPerPage.val(settings.default_rows_per_page == "-1" ? "all" : settings.default_rows_per_page).change();
            tableHeight.val(settings.vertical_scroll).change();
            cellFormat.val(settings.cell_format).change();
            redirectionType.val(settings.redirection_type).change();
            allowSorting.prop("checked", settings.allow_sorting == "true" ? true : false);
            searchTable.prop("checked", settings.search_bar == "true" ? true : false);
            tableCache.prop("checked", settings.table_cache == "true" ? true : false);
            }

            })


            #>
            <?php }

    public function table_container() {
        ?>
            <div class="gswpts_create_table_container gswpts_table_{{{ settings.choose_table }}}"
                id="{{{ settings.choose_table }}}"
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
                        style="width:70%; margin: 0 auto;text-align: center; font-weight: bold;">Choose any saved
                        table
                        to load data</div>
                </div>
            </div>
            <?php
}

    public function showTableSettings() {
        ?>
            <div class="gswpts_table_settings" style="margin-right: 0px;">
                <div class="block_initializer">
                    <div class="ui green message"
                        style="width:70%; margin: 0 auto;text-align: center; font-weight: bold;">Choose any saved
                        table
                        to load data</div>
                </div>
            </div>
            <?php
}

}