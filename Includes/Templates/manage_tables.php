<?php
global $gswpts;
$gswpts->bootstrap_files();
$gswpts->data_table_styles();
$gswpts->data_table_scripts();
?>
<div class="gswpts_container">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <table id="manage_tables" class="ui celled table" style="width:100%">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" name="manage_tables_checkbox" class="manage_tables_checkbox">
                            </th>
                            <th>Table ID</th>
                            <th>Table Name</th>
                            <th>Type</th>
                            <th>Shortcode</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input type="checkbox" name="manage_tables_checkbox" class="manage_tables_checkbox">
                            </td>
                            <td>System Architect</td>
                            <td>Edinburgh</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="manage_tables_checkbox" class="manage_tables_checkbox">
                            </td>
                            <td>Accountant</td>
                            <td>Tokyo</td>
                            <td>63</td>
                            <td>2011/07/25</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>