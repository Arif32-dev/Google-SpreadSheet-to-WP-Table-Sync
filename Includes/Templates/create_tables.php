<?php
global $gswpts;
$gswpts->bootstrap_files();
$gswpts->data_table_styles();
$gswpts->data_table_scripts();
// $gswpts->dropdown_files();
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




        <!-- <div class="row mt-4 gswpts_input_skeleton">
                <div class="col-lg-7 <?php echo (isset($_GET['id']) && !empty($_GET['id'])) ? 'transition visible' : 'transition hidden' ?>">
                    <div class="ui placeholder" style="max-width: 100%;">
                        <div class="very long line"></div>
                        <div class="line"></div>
                    </div>
                </div>
                <div class="col-lg-5 <?php echo (isset($_GET['id']) && !empty($_GET['id'])) ? 'transition visible' : 'transition hidden' ?>">
                    <div class="ui placeholder" style="max-width: 100%;">
                        <div class="very long line"></div>
                        <div class="line"></div>
                    </div>
                </div>
            </div> -->

        <!-- <div class="row gswpts_form_container mt-4 <?php echo (isset($_GET['id']) && !empty($_GET['id'])) ? 'transition hidden' : 'transition visible' ?>">
                <div class="col-12">
                    <form id="gswpts_create_table" class="ui form">
                        <?php $gswpts->nonce_field('gswpts_sheet_nonce_action', 'gswpts_sheet_nonce') ?>
                        <div class="row">
                            <div class="field col-12 col-lg-7">
                                <label for="sheet_url">Google SpreadSheet Url: </label>
                                <input required type="text" name="sheet_url" value="" placeholder="https://docs.google.com/spreadsheets/d/1t7MnIPlu_lU9srlftEvtSnSx3db3-hLctNXFao3wRVQ/edit">
                            </div>
                            <div class="field col-12 col-lg-5">
                                <label for="sheet_url">Table Name: </label>
                                <input type="text" name="table_name" value="" id="table_name" placeholder="Table Name">
                            </div>
                        </div>
                        <button class="mt-2 ui button" type="submit" req-type="<?php echo isset($_GET['id']) && !empty($_GET['id']) ? 'save' : 'fetch' ?>">
                            <?php echo isset($_GET['id']) && !empty($_GET['id']) ? 'Save Table' : 'Fetch Data' ?>
                        </button>
                    </form>
                </div>
            </div> -->



        <!-- <div class="row mt-5 gswpts_sheet_details_skeleton <?php echo (isset($_GET['id']) && !empty($_GET['id'])) ? 'transition visible' : 'transition hidden' ?>">
                <div class="col-12 mt-2">
                    <div class="ui placeholder">
                        <div class="line"></div>
                        <div class="line"></div>
                        <div class="line"></div>
                        <div class="line"></div>
                        <div class="line"></div>
                    </div>
                </div>
            </div> -->


        <!-- <div class="row transition hidden" id="sheet_details">
            </div> -->

        <!-- <div class="row mt-5 pr-0 pl-3">
                <div id="spreadsheet_container" class="col-12 d-flex justify-content-center align-content-center p-relative">

                    <?php if (isset($_GET['id']) && !empty($_GET['id'])) : ?>

                        <div class="ui segment" style="width: 102%; min-height: 400px; margin-left: -5px;">
                            <div class="ui active inverted dimmer">
                                <div class="ui large text loader">Loading</div>
                            </div>
                            <p></p>
                            <p></p>
                            <p></p>
                        </div>

                    <?php endif ?>

                </div>
            </div> -->

        <!-- <div id="create_button_container" class="row">

                <div class="col-12 p-0">
                    <button id="create_button" <?php echo isset($_GET['id']) && !empty($_GET['id']) ? 'disabled' : '' ?> class="positive ui button float-right <?php echo isset($_GET['id']) && !empty($_GET['id']) ? 'transition visible' : 'transition hidden' ?>">
                        <span>
                            Create New &nbsp;
                            <i class="plus icon"></i>
                        </span>
                    </button>
                </div>
            </div> -->

        <div class="row mt-3">
            <div class="col-12 p-0 d-flex align-items-center justify-content-end">
                <button id="create_button" class="ui violet button m-0 mr-2 transition hidden"
                    style="padding-left: 30px;">
                    Create New &nbsp; <i class="plus icon"></i>
                </button>
                <button class="ui inverted green button m-0" type="button"
                    req-type="<?php echo isset($_GET['id']) && !empty($_GET['id']) ? 'save' : 'fetch' ?>">
                    <?php echo isset($_GET['id']) && !empty($_GET['id']) ? 'Save Table' : 'Fetch Data' ?>
                </button>
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
                                    <div class="field col-12 p-0 col-md-3">
                                        
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




    </div>

</div>