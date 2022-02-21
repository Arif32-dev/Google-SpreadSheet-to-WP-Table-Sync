export default class Global_Table_Config {
    table_configuration($, i, elem, table_name, table_settings, sheetUrl) {
        let dom = `<"filtering_input filtering_input_${i}"${
            table_settings.show_x_entries == "true" ? "l" : ""
        }${table_settings.search_bar == "true" ? "f" : ""}>rt<"bottom_options bottom_options_${i}"${
            table_settings.show_info_block == "true" ? "i" : ""
        }p>`;

        if (this.isProPluginActive()) {
            dom = `B<"filtering_input filtering_input_${i}"${
                table_settings.show_x_entries == "true" ? "l" : ""
            }${
                table_settings.search_bar == "true" ? "f" : ""
            }>rt<"bottom_options bottom_options_${i}"${
                table_settings.show_info_block == "true" ? "i" : ""
            }p>`;

            // change the cell format according to feature saved at database
            this.changeCellFormat(
                table_settings.cell_format,
                $(elem).find("#create_tables th, td"),
                $
            );

            this.changeRedirectionType(
                table_settings.redirection_type,
                $(elem).find("#create_tables a"),
                $
            );
        }

        $(elem)
            .find("#create_tables")
            .DataTable(this.table_obj($, table_name, table_settings, dom));

        this.setPdfUrl(sheetUrl, $(elem).find(".dt-buttons"));

        if (this.isProPluginActive()) {
            this.reveal_export_btns($, elem, table_settings);
        }

        this.swap_input_filter($, i, table_settings);

        this.swap_bottom_options($, i, table_settings);
    }

    changeRedirectionType(type, links, $) {
        if (!links.length) return;
        $.each(links, function (i, link) {
            $(link).attr("target", type);
        });
    }

    changeCellFormat(formatStyle, tableCell, $) {
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

    isProPluginActive() {
        if (front_end_data.isProActive) {
            return true;
        } else {
            return false;
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

    setPdfUrl(url, elem) {
        let spreadsheetID = this.getSpreadsheetID(url);
        let gridID = this.getGridID(url);
        let pdfUrl = `https://docs.google.com/spreadsheets/d/${spreadsheetID}/export?format=pdf&id=${spreadsheetID}&gid=${gridID}`;

        elem.append(
            `<a class="ui inverted red button export_btns pdf_btn"
                href="${pdfUrl}"
                download>
                <span>
                    PDF &nbsp;<img src="${front_end_data.iconsURL.filePdf}" />
                </span>
            </a>`
        );
    }

    table_obj($, table_name, table_settings, dom) {
        let table_object = {
            dom: dom,
            order: [],
            responsive: true,
            lengthMenu: [
                [1, 5, 10, 15],
                [1, 5, 10, 15],
            ],
            pageLength: parseInt(table_settings.default_rows_per_page),
            lengthChange: true,
            ordering: table_settings.allow_sorting == "true" ? true : false,
            destroy: true,
            scrollX: true,
        };

        if (this.isProPluginActive()) {
            table_object.buttons = [
                {
                    text: `JSON &nbsp;<img src="${front_end_data.iconsURL.curlyBrackets}" />`,
                    className: "ui inverted yellow button export_btns json_btn",
                    action: function (e, dt, button, config) {
                        var data = dt.buttons.exportData();
                        $.fn.dataTable.fileSave(
                            new Blob([JSON.stringify(data)]),
                            `${table_name}.json`
                        );
                    },
                },
                // {
                //     text: `PDF &nbsp;<img src="${front_end_data.iconsURL.filePdf}" />`,
                //     extend: "pdf",
                //     className: "ui inverted red button export_btns pdf_btn",
                //     title: `${table_name}`,
                // },
                {
                    text: `CSV &nbsp;<img src="${front_end_data.iconsURL.fileCSV}" />`,
                    extend: "csv",
                    className: "ui inverted green button export_btns csv_btn",
                    title: `${table_name}`,
                },
                {
                    text: `Excel &nbsp;<img src="${front_end_data.iconsURL.fileExcel}" />`,
                    extend: "excel",
                    className: "ui inverted green button export_btns excel_btn",
                    title: `${table_name}`,
                },
                {
                    text: `Print &nbsp;<img src="${front_end_data.iconsURL.printIcon}" />`,
                    extend: "print",
                    className: "ui inverted secondary button export_btns print_btn",
                    title: `${table_name}`,
                },
                {
                    text: `Copy &nbsp;<img src="${front_end_data.iconsURL.copySolid}" />`,
                    extend: "copy",
                    className: "ui inverted violet button export_btns copy_btn",
                    title: `${table_name}`,
                },
            ];

            table_object.lengthMenu = [
                [1, 5, 10, 15, 25, 50, 100, -1],
                [1, 5, 10, 15, 25, 50, 100, "All"],
            ];

            if (table_object.scrollY != "default") {
                table_object.scrollY = `${table_settings.vertical_scroll}px`;
            }

            if (this.screenSize() === "desktop") {
                if (table_settings.hide_column) {
                    table_object.columnDefs = this.hideColumnByScreen(
                        table_settings.hide_column.desktopValues
                    );
                }
            } else {
                if (table_settings.hide_column) {
                    table_object.columnDefs = this.hideColumnByScreen(
                        table_settings.hide_column.mobileValues
                    );
                }
            }
        }

        return table_object;
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

    reveal_export_btns($, elem, table_settings) {
        if (!table_settings) return;

        let export_btns = table_settings.table_export;

        if (export_btns != "empty" && export_btns) {
            export_btns.forEach((btn) => {
                $(elem)
                    .find("." + btn + "_btn")
                    .removeClass("export_btns");
            });
        }
    }

    swap_input_filter($, i, table_settings) {
        /* If checkbox is checked then swap filter */
        if (table_settings.swap_filter_inputs == "true") {
            $(".filtering_input_" + i + "").css("flex-direction", "row-reverse");
            $(".filtering_input_" + i + " > #create_tables_length").css({
                "margin-right": "0",
                "margin-left": "auto",
            });
            $(".filtering_input_" + i + " > #create_tables_filter").css({
                "margin-left": "0",
                "margin-right": "auto",
            });
        } else {
            /* Set back to default position */
            $(".filtering_input_" + i + "").css("flex-direction", "row");
            $(".filtering_input_" + i + " > #create_tables_length").css({
                "margin-right": "auto",
                "margin-left": "0",
            });
            $(".filtering_input_" + i + " > #create_tables_filter").css({
                "margin-left": "auto",
                "margin-right": "0",
            });
        }
    }

    swap_bottom_options($, i, table_settings) {
        let style = {
            flex_direction: "row-reverse",
            table_info_style: {
                margin_left: "auto",
                margin_right: 0,
            },
            table_paginate_style: {
                margin_left: 0,
                margin_right: "auto",
            },
        };

        if (table_settings.swap_bottom_options == "true") {
            this.bottom_option_style($, style, i);
        } else {
            style["flex_direction"] = "row";

            style.table_info_style["margin_left"] = 0;
            style.table_info_style["margin_right"] = "auto";

            style.table_paginate_style["margin_left"] = "auto";
            style.table_paginate_style["margin_right"] = 0;

            this.bottom_option_style($, style, i);
        }
    }

    bottom_option_style($, $arg, i) {
        $(".bottom_options_" + i + "").css("flex-direction", $arg["flex_direction"]);
        $(".bottom_options_" + i + " > #create_tables_info").css({
            "margin-left": $arg["table_info_style"]["margin_left"],
            "margin-right": $arg["table_info_style"]["margin_right"],
        });
        $(".bottom_options_" + i + " > #create_tables_paginate").css({
            "margin-left": $arg["table_paginate_style"]["margin_left"],
            "margin-right": $arg["table_paginate_style"]["margin_right"],
        });
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

    clearOverflow() {
        if (
            /Android|webOS|iPhone|iPad|Mac|Macintosh|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
                navigator.userAgent
            )
        ) {
            if (
                this.detectBrowser() == "Chrome" ||
                this.detectBrowser() == "Safari" ||
                this.detectBrowser() == "MSIE"
            ) {
                let tableScrollBody = document.querySelectorAll(
                    ".gswpts_tables_container .dataTables_scrollBody"
                );

                if (tableScrollBody) {
                    tableScrollBody.forEach((element) => {
                        if (
                            element.parentElement.parentElement.parentElement.parentElement.classList.contains(
                                "collapse_style"
                            )
                        ) {
                            element.style.overflow = "unset";
                            element.style.height = "unset";
                            element.style.maxHeight = "unset";
                        }
                    });
                }

                // this.hideEmptyCell();
            }
        }
    }

    detectBrowser() {
        if ((navigator.userAgent.indexOf("Opera") || navigator.userAgent.indexOf("OPR")) != -1) {
            return "Opera";
        } else if (navigator.userAgent.indexOf("Chrome") != -1) {
            return "Chrome";
        } else if (navigator.userAgent.indexOf("Safari") != -1) {
            return "Safari";
        } else if (navigator.userAgent.indexOf("Firefox") != -1) {
            return "Firefox";
        } else if (navigator.userAgent.indexOf("MSIE") != -1 || !!document.documentMode == true) {
            return "IE"; //crap
        } else {
            return "Unknown";
        }
    }

    hideEmptyCell() {
        let tableCells = document.querySelectorAll(".gswpts_tables_container td");
        if (!tableCells) return;

        tableCells.forEach((element) => {
            if (element.innerText == "") {
                // element.style.display = "none";
                element.innerText = "No data";
            }
        });
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
}
