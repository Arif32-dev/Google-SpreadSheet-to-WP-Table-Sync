<?php
global $gswpts;
$gswpts->bootstrap_files();
$gswpts->data_table_styles();
$gswpts->data_table_scripts();
$gswpts->alert_files();
?>
<style>
    #create_tables_paginate>div {
        padding-top: 0 !important;
    }
</style>
<div class="gswpts_container">
    <div class="container pl-0">
        <div id="create_button_container" class="row <?php echo isset($_GET['id']) && !empty($_GET['id']) ? 'mt-4' : '' ?>">
            <div class="col-12">
                <button id="create_button" class="positive ui button float-right <?php echo isset($_GET['id']) && !empty($_GET['id']) ? 'transition visible' : 'transition hidden' ?>">
                    <span>
                        Create New &nbsp;
                        <i class="plus icon"></i>
                    </span>
                </button>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <form id="gswpts_create_table" class="ui form">
                    <?php $gswpts->nonce_field('gswpts_sheet_nonce_action', 'gswpts_sheet_nonce') ?>
                    <div class="row">
                        <div class="field col-12 col-lg-7">
                            <label for="sheet_url">Google SpreadSheet Url: </label>
                            <input required type="text" name="sheet_url" value="<?php echo isset($_GET['id']) && !empty($_GET['id']) ? $gswpts->input_values()['sheet_url'] : '' ?>" placeholder="https://docs.google.com/spreadsheets/d/1t7MnIPlu_lU9srlftEvtSnSx3db3-hLctNXFao3wRVQ/edit">
                        </div>
                        <div class="field col-12 col-lg-5">
                            <label for="sheet_url">Table Name: </label>
                            <input type="text" name="table_name" value="<?php echo isset($_GET['id']) && !empty($_GET['id']) ? $gswpts->input_values()['table_name'] : '' ?>" id="table_name" placeholder="Table Name">
                        </div>
                    </div>
                    <button class="mt-2 ui button" type="submit" req-type="fetch">Fetch Data</button>
                </form>
            </div>
        </div>
        <div class="row <?php echo isset($_GET['id']) && !empty($_GET['id']) ? 'mt-5 p-3' : '' ?>" id="sheet_details">
            <?php echo $gswpts->sheet_details($gswpts->get_table()) ?>
        </div>
        <div class="row mt-5 p-3">
            <div id="spreadsheet_container" class="col-12 d-flex justify-content-center align-content-center p-relative">
                <?php echo $gswpts->get_table()['table']['table'] ?>
            </div>
        </div>
    </div>
</div>