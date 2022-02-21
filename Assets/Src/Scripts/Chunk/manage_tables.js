import BaseClass from "../base/base_class";

jQuery(document).ready(function ($) {
    class Manage_Tables extends BaseClass {
        constructor() {
            super($);
            this.table_container = $("#gswpts_tables_container");
            this.unSelectBtn = $("#unselect_btn");
            this.checkbox_switcher = false;
            this.events();
        }
        events() {
            this.fetch_data_by_page();
            $(document).on("click", ".gswpts_sortcode_copy", (e) => {
                this.copy_shorcode(e);
                this.higlightSortcodeText(e);
            });
            $(document).on("click", "#manage_tables_checkbox", (e) => {
                this.toggle_content(e);
            });

            this.unSelectBtn.on("click", (e) => {
                this.clearSelection();
            });
        }

        higlightSortcodeText(e) {
            // implement plan js to highlight text
            let node = $(e.currentTarget)[0];
            if (document.body.createTextRange) {
                const range = document.body.createTextRange();
                range.moveToElementText(node);
                range.select();
            } else if (window.getSelection) {
                const selection = window.getSelection();
                const range = document.createRange();
                range.selectNodeContents(node);
                selection.removeAllRanges();
                selection.addRange(range);
            } else {
                console.warn("Could not select text in node: Unsupported browser.");
            }
        }
        fetch_data_by_page() {
            if (
                !this.get_slug_parameter("page") ||
                this.get_slug_parameter("page") !== "gswpts-dashboard" ||
                this.get_slug_parameter("subpage") == "create-table"
            ) {
                return;
            }
            $.ajax({
                url: file_url.admin_ajax,

                data: {
                    action: "gswpts_table_fetch",
                    page_slug: this.get_slug_parameter("page"),
                },
                type: "post",

                success: (res) => {
                    if (JSON.parse(res).response_type == "invalid_action" || JSON.parse(res).response_type == "invalid_request") {
                        this.table_container.html("");
                        this.call_alert("Error &#128683;", JSON.parse(res).output, "error", 4);
                    }
                    if (JSON.parse(res).response_type == "success") {
                        this.table_container.html(JSON.parse(res).output);
                    }
                },

                error: (err) => {
                    this.call_alert("Error &#128683;", "<b>Something went wrong</b>", "error", 3);
                    this.sheet_container.html("");
                },

                complete: (res) => {
                    $(".create_table_btn").transition("show");

                    if (JSON.parse(res.responseText).response_type == "success") {
                        $("#manage_tables").DataTable({
                            columnDefs: [
                                {
                                    targets: [0, 5],
                                    orderable: false,
                                },
                            ],
                            bInfo: false,
                            order: [],
                        });

                        if (JSON.parse(res.responseText).no_data == "true") {
                            this.call_alert("Warning &#9888;&#65039;", "<b>No tables found. Create a new</b>", "warning", 3);
                        } else {
                            this.call_alert("Successfull &#128077;", "<b>All Tables Fetched Successfully</b>", "success", 3);
                        }
                    }
                },
            });
        }

        toggle_content(e) {
            if ($(e.currentTarget).attr("data-show") == "false") {
                $("#delete_button").transition("scale");
                $("#unselect_btn").transition("scale");
                this.check_all_checkbox();
                this.checkbox_switcher = true;
            }
            if ($(e.currentTarget).attr("data-show") == "true") {
                $("#delete_button").transition("scale");
                $("#unselect_btn").transition("scale");
                this.uncheck_all_checkbox();
                this.checkbox_switcher = false;
            }
            $(e.currentTarget).attr("data-show", "" + this.checkbox_switcher + "");
        }

        check_all_checkbox() {
            $(".manage_tables_checkbox").prop("checked", true);
        }

        uncheck_all_checkbox() {
            $(".manage_tables_checkbox").prop("checked", false);
        }

        // Clear all the selected table that are meant to be deleted.
        clearSelection(e) {
            $("#manage_tables_checkbox").prop("checked", false);
            $("#manage_tables_checkbox").attr("data-show", false);
            $("#delete_button").transition("scale");
            $("#unselect_btn").transition("scale");
            this.uncheck_all_checkbox();
            this.checkbox_switcher = false;
        }
    }
    new Manage_Tables();
});
