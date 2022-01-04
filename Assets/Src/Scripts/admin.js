import "../Styles/Backend/admin.scss";

import "./Chunk/documentation";
import "./Chunk/create_table";
import "./Chunk/fetch_sheet_data";
import "./Chunk/manage_tables";
import "./Chunk/ud_tables";
import "./Chunk/table_changes";
import "./Chunk/general_settings";
import "./Chunk/manage_tab_table";
import "./Chunk/create_tab";

jQuery(document).ready(function ($) {
    if ($(".gswpts_loader").length) {
        $(".gswpts_loader").transition("fade");
    } else {
        return;
    }
    setTimeout(() => {
        $(
            ".dashboard_content, .manage_table_content, .create_table_content, .create_tab_content, .settings_content"
        ).transition("fade");
    }, 300);
});
