<?php
global $gswpts;
$gswpts->bootstrap_files();
$gswpts->data_table_styles();
$gswpts->data_table_scripts();
?>
<style>
    #manage_tables_paginate>div {
        padding-top: 0 !important;
    }

    #manage_tables th,
    #manage_tables td {
        vertical-align: middle;
    }
</style>
<div class="gswpts_container position-relative" style="min-height: 100vh;">

    <?php if (wp_style_is('GSWPTS-semanticui-css', 'enqueued') && wp_script_is('GSWPTS-semantic-js', 'enqueued')) : ?>

        <div class="ui segment gswpts_main_loader mr-3" style="position: absolute; left: 0; top:0; right: 0; bottom: 0; z-index: 5;">
            <div class="ui active inverted dimmer">
                <div class="ui massive text loader"></div>
            </div>
            <p></p>
            <p></p>
            <p></p>
        </div>

        <div class="container gswpts_content_container transition hidden">

            <div id="delete_button_container" class="row">
                <div class="col-12">
                    <button id="delete_button" class="negative ui button float-right transition hidden" data-show="false">
                        <span>
                            Delete Selected &nbsp;
                        </span>
                    </button>
                </div>
            </div>

            <div class="row mt-4 pr-0 pl-3">

                <div class="col-12 gswpts_modal d-flex justify-content-center align-items-center transition hidden">
                    <div class="ui action input" style="margin-right: 85px;">
                        <input type="text" placeholder="Table Name" class="gswpts_input_table_name">
                        <button class="ui button gswpts_popup_edit">Edit</button>
                    </div>
                </div>

                <div id="gswpts_tables_container" class="col-12">
                    <div class="ui segment" style="width: 100%; min-height: 400px; margin-left: -18px;">
                        <div class="ui active inverted dimmer">
                            <div class="ui large text loader">Loading</div>
                        </div>
                        <p></p>
                        <p></p>
                        <p></p>
                    </div>
                </div>

            </div>

        </div>

    <?php endif; ?>

</div>