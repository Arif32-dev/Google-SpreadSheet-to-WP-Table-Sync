import Base_Class from "./../Base/base_class";

jQuery(document).ready(function ($) {
    class Fetch_Sheet_Data extends Base_Class {
        constructor() {
            super($);
            this.events();
        }
        events() {
            this.fetch_data_by_id();
        }
        fetch_data_by_id() {
            if (!this.get_slug_parameter("id")) {
                return;
            }
            $.ajax({
                url: file_url.admin_ajax,

                data: {
                    action: "gswpts_sheet_fetch",
                    id: this.get_slug_parameter("id"),
                },
                type: "post",

                beforeSend: () => {
                    $("#tab1").prop("checked", true);

                    $("#gswpts_tabs ul li:not(:nth-child(1))").addClass("disabled_checkbox");
                },

                success: (res) => {
                    let parsedResponse = JSON.parse(res);

                    console.log(parsedResponse);

                    if (
                        parsedResponse.response_type == "invalid_action" ||
                        parsedResponse.response_type == "invalid_request"
                    ) {
                        this.sheet_container.html("");
                        this.call_alert("Error &#128683;", parsedResponse.output, "error", 4);
                    }

                    if (parsedResponse.response_type == "success") {
                        this.sheet_details.addClass("mt-4 p-0");
                        $("#gswpts_tabs ul li:not(:nth-child(1))").removeClass("disabled_checkbox");

                        setTimeout(() => {
                            $(".edit_table_name")
                                .siblings("input[name=table_name]")
                                .val(parsedResponse.table_data.table_name);
                            $(".edit_table_name").parent().transition("fade up");
                            $("#table_type").dropdown(
                                "set selected",
                                parsedResponse.table_data.source_type
                            );
                            this.sheet_form
                                .find("input[name=file_input]")
                                .val(parsedResponse.table_data.source_url);
                            this.sheet_details.html(this.sheet_details_html(parsedResponse));
                            this.sheet_details.transition("scale");
                            this.show_shortcode(this.get_slug_parameter("id"));
                        }, 400);

                        this.sheet_container.html(parsedResponse.output);
                        // insert the column value to input field and make a drop
                        this.insertColumnValueToInput(parsedResponse.tableColumns);
                    }
                },

                error: (err) => {
                    this.call_alert("Error &#128683;", "<b>Something went wrong</b>", "error", 3);
                    this.sheet_container.html("");
                },

                complete: (res) => {
                    if (JSON.parse(res.responseText).response_type == "success") {
                        let table_settings = JSON.parse(
                            JSON.parse(res.responseText).table_data.table_settings
                        );

                        let table_name = JSON.parse(res.responseText).table_data.table_name;

                        let dom = `<"#filtering_input"${
                            table_settings.show_x_entries == "true" ? "l" : ""
                        }${table_settings.search_bar == "true" ? "f" : ""}>rt<"#bottom_options"${
                            table_settings.show_info_block == "true" ? "i" : ""
                        }p>`;

                        if (this.isProPluginActive()) {
                            dom = `B<"#filtering_input"${
                                table_settings.show_x_entries == "true" ? "l" : ""
                            }${
                                table_settings.search_bar == "true" ? "f" : ""
                            }>rt<"#bottom_options"${
                                table_settings.show_info_block == "true" ? "i" : ""
                            }p>`;
                        }

                        let swap_filter_inputs =
                            table_settings.swap_filter_inputs == "true" ? true : false;
                        let swap_bottom_options =
                            table_settings.swap_bottom_options == "true" ? true : false;

                        /* This will trigger the change event and its related functionality in table_changes.js  */
                        this.reconfigure_input_fields(
                            JSON.parse(JSON.parse(res.responseText).table_data.table_settings)
                        );

                        setTimeout(() => {
                            let tableSettings = {
                                allowSorting: table_settings.allow_sorting || "",
                                cellFormat: table_settings.cell_format || "",
                                defaultRowsPerPage: table_settings.default_rows_per_page || "",
                                hideColumn: table_settings.hide_column || "",
                                redirectionType: table_settings.redirection_type || "",
                                responsiveStyle: table_settings.responsive_style || "",
                                searchBar: table_settings.search_bar || "",
                                showInfoBlock: table_settings.show_info_block || "",
                                showXEntries: table_settings.show_x_entries || "",
                                swapBottomOptions: table_settings.swap_bottom_options || "",
                                swapFilterInputs: table_settings.swap_filter_inputs || "",
                                tableCache: table_settings.table_cache || "",
                                tableExport: table_settings.table_export || "",
                                tableStyle: table_settings.table_style || "",
                                tableTitle: table_settings.table_title || "",
                                verticalScroll: table_settings.vertical_scroll || "",
                            };

                            $("#create_tables").DataTable(
                                this.table_object(table_name, dom, tableSettings)
                            );

                            this.addDraggingAbility();

                            this.swap_filter_inputs(swap_filter_inputs);

                            this.swap_bottom_options(swap_bottom_options);

                            if (table_settings.table_export != "empty") {
                                this.export_buttons_row_revealer(table_settings.table_export);
                            }

                            this.show_fetch_btn();

                            this.call_alert(
                                "Successfull &#128077;",
                                "<b>Google Sheet data fetched successfully</b>",
                                "success",
                                3
                            );

                            if (!this.isProPluginActive()) {
                                this.call_alert(
                                    "Warning &#9888;&#65039;",
                                    "<b>Live sync is limited to 20 rows.<br/><a target='blank' href='https://wppool.dev/sheets-to-wp-table-live-sync/'>Upgrade to Pro</a> for showing full google sheet.</b>",
                                    "warning",
                                    10
                                );
                            }
                        }, 200);
                    }
                },
            });
        }
        show_fetch_btn() {
            if ($("#fetch_save_btn").hasClass("hidden")) {
                $("#fetch_save_btn").transition("scale");
            }
        }
    }
    new Fetch_Sheet_Data();
});
