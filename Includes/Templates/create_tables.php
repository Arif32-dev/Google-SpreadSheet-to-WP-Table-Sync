<?php
global $gswpts;
$gswpts->bootstrap_files();
$gswpts->data_table_styles();
$gswpts->data_table_scripts();
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
                    <input <?php echo isset($_GET['id']) && !empty($_GET['id']) ? 'disabled' : '' ?> type="text" placeholder="Table Name" id="table_name" class="table_name" value="GSWPTS Table">
                    <button class="ui button edit_table_name ">
                        Edit &nbsp;
                        <span><i class=" edit icon"></i></span>
                    </button>
                </div>

                <div class="col p-0 d-flex align-items-center justify-content-end">
                    <button id="create_button" class="positive ui button m-0 mr-2 transition hidden" style="padding-left: 30px;">
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
                    <input type="radio" id="tab2" name="tab-control">
                    <input type="radio" id="tab3" name="tab-control">
                    <input type="radio" id="tab4" name="tab-control">
                    <ul>
                        <li title="Data Source">
                            <label for="tab1" role="button">
                                <span><i class="fas fa-archive"></i></span>
                                <span>Data Source</span>
                            </label>
                        </li>

                        <li title="Display Settings">
                            <label for="tab2" role="button">
                                <span><i class="fas fa-cogs"></i></span>
                                <span>Display Settings</span>
                            </label>
                        </li>

                        <li title="Delivery Contents">
                            <label for="tab3" role="button">
                                <span><i class="fas fa-sort-numeric-up"></i></span>
                                <span>Sort & Filter</span>
                            </label>
                        </li>

                        <li title="Table Tools">
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
                                                    <div class="item d-flex justify-content-between align-items-center" data-value="csv">
                                                        CSV File
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

                                        <div class="col-12 col-md-6 transition hidden file_input">
                                            <div class="ui icon input">
                                                <input required type="text" name="file_input" placeholder="Enter URL or path, or click Browse to choose.">
                                                <i class="file icon"></i>
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

                        <!-- <section>
                          
                        </section> -->
                        <!-- <section>
                         
                        </section> -->
                        <!-- <section>
                         
                        </section> -->
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