export default class Base_Class {
    constructor($) {
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
                #redirection_type
                #table_style
                `
            );
        } else {
            this.settings_field = $(
                "#show_title, #rows_per_page, #info_block, #show_entries, #swap_filter_inputs, #swap_bottom_options, #sorting, #search_table"
            );
        }
    }

    call_alert(title, description, type, time, pos = "bottom-right") {
        $.suiAlert({
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
    export_to_json(e, target) {
        target.tableHTMLExport({ type: "json", filename: "sample.json" });
    }
    export_to_csv(e, target) {
        target.tableHTMLExport({ type: "csv", filename: "sample.csv" });
    }
    export_to_pdf(e, target) {
        target.tableHTMLExport({ type: "pdf", filename: "sample.pdf" });
    }

    table_settings_obj() {
        let settings = {
            table_title: $("#show_title").prop("checked"),
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
            settings.responsiveTable = $("#responsive").prop("checked");
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
        }
        return settings;
    }

    default_settings() {
        let default_settings = {
            table_title: false,
            defaultRowsPerPage: 10,
            showInfoBlock: true,
            responsiveTable: false,
            showXEntries: true,
            swapFilterInputs: false,
            swapBottomOptions: false,
            allowSorting: true,
            searchBar: true,
            tableExport: null,
            verticalScroll: 400,
            cellFormat: "wrap",
            tableCache: false,
            tableStyle: null,
        };

        return default_settings;
    }

    table_changer(table_name, table_settings, dom) {
        $("#create_tables").DataTable(
            this.table_object(
                table_name,
                table_settings.defaultRowsPerPage,
                table_settings.allowSorting,
                dom,
                table_settings.verticalScroll
            )
        );
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

            if (pagination_menu.children().length > 5) {
                this.overflow_menu_style();
            } else {
                this.bottom_option_style(style);
            }
        } else {
            if (pagination_menu.children().length > 5) {
                this.overflow_menu_style();
            } else {
                style["flex_direction"] = "row";

                style.table_info_style["margin_left"] = 0;
                style.table_info_style["margin_right"] = "auto";

                style.table_paginate_style["margin_left"] = "auto";
                style.table_paginate_style["margin_right"] = 0;

                this.bottom_option_style(style);
            }
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

    table_object(table_name, pageLength, ordering, dom, verticalScroll) {
        let obj = {
            dom: dom,
            order: [],
            responsive: true,
            lengthMenu: [
                [1, 5, 10, 15],
                [1, 5, 10, 15],
            ],
            pageLength: parseInt(pageLength),
            lengthChange: true,
            ordering: ordering,
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
                    text: `PDF &nbsp;<img src="${file_url.iconsURL.filePdf}" />`,
                    extend: "pdf",
                    className: "ui inverted red button transition hidden pdf_btn",
                    title: `${table_name}`,
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

            if (verticalScroll != "default") {
                obj.scrollY = `${verticalScroll}px`;
            }
        }
        return obj;
    }

    /* This function will reconfigure tables fields based on data */
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

        if (this.isProPluginActive()) {
            $("#responsive").prop("checked", settings.responsive_table == "true" ? true : false);
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
                this.tableStyle(settings.table_style);
            }
        }
    }

    tableStyle(tableStyle) {
        // let headerCss = {};

        // if (tableStyle == "style-1") {
        //     headerCss = {
        //         headerBackgroundColor: "#6807f9",
        //         textColor: "#fff",
        //     };
        // } else if (tableStyle == "style-2") {
        //     headerCss = {
        //         headerBackgroundColor: "#36304a",
        //         textColor: "#fff",
        //     };
        // } else if (tableStyle == "style-3") {
        //     headerCss = {
        //         headerBackgroundColor: "#6c7ae0",
        //         textColor: "#fff",
        //     };
        // } else if (tableStyle == "style-4") {
        //     headerCss = {
        //         headerBackgroundColor: "#000",
        //         textColor: "#fff",
        //     };
        // } else if (tableStyle == "style-5") {
        //     headerCss = {
        //         headerBackgroundColor: "rgba(249, 250, 251, 1)",
        //         textColor: "#000",
        //     };
        // }

        if (file_url.tableStyles) {
            for (const style in file_url.tableStyles) {
                $("#spreadsheet_container").removeClass(`gswpts_${style}`);
            }
        }

        // if style is default then remove the header style and set ti back to previous style
        // if (tableStyle == "default-style") {
        //     let styleString = $("#spreadsheet_container table thead th").attr("style");
        //     if (styleString) {
        //         let styleStringArray = styleString.split(";");

        //         if (styleStringArray) {
        //             styleStringArray = styleStringArray.filter((val) => {
        //                 return val.match("width");
        //             });
        //             $("#spreadsheet_container table thead th").attr(
        //                 "style",
        //                 styleStringArray[0].trim()
        //             );
        //         }
        //     }
        // }

        $("#spreadsheet_container").addClass(`gswpts_${tableStyle}`);
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

            case "clip":
                $.each(tableCell, function (i, cell) {
                    $(cell).removeClass("wrap_style");
                    $(cell).removeClass("expanded_style");
                    $(cell).addClass("clip_style");
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
}
