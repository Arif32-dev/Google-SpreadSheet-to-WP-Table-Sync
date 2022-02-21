import "../styles/backend/admin.scss";

import "./chunk/documentation";
import "./chunk/create_table";
import "./chunk/fetch_sheet_data";
import "./chunk/manage_tables";
import "./chunk/ud_tables";
import "./chunk/table_changes";
import "./chunk/general_settings";
import "./chunk/manage_tab_table";
import "./chunk/create_tab";

jQuery(document).ready(function ($) {
    if ($(".gswpts_loader").length) {
        $(".gswpts_loader").transition("fade");
    } else {
        return;
    }
    setTimeout(() => {
        $(".dashboard_content, .manage_table_content, .create_table_content, .create_tab_content, .settings_content").transition("fade");
    }, 300);
});
