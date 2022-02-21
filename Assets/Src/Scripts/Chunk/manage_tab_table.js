import BaseClass from "../base/base_class";

jQuery(document).ready(function ($) {
    class ManageTabTables extends BaseClass {
        constructor() {
            super($);
            this.tabContainer = $("#gswpts_tab_container");
            this.unSelectBtn = $("#manage_tab_unselect_btn");
            this.deleteBtn = $("#tab_delete_button");
            this.checkbox_switcher = false;
            this.events();
        }
        events() {
            this.fetch_data_by_page();

            $(document).on("click", ".gswpts_tab_sortcode_copy", (e) => {
                this.copy_shorcode(e);
                this.higlightSortcodeText(e);
            });

            $(document).on("click", "#manage_tab_checkbox", (e) => {
                this.toggle_content(e);
            });

            this.unSelectBtn.on("click", (e) => {
                this.clearSelection();
            });

            $(document).on("change", ".manage_tab_name_toggle > input", (e) => {
                this.updateTabNameToggle(e);
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
                this.get_slug_parameter("page") !== "gswpts-manage-tab" ||
                this.get_slug_parameter("subpage") == "create-tab"
            ) {
                return;
            }

            $.ajax({
                url: file_url.admin_ajax,

                data: {
                    action: "gswpts_manage_tab",
                    page_slug: this.get_slug_parameter("page"),
                },
                type: "post",

                success: (res) => {
                    if (JSON.parse(res).response_type == "invalid_action" || JSON.parse(res).response_type == "invalid_request") {
                        this.tabContainer.html("");
                        this.call_alert("Error &#128683;", JSON.parse(res).output, "error", 4);
                    }

                    if (JSON.parse(res).response_type == "success") {
                        this.tabContainer.html(JSON.parse(res).output);
                    }
                },

                complete: (res) => {
                    $(".tab_create_btn").transition("show");

                    if (JSON.parse(res.responseText).response_type == "success") {
                        $("#manage_tabs").DataTable({
                            columnDefs: [
                                {
                                    targets: [0, 4],
                                    orderable: false,
                                },
                            ],
                            bInfo: false,
                            order: [],
                        });

                        if (JSON.parse(res.responseText).no_data == "true") {
                            this.call_alert("Warning &#9888;&#65039;", "<b>No tab found. Create a new</b>", "warning", 3);
                        } else {
                            this.call_alert("Successfull &#128077;", "<b>All Tabs Fetched Successfully</b>", "success", 3);
                        }
                    }
                },

                error: (err) => {
                    this.call_alert("Error &#128683;", "<b>Something went wrong</b>", "error", 3);
                    this.tabContainer.html("");
                    $(".tab_create_btn").css({
                        "margin-left": "unset",
                        top: "unset",
                    });
                },
            });
        }

        toggle_content(e) {
            if ($(e.currentTarget).attr("data-show") == "false") {
                this.deleteBtn.transition("scale");
                this.unSelectBtn.transition("scale");
                this.check_all_checkbox();
                this.checkbox_switcher = true;
            }
            if ($(e.currentTarget).attr("data-show") == "true") {
                this.deleteBtn.transition("scale");
                this.unSelectBtn.transition("scale");
                this.uncheck_all_checkbox();
                this.checkbox_switcher = false;
            }
            $(e.currentTarget).attr("data-show", "" + this.checkbox_switcher + "");
        }

        check_all_checkbox() {
            $(".manage_tab_checkbox").prop("checked", true);
        }

        uncheck_all_checkbox() {
            $(".manage_tab_checkbox").prop("checked", false);
        }

        // Clear all the selected table that are meant to be deleted.
        clearSelection(e) {
            $("#manage_tab_checkbox").prop("checked", false);
            $("#manage_tab_checkbox").attr("data-show", false);
            this.deleteBtn.transition("scale");
            this.unSelectBtn.transition("scale");
            this.uncheck_all_checkbox();
            this.checkbox_switcher = false;
        }

        // Update the tab name toggle checkbox to show/hide the tab name in frontend
        updateTabNameToggle(e) {
            let target = $(e.currentTarget);
            let value = target.prop("checked");
            let tabID = target.attr("data-id");

            if (!tabID) {
                this.call_alert("Warning &#9888;&#65039;", "Tab id not found to update", "warning", 3);
                return;
            }

            $.ajax({
                url: file_url.admin_ajax,

                data: {
                    action: "gswpts_manage_tab_toggle",
                    show_name: value,
                    tabID,
                },

                type: "post",

                success: (res) => {
                    if (JSON.parse(res).response_type == "invalid_action" || JSON.parse(res).response_type == "invalid_request") {
                        this.call_alert("Error &#128683;", JSON.parse(res).output, "error", 4);
                    }

                    if (JSON.parse(res).response_type == "success") {
                        this.call_alert("Successfull &#128077;", JSON.parse(res).output, "success", 3);
                    }
                },

                error: (err) => {
                    this.call_alert("Error &#128683;", "<b>Something went wrong</b>", "error", 3);
                },
            });
        }
    }
    new ManageTabTables();
});
