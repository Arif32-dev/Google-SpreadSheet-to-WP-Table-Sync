import { Dropdown } from "semantic-ui-react";
import { saveChanges, callAlert } from "./Parts/helperFunctions";

const { useEffect, useRef } = wp.element;
const { InspectorControls } = wp.blockEditor;
const { Panel, PanelBody, PanelRow, SelectControl, ToggleControl } = wp.components;

import {
    formatCellValues,
    rowsPerPage,
    scrollHeights,
    redirectionValues,
    tableStyles,
    responsiveStyles,
} from "./Parts/selectValues";

export default function editFucntion({ attributes, setAttributes }) {
    useEffect(() => {
        if (attributes.sortcode_id) {
            fetch_data_by_id(attributes.sortcode_id);
            setAttributes({ table_selection: attributes.sortcode_id });
            setAttributes({ show_choose_table: true });
            setAttributes({ initializer_button_action: "choose_table" });
        }
    }, []);

    const spreadsheet_container = useRef(null);

    function get_table_name_and_data() {
        let select_options = [{ value: "no_selection", label: "Select a table" }];
        if (attributes.saved_tables) {
            attributes.saved_tables.forEach((table) => {
                select_options.push({
                    value: parseInt(table.id),
                    label: table.table_name,
                });
            });
        }
        return select_options;
    }

    function fetch_data_by_url(url) {
        $.ajax({
            url: gswpts_gutenberg_block.admin_ajax,

            data: {
                action: "gswpts_sheet_create",
                type: attributes.req_type,
                table_name: attributes.init_table_name,
                table_settings: attributes.table_settings,
                file_input: url,
                source_type: "spreadsheet",
                gutenberg_req: true,
            },

            type: "POST",

            beforeSend: () => {
                setAttributes({ show_settings: false });
                if (attributes.req_type != "save") {
                    setAttributes({ innerHTML: loader });
                    setAttributes({ btn_text: "Fetch Data" });
                }
                setAttributes({ req_type: "fetch" });
            },

            success: (res) => {
                console.log(res);

                let response = res ? JSON.parse(res) : "";

                console.log(response);

                if (!res) return;

                if (
                    response.response_type == "invalid_action" ||
                    response.response_type == "invalid_request"
                ) {
                    callAlert("Error &#128683;", response.output, "error", 4);

                    setAttributes({ req_type: "fetch" });
                    setAttributes({ btn_text: "Fetch Data" });
                    setAttributes({ show_settings: false });
                    setAttributes({ innerHTML: response.output });
                }

                if (response.response_type == "empty_field") {
                    callAlert("Warning &#9888;&#65039;", response.output, "warning", 3);
                }

                if (response.response_type == "success") {
                    setAttributes({ req_type: "save" });
                    setAttributes({ btn_text: "Save Table" });
                    setAttributes({ innerHTML: JSON.parse(res).output });
                    setAttributes({ show_settings: true });

                    let tableColumns = response.tableColumns;
                    let formattedColumnValues = constructColumnValues(tableColumns);

                    // Set the column header values in tableColumns attribute
                    setAttributes({ tableColumns: formattedColumnValues });

                    immidiateSaveTable(url);
                }
            },

            complete: (res) => {
                if (JSON.parse(res.responseText).response_type == "success") {
                    let default_settings = table_default_settings();
                    let defaultRowsPerPage = default_settings.defaultRowsPerPage;
                    let allowSorting = default_settings.allowSorting;
                    let verticalScroll = default_settings.verticalScroll;
                    let dom = '<"#filtering_input"lf>rt<"#bottom_options"ip>';

                    $("#" + spreadsheet_container.current.id + " #create_tables").DataTable(
                        table_object(defaultRowsPerPage, allowSorting, dom, verticalScroll)
                    );

                    setTimeout(() => {
                        callAlert(
                            "Successfull &#128077;",
                            "<b>Google Sheet data fetched successfully</b>",
                            "success",
                            3
                        );
                    }, 700);
                }
            },

            error: (err) => {
                callAlert("Error &#128683;", "<b>Something went wrong</b>", "error", 3);
                setAttributes({ show_settings: false });
                setAttributes({ innerHTML: "<b>Something went wrong</b>" });
            },
        });
    }

    function immidiateSaveTable(url) {
        $.ajax({
            url: gswpts_gutenberg_block.admin_ajax,

            data: {
                action: "gswpts_sheet_create",
                type: "save",
                table_name: attributes.init_table_name,
                table_settings: attributes.table_settings,
                file_input: url,
                source_type: "spreadsheet",
                gutenberg_req: true,
            },

            type: "POST",

            beforeSend: () => {
                setAttributes({ show_settings: false });
            },

            success: (res) => {
                if (
                    JSON.parse(res).response_type == "invalid_action" ||
                    JSON.parse(res).response_type == "invalid_request"
                ) {
                    callAlert("Error &#128683;", JSON.parse(res).output, "error", 4);
                }

                if (JSON.parse(res).response_type == "empty_field") {
                    callAlert("Warning &#9888;&#65039;", JSON.parse(res).output, "warning", 3);
                }

                if (JSON.parse(res).response_type == "saved") {
                    let id = Object.values(JSON.parse(res).id)[0];
                    let tableName = attributes.init_table_name;
                    setAttributes({ sortcode_id: parseInt(id) });
                    setAttributes({ is_table_saved_to_db: true });
                    setAttributes({ show_settings: true });
                    setAttributes({ table_name: tableName });

                    callAlert("Successfull &#128077;", JSON.parse(res).output, "success", 3);
                }

                if (JSON.parse(res).response_type == "sheet_exists") {
                    setAttributes({ show_settings: false });

                    setAttributes({ block_init: false });
                    setAttributes({ sheet_url: null });
                    setAttributes({ btn_text: "Fetch Data" });
                    setAttributes({ req_type: "fetch" });
                    callAlert(
                        "Warning &#9888;&#65039;",
                        "<b>Google sheet previously saved. Try choose table instead of creating</b>",
                        "warning",
                        6
                    );
                }
            },

            error: (err) => {
                callAlert("Error &#128683;", "<b>Something went wrong</b>", "error", 3);
                setAttributes({ show_settings: false });
                setAttributes({ innerHTML: "<b>Something went wrong</b>" });
            },
        });
    }

    function table_default_settings() {
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
            verticalScroll: "",
            cellFormat: "wrap",
            redirectionType: "_self",
            tableStyle: "default-style",
        };
        return default_settings;
    }

    function fetch_data_by_id(id) {
        if (typeof parseInt(id) != "number") {
            setAttributes({
                innerHTML: "<h4>Choose saved table from block settings</h4>",
            });
            return;
        }

        $.ajax({
            url: gswpts_gutenberg_block.admin_ajax,
            data: {
                action: "gswpts_sheet_fetch",
                id: parseInt(id),
            },
            type: "POST",

            beforeSend: () => {
                setAttributes({ show_settings: false });
                setAttributes({ table_name: "" });
                setAttributes({ innerHTML: loader });
            },

            success: (res) => {
                if (
                    JSON.parse(res).response_type == "invalid_action" ||
                    JSON.parse(res).response_type == "invalid_request"
                ) {
                    setAttributes({ innerHTML: JSON.parse(res).output });
                    setAttributes({ show_settings: false });
                    setAttributes({ table_name: "" });

                    callAlert("Error &#128683;", JSON.parse(res).output, "error", 4);
                }

                if (JSON.parse(res).response_type == "success") {
                    setAttributes({ innerHTML: JSON.parse(res).output });
                    setAttributes({ show_settings: true });

                    let tableColumns = JSON.parse(res).tableColumns;
                    let formattedColumnValues = constructColumnValues(tableColumns);

                    // Set the column header values in tableColumns attribute
                    setAttributes({ tableColumns: formattedColumnValues });

                    callAlert(
                        "Successfull &#128077;",
                        "<b>Google Sheet data fetched successfully</b>",
                        "success",
                        3
                    );
                }
            },

            error: (err) => {
                callAlert("Error &#128683;", "<b>Something went wrong</b>", "error", 3);
                setAttributes({ innerHTML: "" });
                setAttributes({ btn_text: "Fetch Data" });
            },

            complete: (res) => {
                if (JSON.parse(res.responseText).response_type == "success") {
                    let table_settings = JSON.parse(
                        JSON.parse(res.responseText).table_data.table_settings
                    );

                    console.log(table_settings);

                    let table_name = JSON.parse(res.responseText).table_data.table_name;

                    let dom = `<"#filtering_input"${
                        table_settings.show_x_entries == "true" ? "l" : ""
                    }${table_settings.search_bar == "true" ? "f" : ""}>rt<"#bottom_options"${
                        table_settings.show_info_block == "true" ? "i" : ""
                    }p>`;

                    let defaultRowsPerPage = table_settings.default_rows_per_page;
                    let allowSorting = table_settings.allow_sorting == "true" ? true : false;
                    let verticalScroll = table_settings.vertical_scroll;
                    let cellFormat = table_settings.cell_format;
                    let redirectionType = table_settings.redirection_type;
                    let hideColumn = table_settings.hide_column;

                    setAttributes({ table_name: table_name });

                    setTimeout(() => {
                        if (isProPluginActive()) {
                            changeCellFormat(cellFormat, id);
                            changeRedirectionType(redirectionType, id);
                        }

                        // Intitiale the data table feature in gutenberg table
                        $("#" + id + " #create_tables").DataTable(
                            table_object(
                                defaultRowsPerPage,
                                allowSorting,
                                dom,
                                verticalScroll,
                                hideColumn
                            )
                        );

                        update_default_attibutes(table_settings);

                        let swap_filter_state =
                            table_settings.swap_filter_inputs == "true" ? true : false;
                        let swap_bottom_state =
                            table_settings.swap_bottom_options == "true" ? true : false;

                        swap_input_filter(id, swap_filter_state);
                        swap_bottom_options(id, swap_bottom_state);
                    }, 700);
                }
            },
        });
    }

    function constructColumnValues(columns) {
        let columnValues = [];

        if (!columns) return columnValues;

        columns.forEach((column, i) => {
            columnValues.push({
                key: i,
                value: i,
                text: column,
            });
        });

        return columnValues;
    }

    function update_default_attibutes(ajax_table_settings) {
        const prevSettingObj = { ...attributes.table_settings };
        prevSettingObj.table_title = ajax_table_settings.table_title == "true" ? true : false;
        prevSettingObj.defaultRowsPerPage = ajax_table_settings.default_rows_per_page;
        prevSettingObj.showInfoBlock = ajax_table_settings.show_info_block == "true" ? true : false;

        prevSettingObj.showXEntries = ajax_table_settings.show_x_entries == "true" ? true : false;
        prevSettingObj.swapFilterInputs =
            ajax_table_settings.swap_filter_inputs == "true" ? true : false;
        prevSettingObj.swapBottomOptions =
            ajax_table_settings.swap_bottom_options == "true" ? true : false;
        prevSettingObj.allowSorting = ajax_table_settings.allow_sorting == "true" ? true : false;
        prevSettingObj.searchBar = ajax_table_settings.search_bar == "true" ? true : false;

        if (isProPluginActive()) {
            if (ajax_table_settings.responsive_style) {
                prevSettingObj.responsiveStyle = ajax_table_settings.responsive_style;
            }

            if (ajax_table_settings.vertical_scroll) {
                prevSettingObj.verticalScroll = ajax_table_settings.vertical_scroll;
            }

            if (ajax_table_settings.cell_format) {
                prevSettingObj.cellFormat = ajax_table_settings.cell_format;
            }

            if (ajax_table_settings.redirection_type) {
                prevSettingObj.redirectionType = ajax_table_settings.redirection_type;
            }

            // update the table cache input value
            prevSettingObj.tableCache = ajax_table_settings.table_cache == "true" ? true : false;

            // update the table style input
            if (ajax_table_settings.redirection_type) {
                prevSettingObj.tableStyle = ajax_table_settings.table_style;
            }

            // Update the Hide column values to show it in input field
            if (ajax_table_settings.hide_column) {
                prevSettingObj.hideColumn = ajax_table_settings.hide_column;
            }

            // Update the Import sheet style input value
            prevSettingObj.importStyles =
                ajax_table_settings.import_styles == "true" ? true : false;
        }
        setAttributes({ table_settings: prevSettingObj });
    }

    function table_changer(id = null, prevSettingObj) {
        let dom = `<"#filtering_input"${prevSettingObj.showXEntries ? "l" : ""}${
            prevSettingObj.searchBar ? "f" : ""
        }>rt<"#bottom_options"${prevSettingObj.showInfoBlock ? "i" : ""}p>`;
        if (id == null) {
            $("#" + spreadsheet_container.current.id + " #create_tables").DataTable(
                table_object(
                    prevSettingObj.defaultRowsPerPage,
                    prevSettingObj.allowSorting,
                    dom,
                    prevSettingObj.verticalScroll,
                    prevSettingObj.hideColumn
                )
            );
        } else {
            $("#" + id + " #create_tables").DataTable(
                table_object(
                    prevSettingObj.defaultRowsPerPage,
                    prevSettingObj.allowSorting,
                    dom,
                    prevSettingObj.verticalScroll,
                    prevSettingObj.hideColumn
                )
            );
        }
    }

    function swap_input_filter(table_id, filter_state) {
        let selector = null;

        if (table_id == null) {
            selector = spreadsheet_container.current.id;
        } else {
            selector = table_id;
        }

        /* If checkbox is checked then swap filter */

        if (filter_state) {
            $("#" + selector + " #filtering_input").css("flex-direction", "row-reverse");
            $("#" + selector + " #create_tables_length").css({
                "margin-right": "0",
                "margin-left": "auto",
            });
            $("#" + selector + " #create_tables_filter").css({
                "margin-left": "0",
                "margin-right": "auto",
            });
        } else {
            /* Set back to default position */
            $("#" + selector + " #filtering_input").css("flex-direction", "row");
            $("#" + selector + " #create_tables_length").css({
                "margin-right": "auto",
                "margin-left": "0",
            });
            $("#" + selector + " #create_tables_filter").css({
                "margin-left": "auto",
                "margin-right": "0",
            });
        }
    }

    function swap_bottom_options(table_id, bottom_state) {
        let selector = null;

        if (table_id == null) {
            selector = spreadsheet_container.current.id;
        } else {
            selector = table_id;
        }

        let pagination_menu = $("#" + selector + " #bottom_options .pagination.menu");

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

        if (bottom_state) {
            if (pagination_menu.children().length > 5) {
                overflow_menu_style(selector);
            } else {
                bottom_option_style(style, selector);
            }
        } else {
            if (pagination_menu.children().length > 5) {
                overflow_menu_style(selector);
            } else {
                style["flex_direction"] = "row";

                style.table_info_style["margin_left"] = 0;
                style.table_info_style["margin_right"] = "auto";

                style.table_paginate_style["margin_left"] = "auto";
                style.table_paginate_style["margin_right"] = 0;

                bottom_option_style(style, selector);
            }
        }
    }

    function overflow_menu_style(selector) {
        $("#" + selector + " #bottom_options").css("flex-direction", "column");
        $("#" + selector + " #create_tables_info").css({
            margin: "5px auto",
        });
        $("#" + selector + " #create_tables_paginate").css({
            margin: "5px auto",
        });
    }

    function bottom_option_style($arg, selector) {
        $("#" + selector + " #bottom_options").css("flex-direction", $arg["flex_direction"]);
        $("#" + selector + " #create_tables_info").css({
            "margin-left": $arg["table_info_style"]["margin_left"],
            "margin-right": $arg["table_info_style"]["margin_right"],
        });
        $("#" + selector + " #create_tables_paginate").css({
            "margin-left": $arg["table_paginate_style"]["margin_left"],
            "margin-right": $arg["table_paginate_style"]["margin_right"],
        });
    }

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

    function table_object(pageLength, ordering, dom, verticalScroll, hideColumn) {
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

        if (isProPluginActive()) {
            obj.lengthMenu = [
                [1, 5, 10, 15, 25, 50, 100, -1],
                [1, 5, 10, 15, 25, 50, 100, "All"],
            ];

            if (verticalScroll != "default") {
                obj.scrollY = `${verticalScroll}px`;
            }
        }

        if (screenSize() === "desktop") {
            if (hideColumn?.desktopValues) {
                obj.columnDefs = hideColumnByScreen(hideColumn.desktopValues);
            }
        } else {
            if (hideColumn?.mobileValues) {
                obj.columnDefs = hideColumnByScreen(hideColumn.mobileValues);
            }
        }

        return obj;
    }

    // Return an array that will define the columns to hide
    function hideColumnByScreen(arrayValues) {
        return [
            {
                targets: convertArrayStringToInteger(arrayValues),
                visible: false,
                searchable: false,
            },
        ];
    }

    // convert string to integer from arrays
    function convertArrayStringToInteger(arr) {
        if (!arr) return [];
        return arr.map((val) => parseInt(val));
    }
    // convert string to integer from arrays
    function convertArrayIntegerToString(arr) {
        if (!arr) return [];
        return arr.map((val) => `${val}`);
    }

    // get the current screen size of user if greater than 740 return desktop or return mobile
    function screenSize() {
        // Desktop screen size
        if (screen.width > 740) {
            return "desktop";
        } else {
            return "mobile";
        }
    }

    function isProPluginActive() {
        if (gswpts_gutenberg_block.isProActive) {
            return true;
        } else {
            return false;
        }
    }

    // Change the cell format of the table
    function changeCellFormat(formatStyle, tableID) {
        let tableCells = null;
        if (tableID == null) {
            tableCells = $("#" + spreadsheet_container.current.id + " table th, td");
        } else {
            tableCells = $("#" + tableID + " table th, td");
        }

        switch (formatStyle) {
            case "wrap":
                $.each(tableCells, function (i, cell) {
                    $(cell).removeClass("clip_style");
                    $(cell).removeClass("expanded_style");
                    $(cell).addClass("wrap_style");
                });
                break;

            case "clip":
                $.each(tableCells, function (i, cell) {
                    $(cell).removeClass("wrap_style");
                    $(cell).removeClass("expanded_style");
                    $(cell).addClass("clip_style");
                });
                break;
            case "expand":
                $.each(tableCells, function (i, cell) {
                    $(cell).removeClass("clip_style");
                    $(cell).removeClass("wrap_style");
                    $(cell).addClass("expanded_style");
                });
                break;

            default:
                break;
        }
    }

    function changeRedirectionType(type, tableID = null) {
        let links = null;
        if (tableID == null) {
            links = $("#" + spreadsheet_container.current.id + " table a");
        } else {
            links = $("#" + tableID + " table a");
        }
        if (!links.length) return;
        $.each(links, function (i, link) {
            $(link).attr("target", type);
        });
    }

    return [
        <InspectorControls style="margin-top: 40px">
            <Panel header="Sheets To WP Table Live Sync">
                {attributes.show_choose_table ? (
                    <PanelBody title="Choose Table" icon="media-text" initialOpen={true}>
                        <SelectControl
                            label="Select Table"
                            value={attributes.table_selection}
                            onChange={(val) => {
                                setAttributes({ table_selection: val });
                                setAttributes({
                                    sortcode_id: typeof val == "string" ? parseInt(val) : null,
                                });
                                fetch_data_by_id(val);
                            }}
                            options={get_table_name_and_data()}
                        />
                    </PanelBody>
                ) : (
                    <></>
                )}

                {attributes.show_settings ? (
                    <>
                        <PanelBody
                            title="Display Settings"
                            icon="admin-settings"
                            initialOpen={false}
                        >
                            <PanelRow>
                                <ToggleControl
                                    label="Show Title"
                                    help="Enable this to show the table title in h3 tag above the table"
                                    checked={attributes.table_settings.table_title}
                                    onChange={() => {
                                        const prevSettingObj = { ...attributes.table_settings };
                                        prevSettingObj.table_title = !prevSettingObj.table_title;
                                        setAttributes({ table_settings: prevSettingObj });

                                        saveChanges(attributes.sortcode_id, prevSettingObj);
                                    }}
                                />
                                <br />
                            </PanelRow>

                            <PanelRow>
                                <div class="default_rows">
                                    <h5 class="header">Default rows per page</h5>
                                    <p>This will show rows per page in the frontend</p>
                                    <Dropdown
                                        placeholder="Default rows per page"
                                        defaultValue={attributes.table_settings.defaultRowsPerPage}
                                        fluid
                                        selection
                                        options={rowsPerPage(isProPluginActive())}
                                        onChange={(e, { value }) => {
                                            const prevSettingObj = {
                                                ...attributes.table_settings,
                                            };
                                            prevSettingObj.defaultRowsPerPage = value;
                                            setAttributes({ table_settings: prevSettingObj });

                                            saveChanges(attributes.sortcode_id, prevSettingObj);

                                            table_changer(attributes.sortcode_id, prevSettingObj);

                                            swap_input_filter(
                                                attributes.sortcode_id,
                                                prevSettingObj.swapFilterInputs
                                            );
                                            swap_bottom_options(
                                                attributes.sortcode_id,
                                                prevSettingObj.swapBottomOptions
                                            );
                                        }}
                                    />
                                </div>
                                <br />
                            </PanelRow>

                            <PanelRow>
                                <ToggleControl
                                    label="Show info block"
                                    help="Show Showing X to Y of Z entries block below the table"
                                    checked={attributes.table_settings.showInfoBlock}
                                    onChange={() => {
                                        const prevSettingObj = { ...attributes.table_settings };
                                        prevSettingObj.showInfoBlock =
                                            !prevSettingObj.showInfoBlock;
                                        setAttributes({ table_settings: prevSettingObj });

                                        saveChanges(attributes.sortcode_id, prevSettingObj);

                                        table_changer(attributes.sortcode_id, prevSettingObj);

                                        swap_input_filter(
                                            attributes.sortcode_id,
                                            prevSettingObj.swapFilterInputs
                                        );
                                        swap_bottom_options(
                                            attributes.sortcode_id,
                                            prevSettingObj.swapBottomOptions
                                        );
                                    }}
                                />
                                <br />
                            </PanelRow>

                            {isProPluginActive() ? (
                                <PanelRow>
                                    <div class="responsive_style">
                                        <h5 class="header">Resposive Style</h5>
                                        <p>
                                            Allow the table to collapse or scroll on mobile and
                                            tablet screen.
                                        </p>
                                        <Dropdown
                                            placeholder="Resposive Style"
                                            defaultValue={attributes.table_settings.responsiveStyle}
                                            fluid
                                            selection
                                            options={responsiveStyles(
                                                isProPluginActive(),
                                                gswpts_gutenberg_block.responsiveStyles
                                            )}
                                            onChange={(e, { value }) => {
                                                const prevSettingObj = {
                                                    ...attributes.table_settings,
                                                };

                                                prevSettingObj.responsiveStyle = value;
                                                setAttributes({ table_settings: prevSettingObj });

                                                saveChanges(attributes.sortcode_id, prevSettingObj);
                                            }}
                                        />
                                    </div>
                                    <br />
                                </PanelRow>
                            ) : null}

                            <PanelRow>
                                <ToggleControl
                                    label="Show X entries"
                                    help="Show X entries per page dropdown"
                                    checked={attributes.table_settings.showXEntries}
                                    onChange={() => {
                                        const prevSettingObj = { ...attributes.table_settings };
                                        prevSettingObj.showXEntries = !prevSettingObj.showXEntries;
                                        setAttributes({ table_settings: prevSettingObj });

                                        saveChanges(attributes.sortcode_id, prevSettingObj);

                                        table_changer(attributes.sortcode_id, prevSettingObj);
                                        swap_input_filter(
                                            attributes.sortcode_id,
                                            prevSettingObj.swapFilterInputs
                                        );
                                        swap_bottom_options(
                                            attributes.sortcode_id,
                                            prevSettingObj.swapBottomOptions
                                        );
                                    }}
                                />
                                <br />
                            </PanelRow>

                            {isProPluginActive() ? (
                                <PanelRow>
                                    <div class="verticall_scrolling">
                                        <h5 class="header">Table Height</h5>
                                        <p>
                                            Choose the height of the table to scroll vertically.
                                            Activating this feature will allow the table to behave
                                            as sticky header
                                        </p>
                                        <Dropdown
                                            placeholder="Vertical Scroll"
                                            defaultValue={attributes.table_settings.verticalScroll}
                                            fluid
                                            selection
                                            options={scrollHeights(
                                                isProPluginActive(),
                                                gswpts_gutenberg_block.scrollHeights
                                            )}
                                            onChange={(e, { value }) => {
                                                const prevSettingObj = {
                                                    ...attributes.table_settings,
                                                };

                                                prevSettingObj.verticalScroll = value;
                                                setAttributes({ table_settings: prevSettingObj });

                                                saveChanges(attributes.sortcode_id, prevSettingObj);
                                                table_changer(
                                                    attributes.sortcode_id,
                                                    prevSettingObj
                                                );
                                                swap_input_filter(
                                                    attributes.sortcode_id,
                                                    prevSettingObj.swapFilterInputs
                                                );
                                                swap_bottom_options(
                                                    attributes.sortcode_id,
                                                    prevSettingObj.swapBottomOptions
                                                );
                                            }}
                                        />
                                    </div>
                                    <br />
                                </PanelRow>
                            ) : null}

                            {isProPluginActive() ? (
                                <PanelRow>
                                    <div class="cell_format">
                                        <h5 class="header">Format Table Cell</h5>
                                        <p>
                                            Format the table cell as like google sheet cell
                                            formatting. Format your cell as Wrap or Clip or Expanded
                                            style
                                        </p>
                                        <Dropdown
                                            placeholder="Cell Format"
                                            defaultValue={attributes.table_settings.cellFormat}
                                            fluid
                                            selection
                                            options={formatCellValues(isProPluginActive())}
                                            onChange={(e, { value }) => {
                                                const prevSettingObj = {
                                                    ...attributes.table_settings,
                                                };

                                                prevSettingObj.cellFormat = value;
                                                setAttributes({ table_settings: prevSettingObj });

                                                saveChanges(attributes.sortcode_id, prevSettingObj);

                                                changeCellFormat(
                                                    prevSettingObj.cellFormat,
                                                    attributes.sortcode_id
                                                );

                                                table_changer(
                                                    attributes.sortcode_id,
                                                    prevSettingObj
                                                );
                                                swap_input_filter(
                                                    attributes.sortcode_id,
                                                    prevSettingObj.swapFilterInputs
                                                );
                                                swap_bottom_options(
                                                    attributes.sortcode_id,
                                                    prevSettingObj.swapBottomOptions
                                                );
                                            }}
                                        />
                                    </div>
                                    <br />
                                </PanelRow>
                            ) : null}

                            {isProPluginActive() ? (
                                <PanelRow>
                                    <div class="redirection_type">
                                        <h5 class="header">Link Redirection Type</h5>
                                        <p>
                                            Choose your desired table style for this table. This
                                            will change the design & color of this table according
                                            to your selected table design
                                        </p>
                                        <Dropdown
                                            placeholder="Redirection Type"
                                            defaultValue={attributes.table_settings.redirectionType}
                                            fluid
                                            selection
                                            options={redirectionValues(isProPluginActive())}
                                            onChange={(e, { value }) => {
                                                const prevSettingObj = {
                                                    ...attributes.table_settings,
                                                };

                                                prevSettingObj.redirectionType = value;
                                                setAttributes({ table_settings: prevSettingObj });

                                                saveChanges(attributes.sortcode_id, prevSettingObj);

                                                changeRedirectionType(
                                                    prevSettingObj.redirectionType,
                                                    attributes.sortcode_id
                                                );

                                                table_changer(
                                                    attributes.sortcode_id,
                                                    prevSettingObj
                                                );
                                                swap_input_filter(
                                                    attributes.sortcode_id,
                                                    prevSettingObj.swapFilterInputs
                                                );
                                                swap_bottom_options(
                                                    attributes.sortcode_id,
                                                    prevSettingObj.swapBottomOptions
                                                );
                                            }}
                                        />
                                    </div>
                                    <br />
                                </PanelRow>
                            ) : null}

                            <PanelRow>
                                <ToggleControl
                                    label="Swap Filters"
                                    help="Swap the places of X entries dropdown and search filter input"
                                    checked={attributes.table_settings.swapFilterInputs}
                                    onChange={() => {
                                        const prevSettingObj = { ...attributes.table_settings };
                                        prevSettingObj.swapFilterInputs =
                                            !prevSettingObj.swapFilterInputs;
                                        setAttributes({ table_settings: prevSettingObj });
                                        swap_input_filter(
                                            attributes.sortcode_id,
                                            prevSettingObj.swapFilterInputs
                                        );

                                        saveChanges(attributes.sortcode_id, prevSettingObj);
                                    }}
                                />
                                <br />
                            </PanelRow>

                            <PanelRow>
                                <ToggleControl
                                    label="Swap Bottom Elements"
                                    help="Swap the places of Showing X to Y of Z entries with table pagination filter"
                                    checked={attributes.table_settings.swapBottomOptions}
                                    onChange={() => {
                                        const prevSettingObj = { ...attributes.table_settings };
                                        prevSettingObj.swapBottomOptions =
                                            !prevSettingObj.swapBottomOptions;
                                        setAttributes({ table_settings: prevSettingObj });
                                        swap_bottom_options(
                                            attributes.sortcode_id,
                                            prevSettingObj.swapBottomOptions
                                        );

                                        saveChanges(attributes.sortcode_id, prevSettingObj);
                                    }}
                                />
                                <br />
                            </PanelRow>

                            {isProPluginActive() ? (
                                <PanelRow>
                                    <div class="table_style">
                                        <h5 class="header">Table Style</h5>
                                        <p>
                                            Choose your desired table style for this table. This
                                            will change the design & color of this table according
                                            to your selected table design
                                        </p>
                                        <Dropdown
                                            placeholder="Choose Style"
                                            defaultValue={attributes.table_settings.tableStyle}
                                            fluid
                                            selection
                                            options={tableStyles(
                                                isProPluginActive(),
                                                gswpts_gutenberg_block.tableStyles
                                            )}
                                            onChange={(e, { value }) => {
                                                const prevSettingObj = {
                                                    ...attributes.table_settings,
                                                };

                                                prevSettingObj.tableStyle = value;
                                                setAttributes({ table_settings: prevSettingObj });

                                                saveChanges(attributes.sortcode_id, prevSettingObj);

                                                table_changer(
                                                    attributes.sortcode_id,
                                                    prevSettingObj
                                                );
                                            }}
                                        />
                                    </div>
                                    <br />
                                </PanelRow>
                            ) : null}

                            {isProPluginActive() ? (
                                <PanelRow>
                                    <div class="import_styles">
                                        <h5 class="header">Import Sheet Styles</h5>
                                        <ToggleControl
                                            label="Import Sheet Styles"
                                            help=" Import cell backgorund color & cell font color from
                                            google sheet. If you activate this feature it will
                                            overrider <i>Table Style</i> settings"
                                            checked={attributes.table_settings.importStyles}
                                            onChange={(val) => {
                                                const prevSettingObj = {
                                                    ...attributes.table_settings,
                                                };

                                                prevSettingObj.importStyles =
                                                    !prevSettingObj.importStyles;

                                                setAttributes({ table_settings: prevSettingObj });

                                                saveChanges(attributes.sortcode_id, prevSettingObj);
                                            }}
                                        />
                                    </div>
                                    <br />
                                </PanelRow>
                            ) : null}
                        </PanelBody>

                        <PanelBody title="Sort & Filter" icon="filter" initialOpen={false}>
                            <PanelRow>
                                <ToggleControl
                                    label="Allow Sorting"
                                    help="Enable this feature to sort table data for frontend."
                                    checked={attributes.table_settings.allowSorting}
                                    onChange={() => {
                                        const prevSettingObj = { ...attributes.table_settings };
                                        prevSettingObj.allowSorting = !prevSettingObj.allowSorting;
                                        setAttributes({ table_settings: prevSettingObj });
                                        table_changer(attributes.sortcode_id, prevSettingObj);
                                        swap_input_filter(
                                            attributes.sortcode_id,
                                            prevSettingObj.swapFilterInputs
                                        );
                                        swap_bottom_options(
                                            attributes.sortcode_id,
                                            prevSettingObj.swapBottomOptions
                                        );

                                        saveChanges(attributes.sortcode_id, prevSettingObj);
                                    }}
                                />
                                <br />
                            </PanelRow>

                            <PanelRow>
                                <ToggleControl
                                    label="Search Bar"
                                    help="Enable this feature to show a search bar in for the table. It will help user to search data in the table"
                                    checked={attributes.table_settings.searchBar}
                                    onChange={() => {
                                        const prevSettingObj = { ...attributes.table_settings };
                                        prevSettingObj.searchBar = !prevSettingObj.searchBar;
                                        setAttributes({ table_settings: prevSettingObj });
                                        table_changer(attributes.sortcode_id, prevSettingObj);
                                        swap_input_filter(
                                            attributes.sortcode_id,
                                            prevSettingObj.swapFilterInputs
                                        );
                                        swap_bottom_options(
                                            attributes.sortcode_id,
                                            prevSettingObj.swapBottomOptions
                                        );

                                        saveChanges(attributes.sortcode_id, prevSettingObj);
                                    }}
                                />
                                <br />
                            </PanelRow>
                        </PanelBody>

                        {/* Table Tools */}

                        {isProPluginActive() ? (
                            <PanelBody title="Table Tools" icon="admin-tools" initialOpen={false}>
                                <PanelRow>
                                    <ToggleControl
                                        label="Table Caching"
                                        help="Enabling this feature would cache the Google sheet data & therefore the table will load faster than before. Also it will load the updated data when there is a change in your Google sheet. "
                                        checked={attributes.table_settings.tableCache}
                                        onChange={() => {
                                            const prevSettingObj = { ...attributes.table_settings };
                                            prevSettingObj.tableCache = !prevSettingObj.tableCache;
                                            setAttributes({ table_settings: prevSettingObj });

                                            saveChanges(attributes.sortcode_id, prevSettingObj);
                                        }}
                                    />
                                    <br />
                                </PanelRow>

                                {/* Column hide feature in desktop */}
                                <PanelRow>
                                    <div class="hide_column">
                                        <h5 class="header">Hide Columns In Desktop Screen:</h5>
                                        <p>Hide your table columns on desktop screen size.</p>
                                        <Dropdown
                                            placeholder=""
                                            defaultValue={
                                                attributes.table_settings.hideColumn?.desktopValues
                                                    ? convertArrayStringToInteger(
                                                          attributes.table_settings.hideColumn
                                                              .desktopValues
                                                      )
                                                    : ""
                                            }
                                            fluid
                                            selection
                                            multiple
                                            options={attributes.tableColumns}
                                            onChange={(e, { value }) => {
                                                const prevSettingObj = {
                                                    ...attributes.table_settings,
                                                };
                                                prevSettingObj.hideColumn.desktopValues =
                                                    convertArrayIntegerToString(value);

                                                setAttributes({ table_settings: prevSettingObj });
                                                saveChanges(attributes.sortcode_id, prevSettingObj);

                                                table_changer(
                                                    attributes.sortcode_id,
                                                    prevSettingObj
                                                );
                                                swap_input_filter(
                                                    attributes.sortcode_id,
                                                    prevSettingObj.swapFilterInputs
                                                );
                                                swap_bottom_options(
                                                    attributes.sortcode_id,
                                                    prevSettingObj.swapBottomOptions
                                                );
                                            }}
                                        />
                                    </div>
                                    <br />
                                </PanelRow>

                                {/* Column hide feature in mobile */}
                                <PanelRow>
                                    <div class="hide_column">
                                        <h5 class="header">Hide Columns In Mobile Screen:</h5>
                                        <p>Hide your table columns on mobile screen size.</p>
                                        <Dropdown
                                            placeholder=""
                                            defaultValue={
                                                attributes.table_settings.hideColumn
                                                    ?.mobileValues != null
                                                    ? convertArrayStringToInteger(
                                                          attributes.table_settings.hideColumn
                                                              .mobileValues
                                                      )
                                                    : ""
                                            }
                                            fluid
                                            selection
                                            multiple
                                            options={attributes.tableColumns}
                                            onChange={(e, { value }) => {
                                                const prevSettingObj = {
                                                    ...attributes.table_settings,
                                                };
                                                prevSettingObj.hideColumn.mobileValues =
                                                    convertArrayIntegerToString(value);
                                                setAttributes({ table_settings: prevSettingObj });
                                                saveChanges(attributes.sortcode_id, prevSettingObj);

                                                table_changer(
                                                    attributes.sortcode_id,
                                                    prevSettingObj
                                                );
                                                swap_input_filter(
                                                    attributes.sortcode_id,
                                                    prevSettingObj.swapFilterInputs
                                                );
                                                swap_bottom_options(
                                                    attributes.sortcode_id,
                                                    prevSettingObj.swapBottomOptions
                                                );
                                            }}
                                        />
                                    </div>
                                    <br />
                                </PanelRow>
                            </PanelBody>
                        ) : null}
                        {/* End of table tools */}
                    </>
                ) : (
                    <></>
                )}
            </Panel>
        </InspectorControls>,
        <div
            class="gswpts_create_table_container"
            class={`gswpts_create_table_container gswpts_${embedTableStyleClass(attributes)}`}
            id={attributes.sortcode_id}
            style={{ marginRight: "0" }}
        >
            {" "}
            {attributes.table_name != "" && attributes.table_settings.table_title ? (
                <h3> {attributes.table_name} </h3>
            ) : (
                <> </>
            )}{" "}
            {attributes.block_init ? (
                attributes.initializer_button_action == "choose_table" ? (
                    <div
                        id="spreadsheet_container"
                        dangerouslySetInnerHTML={{ __html: attributes.innerHTML }}
                    ></div>
                ) : (
                    <>
                        {attributes.is_table_saved_to_db == false ? (
                            <div class="create_table_input">
                                <div class="ui icon input">
                                    <input
                                        required
                                        type="text"
                                        name="table_name"
                                        placeholder="Table Name"
                                        value={attributes.init_table_name}
                                        onChange={(e) => {
                                            setAttributes({ init_table_name: e.target.value });
                                        }}
                                    />{" "}
                                </div>

                                <div class="ui icon input">
                                    <input
                                        required
                                        type="text"
                                        name="file_input"
                                        placeholder="Enter the google spreadsheet public url."
                                        value={attributes.sheet_url}
                                        onChange={(e) => {
                                            setAttributes({ sheet_url: e.target.value });
                                        }}
                                    />{" "}
                                    <i class="file icon"> </i>{" "}
                                </div>

                                <button
                                    class="ui violet button"
                                    type="button"
                                    id="fetch_save_btn"
                                    onClick={(e) => {
                                        fetch_data_by_url(attributes.sheet_url);
                                    }}
                                >
                                    {" "}
                                    {attributes.btn_text}{" "}
                                </button>
                            </div>
                        ) : (
                            <></>
                        )}
                        <div
                            ref={spreadsheet_container}
                            id="spreadsheet_container"
                            dangerouslySetInnerHTML={{ __html: attributes.innerHTML }}
                        ></div>{" "}
                    </>
                )
            ) : (
                <div class="block_initializer">
                    <button
                        id="create_button"
                        class="positive ui button"
                        onClick={(e) => {
                            setAttributes({ block_init: true });
                            setAttributes({ initializer_button_action: "create_new" });
                            setAttributes({ innerHTML: "" });
                        }}
                    >
                        Create New &nbsp; <i class="plus icon"> </i>{" "}
                    </button>

                    <button
                        class="ui violet button"
                        type="button"
                        onClick={(e) => {
                            setAttributes({ block_init: true });
                            setAttributes({ initializer_button_action: "choose_table" });
                            setAttributes({ show_choose_table: true });
                            // document.querySelector(".interface-pinned-items > button").click();
                        }}
                    >
                        Choose Table{" "}
                    </button>
                </div>
            )}
        </div>,
    ];
}

// Inject the table style class if import style is not active
function embedTableStyleClass(attributes) {
    if (attributes.table_settings.importStyles) {
        return "default-style";
    } else {
        return attributes.table_settings.tableStyle;
    }
}
