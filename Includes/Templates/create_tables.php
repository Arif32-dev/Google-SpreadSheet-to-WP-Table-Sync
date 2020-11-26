<?php
global $gswpts;
$gswpts->bootstrap_files();
$gswpts->data_table_styles();
$gswpts->data_table_scripts();
?>
<style>
    #create_tables_paginate>div {
        padding-top: 0 !important;
    }
</style>

<div class="gswpts_container pr-3 position-relative" style="min-height: 100vh;">

    <?php if (wp_style_is('GSWPTS-semanticui-css', 'enqueued') && wp_script_is('GSWPTS-semantic-js', 'enqueued')) : ?>

        <div class="ui segment gswpts_main_loader mr-3" style="position: absolute; left: 0; top:0; right: 0; bottom: 0; z-index: 5;">
            <div class="ui active inverted dimmer">
                <div class="ui massive text loader"></div>
            </div>
            <p></p>
            <p></p>
            <p></p>
        </div>

        <div class="container pl-0 gswpts_content_container transition hidden">

            <div id="create_button_container" class="row <?php echo isset($_GET['id']) && !empty($_GET['id']) ? 'mt-4' : '' ?>">
                <div class="col-12">
                    <button id="create_button" <?php echo isset($_GET['id']) && !empty($_GET['id']) ? 'disabled' : '' ?> class="positive ui button float-right <?php echo isset($_GET['id']) && !empty($_GET['id']) ? 'transition visible' : 'transition hidden' ?>">
                        <span>
                            Create New &nbsp;
                            <i class="plus icon"></i>
                        </span>
                    </button>
                </div>
            </div>


            <div class="row mt-4 gswpts_input_skeleton">
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
            </div>

            <div class="row gswpts_form_container mt-4 <?php echo (isset($_GET['id']) && !empty($_GET['id'])) ? 'transition hidden' : 'transition visible' ?>">
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
            </div>



            <div class="row mt-5 gswpts_sheet_details_skeleton <?php echo (isset($_GET['id']) && !empty($_GET['id'])) ? 'transition visible' : 'transition hidden' ?>">
                <div class="col-12 mt-2">
                    <div class="ui placeholder">
                        <div class="line"></div>
                        <div class="line"></div>
                        <div class="line"></div>
                        <div class="line"></div>
                        <div class="line"></div>
                    </div>
                </div>
            </div>


            <div class="row transition hidden" id="sheet_details">
            </div>


            <div class="row mt-5 pr-0 pl-3">
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
            </div>

        </div>
    <?php endif; ?>
</div>