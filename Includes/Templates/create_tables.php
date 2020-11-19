<?php
global $gswpts;
$gswpts->bootstrap_files();
$gswpts->data_table_styles();
$gswpts->data_table_scripts();
?>
<div class="gswpts_container">
    <div class="container pl-0">
        <div class="row mt-5">
            <div class="col-12">
                <form id="gswpts_create_table" class="ui form">
                    <?php $gswpts->nonce_field('gswpts_sheet_nonce_action', 'gswpts_sheet_nonce') ?>
                    <div class="field">
                        <label for="sheet_url">Google SpreadSheet Url: </label>
                        <input type="text" name="sheet_url" placeholder="https://docs.google.com/spreadsheets/d/1t7MnIPlu_lU9srlftEvtSnSx3db3-hLctNXFao3wRVQ/edit">
                    </div>
                    <button class="ui button" type="submit">Save Changes</button>
                </form>
            </div>
        </div>
        <div class="row mt-5 p-3">
            <div id="spreadsheet_container" class="col-12 d-flex justify-content-center align-content-center p-relative">
                <!-- <table id="create_tables" class="ui celled table">
                    <thead>
                        <tr>
                            <th>Table ID</th>
                            <th>Table Name</th>
                            <th>Type</th>
                            <th>Shortcode</th>
                        </tr>
                    </thead>
                    <tr>
                        <td>System Architect</td>
                        <td>Edinburgh</td>
                        <td>61</td>
                        <td>2011/04/25</td>
                    </tr>
                    <tr>
                        <td>Accountant</td>
                        <td>Tokyo</td>
                        <td>63</td>
                        <td>2011/07/25</td>
                    </tr>
                </table> -->
            </div>
        </div>
    </div>
</div>