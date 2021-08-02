import "../Styles/Backend/admin.scss";

import "./Chunk/documentation";
import "./Chunk/create_google_sheet";
import "./Chunk/fetch_sheet_data";
import "./Chunk/manage_tables";
import "./Chunk/ud_tables";
import "./Chunk/table_changes";
import "./Chunk/general_settings";

jQuery(document).ready(function ($) {
    if ($(".gswpts_loader").length) {
        $(".gswpts_loader").transition("fade");
    } else {
        return;
    }
    setTimeout(() => {
        $(
            ".dashboard_content, .manage_table_content, .create_table_content, .settings_content"
        ).transition("fade");
    }, 300);
});
