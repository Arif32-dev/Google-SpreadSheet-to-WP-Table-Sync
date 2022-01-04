var $ = jQuery.noConflict();

export default class Base_Class {
    constructor() {
        this.sheet_form = $("#gswpts_create_table");
        this.sheet_details = $("#sheet_details");
        this.sheet_container = $("#spreadsheet_container");

        if (this.isProPluginActive()) {
            this.settings_field = $(
                `#show_title, 
                #rows_per_page, 
                #info_block, 
                #responsive, 
                #show_entries, 
                #swap_filter_inputs, 
                #swap_bottom_options, 
                #sorting, 
                #search_table, 
                #table_exporting, 
                #vertical_scrolling,
                #cell_format,
                #import_styles
                `
            );
        } else {
            this.settings_field = $(
                "#show_title, #rows_per_page, #info_block, #show_entries, #swap_filter_inputs, #swap_bottom_options, #sorting, #search_table"
            );
        }
    }

    call_alert(title, description, type, time, pos = "bottom-right") {
        this.suiAlert({
            title: title,
            description: description,
            type: type,
            time: time,
            position: pos,
        });
    }

    isProPluginActive() {
        if (file_url.isProActive) {
            return true;
        } else {
            return false;
        }
    }

    html_loader() {
        let loader = `
               <div class="ui segment gswpts_table_loader">
                        <div class="ui active inverted dimmer">
                            <div class="ui large text loader">Loading</div>
                        </div>
                        <p></p>
                        <p></p>
                        <p></p>
                </div>
            `;
        return loader;
    }

    sheet_details_html(res) {
        let details = `
                <div id="sheet_ui_card" class="ui card" style="min-width: 360px;">
                    <div class="content">
                        <div class="row">
                            <div id="shortcode_container" style="display: none !important;" class="col-12 d-flex align-items-center justify-content-center transition hidden">
                                <h6 class="m-0" style="white-space: nowrap;font-weight: bold;">Table Shortcode: </h6>
                                <h6 class="m-0 ml-2">
                                    <div class="ui action input">
                                        <input id="sortcode_value" type="text" class="copyInput" value="">
                                        <button id="sortcode_copy" type="button" name="copyToken" value="copy" class="copyToken ui right icon button">
                                            <i class="clone icon"></i>
                                        </button>
                                    </div>
                                </h6>
                            </div>
                        </div>
                    </div>
            </div>
            `;
        return details;
    }

    show_shortcode(shortcode_id) {
        $("#shortcode_container").removeAttr("style");
        $("#shortcode_container").transition("scale");
        $("#sortcode_value").val(`[gswpts_table id=${shortcode_id}]`);
    }

    copy_shorcode(e) {
        let input = $(e.currentTarget).siblings("input");
        input.attr("type", "text");
        input.focus();
        input.select();
        document.execCommand("copy");
        input.attr("type", "hidden");
        this.call_alert("Copied &#128077;", "<b>Sortcode copied successfully</b>", "info", 2);
    }

    btn_changer(submit_button, text, reqType = false) {
        submit_button.html(`
                            ${text}
                        `);

        if (reqType !== false) {
            submit_button.attr("req-type", reqType);
        }
    }

    get_slug_parameter(slug) {
        let url = new URL(window.location);
        let params = new URLSearchParams(url.search);
        let retrieve_param = params.get(slug);
        if (retrieve_param) {
            return retrieve_param;
        } else {
            return false;
        }
    }

    table_settings_obj() {
        let settings = {
            tableTitle: $("#show_title").prop("checked"),
            defaultRowsPerPage:
                $("#rows_per_page").find("input[name=rows_per_page]").val() == "all"
                    ? -1
                    : $("#rows_per_page").find("input[name=rows_per_page]").val(),
            showInfoBlock: $("#info_block").prop("checked"),
            showXEntries: $("#show_entries").prop("checked"),
            swapFilterInputs: $("#swap_filter_inputs").prop("checked"),
            swapBottomOptions: $("#swap_bottom_options").prop("checked"),
            allowSorting: $("#sorting").prop("checked"),
            searchBar: $("#search_table").prop("checked"),
        };

        if (this.isProPluginActive()) {
            settings.responsiveStyle = $("#responsive_style")
                .find("input[name=responsive_style]")
                .val();
            settings.tableExport = $("#table_exporting").val().split(",") || null;
            settings.verticalScroll = $("#vertical_scrolling")
                .find("input[name=vertical_scrolling]")
                .val();
            settings.cellFormat = $("#cell_format").find("input[name=cell_format]").val();
            settings.redirectionType = $("#redirection_type")
                .find("input[name=redirection_type]")
                .val();
            settings.tableCache = $("#table_cache").prop("checked");
            settings.tableStyle = $("#table_style").val();
            settings.hideColumn = $("#hide_column").val()
                ? JSON.parse($("#hide_column").val())
                : "";
            settings.hideRows = $("#hide_rows").val()
                ? JSON.parse($("#hide_rows").val()).length
                    ? JSON.parse($("#hide_rows").val())
                    : ""
                : "";
            settings.hideCell = $("#hide_cell").val()
                ? JSON.parse($("#hide_cell").val()).length
                    ? JSON.parse($("#hide_cell").val())
                    : ""
                : "";
            settings.importStyles = $("#import_styles").prop("checked");
        }
        return settings;
    }

    default_settings() {
        let default_settings = {
            tableTitle: false,
            defaultRowsPerPage: 10,
            showInfoBlock: true,
            responsiveTable: false,
            showXEntries: true,
            swapFilterInputs: false,
            swapBottomOptions: false,
            allowSorting: true,
            searchBar: true,
            tableExport: null,
            verticalScroll: null,
            cellFormat: "expanded",
            tableCache: false,
            tableStyle: null,
            hideColumn: null,
            hideRows: null,
            tableStyles: false,
        };

        return default_settings;
    }

    table_changer(table_name, table_settings, dom) {
        $("#create_tables").DataTable(this.table_object(table_name, dom, table_settings));
    }

    swap_filter_inputs(state) {
        /* If checkbox is checked then swap filter */
        if (state) {
            $("#filtering_input").css("flex-direction", "row-reverse");
            $("#create_tables_length").css({
                "margin-right": "0",
                "margin-left": "auto",
            });
            $("#create_tables_filter").css({
                "margin-left": "0",
                "margin-right": "auto",
            });
        } else {
            /* Set back to default position */
            $("#filtering_input").css("flex-direction", "row");
            $("#create_tables_length").css({
                "margin-right": "auto",
                "margin-left": "0",
            });
            $("#create_tables_filter").css({
                "margin-left": "auto",
                "margin-right": "0",
            });
        }
    }

    swap_bottom_options(state) {
        let pagination_menu = $("#bottom_options .pagination.menu");
        let style = {
            flex_direction: "row-reverse",
            table_info_style: {
                margin_right: 0,
                margin_left: "auto",
            },
            table_paginate_style: {
                margin_right: "auto",
                margin_left: 0,
            },
        };
        if (state) {
            this.bottom_option_style(style);
        } else {
            style["flex_direction"] = "row";

            style.table_info_style["margin_left"] = 0;
            style.table_info_style["margin_right"] = "auto";

            style.table_paginate_style["margin_left"] = "auto";
            style.table_paginate_style["margin_right"] = 0;

            this.bottom_option_style(style);
        }
    }

    overflow_menu_style() {
        $("#bottom_options").css("flex-direction", "column");
        $("#create_tables_info").css({
            margin: "5px auto",
        });
        $("#create_tables_paginate").css({
            margin: "5px auto",
        });
    }

    bottom_option_style($arg) {
        $("#bottom_options").css("flex-direction", $arg["flex_direction"]);
        $("#create_tables_info").css({
            "margin-left": $arg["table_info_style"]["margin_left"],
            "margin-right": $arg["table_info_style"]["margin_right"],
        });
        $("#create_tables_paginate").css({
            "margin-left": $arg["table_paginate_style"]["margin_left"],
            "margin-right": $arg["table_paginate_style"]["margin_right"],
        });
    }

    export_buttons_row_revealer(export_btns) {
        if (export_btns) {
            export_btns.forEach((btn) => {
                setTimeout(() => {
                    this.export_button_revealer_by_other_input(btn);
                }, 300);
            });
        }
    }

    export_button_revealer_by_other_input(btn) {
        if ($("." + btn + "_btn").hasClass("hidden")) {
            $("." + btn + "_btn").transition("scale");
        }
    }

    getSpreadsheetID(url) {
        if (!url || url == "") return;

        let sheetID = null;

        sheetID = url.split(/\//)[5];

        if (sheetID) return sheetID;

        return null;
    }

    getGridID(url) {
        if (!url || url == "") return;

        let gridID = null;

        gridID = url.match(/gid=(\w+)/)[1];

        if (gridID) return gridID;

        return null;
    }

    setPdfUrl(url) {
        let spreadsheetID = this.getSpreadsheetID(url);
        let gridID = this.getGridID(url);
        let pdfUrl = `https://docs.google.com/spreadsheets/d/${spreadsheetID}/export?format=pdf&id=${spreadsheetID}&gid=${gridID}`;

        if (!$("#create_tables_wrapper .dt-buttons .pdf_btn").length) {
            $("#create_tables_wrapper .dt-buttons").append(
                `<a class="ui inverted red button transition hidden pdf_btn"
                    href="${pdfUrl}"
                    download>
                    <span>
                        PDF &nbsp;<img src="${file_url.iconsURL.filePdf}" />
                    </span>
                </a>`
            );
        }
    }

    table_object(table_name, dom, table_settings) {
        let obj = {
            dom: dom,
            order: [],
            responsive: true,
            lengthMenu: [
                [1, 5, 10, 15],
                [1, 5, 10, 15],
            ],
            pageLength: parseInt(table_settings.defaultRowsPerPage),
            lengthChange: true,
            ordering: table_settings.allowSorting,
            destroy: true,
            scrollX: true,
        };

        if (this.isProPluginActive()) {
            obj.buttons = [
                {
                    text: `JSON &nbsp;<img src="${file_url.iconsURL.curlyBrackets}" />`,
                    className: "ui inverted yellow button transition hidden json_btn",
                    action: function (e, dt, button, config) {
                        var data = dt.buttons.exportData();

                        $.fn.dataTable.fileSave(
                            new Blob([JSON.stringify(data)]),
                            `${table_name}.json`
                        );
                    },
                },
                {
                    text: `CSV &nbsp;<img src="${file_url.iconsURL.fileCSV}" />`,
                    extend: "csv",
                    className: "ui inverted green button transition hidden csv_btn",
                    title: `${table_name}`,
                },
                {
                    text: `Excel &nbsp;<img src="${file_url.iconsURL.fileExcel}" />`,
                    extend: "excel",
                    className: "ui inverted green button transition hidden excel_btn",
                    title: `${table_name}`,
                },
                {
                    text: `Print &nbsp;<img src="${file_url.iconsURL.printIcon}" />`,
                    extend: "print",
                    className: "ui inverted secondary button transition hidden print_btn",
                    title: `${table_name}`,
                },
                {
                    text: `Copy &nbsp;<img src="${file_url.iconsURL.copySolid}" />`,
                    extend: "copy",
                    className: "ui inverted violet button transition hidden copy_btn",
                    title: `${table_name}`,
                },
            ];

            obj.lengthMenu = [
                [1, 5, 10, 15, 25, 50, 100, -1],
                [1, 5, 10, 15, 25, 50, 100, "All"],
            ];

            if (table_settings.verticalScroll != "default") {
                obj.scrollY = `${table_settings.verticalScroll}px`;
            }

            if (this.screenSize() === "desktop") {
                if (table_settings.hideColumn) {
                    obj.columnDefs = this.hideColumnByScreen(
                        table_settings.hideColumn.desktopValues
                    );
                }
            } else {
                if (table_settings.hideColumn) {
                    obj.columnDefs = this.hideColumnByScreen(
                        table_settings.hideColumn.mobileValues
                    );
                }
            }
        }
        return obj;
    }

    // Return an array that will define the columns to hide
    hideColumnByScreen(arrayValues) {
        return [
            {
                targets: this.convertArrayStringToInteger(arrayValues),
                visible: false,
                searchable: false,
            },
        ];
    }

    // get the current screen size of user if greater than 740 return desktop or return mobile
    screenSize() {
        // Desktop screen size
        if (screen.width > 740) {
            return "desktop";
        } else {
            return "mobile";
        }
    }

    // convert string to integer from arrays
    convertArrayStringToInteger(arr) {
        if (!arr) return [];
        return arr.map((val) => parseInt(val));
    }

    /* This function will reconfigure tables settings fields based on saved data */
    reconfigure_input_fields(settings) {
        $("#show_title").prop("checked", settings.table_title == "true" ? true : false);
        $("#rows_per_page").dropdown(
            "set selected",
            settings.default_rows_per_page == "-1" ? "all" : settings.default_rows_per_page
        );
        $("#info_block").prop("checked", settings.show_info_block == "true" ? true : false);
        $("#show_entries").prop("checked", settings.show_x_entries == "true" ? true : false);
        $("#swap_filter_inputs").prop(
            "checked",
            settings.swap_filter_inputs == "true" ? true : false
        );
        $("#swap_bottom_options").prop(
            "checked",
            settings.swap_bottom_options == "true" ? true : false
        );
        $("#sorting").prop("checked", settings.allow_sorting == "true" ? true : false);
        $("#search_table").prop("checked", settings.search_bar == "true" ? true : false);

        // Integrate all the pro feature if the pro plugin is active
        if (this.isProPluginActive()) {
            $("#responsive_style").dropdown("set selected", settings.responsive_style);
            $("#vertical_scrolling").dropdown("set selected", settings.vertical_scroll);
            if (settings.table_export != "empty" && settings.table_export) {
                settings.table_export.forEach((export_type) => {
                    $("#table_exporting_container").dropdown("set selected", export_type);
                });
            }
            $("#cell_format").dropdown("set selected", settings.cell_format);
            $("#redirection_type").dropdown("set selected", settings.redirection_type);
            $("#table_cache").prop("checked", settings.table_cache == "true" ? true : false);

            if (settings.table_style) {
                $("#table_style").val(settings.table_style);
                $(".styleWrapper").find(`label[for=${settings.table_style}]`).addClass("active");

                let args = {
                    tableStyle: settings.table_style,
                    importStyles: settings.import_styles == "true" ? true : false,
                };

                this.tableStyle(args);
            }

            // if hide column value is saved to db and not empty set the hide column input field value & also the select field values
            if (settings.hide_column) {
                $("#hide_column").val(JSON.stringify(settings.hide_column));

                // Set the desktop column values in desktop select input
                if (settings.hide_column.desktopValues) {
                    settings.hide_column.desktopValues.forEach((export_type) => {
                        $("#desktop-hide-columns").dropdown("set selected", export_type);
                    });
                }

                // Set the mobile column values in mobile select input
                if (settings.hide_column.mobileValues) {
                    settings.hide_column.mobileValues.forEach((export_type) => {
                        $("#mobile-hide-columns").dropdown("set selected", export_type);
                    });
                }
            }

            // if hidden row values saved to db then hide table rows and also update the settings with hidden row values
            if (settings.hide_rows) {
                $("#hide_rows").val(JSON.stringify(settings.hide_rows));
            }

            // if hidden cell values saved to db then hide table cell and also update the settings with hidden cell values
            if (settings.hide_cell) {
                $("#hide_cell").val(JSON.stringify(settings.hide_cell));
            }

            // if import_style value is true than check the checkbox
            $("#import_styles").prop("checked", settings.import_styles == "true" ? true : false);
        }
    }

    tableStyle(args) {
        if (file_url.tableStyles) {
            for (const style in file_url.tableStyles) {
                $("#spreadsheet_container").removeClass(`gswpts_${style}`);
            }
        }

        if (args.importStyles !== undefined && args.importStyles == true) {
            $("#spreadsheet_container").addClass(`gswpts_default-style`);
        } else {
            $("#spreadsheet_container").addClass(`gswpts_${args.tableStyle}`);
        }
    }

    changeCellFormat(formatStyle, tableSelector) {
        let tableCell = $(`${tableSelector} th, td`);

        switch (formatStyle) {
            case "wrap":
                $.each(tableCell, function (i, cell) {
                    $(cell).removeClass("clip_style");
                    $(cell).removeClass("expanded_style");
                    $(cell).addClass("wrap_style");
                });
                break;

            case "expand":
                $.each(tableCell, function (i, cell) {
                    $(cell).removeClass("clip_style");
                    $(cell).removeClass("wrap_style");
                    $(cell).addClass("expanded_style");
                });
                break;

            default:
                break;
        }
    }

    bindDragScroll($container, $scroller) {
        var $window = $(window);

        var x = 0;
        var y = 0;

        var x2 = 0;
        var y2 = 0;
        var t = 0;

        $container.on("mousedown", down);
        $container.on("click", preventDefault);
        $scroller.on("mousewheel", horizontalMouseWheel); // prevent macbook trigger prev/next page while scrolling

        function down(evt) {
            if (evt.button === 0) {
                t = Date.now();
                x = x2 = evt.pageX;
                y = y2 = evt.pageY;

                $container.addClass("down");
                $window.on("mousemove", move);
                $window.on("mouseup", up);

                evt.preventDefault();
            }
        }

        function move(evt) {
            // alert("move");
            if ($container.hasClass("down")) {
                var _x = evt.pageX;
                var _y = evt.pageY;
                var deltaX = _x - x;
                var deltaY = _y - y;

                $scroller[0].scrollLeft -= deltaX;

                x = _x;
                y = _y;
            }
        }

        function up(evt) {
            $window.off("mousemove", move);
            $window.off("mouseup", up);

            var deltaT = Date.now() - t;
            var deltaX = evt.pageX - x2;
            var deltaY = evt.pageY - y2;
            if (deltaT <= 300) {
                $scroller.stop().animate(
                    {
                        scrollTop: "-=" + deltaY * 3,
                        scrollLeft: "-=" + deltaX * 3,
                    },
                    500,
                    function (x, t, b, c, d) {
                        // easeOutCirc function from http://gsgd.co.uk/sandbox/jquery/easing/
                        return c * Math.sqrt(1 - (t = t / d - 1) * t) + b;
                    }
                );
            }

            t = 0;

            $container.removeClass("down");
        }

        function preventDefault(evt) {
            if (x2 !== evt.pageX || y2 !== evt.pageY) {
                evt.preventDefault();
                return false;
            }
        }

        function horizontalMouseWheel(evt) {
            evt = evt.originalEvent;
            var x = $scroller.scrollLeft();
            var max = $scroller[0].scrollWidth - $scroller[0].offsetWidth;
            var dir = evt.deltaX || evt.wheelDeltaX;
            var stop = dir > 0 ? x >= max : x <= 0;
            if (stop && dir) {
                evt.preventDefault();
            }
        }
    }

    addGrabCursonOnMouseDown(elem) {
        elem.mousedown((e) => {
            elem.css({
                cursor: "grab",
            });
        });
        elem.mouseup((e) => {
            elem.css({
                cursor: "auto",
            });
        });
    }

    // this will add dragging capability to table if pro plugin is activated
    addDraggingAbility() {
        if (this.isProPluginActive()) {
            let scrollerContainer = $("#spreadsheet_container .dataTables_scroll");
            let scrollerElement = $("#spreadsheet_container .dataTables_scrollBody");
            // add functionality of scolling the table
            this.bindDragScroll(scrollerContainer, scrollerElement);
            this.addGrabCursonOnMouseDown($("#create_tables"));
        }
    }

    // Insert column value from sheet to input box for column hiding
    insertColumnValueToInput(columns) {
        let desktopColumnInput = $("#desktop-hide-columns");
        let mobileColumnInput = $("#mobile-hide-columns");

        $.each([desktopColumnInput, mobileColumnInput], function (index, value) {
            let menu = $(value).find(".menu");
            if (columns) {
                columns.forEach((column, columIndex) => {
                    if (file_url.isProActive) {
                        menu.append(`
                            <div class="item" data-value="${columIndex}">
                                ${column ? column : "&nbsp;"}
                            </div>
                         `);
                    } else {
                        menu.append(`
                            <div class="item pro_feature_input pro_column_select" data-value="${columIndex}">
                                ${column ? column : "&nbsp;"}
                            </div>
                        `);
                    }
                });
            }
            $(value).dropdown();
        });
    }

    // Insert hidden row values from sheet to input box for row hiding
    insertHiddenRowsToSelectBox(rowIndex) {
        let hiddenRows = $("#hidden_rows");
        let menu = hiddenRows.find(".menu");
        if (rowIndex) {
            if (this.isProPluginActive()) {
                let prevMenuList = menu.find(`[data-value=${rowIndex}]`);
                if (!prevMenuList.length) {
                    menu.append(`
                        <div class="item" data-value="${rowIndex}">
                            Row ${rowIndex ? `#${rowIndex}` : "&nbsp;"}
                        </div>
                    `);
                }
                setTimeout(() => {
                    hiddenRows.dropdown("set selected", rowIndex);
                }, 400);
            }
        }
    }

    // Insert hidden row values from sheet to input box for row hiding
    insertSelectedCellToSelectBox(cellIndex) {
        let hiddenCells = $("#hidden_cells");
        let menu = hiddenCells.find(".menu");
        if (cellIndex) {
            if (this.isProPluginActive()) {
                // The column position of the cell
                let cellColumnIndex = JSON.parse(cellIndex)[0];
                // The row position of the cell
                let cellRowIndex = JSON.parse(cellIndex)[1];

                cellIndex = JSON.parse(cellIndex).join("-");

                let prevMenuList = menu.find(`[data-value=${cellIndex}]`);
                if (!prevMenuList.length) {
                    menu.append(`
                        <div class="item" data-value="${cellIndex}">
                           Position: Col #${cellColumnIndex}, Row #${cellRowIndex}
                        </div>
                    `);
                    setTimeout(() => {
                        hiddenCells.dropdown("set selected", cellIndex);
                    }, 400);
                }
            }
        }
    }

    // Add the values to hidden input value as an json object for saving in database
    insertHiddenRowsToInputBox(rowIndex) {
        let hiddenRows = [],
            jsonFormatData,
            hiddenRowValues = $("#hide_rows");
        if (hiddenRowValues.val()) {
            hiddenRows = JSON.parse(hiddenRowValues.val());
            let valueExists = false;

            for (let index = 0; index < hiddenRows.length; index++) {
                const currentRowIndexValue = hiddenRows[index];

                if (currentRowIndexValue == rowIndex) {
                    valueExists = true;
                    break;
                }
            }

            if (valueExists == false) {
                hiddenRows.push(rowIndex);
                jsonFormatData = JSON.stringify(hiddenRows);
                hiddenRowValues.val(jsonFormatData);
            }
        } else {
            hiddenRows.push(rowIndex);
            jsonFormatData = JSON.stringify(hiddenRows);
            hiddenRowValues.val(jsonFormatData);
        }
    }

    // Add the values to hidden input value as an json object for saving in database
    insertHiddenCellToInputBox(cellIndex) {
        let hiddenCell = [],
            jsonFormatData,
            hiddenCellValues = $("#hide_cell");
        if (hiddenCellValues.val()) {
            hiddenCell = JSON.parse(hiddenCellValues.val());

            let valueExists = false;
            for (let index = 0; index < hiddenCell.length; index++) {
                const currentCellIndexValue = hiddenCell[index];

                if (currentCellIndexValue == cellIndex) {
                    valueExists = true;
                    break;
                }
            }

            if (valueExists == false) {
                hiddenCell.push(cellIndex);
                jsonFormatData = JSON.stringify(hiddenCell);
                hiddenCellValues.val(jsonFormatData);
            }
        } else {
            hiddenCell.push(cellIndex);
            jsonFormatData = JSON.stringify(hiddenCell);
            hiddenCellValues.val(jsonFormatData);
        }
    }

    suiAlert(options) {
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
}
