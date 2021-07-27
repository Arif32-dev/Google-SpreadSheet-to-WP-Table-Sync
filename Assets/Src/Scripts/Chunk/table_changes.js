import Base_Class from "./../Base/base_class";

jQuery(document).ready(function ($) {
    class Table_Changes extends Base_Class {
        constructor() {
            super($);
            this.promo_close_btn = $(".promo_close_btn");
            this.pro_feature_input = $(".pro_feature_input");
            this.chooseStyle = $(".chooseStyle").parents(".card");
            this.tableStyleActionBtn = $(".styleModal .svg_icons, .styleModal .actions > .button");
            this.tableStylesInput = $(".styleModal .body input");
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

            this.pro_feature_input.on("click", (e) => {
                this.pro_feature_popup(e);
            });
            this.promo_close_btn.on("click", (e) => {
                this.close_promo_popup(e);
            });
            this.chooseStyle.on("click", (e) => {
                $(".tableStyleModal").addClass("active");
                $(".styleModal").transition("scale");
                $(".styleModal").css({
                    "margin-top": `${$(document).scrollTop() + 100}px`,
                });
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
            let table_name = $(".edit_table_name").siblings("input[name=table_name]").val();
            let table_settings = this.table_settings_obj();

            if ($(e.currentTarget).attr("id") == "table_exporting" && this.isProPluginActive()) {
                let export_btn = ["json", "pdf", "csv", "excel", "print", "copy"];
                export_btn.forEach((btn) => {
                    this.button_reavealer(e, btn);
                });
            }

            if (
                $(e.currentTarget).attr("id") == "show_title" ||
                "responsive" ||
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
                if ($(e.currentTarget).attr("id") == "redirection_type") {
                    this.changeRedirectionType(table_settings.redirectionType);
                }
            }
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

            let table_name = $(".edit_table_name").siblings("input[name=table_name]").val();
            this.table_changer(table_name, table_settings, dom);
        }

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

        set_default() {
            let default_settings = this.default_settings();

            $.each(this.settings, function (index, value) {
                if ($(value).attr("id") == "show_title") {
                    $(value).prop("checked", default_settings.table_title);
                }
                if ($(value).attr("id") == "responsive") {
                    $(value).prop("checked", default_settings.responsiveTable);
                }
                if ($(value).attr("id") == "search_table") {
                    $(value).prop("checked", default_settings.searchBar);
                }
                if ($(value).attr("id") == "show_entries") {
                    $(value).prop("checked", default_settings.showXEntries);
                }
                if ($(value).attr("id") == "info_block") {
                    $(value).prop("checked", default_settings.showInfoBlock);
                }
                if ($(value).attr("id") == "swap_filter_inputs") {
                    $(value).prop("checked", default_settings.swapFilterInputs);
                }
                if ($(value).attr("id") == "swap_bottom_options") {
                    $(value).prop("checked", default_settings.swapBottomOptions);
                }
            });
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
    }

    new Table_Changes();
});
