<?php
global $gswpts;
$gswpts->bootstrap_files();
$gswpts->semantic_files();
$gswpts->download_datatables();
?>


<div class="gswpts_create_table_container">

    <div class="ui segment gswpts_loader">
        <div class="ui active inverted dimmer">
            <div class="ui massive text loader"></div>
        </div>
        <p></p>
        <p></p>
        <p></p>
    </div>


    <div class="container mt-4 create_table_content transition hidden">

        <div class="row heading_row">
            <div class="col-12 d-flex justify-content-start p-0 align-items-center">
                <img src="<?php echo GSWPTS_BASE_URL . '/Assets/Public/Images/Google_Sheets_logo.svg' ?>" alt="">
                <span class="ml-2">
                    <strong>Google Spredsheet to WP Table Sync</strong>
                </span>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 p-0 d-flex align-items-center">

                <div class="ui action input <?php echo isset($_GET['id']) && !empty($_GET['id']) ? 'transition hidden' : '' ?>">
                    <input <?php echo isset($_GET['id']) && !empty($_GET['id']) ? 'disabled' : '' ?> type="text" placeholder="Table Name" id="table_name" name="table_name" value="GSWPTS Table">
                    <button <?php echo isset($_GET['id']) && !empty($_GET['id']) ? 'disabled' : '' ?> class="ui button edit_table_name ">
                        Edit Title &nbsp;
                        <span><i class=" edit icon"></i></span>
                    </button>
                </div>

                <div class="col p-0 d-flex align-items-center justify-content-end">
                    <button id="create_button" class="positive ui button m-0 mr-2 <?php echo isset($_GET['id']) && !empty($_GET['id']) ? '' : 'transition hidden' ?>" style="padding-left: 30px;">
                        Create New &nbsp; <i class="plus icon"></i>
                    </button>
                    <button class="ui violet button m-0 transition hidden" type="button" id="fetch_save_btn" req-type="<?php echo isset($_GET['id']) && !empty($_GET['id']) ? 'save' : 'fetch' ?>">
                        <?php echo isset($_GET['id']) && !empty($_GET['id']) ? 'Save Table' : 'Fetch Data' ?>
                    </button>
                </div>

            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 p-0" id="gswpts_tabs">

                <div class="tabs">

                    <input type="radio" id="tab1" name="tab-control" checked>
                    <input <?php echo isset($_GET['id']) && !empty($_GET['id']) ? '' : 'disabled' ?> type="radio" id="tab2" name="tab-control" class="secondary_inputs">
                    <input <?php echo isset($_GET['id']) && !empty($_GET['id']) ? '' : 'disabled' ?> type="radio" id="tab3" name="tab-control" class="secondary_inputs">
                    <input <?php echo isset($_GET['id']) && !empty($_GET['id']) ? '' : 'disabled' ?> type="radio" id="tab4" name="tab-control" class="secondary_inputs">
                    <ul>
                        <li title="Data Source" class="tables_settings" data-btn-text="<?php echo isset($_GET['id']) && !empty($_GET['id']) ? 'Save Table' : 'Fetch Data' ?>" data-attr-text="<?php echo isset($_GET['id']) && !empty($_GET['id']) ? 'save' : 'fetch' ?>">
                            <label for="tab1" role="button">
                                <span><i class="fas fa-archive"></i></span>
                                <span>Data Source</span>
                            </label>
                        </li>

                        <li title="Display Settings" class="<?php echo isset($_GET['id']) && !empty($_GET['id']) ? 'tables_settings' : 'disabled_checkbox' ?>" data-btn-text="Save Changes" data-attr-text="save_changes">
                            <label for="tab2" role="button">
                                <span><i class="fas fa-cogs"></i></span>
                                <span>Display Settings</span>
                            </label>
                        </li>

                        <li title="Delivery Contents" class="<?php echo isset($_GET['id']) && !empty($_GET['id']) ? 'tables_settings' : 'disabled_checkbox' ?>" data-btn-text="Save Changes" data-attr-text="save_changes">
                            <label for="tab3" role="button">
                                <span><i class="fas fa-sort-numeric-up"></i></span>
                                <span>Sort & Filter</span>
                            </label>
                        </li>

                        <li title="Table Tools" class="<?php echo isset($_GET['id']) && !empty($_GET['id']) ? 'tables_settings' : 'disabled_checkbox' ?>" data-btn-text="Save Changes" data-attr-text="save_changes">
                            <label for="tab4" role="button">
                                <span><i class="fas fa-tools"></i></span>
                                <span>Table Tools</span>
                            </label>
                        </li>

                    </ul>

                    <div class="slider">
                        <div class="indicator"></div>
                    </div>
                    <div class="content">

                        <section>

                            <div class="col-12 p-0">
                                <form id="gswpts_create_table" class="ui form">
                                    <?php $gswpts->nonce_field('gswpts_sheet_nonce_action', 'gswpts_sheet_nonce') ?>
                                    <div class="row input_fields">

                                        <div class="col-12 col-md-4">

                                            <div class="ui fluid search selection dropdown" id="table_type">
                                                <input type="hidden" name="source_type">
                                                <i class="dropdown icon"></i>
                                                <div class="default text">Choose Source Type</div>
                                                <div class="menu">
                                                    <div class="item" data-value="spreadsheet">
                                                        Google Spreadsheet
                                                    </div>
                                                    <div class="item d-flex justify-content-between align-items-center disabled item" data-value="csv">
                                                        <span>CSV File</span>
                                                        <i class="fas fa-medal"></i>
                                                    </div>
                                                    <div class="item d-flex justify-content-between align-items-center disabled item" data-value="excel">
                                                        <span>Excel File</span>
                                                        <i class="fas fa-medal"></i>
                                                    </div>
                                                    <div class="item d-flex justify-content-between align-items-center disabled item" data-value="xml">
                                                        <span>XML File</span>
                                                        <i class="fas fa-medal"></i>
                                                    </div>
                                                    <div class="item d-flex justify-content-between align-items-center disabled item" data-value="json">
                                                        <span>JSON File</span>
                                                        <i class="fas fa-medal"></i>
                                                    </div>
                                                    <div class="item d-flex justify-content-between align-items-center disabled item" data-value="php_array">
                                                        <span>PHP Array</span>
                                                        <i class="fas fa-medal"></i>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-12 col-md-8 transition hidden file_input">
                                            <div class="ui icon input">
                                                <input required type="text" name="file_input" placeholder="Enter URL of spreadsheet to load data">
                                                <span class="ui icon button p-0 m-0 helper_text" data-tooltip="Share your sheet publicly. Publish the sheet to web & click the share button at the top of your spreadsheet" data-position="left center" data-inverted="">
                                                    <i class="fas fa-info-circle"></i>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-2 pl-2 transition hidden browse_input">
                                            <button id="browse_input" class="positive ui button m-0">
                                                Browse File&nbsp;
                                                <i class="fas fa-hand-pointer"></i>
                                            </button>
                                        </div>

                                    </div>
                                </form>

                            </div>

                        </section>

                        <section id="display_settings">
                            <div class="row">

                                <?php load_template(GSWPTS_BASE_PATH . 'Includes/Templates/Parts/indiviual_feature.php', false, [
                                    'feature_title' => 'Table Title',
                                    'feature_desc' => 'Enable this to show the table title in <i>h3</i> tag above the table',
                                    'input_name' => 'show_title'
                                ]); ?>

                                <div class="col-md-4 mt-3 mb-3">
                                    <div class="ui cards">
                                        <div class="card">
                                            <div class="content">
                                                <div class="header">Default rows per page</div>
                                                <div class="description">
                                                    This will show rows per page in the frontend
                                                </div>

                                                <div class="ui fluid selection dropdown" id="rows_per_page">
                                                    <input type="hidden" name="rows_per_page">
                                                    <i class="dropdown icon"></i>
                                                    <div class="default text">Rows Per Page</div>

                                                    <div class="menu">
                                                        <div class="item" data-value="1">
                                                            1
                                                        </div>
                                                        <div class="item" data-value="5">
                                                            5
                                                        </div>
                                                        <div class="item" data-value="10">
                                                            10
                                                        </div>
                                                        <div class="item" data-value="15">
                                                            15
                                                        </div>
                                                        <div class="item d-flex justify-content-between align-items-center disabled item" data-value="25">
                                                            <span>25</span>
                                                            <i class="fas fa-medal"></i>
                                                        </div>
                                                        <div class="item d-flex justify-content-between align-items-center disabled item" data-value="50">
                                                            <span>50</span>
                                                            <i class="fas fa-medal"></i>
                                                        </div>
                                                        <div class="item d-flex justify-content-between align-items-center disabled item" data-value="100">
                                                            <span>100</span>
                                                            <i class="fas fa-medal"></i>
                                                        </div>
                                                        <div class="item d-flex justify-content-between align-items-center disabled item" data-value="all">
                                                            <span>All</span>
                                                            <i class="fas fa-medal"></i>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php load_template(GSWPTS_BASE_PATH . 'Includes/Templates/Parts/indiviual_feature.php', false, [
                                    'feature_title' => 'Show info block',
                                    'feature_desc' => 'Show <i>Showing X to Y of Z entries</i>block below the table',
                                    'input_name' => 'info_block'
                                ]); ?>

                                <?php load_template(GSWPTS_BASE_PATH . 'Includes/Templates/Parts/indiviual_feature.php', false, [
                                    'feature_title' => 'Resposive table',
                                    'feature_desc' => 'Allow collapsing on mobile and tablet screen',
                                    'input_name' => 'responsive',
                                    'is_pro' => true
                                ]); ?>

                                <?php load_template(GSWPTS_BASE_PATH . 'Includes/Templates/Parts/indiviual_feature.php', false, [
                                    'feature_title' => 'Show X entries',
                                    'feature_desc' => '<i>Show X entries</i> per page dropdown',
                                    'input_name' => 'show_entries'
                                ]); ?>

                                <?php load_template(GSWPTS_BASE_PATH . 'Includes/Templates/Parts/indiviual_feature.php', false, [
                                    'feature_title' => 'Swap Filters',
                                    'feature_desc' => 'Swap the places of <i> X entries</i> dropdown & search filter input',
                                    'input_name' => 'swap_filter_inputs'
                                ]); ?>

                                <?php load_template(GSWPTS_BASE_PATH . 'Includes/Templates/Parts/indiviual_feature.php', false, [
                                    'feature_title' => 'Swap Bottom Elements',
                                    'feature_desc' => 'Swap the places of <i>Showing X to Y of Z entries</i> with table pagination filter',
                                    'input_name' => 'swap_bottom_options'
                                ]); ?>

                                <?php load_template(GSWPTS_BASE_PATH . 'Includes/Templates/Parts/indiviual_feature.php', false, [
                                    'feature_title' => 'Vertical Scrolling',
                                    'feature_desc' => 'Turning ON this feature will enable the table to scroll vertically',
                                    'input_name' => 'vertical_scrolling',
                                    'is_pro' => true
                                ]); ?>

                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <button class="ui violet button m-0" type="button">
                                        Display Documention <span class="ml-2"><i class="fas fa-cogs"></i></span>
                                    </button>
                                </div>
                            </div>
                        </section>

                        <section id="sort_filter">
                            <div class="row">

                                <?php load_template(GSWPTS_BASE_PATH . 'Includes/Templates/Parts/indiviual_feature.php', false, [
                                    'feature_title' => 'Allow Sorting',
                                    'feature_desc' => 'Enable this feature to sort table data for frontend.',
                                    'input_name' => 'sorting'
                                ]); ?>

                                <?php load_template(GSWPTS_BASE_PATH . 'Includes/Templates/Parts/indiviual_feature.php', false, [
                                    'feature_title' => 'Search Bar',
                                    'feature_desc' => 'Enable this feature to show a search bar in for the table. It will help user to search data in the table',
                                    'input_name' => 'search_table'
                                ]); ?>

                                <?php load_template(GSWPTS_BASE_PATH . 'Includes/Templates/Parts/indiviual_feature.php', false, [
                                    'feature_title' => 'Rows Highlight',
                                    'feature_desc' => 'Enable this feature to show highlighted rows of the table in the frontend selected by admin/user',
                                    'input_name' => 'rows_highlight',
                                    'is_pro' => true
                                ]); ?>

                                <?php load_template(GSWPTS_BASE_PATH . 'Includes/Templates/Parts/indiviual_feature.php', false, [
                                    'feature_title' => 'Chart Integration',
                                    'feature_desc' => 'Enable this feature to filter data by various terms in the sheet & is going to show all the filtered data in the table as well as in a chart',
                                    'input_name' => 'chart_integration',
                                    'is_pro' => true
                                ]); ?>

                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <button class="ui violet button m-0" type="button">
                                        Sorting Documention <span class="ml-2"><i class="fas fa-sort-numeric-up"></i></span>
                                    </button>
                                </div>
                            </div>
                        </section>

                        <section id="table_tools">

                            <div class="row">

                                <div class="col-md-4 mt-3 mb-3">
                                    <div class="ui cards">
                                        <div class="card">
                                            <div class="content">
                                                <span class="pro_feature"><i class="fas fa-medal"></i></span>
                                                <div class="header">Table Exporting</div>
                                                <div class="description">
                                                    Enable this feature in order to allow your user to download your table content as various format.
                                                </div>
                                                <select name="skills" multiple="" class="ui fluid dropdown mt-2 pro_feature_input" id="table_exporting">
                                                    <option value="">Select Type</option>
                                                    <option value="json">JSON</option>
                                                    <option value="pdf">PDF</option>
                                                    <option value="csv">CSV</option>
                                                    <option value="excel">Excel</option>
                                                    <option value="print">Print</option>
                                                    <option value="copy">Copy</option>
                                                </select>
                                                <div class="pro_feature_input selectbox_overlay">

                                                </div>
                                            </div>
                                            <?php load_template(GSWPTS_BASE_PATH . 'Includes/Templates/Parts/promo.php', false); ?>

                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <button class="ui violet button m-0" type="button">
                                        Table Tools Doc<span class="ml-2"><i class="fas fa-tools"></i></span>
                                    </button>
                                </div>
                            </div>

                        </section>
                    </div>
                </div>

            </div>
        </div>


        <div class="row transition hidden" id="sheet_details">
        </div>

        <div class="row mt-4">
            <div id="spreadsheet_container" class="col-12 d-flex justify-content-center align-content-center p-relative p-0 position-relative">

                <?php if (isset($_GET['id']) && !empty($_GET['id'])) : ?>

                    <div class="ui segment gswpts_table_loader" style="z-index: -1;">
                        <div class="ui active inverted dimmer">
                            <div class="ui large text loader">Loading</div>
                        </div>
                        <p></p>
                        <p></p>
                        <p></p>
                    </div>

                <?php endif ?>


            </div>
        </div>


    </div>

</div>