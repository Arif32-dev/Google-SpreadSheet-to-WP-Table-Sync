var $ = jQuery.noConflict();

export function callAlert(title, description, type, time, pos = "bottom-right") {
    suiAlert({
        title: title,
        description: description,
        type: type,
        time: time,
        position: pos,
    });
}

function suiAlert(options) {
    if (options.type == "info") {
        // announcement
        options.icon = "announcement";
    } else if (options.type == "success") {
        // checkmark, checkmark box
        options.icon = "checkmark";
    } else if (options.type == "error") {
        // ban, remove, remove circle
        options.icon = "remove";
    } else if (options.type == "warning") {
        // warning sign, warning circle
        options.icon = "warning circle";
    }

    // set close animation
    var close_anim = "drop";
    if (options.position == "top-right") {
        close_anim = "fly left";
    } else if (options.position == "top-center") {
        close_anim = "fly down";
    } else if (options.position == "top-left") {
        close_anim = "fly right";
    } else if (options.position == "bottom-right") {
        close_anim = "fly left";
    } else if (options.position == "bottom-center") {
        close_anim = "fly up";
    } else if (options.position == "bottom-left") {
        close_anim = "fly right";
    }

    // screen size check
    var alert_size = "";
    var screen_width = $(window).width();
    if (screen_width < 425) alert_size = "mini";

    var alerts_class = "ui-alerts." + options.position;
    if (!$("body > ." + alerts_class).length) {
        $("body").append('<div class="ui-alerts ' + options.position + '"></div>');
    }

    var _alert = $(
        '<div class="ui icon floating ' +
            alert_size +
            " message " +
            options.type +
            '" id="alert"> <i class="' +
            options.icon +
            ' icon"></i> <i class="close icon" id="alertclose"></i> <div class="content"> <div class="header">' +
            options.title +
            "</div> <p>" +
            options.description +
            "</p> </div> </div>"
    );
    $("." + alerts_class).prepend(_alert);

    _alert.transition("pulse");

    /**
     * Close the alert
     */
    $("#alertclose").on("click", function () {
        $(this)
            .closest("#alert")
            .transition({
                animation: close_anim,
                onComplete: function () {
                    _alert.remove();
                },
            });
    });

    var timer = 0;
    $(_alert)
        .mouseenter(function () {
            clearTimeout(timer);
        })
        .mouseleave(function () {
            alertHide();
        });

    alertHide();

    function alertHide() {
        timer = setTimeout(function () {
            _alert.transition({
                animation: close_anim,
                duration: "2s",
                onComplete: function () {
                    _alert.remove();
                },
            });
        }, options.time * 1000);
    }
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
