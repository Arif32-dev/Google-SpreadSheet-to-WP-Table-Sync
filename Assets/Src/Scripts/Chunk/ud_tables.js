import Base_Class from "../Base/base_class";

jQuery(document).ready(function ($) {
    class UD_tables extends Base_Class {
        constructor() {
            super($);
            this.deleteBtn = $("#delete_button");
            this.events();
        }
        events() {
            $(document).on("click", ".gswpts_edit_table", (e) => {
                this.edit_table_name(e);
            });
            $(document).on("click", ".table_name_save", (e) => {
                this.update_table_name(e);
                this.edit_tag_value(e);
            });
            $(document).on("click", ".gswpts_table_delete_btn", (e) => {
                this.delete_table(e);
            });
            this.deleteBtn.on("click", (e) => {
                this.delete_all_table(e);
            });
        }
        update_table_name(e) {
            let table_name = $(e.currentTarget).parent().parent().find(".table_name").text();

            let data = {
                reqType: "update",
                table_id: $(e.currentTarget).attr("id"),
                table_name: table_name,
            };
            console.log(data);
            this.ajax_request(data, e);
        }

        delete_table(e) {
            let data = {
                reqType: "delete",
                table_id: $(e.currentTarget).attr("id"),
            };
            this.ajax_request(data, e);
        }

        delete_all_table(e) {
            let allCheckBox = $("input[name='manage_tables_checkbox']:checked");

            let data = {
                reqType: "deleteAll",
            };
            let table_ids = [];

            $.each(allCheckBox, function (indexInArray, valueOfElement) {
                table_ids.push($(valueOfElement).val());
            });

            data.table_ids = table_ids;
            if (data.table_ids.length > 0) {
                if (confirm("Are you sure you want to delete selected tables ?")) {
                    this.ajax_request(data, e);
                } else {
                    return;
                }
            } else {
                this.call_alert(
                    "Warning &#9888;&#65039;",
                    "<b>No table is selected to delete</b>",
                    "warning",
                    3
                );
                return;
            }
        }

        ajax_request(data, e) {
            let currentTarget = $(e.currentTarget);
            $.ajax({
                url: file_url.admin_ajax,

                data: {
                    action: "gswpts_ud_table",
                    data: data,
                },
                type: "post",

                beforeSend: () => {
                    if (data.reqType == "update") {
                        $(e.currentTarget).html(`
                        <div class="ui active mini inline loader"></div>
                    `);
                    }
                    if (data.reqType == "delete") {
                        $(e.currentTarget).html(`
                        Deleting &nbsp;
                        <div class="ui active mini inline loader"></div>
                    `);
                    }
                    if (data.reqType == "deleteAll") {
                        $(e.currentTarget).html(`
                        Deleting &nbsp;
                        <div class="ui active mini inline loader"></div>
                    `);
                    }
                },

                success: (res) => {
                    console.log(res);
                    if (
                        JSON.parse(res).response_type == "invalid_action" ||
                        JSON.parse(res).response_type == "invalid_request"
                    ) {
                        this.call_alert("Error &#128683;", JSON.parse(res).output, "error", 4);
                    }
                    if (JSON.parse(res).response_type == "updated") {
                        // currentTarget.html("Update");
                        currentTarget.html(`
                            <i class="edit icon"></i>
                        `);
                        this.call_alert(
                            "Successfull &#128077;",
                            JSON.parse(res).output,
                            "success",
                            3
                        );
                    }
                    if (JSON.parse(res).response_type == "deleted") {
                        currentTarget.html("Deleted");
                        currentTarget.parent().parent().transition("fade");
                        this.call_alert(
                            "Successfull &#128077;",
                            JSON.parse(res).output,
                            "success",
                            3
                        );
                    }
                    if (JSON.parse(res).response_type == "deleted_All") {
                        this.remove_seleted_tables();
                        currentTarget.html("Delete Selected");
                        this.call_alert(
                            "Successfull &#128077;",
                            JSON.parse(res).output,
                            "success",
                            3
                        );
                    }
                },

                error: (err) => {
                    this.call_alert("Error &#128683;", "<b>Something went wrong</b>", "error", 3);
                },
            });
        }

        remove_seleted_tables() {
            let allCheckBox = $("input[name='manage_tables_checkbox']:checked");
            $.each(allCheckBox, function (indexInArray, valueOfElement) {
                $(valueOfElement).parent().parent().transition("fade");
            });
            setTimeout(() => {
                $.each(allCheckBox, function (indexInArray, valueOfElement) {
                    $(valueOfElement).parent().parent().remove();
                });
            }, 300);
        }

        edit_table_name(e) {
            let currentTarget = $(e.currentTarget);
            currentTarget.css({
                "padding-left": "15px",
                "padding-right": "15px",
            });

            currentTarget.addClass("table_name_save");
            currentTarget.html(`
                <i class="save icon"></i>
            `);
            let link_tag = currentTarget.siblings("a");
            link_tag.css({
                cursor: "auto",
            });
            link_tag.attr("contentEditable", true);
            link_tag.focus();
            link_tag.unbind("click");
        }

        edit_tag_value(e) {
            let currentTarget = $(e.currentTarget);

            currentTarget.removeClass("table_name_save");

            let link_tag = currentTarget.siblings("a");
            link_tag.css({
                cursor: "pointer",
            });
            link_tag.attr("contentEditable", false);
            link_tag.focusout();
            link_tag.blur();
            link_tag.bind("click");
        }
    }
    new UD_tables();
});
