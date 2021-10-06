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
                ".hide-column-modal-wrapper .gswpts-hide-modal .svg_icons, .hide-column-modal-wrapper .gswpts-hide-modal .actions > .button"
            );
            this.hideRowsActionBtns = $(
                ".hide-rows-modal-wrapper .gswpts-hide-modal .svg_icons, .hide-rows-modal-wrapper .gswpts-hide-modal .actions > .button"
            );
            this.hideCellsActionBtns = $(
                ".hide-cell-modal-wrapper .gswpts-hide-modal .svg_icons, .hide-cell-modal-wrapper .gswpts-hide-modal .actions > .button"
            );
            this.hideRowsActivator = $("#active_hide_rows");
            this.hideCellActivator = $("#active_hidden_cells");
            this.hiddenRowsInput = $("#hidden_rows");
            this.hiddenCellInput = $("#hidden_cells");
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
                    let wrapper = $(".tableStyleModal");
                    let modal = $(".styleModal");
                    this.handleModal(wrapper, modal);
                }

                // Activate hide column popup modal in to the page
                if (input.attr("id") === "hide_column") {
                    let wrapper = $(".hide-column-modal-wrapper");
                    let modal = $(".hide-column-modal-wrapper .gswpts-hide-modal");
                    this.handleModal(wrapper, modal);
                }

                // Activate hide rows popup modal in to the page
                if (input.attr("id") === "hide_rows") {
                    let wrapper = $(".hide-rows-modal-wrapper");
                    let modal = $(".hide-rows-modal-wrapper .gswpts-hide-modal");
                    this.handleModal(wrapper, modal);
                }

                // Activate hide cell popup modal in to the page
                if (input.attr("id") === "hide_cell") {
                    let wrapper = $(".hide-cell-modal-wrapper");
                    let modal = $(".hide-cell-modal-wrapper .gswpts-hide-modal");
                    this.handleModal(wrapper, modal);
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

            // Hide Column modal action buttons for managing popup modal
            this.hideColumnActionBtns.on("click", (e) => {
                this.hideColumnActionCallback(e);
            });

            // Hide row modal action buttons for managing popup modal
            this.hideRowsActionBtns.on("click", (e) => {
                this.hideRowsActionCallback(e);
            });

            // Hide Cell modal action buttons for managing popup modal
            this.hideCellsActionBtns.on("click", (e) => {
                this.hideCellActionCallback(e);
            });

            this.hideCellActivator.click("click", (e) => {
                this.hideCellActionCallback(e);
            });

            $("#redirection_type > input:nth-child(1)").on("change", (e) => {
                this.update_table_by_changes(e);
            });

            this.hideRowsActivator.on("click", (e) => {
                this.hideRowsActionCallback(e);
            });

            // Upon clicking on selected labels display those hidden table rows
            this.hiddenRowsInput.on("click", (e) => {
                this.handleRowsVisibility(e);
            });

            // Upon clicking on selected labels display those hidden table cell
            this.hiddenCellInput.on("click", (e) => {
                this.handleCellVisibility(e);
            });

            $(document).on("click", ".ui.stackable.pagination.menu .paginate_button", (e) => {
                this.showTableRows(e);
                this.showTableCells(e);
            });
        }

        changeBtnOnCondition(e) {
            if (!this.get_slug_parameter("id")) {
                if ($(e.currentTarget).attr("id") == "tab4") {
                    this.btnAttAndTextChanger({
                        selector: "next-setting",
                        btnText: "Save Table &nbsp;<i class='fas fa-save'></i>",
                        btnAttribute: "save",
                        btnBackgroundColor: "#6435c9",
                        btnClass: "fetch_save_btn",
                    });
                }
            }
        }

        btnAttAndTextChanger(arg) {
            let target = $(`.${arg.selector}`);

            this.btn_changer(target, arg.btnText, arg.btnAttribute);

            target.css({
                backgroundColor: arg.btnBackgroundColor,
            });

            target.removeClass(arg.selector);
            target.addClass(arg.btnClass);
        }

        change_btn_text(e) {
            let btn_text_value = $(e.currentTarget).attr("data-btn-text");
            let btn_attr_value = $(e.currentTarget).attr("data-attr-text");
            $(".fetch_save_btn").html(btn_text_value + " &nbsp; <i class='fas fa-save'></i>");
            $(".fetch_save_btn").attr("req-type", btn_attr_value);
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

        handleModal(wrapper, modal) {
            wrapper.addClass("active");
            modal.transition("scale");
            modal.css({
                "margin-top": `${$(document).scrollTop() + 100}px`,
            });
        }

        hideColumnActionCallback(e) {
            let target = $(e.currentTarget);
            $(".hide-column-modal-wrapper").removeClass("active");
            $(".hide-column-modal-wrapper .gswpts-hide-modal").transition("scale");

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

        // Handle hide rows modal popup action
        hideRowsActionCallback(e) {
            let target = $(e.currentTarget);
            $(".hide-rows-modal-wrapper").removeClass("active");
            $(".hide-rows-modal-wrapper .gswpts-hide-modal").transition("scale");

            // set data to input value for saving into database
            if (this.isProPluginActive()) {
                if (target.hasClass("selectBtn")) {
                    let tableBody = $(".dataTables_scrollBody table tbody");

                    if (target.prop("checked")) {
                        // if checkbox is on add row hidding feature and class with cursor changing

                        // Deactive cell hididng feature when row hiding is active
                        $("#active_hidden_cells").prop("checked", false);

                        tableBody.addClass("hiding_active");

                        $(document).on("click", ".dataTables_scrollBody table tr", (e) => {
                            if (!$("#active_hide_rows").prop("checked")) return;

                            e.stopPropagation();

                            $(".dataTables_scrollBody table tr > td").unbind("click");

                            let target = $(e.currentTarget),
                                rowIndex = target.attr("data-index");
                            this.insertHiddenRowsToSelectBox(rowIndex);
                            this.insertHiddenRowsToInputBox(rowIndex);
                            target.hide(300);
                        });
                    } else {
                        tableBody.removeClass("hiding_active");
                    }
                }
            }
        }

        // Handle hide rows modal popup action
        hideCellActionCallback(e) {
            let target = $(e.currentTarget);
            $(".hide-cell-modal-wrapper").removeClass("active");
            $(".hide-cell-modal-wrapper .gswpts-hide-modal").transition("scale");

            // set data to input value for saving into database
            if (this.isProPluginActive()) {
                if (target.hasClass("selectBtn")) {
                    let tableBody = $(".dataTables_scrollBody table tbody");
                    if (target.prop("checked")) {
                        // Deactive row hididng feature when cell hiding is active
                        $("#active_hide_rows").prop("checked", false);

                        // if checkbox is on add row hidding feature and class with cursor changing
                        tableBody.addClass("cell_hiding_active");

                        $(document).on("click", ".dataTables_scrollBody table tr > td", (e) => {
                            if (!$("#active_hidden_cells").prop("checked")) return;

                            e.stopPropagation();

                            let target = $(e.currentTarget),
                                cellIndex = target.attr("data-index");
                            this.insertSelectedCellToSelectBox(cellIndex);
                            this.insertHiddenCellToInputBox(cellIndex);
                            target.find(".cell_div").hide(300);
                        });
                    } else {
                        tableBody.removeClass("cell_hiding_active");
                        $(".dataTables_scrollBody table tr > td").unbind("click");
                    }
                }
            }
        }

        // On remove of hidden rows from dropdown display the removed rows in table and also delete it from menu
        handleRowsVisibility(e) {
            let target = $(e.currentTarget);
            let visibleRowsValue = target.find(".menu .item:not(.active)");
            if (!visibleRowsValue) return;

            $.each(visibleRowsValue, function (indexInArray, valueOfElement) {
                let indexValue = $(valueOfElement).attr("data-value");
                $(`.dataTables_scrollBody table tbody .row_${indexValue}`).show(300);

                // Remove the the hidden row value from hidden input in order to save into database
                let hiddenRows = [],
                    jsonFormatData,
                    hiddenRowValues = $("#hide_rows");

                if (hiddenRowValues.val()) {
                    hiddenRows = JSON.parse(hiddenRowValues.val());

                    let rowIndexPositionInArray = hiddenRows.indexOf(indexValue);

                    if (rowIndexPositionInArray != -1) {
                        hiddenRows.splice(rowIndexPositionInArray, 1);

                        jsonFormatData = JSON.stringify(hiddenRows);

                        hiddenRowValues.val(jsonFormatData);
                    }
                }

                setTimeout(() => {
                    target.find(`.menu [data-value=${indexValue}]`).remove();
                }, 200);
            });
        }

        // On remove of hidden Cell from dropdown display the removed cell in table and also delete it from menu
        handleCellVisibility(e) {
            let target = $(e.currentTarget);
            let visibleRowsValue = target.find(".menu .item:not(.active)");
            if (!visibleRowsValue) return;

            $.each(visibleRowsValue, function (indexInArray, valueOfElement) {
                let indexValue = $(valueOfElement).attr("data-value");
                $(`.dataTables_scrollBody table tbody tr .cell_index_${indexValue} .cell_div`).show(
                    300
                );

                // Remove the the hidden cell value from hidden input in order to save into database
                let hiddenCell = [],
                    jsonFormatData,
                    hiddenCellValues = $("#hide_cell");

                if (hiddenCellValues.val()) {
                    hiddenCell = JSON.parse(hiddenCellValues.val());

                    let fomattedIndexValue = `[${indexValue.split("-")}]`;

                    for (let index = 0; index < hiddenCell.length; index++) {
                        const cell = hiddenCell[index];
                        if (cell == fomattedIndexValue) {
                            hiddenCell.splice(index, 1);

                            jsonFormatData = JSON.stringify(hiddenCell);

                            hiddenCellValues.val(jsonFormatData);

                            break;
                        }
                    }
                }

                setTimeout(() => {
                    target.find(`.menu [data-value=${indexValue}]`).remove();
                }, 200);
            });
        }

        // On clicking of pagination link show the hideen table rows if those rows are not in hidden rows dropdown
        showTableRows(e) {
            let tableRows = $(".dataTables_scrollBody table .gswpts_rows");

            if (!tableRows) return;

            $.each(tableRows, function (indexInArray, valueOfElement) {
                let rowIndex = $(valueOfElement).attr("data-index"),
                    selectedLables = $(`#hidden_rows > [data-value=${rowIndex}]`);

                if (selectedLables.length == 0) {
                    $(valueOfElement).show(300);
                }
            });
        }

        // On clicking of pagination link show the hideen table rows if those rows are not in hidden rows dropdown
        showTableCells(e) {
            let tableCells = $(".dataTables_scrollBody table .gswpts_rows td");

            if (!tableCells) return;

            $.each(tableCells, function (indexInArray, valueOfElement) {
                let cellIndex = JSON.parse($(valueOfElement).attr("data-index")).join("-"),
                    selectedLables = $(`#hidden_cells > [data-value=${cellIndex}]`);
                if (selectedLables.length == 0) {
                    $(valueOfElement).find(".cell_div").show(300);
                }
            });
        }
    }

    new Table_Changes();
});
