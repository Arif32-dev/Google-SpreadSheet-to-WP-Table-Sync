import BaseClass from "../base/base_class";

jQuery(document).ready(function ($) {
    class UD_tables extends BaseClass {
        constructor() {
            super($);
            this.deleteBtn = $("#delete_button, #tab_delete_button");
            this.dataActionType = null;
            this.events();
        }

        events() {
            $(document).on("click", ".gswpts_edit_table, .gswpts_edit_tab", (e) => {
                this.editName(e);
            });

            $(document).on("click", ".table_name_save, .tab_name_save", (e) => {
                this.updateName(e);
                this.edit_tag_value(e);
            });

            $(document).on("click", ".semntic-popup-modal .actions > .yes-btn", (e) => {
                let id = $(e.currentTarget).attr("data-id");
                if (id) {
                    this.deleteData(id);
                }
            });

            $(document).on("click", ".gswpts_table_delete_btn, .gswpts_tab_delete_btn", (e) => {
                let id = $(e.currentTarget).attr("data-id");

                this.initiatePopup(
                    {
                        deleteAll: false,
                        id,
                        contentText: "Are you sure you want to delete your this?",
                    },
                    e
                );
            });

            this.deleteBtn.on("click", (e) => {
                this.initiatePopup(
                    {
                        deleteAll: true,
                        contentText: "Are you sure you want to delete your selected data?",
                    },
                    e
                );
            });
        }

        initiatePopup(arg, e) {
            let popupModal = $(".semntic-popup-modal");
            popupModal.modal("show");

            popupModal.find(".gswpts_popup_content").text(arg.contentText);

            if (arg.deleteAll == false) {
                $(".semntic-popup-modal .actions > .yes-btn").attr("data-id", arg.id);
            }

            if ($(e.currentTarget).hasClass("gswpts_table_delete_btn")) {
                this.dataActionType = "gswpts_ud_table";
            }

            if ($(e.currentTarget).hasClass("gswpts_tab_delete_btn")) {
                this.dataActionType = "gswpts_ud_tab";
            }

            // implementing plain javascript for resolving (this) keyword conflict issue
            document.querySelector(".semntic-popup-modal .actions > .yes-btn").addEventListener("click", () => {
                if (arg.deleteAll) {
                    this.delete_all_table(e);
                }
            });
        }

        updateName(e) {
            let name = "Name";

            if ($(e.currentTarget).hasClass("table_name_save")) {
                name = $(e.currentTarget).parent().parent().find(".table_name_hidden_input").val();
                this.dataActionType = "gswpts_ud_table";
            }

            if ($(e.currentTarget).hasClass("tab_name_save")) {
                name = $(e.currentTarget).parent().parent().find(".tab_name_hidden_input").val();
                this.dataActionType = "gswpts_ud_tab";
            }

            let data = {
                reqType: "update",
                id: $(e.currentTarget).attr("id"),
                name,
                dataActionType: this.dataActionType,
            };

            this.ajax_request(data);
        }

        deleteData(id) {
            let data = {
                reqType: "delete",
                id,
                dataActionType: this.dataActionType,
            };

            this.ajax_request(data);
        }

        delete_all_table(e) {
            let allCheckBox = null;

            if ($(e.currentTarget).attr("id") == "delete_button") {
                allCheckBox = $("input[name='manage_tables_checkbox']:checked");
                this.dataActionType = "gswpts_ud_table";
            }

            if ($(e.currentTarget).attr("id") == "tab_delete_button") {
                allCheckBox = $("input[name='manage_tab_checkbox']:checked");
                this.dataActionType = "gswpts_ud_tab";
            }

            let data = {
                reqType: "deleteAll",
                dataActionType: this.dataActionType,
            };

            let ids = [];

            $.each(allCheckBox, function (indexInArray, valueOfElement) {
                ids.push($(valueOfElement).val());
            });

            data.ids = ids;

            if (data.ids.length > 0) {
                this.ajax_request(data);
            } else {
                this.call_alert("Warning &#9888;&#65039;", "<b>No table is selected to delete</b>", "warning", 3);
                return;
            }
        }

        ajax_request(data) {
            let currentTarget;

            let action = null;

            if (data.dataActionType == "gswpts_ud_table" && (data.reqType == "delete" || data.reqType == "deleteAll")) {
                currentTarget = $(`#table-${data.id}`);
            }

            if (data.dataActionType == "gswpts_ud_table" && data.reqType == "update") {
                currentTarget = $(`#${data.id}`);
            }

            if (data.dataActionType == "gswpts_ud_tab" && (data.reqType == "delete" || data.reqType == "deleteAll")) {
                currentTarget = $(`#tab-${data.id}`);
            }

            if (data.dataActionType == "gswpts_ud_tab" && data.reqType == "update") {
                currentTarget = $(`#${data.id}`);
            }

            if (data.dataActionType == "gswpts_ud_table") {
                action = "gswpts_ud_table";
            }

            if (data.dataActionType == "gswpts_ud_tab") {
                action = "gswpts_ud_tab";
            }

            if (currentTarget.hasClass("table_name_save")) {
                action = "gswpts_ud_table";
            }

            if (currentTarget.hasClass("tab_name_save")) {
                action = "gswpts_ud_tab";
            }

            $.ajax({
                url: file_url.admin_ajax,

                data: {
                    action,
                    data: data,
                },
                type: "post",

                beforeSend: () => {
                    if (data.reqType == "update") {
                        currentTarget.html(`
                        <div class="ui active mini inline loader"></div>
                    `);
                    }
                    if (data.reqType == "delete") {
                        currentTarget.html(`
                        Deleting &nbsp;
                        <div class="ui active mini inline loader"></div>
                    `);
                    }
                    if (data.reqType == "deleteAll") {
                        currentTarget.html(`
                        Deleting &nbsp;
                        <div class="ui active mini inline loader"></div>
                    `);
                    }
                },

                success: (res) => {
                    console.log(res);
                    if (JSON.parse(res).response_type == "invalid_action" || JSON.parse(res).response_type == "invalid_request") {
                        this.call_alert("Error &#128683;", JSON.parse(res).output, "error", 4);
                    }
                    if (JSON.parse(res).response_type == "updated") {
                        currentTarget.html(`
                            <img src="${file_url.renameIcon}" width="24px" height="15px" alt="rename-icon"/>
                        `);
                        this.call_alert("Successfull &#128077;", JSON.parse(res).output, "success", 3);
                    }

                    if (JSON.parse(res).response_type == "deleted") {
                        currentTarget.html("Deleted");

                        currentTarget.parent().parent().transition("fade");
                        this.call_alert("Successfull &#128077;", JSON.parse(res).output, "success", 3);
                    }

                    if (JSON.parse(res).response_type == "deleted_All") {
                        this.remove_seleted_tables();
                        currentTarget.html("Delete Selected");
                        this.call_alert("Successfull &#128077;", JSON.parse(res).output, "success", 3);
                    }
                },

                error: (err) => {
                    this.call_alert("Error &#128683;", "<b>Something went wrong</b>", "error", 3);
                },
            });
        }

        remove_seleted_tables() {
            let allCheckBox = null;

            if (this.dataActionType === "gswpts_ud_table") {
                allCheckBox = $("input[name='manage_tables_checkbox']:checked");
            }

            if (this.dataActionType === "gswpts_ud_tab") {
                allCheckBox = $("input[name='manage_tab_checkbox']:checked");
            }

            $.each(allCheckBox, function (indexInArray, valueOfElement) {
                $(valueOfElement).parent().parent().transition("fade");
            });

            setTimeout(() => {
                $.each(allCheckBox, function (indexInArray, valueOfElement) {
                    $(valueOfElement).parent().parent().remove();
                });
            }, 300);
        }

        editName(e) {
            let currentTarget = $(e.currentTarget);

            if (currentTarget.hasClass("gswpts_edit_tab")) {
                currentTarget.addClass("tab_name_save");
            }

            if (currentTarget.hasClass("gswpts_edit_table")) {
                currentTarget.addClass("table_name_save");
            }

            currentTarget.html(`
                <i class="save icon"></i>
            `);

            let linkTag = currentTarget.siblings("a");

            linkTag.css({
                display: "none",
            });

            let inputTag = currentTarget.parent().find(".ui.input");

            inputTag.addClass("active");
        }

        edit_tag_value(e) {
            let currentTarget = $(e.currentTarget);

            if (currentTarget.hasClass("gswpts_edit_tab")) {
                currentTarget.removeClass("tab_name_save");
            } else {
                currentTarget.removeClass("table_name_save");
            }

            let linkTag = currentTarget.siblings("a");

            linkTag.css({
                display: "unset",
            });

            let inputTag = currentTarget.parent().find(".ui.input");

            linkTag.text(inputTag.find("input").val());

            inputTag.removeClass("active");
        }

        // Clear all the selected table that are meant to be deleted.
        clearSelection(e) {
            $(".manage_tables_checkbox").prop("checked", false);
        }
    }
    new UD_tables();
});
