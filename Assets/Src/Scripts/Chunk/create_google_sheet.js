import Base_Class from "./../Base/base_class";

jQuery(document).ready(function ($) {
    class Google_Sheets_Creation extends Base_Class {
        constructor() {
            super($);
            this.fetch_and_save_button = $("#fetch_save_btn");
            this.sheet_url = "";
            this.dropdown_select = $("#table_type");

            this.events();
        }

        events() {
            this.initDropdownSelect();

            this.dropdown_select.on("change", (e) => {
                this.show_fetch_btn(e);
                this.show_hidden_input(e);
            });

            $(document).on("click", "#fetch_save_btn", (e) => {
                this.handle_submit(e);
            });

            $(document).on("click", "#sortcode_copy", (e) => {
                this.copy_shorcode(e);
            });
            $(document).on("click", "#create_button", (e) => {
                this.clear_fields();
            });
            $(".edit_table_name").on("click", (e) => {
                this.edit_table_name(e);
            });
        }

        initDropdownSelect() {
            if ($("#table_type").length) {
                $("#table_type").dropdown();
            }
            if ($("#rows_per_page").length) {
                $("#rows_per_page").dropdown("set selected", "10");
            }
            if ($("#vertical_scrolling").length) {
                $("#vertical_scrolling").dropdown();
            }
            if ($("#cell_format").length) {
                $("#cell_format").dropdown();
            }
            if ($("#redirection_type").length) {
                $("#redirection_type").dropdown();
            }
            $("#table_type").find("input[name=source_type]").val("");
        }
        edit_table_name(e) {
            $(e.currentTarget).siblings("input").select();
        }

        show_fetch_btn(e) {
            if ($(e.currentTarget).find("input[name=source_type]").val()) {
                if ($("#fetch_save_btn").hasClass("hidden")) {
                    $("#fetch_save_btn").transition("scale");
                }
            }
        }

        show_hidden_input(e) {
            if ($(e.currentTarget).find("input[name=source_type]").val() == "spreadsheet") {
                this.show_file_input();
                if ($(".browse_input").hasClass("visible")) {
                    $(".browse_input").transition("scale");
                }
            }
            if ($(e.currentTarget).find("input[name=source_type]").val() == "csv") {
                this.show_file_input();
                this.show_browser_input();
            }
        }

        show_file_input() {
            if ($(".file_input").hasClass("hidden")) {
                $(".file_input").transition("scale");
            }
        }

        show_browser_input() {
            if ($(".browse_input").hasClass("hidden")) {
                $(".browse_input").transition("scale");
            }
        }

        handle_submit(e) {
            e.preventDefault();

            let submit_button = $(e.currentTarget);
            let table_name = $("#table_name").val();
            let form_data = this.sheet_form.serialize();
            if (this.sheet_form.find("input[name=file_input]").val() == "") {
                this.call_alert(
                    "Warning &#9888;&#65039;",
                    "<b>Form field is empty. Please fill out the field</b>",
                    "warning",
                    3
                );
                return;
            }

            $.ajax({
                url: file_url.admin_ajax,

                data: {
                    action: "gswpts_sheet_create",
                    form_data: form_data,
                    table_name: table_name,
                    table_settings: this.table_settings_obj(),
                    id: this.get_slug_parameter("id"),
                    type: submit_button.attr("req-type"),
                },

                type: "post",

                beforeSend: () => {
                    if (submit_button.attr("req-type") == "fetch") {
                        let html = `
                            Fetching Data &nbsp;
                            <div class="ui active mini inline loader"></div>
                        `;
                        this.btn_changer(submit_button, html, "fetch");
                        this.sheet_container.html(this.html_loader);
                    } else {
                        let html = `
                                Saving Table &nbsp;
                                <div class="ui active mini inline loader"></div>
                            `;

                        if (submit_button.attr("req-type") == "save_changes") {
                            html = `
                                Saving Changes &nbsp;
                                <div class="ui active mini inline loader"></div>
                            `;
                        }

                        this.btn_changer(submit_button, html, "save");
                    }
                },

                success: (res) => {
                    console.log(res);
                    if (
                        JSON.parse(res).response_type == "invalid_action" ||
                        JSON.parse(res).response_type == "invalid_request"
                    ) {
                        this.call_alert("Error &#128683;", JSON.parse(res).output, "error", 5);
                        this.btn_changer(submit_button, "Fetch Data", "fetch");
                        this.clear_fields();
                        this.sheet_container.html("");
                    }

                    if (JSON.parse(res).response_type == "empty_field") {
                        this.call_alert(
                            "Warning &#9888;&#65039;",
                            JSON.parse(res).output,
                            "warning",
                            3
                        );
                        this.btn_changer(submit_button, "Fetch Data", "fetch");
                        this.sheet_container.html("");
                    }

                    if (JSON.parse(res).response_type == "success") {
                        /* Chnage the tabs li attribute text that will effect fetch/save button text */
                        $(".tables_settings").attr("data-btn-text", "Save Table");

                        /* Chnage the tabs li attribute text that will effect fetch/save button attribute value to save/fetch*/
                        $(".tables_settings").attr("data-attr-text", "save");

                        /* Remove the disable button atrributes and class */
                        $(".disabled_checkbox").removeClass("disabled_checkbox");
                        $(".secondary_inputs").attr("disabled", false);

                        this.sheet_details.transition("scale");
                        this.sheet_container.parent().removeClass("mt-4").addClass("mt-3");
                        this.sheet_container.html(JSON.parse(res).output);
                    }

                    if (JSON.parse(res).response_type == "saved") {
                        this.btn_changer(submit_button, "Table Saved", "saved");

                        this.sheet_details.addClass("mt-4 p-0");
                        this.sheet_details.html(this.sheet_details_html(JSON.parse(res)));

                        this.call_alert(
                            "Successfull &#128077;",
                            JSON.parse(res).output,
                            "success",
                            3
                        );
                        let id = Object.values(JSON.parse(res).id)[0];
                        this.show_shortcode(id);
                        this.sheet_url = JSON.parse(res).sheet_url;
                        this.show_create_btn();
                        this.push_parameter(id);
                        // Add classname as .tables_settings to save table changes
                        $("#gswpts_tabs ul li").addClass("tables_settings");
                    }

                    if (JSON.parse(res).response_type == "updated") {
                        let html = `
                                Save Changes
                            `;
                        this.btn_changer(submit_button, html, "save_changes");
                        this.call_alert(
                            "Successfull &#128077;",
                            JSON.parse(res).output,
                            "success",
                            3
                        );
                    }

                    if (JSON.parse(res).response_type == "sheet_exists") {
                        this.call_alert(
                            "Warning &#9888;&#65039;",
                            JSON.parse(res).output,
                            "warning",
                            3
                        );
                        this.btn_changer(submit_button, "Save Table", "save");
                    }
                },

                complete: (res) => {
                    if (JSON.parse(res.responseText).response_type == "success") {
                        let default_settings = this.default_settings();
                        let table_name = $("#table_name").val();
                        let defaultRowsPerPage = default_settings.defaultRowsPerPage;
                        let allowSorting = default_settings.allowSorting;
                        let dom = '<"#filtering_input"lf>rt<"#bottom_options"ip>';

                        if (this.isProPluginActive()) {
                            dom = 'B<"#filtering_input"lf>rt<"#bottom_options"ip>';
                        }

                        $("#create_tables").DataTable(
                            this.table_object(table_name, defaultRowsPerPage, allowSorting, dom)
                        );

                        this.btn_changer(submit_button, "Save Table", "save");

                        setTimeout(() => {
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
                        }, 700);
                    }
                },

                error: (err) => {
                    this.call_alert("Error &#128683;", "<b>Something went wrong</b>", "error", 3);
                    this.btn_changer(submit_button, "Fetch Data", "fetch");
                    this.sheet_container.html("");
                },
            });
        }

        copy_shorcode(e) {
            let input = $(e.currentTarget).siblings("input");
            input.focus();
            input.select();
            document.execCommand("copy");
            this.call_alert("Copied &#128077;", "Sortcode copied successfully", "info", 2);
        }

        show_create_btn() {
            if (!$("#create_button").hasClass("visible")) {
                $("#create_button").transition("scale");
            }
        }

        clear_fields() {
            this.sheet_form.find("input[name=file_input]").val("");
            $(".edit_table_name").attr("disabled", false);
            $("#table_name").val("GSWPTS Table");
            $("#table_name").attr("disabled", false);
            $("#tab1").prop("checked", true);

            /* add the disable button atrributes and class */
            $(".tables_settings").addClass("disabled_checkbox");
            $(".tables_settings").unbind("click");
            $(".secondary_inputs").attr("disabled", true);

            $("#sheet_ui_card").transition("scale");
            $("#create_tables_wrapper").transition("scale");
            this.btn_changer(this.fetch_and_save_button, "Fetch Data", "fetch");
            setTimeout(() => {
                this.sheet_details.transition("scale");
                $("#sheet_ui_card").remove();
                $("#create_tables_wrapper").remove();
            }, 300);
        }

        push_parameter(id) {
            const url = new URL(window.location);
            url.searchParams.set("id", id);
            window.history.pushState({}, "", url);
        }
    }
    new Google_Sheets_Creation();
});
