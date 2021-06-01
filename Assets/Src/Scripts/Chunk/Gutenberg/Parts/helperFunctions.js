export function callAlert(title, description, type, time, pos = "bottom-right") {
    $.suiAlert({
        title: title,
        description: description,
        type: type,
        time: time,
        position: pos,
    });
}

export function saveChanges(id, table_settings) {
    $.ajax({
        url: gswpts_gutenberg_block.admin_ajax,

        data: {
            action: "gswpts_sheet_create",
            table_settings: table_settings,
            id: parseInt(id),
            type: "save_changes",
            gutenberg_req: true,
        },

        type: "POST",

        success: (res) => {
            if (
                JSON.parse(res).response_type == "invalid_action" ||
                JSON.parse(res).response_type == "invalid_request"
            ) {
                callAlert("Error &#128683;", JSON.parse(res).output, "error", 4);
            }
        },
        error: (err) => {
            callAlert("Error &#128683;", "<b>Something went wrong</b>", "error", 3);
        },
    });
}
