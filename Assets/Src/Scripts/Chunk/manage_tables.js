import Base_Class from "./../Base/base_class";

jQuery(document).ready(function ($) {
    class Manage_Tables extends Base_Class {
        constructor() {
            super($);
            this.table_container = $("#gswpts_tables_container");
            this.checkbox_switcher = false;
            this.events();
        }
        events() {
            this.fetch_data_by_page();
            $(document).on("click", ".gswpts_sortcode_copy", (e) => {
                this.copy_shorcode(e);
            });
            $(document).on("click", "#manage_tables_checkbox", (e) => {
                this.toggle_content(e);
            });
        }
        fetch_data_by_page() {
            if (
                !this.get_slug_parameter("page") ||
                this.get_slug_parameter("page") !== "gswpts-dashboard"
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
                    if (
                        JSON.parse(res).response_type == "invalid_action" ||
                        JSON.parse(res).response_type == "invalid_request"
                    ) {
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
                    if (JSON.parse(res.responseText).response_type == "success") {
                        $("#manage_tables").DataTable({
                            columnDefs: [
                                {
                                    targets: [0, 5],
                                    orderable: false,
                                },
                            ],
                            order: [],
                        });

                        setTimeout(() => {
                            if (JSON.parse(res.responseText).no_data == "true") {
                                this.call_alert(
                                    "Warning &#9888;&#65039;",
                                    "<b>No tables found. Create a new</b>",
                                    "warning",
                                    3
                                );
                            } else {
                                this.call_alert(
                                    "Successfull &#128077;",
                                    "<b>All Tables Fetched Successfully</b>",
                                    "success",
                                    3
                                );
                            }
                        }, 700);
                    }
                },
            });
        }

        toggle_content(e) {
            if ($(e.currentTarget).attr("data-show") == "false") {
                $("#delete_button").transition("scale");
                this.check_all_checkbox();
                this.checkbox_switcher = true;
            }
            if ($(e.currentTarget).attr("data-show") == "true") {
                $("#delete_button").transition("scale");
                setTimeout(() => {}, 300);
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
    }
    new Manage_Tables();
});
