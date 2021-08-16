import Base_Class from "./../Base/base_class";

jQuery(document).ready(function ($) {
    class Table_Changes extends Base_Class {
        constructor() {
            super($);
            this.promo_close_btn = $(".promo_close_btn");

            this.proInputSelect = $(".pro_input_select");
            this.modalHandler = $(".modal-handler").parents(".card");
            this.tableStyleActionBtn = $(".styleModal .svg_icons, .styleModal .actions > .button");
            this.tableStylesInput = $(".styleModal .body input");
            this.hideColumnActionBtns = $(
                ".gswpts-hide-modal .svg_icons, .gswpts-hide-modal .actions > .button"
            );
            this.events();
        }

        events() {
            $(document).on("click", ".tables_settings", (e) => {
                this.change_btn_text(e);
            });

            $(document).on("click", "#gswpts_tabs .tabs > input", (e) => {
                this.changeBtnOnCondition(e);
            });

            this.add_select_box_style();

            this.settings_field.on("change", (e) => {
                this.update_table_by_changes(e);
            });

            $(document).on("click", ".pro_feature_input", (e) => {
                this.pro_feature_popup(e);
            });

            this.proInputSelect.on("click", (e) => {
                this.revertBacktoInitialValue(e);
            });

            this.promo_close_btn.on("click", (e) => {
                this.close_promo_popup(e);
            });

            this.modalHandler.on("click", (e) => {
                let input = $(e.currentTarget).find("input");

                // Activate table style popup modal in to the page
                if (input.attr("id") === "table_style") {
                    this.handleStyleModal();
                }

                // Activate hide column popup modal in to the page
                if (input.attr("id") === "hide_column") {
                    this.handleHideModal();
                }
            });

            this.tableStyleActionBtn.on("click", (e) => {
                let target = $(e.currentTarget);
                $(".tableStyleModal").removeClass("active");
                $(".styleModal").transition("scale");

                // set data to input value for saving into database
                if (this.isProPluginActive()) {
                    if (target.hasClass("selectBtn")) {
                        let selectedTableStyle = $(".styleModal .body label.active input");
                        $("#table_style").val(selectedTableStyle.val());

                        // Changing the table style
                        this.tableStyle(selectedTableStyle.val());
                    }
                }
            });

            this.tableStylesInput.on("click", (e) => {
                // activate border color
                let target = $(e.currentTarget);
                $(".styleModal .body label").removeClass("active");
                target.parent().toggleClass("active");

                // activate promo if unlocked
                $(".styleModal .body label .pro_feature_promo").removeClass("active");
                target.parent().find(".pro_feature_promo").addClass("active");
            });

            this.hideColumnActionBtns.on("click", (e) => {
                this.hideColumnActionCallback(e);
            });

            $("#redirection_type > input:nth-child(1)").on("change", (e) => {
                this.update_table_by_changes(e);
            });
        }

        changeBtnOnCondition(e) {
            if (!this.get_slug_parameter("id")) {
                if ($(e.currentTarget).attr("id") == "tab4") {
                    this.btnAttAndTextChanger({
                        selector: "#next-setting",
                        btnText: "Save Table",
                        btnAttribute: "save",
                        btnBackgroundColor: "#6435c9",
                        btnId: "fetch_save_btn",
                    });
                } else {
                    this.btnAttAndTextChanger({
                        selector: "#fetch_save_btn",
                        btnText: "Next Setting",
                        btnAttribute: "next",
                        btnBackgroundColor: "#f2711c",
                        btnId: "next-setting",
                    });
                }
            }
        }

        btnAttAndTextChanger(arg) {
            let target = $(arg.selector);

            this.btn_changer(target, arg.btnText, arg.btnAttribute);

            target.css({
                backgroundColor: arg.btnBackgroundColor,
            });

            target.attr("id", arg.btnId);
        }

        change_btn_text(e) {
            let btn_text_value = $(e.currentTarget).attr("data-btn-text");
            let btn_attr_value = $(e.currentTarget).attr("data-attr-text");
            $("#fetch_save_btn").html(btn_text_value);
            $("#fetch_save_btn").attr("req-type", btn_attr_value);
        }

        add_select_box_style() {
            if ($("#rows_per_page").length) {
                $("#rows_per_page").dropdown();
            }
            if ($("#table_exporting_container").length) {
                $("#table_exporting_container").dropdown();
            }
        }

        update_table_by_changes(e) {
            let table_settings = this.table_settings_obj();

            if ($(e.currentTarget).attr("id") == "table_exporting" && this.isProPluginActive()) {
                let export_btn = ["json", "pdf", "csv", "excel", "print", "copy"];
                export_btn.forEach((btn) => {
                    this.button_reavealer(e, btn);
                });
            }

            if (
                $(e.currentTarget).attr("id") == "show_title" ||
                "responsive_style" ||
                "search_table" ||
                "rows_per_page" ||
                "sorting" ||
                "show_entries" ||
                "info_block" ||
                "vertical_scrolling" ||
                "cell_format"
            ) {
                if (this.isProPluginActive()) {
                    this.export_buttons_row_revealer(table_settings.tableExport);
                    this.changeCellFormat(table_settings.cellFormat, "#spreadsheet_container");
                }
                this.reFormatTable();

                this.swap_filter_inputs(table_settings.swapFilterInputs);
                this.swap_bottom_options(table_settings.swapBottomOptions);
            }

            /* Swaping Filter Inputs */
            if ($(e.currentTarget).attr("id") == "swap_filter_inputs") {
                this.swap_filter_inputs($(e.currentTarget).prop("checked"));
            }

            /* Swaping bottom elemts */
            if ($(e.currentTarget).attr("id") == "swap_bottom_options") {
                this.swap_bottom_options($(e.currentTarget).prop("checked"));
            }

            // Changing the link redirection type
            if (this.isProPluginActive()) {
                if ($(e.currentTarget).attr("name") == "redirection_type") {
                    this.changeRedirectionType(table_settings.redirectionType);
                }
            }
            // add dragging ability to table
            this.addDraggingAbility();
        }

        reFormatTable() {
            let table_settings = this.table_settings_obj();

            let dom = `<"#filtering_input"${table_settings.showXEntries ? "l" : ""}${
                table_settings.searchBar ? "f" : ""
            }>rt<"#bottom_options"${table_settings.showInfoBlock ? "i" : ""}p>`;

            if (this.isProPluginActive()) {
                dom = `B<"#filtering_input"${table_settings.showXEntries ? "l" : ""}${
                    table_settings.searchBar ? "f" : ""
                }>rt<"#bottom_options"${table_settings.showInfoBlock ? "i" : ""}p>`;
            }

            let table_name = $("#table_name").val();
            this.table_changer(table_name, table_settings, dom);
        }

        // Change the link attribute target value to either _blank || _self
        changeRedirectionType(type) {
            let links = $("#create_tables a");
            if (!links.length) return;
            $.each(links, function (i, link) {
                $(link).attr("target", type);
            });
        }

        /* Show the export buttons based on user selection */
        button_reavealer(e, target) {
            if ($(e.currentTarget).val().includes(target)) {
                if ($("." + target + "_btn").hasClass("hidden")) {
                    $("." + target + "_btn").transition("scale");
                    return;
                }
            } else {
                if ($("." + target + "_btn").hasClass("visible")) {
                    $("." + target + "_btn").transition("scale");
                    return;
                }
            }
        }

        pro_feature_popup(e) {
            let target = $(e.currentTarget);
            let promo = target.parents(".gswpts_create_table_container").find(".promo_large");
            promo.css({
                opacity: 1,
            });
            promo.find(".popup-box").css({
                "margin-top": `${$(document).scrollTop() + 50}px`,
            });
            promo.addClass("active");
            target.prop("checked", false);
        }
        close_promo_popup(e) {
            let target = $(e.currentTarget);
            let promo = target.parents(".card").find(".pro_feature_promo");
            promo.removeClass("active");
        }

        revertBacktoInitialValue(e) {
            let target = $(e.currentTarget);
            let parentTarget = target.parents(".selection.dropdown");
            let previousValue = parentTarget.find("input").val();
            let defaultTextValue = parentTarget.find(".text").text();

            setTimeout(() => {
                if (previousValue) {
                    parentTarget.dropdown("set selected", previousValue);
                } else {
                    parentTarget.find("input").val("");
                    parentTarget.find(".text").addClass("default").html(defaultTextValue);
                    parentTarget.dropdown();
                }
            }, 200);
        }

        handleStyleModal() {
            $(".tableStyleModal").addClass("active");
            $(".styleModal").transition("scale");
            $(".styleModal").css({
                "margin-top": `${$(document).scrollTop() + 100}px`,
            });
        }

        handleHideModal() {
            $(".hide-column-modal-wrapper").addClass("active");
            $(".gswpts-hide-modal").transition("scale");
            $(".gswpts-hide-modal").css({
                "margin-top": `${$(document).scrollTop() + 100}px`,
            });
        }

        hideColumnActionCallback(e) {
            let target = $(e.currentTarget);
            $(".hide-column-modal-wrapper").removeClass("active");
            $(".gswpts-hide-modal").transition("scale");

            // set data to input value for saving into database
            if (this.isProPluginActive()) {
                if (target.hasClass("selectBtn")) {
                    let desktopColumnInput = $("#desktop-hide-columns");
                    let mobileColumnInput = $("#mobile-hide-columns");

                    let desktopHideColumns = desktopColumnInput.find("input").val()
                        ? desktopColumnInput.find("input").val().split(",")
                        : null;
                    let modbleHideColumns = mobileColumnInput.find("input").val()
                        ? mobileColumnInput.find("input").val().split(",")
                        : null;

                    let hideColumnValues = {
                        desktopValues: desktopHideColumns,
                        mobileValues: modbleHideColumns,
                    };

                    $("#hide_column").val(JSON.stringify(hideColumnValues));

                    this.reFormatTable();
                    this.addDraggingAbility();
                }
            }
        }
    }

    new Table_Changes();
});
