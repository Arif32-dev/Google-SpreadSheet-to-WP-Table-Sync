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

            <div id="create_button_container" class="row">
                <div class="col-12">
                    <button id="delete_button" class="negative ui button float-right transition hidden">
                        <span>
                            Delete Selected &nbsp;
                        </span>
                    </button>
                </div>
            </div>

            <div class="row mt-5 pr-3 pl-3">
                <div id="gswpts_tables_container" class="col-12">

                    <div class="ui segment" style="width: 100%; min-height: 400px; margin-left: -18px;">
                        <div class="ui active inverted dimmer">
                            <div class="ui large text loader">Loading</div>
                        </div>
                        <p></p>
                        <p></p>
                        <p></p>
                    </div>

                    <!-- <table id="manage_tables" class="ui celled table" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <input type="checkbox" name="manage_tables_checkbox" class="manage_tables_checkbox">
                                </th>
                                <th>Table ID</th>
                                <th>Table Name</th>
                                <th>Type</th>
                                <th>Shortcode</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" name="manage_tables_checkbox" class="manage_tables_checkbox">
                                </td>
                                <td>System Architect</td>
                                <td>Edinburgh</td>
                                <td class="d-flex justify-content-around align-items-center">
                                    [gswpts_table id=1]
                                    <button id="sortcode_copy" type="button" name="copyToken" value="copy" class="copyToken ui right icon button">
                                        <i class="clone icon"></i>
                                    </button>
                                </td>
                                <td>2011/04/25</td>
                                <td class="text-center"><button class="negative ui button">Delete</button></td>
                            </tr>
                        </tbody>
                    </table> -->

                </div>
            </div>
        </div>

    <?php endif; ?>

</div>