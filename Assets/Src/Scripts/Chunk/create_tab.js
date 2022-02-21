import BaseClass from "../base/base_class";

jQuery(document).ready(function ($) {
    class ManageTab extends BaseClass {
        constructor() {
            super($);
            this.tabInputBtn = $(".tab_input_btn");
            this.saveTabBtn = $(".gswpts_save_tab_changes.create");
            this.updateTabBtn = $(".gswpts_save_tab_changes.update");
            this.events();
        }
        events() {
            $(document).on("click", ".tab_hidden_input", this.showContent);

            this.tabInputBtn.click(this.addTab);

            $(document).on("click", ".close_tab_container", this.closeTab);

            this.addDragging();

            $(document).on("dblclick", ".tabs .tab_name_label", this.editTabName);

            $(document).on("keypress", ".tabs .tab_name_label", this.closeEditMode);

            $(document).on("change", ".hidden_tab_name", this.changeTabName);

            $(document).on("click", ".tab-content .card_remover", this.removeCard);

            this.saveTabBtn.click(() => {
                this.saveTabChanges();
            });

            this.updateTabBtn.click(() => {
                this.updateTabChanges();
            });

            $(document).on("click", ".tab_positon_btn", this.toggleTabPosition);
        }

        showContent(e) {
            let target = $(e.currentTarget);

            target.parents(".tab_bottom_side").find(`.tab_contents .tab-content`).hide().removeClass("active");

            let inputID = target.attr("id").match(/(\d+)/)[0];

            target.parents(".tab_bottom_side").find(`.tab_contents #tab-content${inputID}`).fadeIn().addClass("active").css({
                display: "flex",
            });
        }

        addDragging() {
            $(".draggable").draggable({
                revert: false,
                stack: ".draggable",
                helper: "clone",
            });

            $(".draggable").on("dragstart", function (e) {
                let target = $(e.currentTarget);

                target.addClass("dragging");

                target.css({
                    "z-index": 2,
                });

                target.parent().find(".ui-draggable-dragging").css({
                    "z-index": 3,
                });

                $(".droppable").droppable({
                    accept: ".draggable",
                    drop: function (event, ui) {
                        let droppable = $(this);
                        let draggable = ui.draggable;

                        // Remove inital content
                        droppable.find(".demo_content").remove();

                        // Move draggable into droppable
                        let drag = $(".droppable").has(ui.draggable).length ? draggable : draggable.clone();

                        drag.appendTo(droppable);

                        // Modify the card remover icon css
                        droppable.find(".card_remover").css({
                            display: "block",
                            margin: "6px 8px 0 0",
                        });

                        droppable.find(".card").css({
                            "min-width": "230px",
                        });
                    },
                });
            });

            $(".draggable").on("dragstop", function (e) {
                let target = $(e.currentTarget);

                target.removeClass("dragging");
            });
        }

        addTab(e) {
            let demoTemplate = $(".demo_template");

            let totolTabCount = $(".tab_bottom_side .tabs").length;
            let tabPageCount = parseInt($(".tab_page_input").val());

            let tabName = $(".container_tab_name").val();

            if (tabPageCount < 1) return;

            let clonedTemplate = demoTemplate.clone(true);

            let tabLists = "";
            let tabContents = "";

            let listCount = $(".tabs li").length - 1;

            let i = listCount;

            tabPageCount += listCount;

            let normalIndex = 0;

            for (i; i < tabPageCount; i++) {
                tabLists += `
                    <li>
                        <input type="radio" 
                                name="tabs${totolTabCount}" 
                                id="tab${i + totolTabCount}" 
                                data-id="${i + totolTabCount}"
                                class="tab_hidden_input" ${normalIndex == 0 ? "checked" : ""} />
                        <label class="tab_name_label unselectable" for="tab${i + totolTabCount}" role="tab">
                            <span class="tab_page_name">tab${normalIndex + 1}</span>
                            <div class="ui input">
                                <input type="text" class="hidden_tab_name" value="tab${normalIndex + 1}" placeholder="Tab name..."/>
                            </div>
                        </label>
                    </li>
                `;

                tabContents += `
                    <div id="tab-content${i + totolTabCount}" class="tab-content droppable ${normalIndex == 0 ? "active" : ""}">
                        <b class="demo_content">
                            Tab #${normalIndex + 1} content
                        </b>
                    </div>
                `;
                normalIndex++;
            }

            clonedTemplate.find(".tab_name").val(tabName);

            clonedTemplate.find(".tabs").html("");
            clonedTemplate.find(".tab_contents").html("");

            clonedTemplate.find(".tabs").append(tabLists);
            clonedTemplate.find(".tab_contents").append(tabContents);

            clonedTemplate.removeClass("demo_template");

            $(".left_side_parent").append(clonedTemplate);
        }

        editTabName(e) {
            let target = $(e.currentTarget);

            target.parent().siblings("li").find(".tab_name_label").removeClass("active");

            let inputValue = target.find(".hidden_tab_name").val();

            if (!inputValue || inputValue == "") {
                target.find(".hidden_tab_name").val(target.parent().find(".tab_hidden_input").attr("id"));
            }

            target.addClass("deactivate_transition");
            target.toggleClass("active");

            setTimeout(() => {
                target.removeClass("deactivate_transition");
                target.addClass("active_transition");
            }, 300);
        }

        // Close the tab edit mode on enter keypressing
        closeEditMode(e) {
            let target = $(e.currentTarget);
            let key = e.which;
            if (key == 13) {
                target.dblclick();
                return false;
            }
        }

        changeTabName(e) {
            let target = $(e.currentTarget);

            let value = target.val();

            let changedValue = "";

            if (!value || value == "") {
                changedValue = target.parents("li").find(".tab_hidden_input").attr("id");
            } else {
                changedValue = value;
            }

            target.parents(".tab_name_label").find(".tab_page_name").html(changedValue);
        }

        closeTab(e) {
            let target = $(e.currentTarget);
            target.parents(".tab_bottom_side").remove();
        }

        // Remove the card from tab page
        removeCard(e) {
            let target = $(e.currentTarget);

            let tabElements = target.parents(".tab-content");

            let id = tabElements.attr("id");

            id = parseInt(id.match(/\d/)[0]);

            // If there are no children inside current tab than add demo content relation to current tab
            if (tabElements.children().length < 2) {
                tabElements.html(`<b class="demo_content">Tab #${id} content</b>`);
            }

            target.parents(".card").remove();
        }

        // Save all the tab changed into db via ajax
        saveTabChanges() {
            let data = this.collectData();

            if (!data.length) {
                this.call_alert("Warning &#9888;&#65039;", "<b>Table is not selected to create tab</b>", "warning", 3);
                return;
            }

            $.ajax({
                url: file_url.admin_ajax,

                data: {
                    action: "gswpts_tab_changes",
                    type: "create",
                    data,
                },
                type: "post",

                beforeSend: () => {
                    $(".gswpts_save_tab_changes").attr("disabled", true);
                },

                success: (res) => {
                    if (
                        JSON.parse(res).response_type == "invalid_action" ||
                        JSON.parse(res).response_type == "invalid_request" ||
                        JSON.parse(res).response_type == "error"
                    ) {
                        this.call_alert("Error &#128683;", JSON.parse(res).output, "error", 4);
                    }

                    if (JSON.parse(res).response_type == "success") {
                        window.location.href = file_url.manageTabURL;
                    }
                },

                complete: () => {},

                error: (err) => {
                    $(".gswpts_save_tab_changes").attr("disabled", false);
                    this.call_alert("Error &#128683;", "<b>Something went wrong</b>", "error", 3);
                },
            });
        }

        // Collect & organize all the tab data in order to save into database
        collectData() {
            let tabs = [
                // Data holder layout
                // {
                //     tabName: 'Tab name',
                //     tabSettings: [
                //         {
                //             id: 1,
                //             name: 'Tab1',
                //             tableID: [114, 115]
                //         },
                //         {
                //             id: 2,
                //             name: 'Tab2',
                //             tableID: [115]
                //         }
                //     ]
                // }
            ];

            let totalTabs = $(".tab_bottom_side:not(.demo_template)");

            if (totalTabs.length < 1) return tabs;

            $.each(totalTabs, function (i, element) {
                let tabPages = $(element).find(".tabs li");

                let tableCards = $(element).find(".tab-content > *:not(.demo_content)");

                if (tableCards.length > 0) {
                    tabs.push(getTabPages(tabPages));
                }
            });

            function getTabPages(tabPages) {
                let pages = {
                    tabName: tabPages.parents(".tab_bottom_side").find(".tab_name_box .tab_name").val() || "Tab name",
                    reverseMode: isReverseMode(tabPages),
                    tabSettings: [],
                };

                if (tabPages.length < 1) return pages;

                $.each(tabPages, function (tabIndex, liElement) {
                    if (tabPages.length) {
                        let id = parseInt($(liElement).find(".tab_hidden_input").attr("data-id"));
                        let cards = $(liElement).parents(".tab_bottom_side").find(`#tab-content${id} .card`);

                        pages.tabSettings.push({
                            id,
                            name: $(liElement).find(".hidden_tab_name").val(),
                            tableID: getTableIDs(cards),
                        });
                    }
                });

                return pages;
            }

            function getTableIDs(cards) {
                let tableIDs = [];

                if (cards.length) {
                    $.each(cards, function (cardIndex, cardElement) {
                        tableIDs.push(parseInt($(cardElement).attr("data-table_id")));
                    });
                }

                return tableIDs;
            }

            //  Check if the tab is upside down or not
            function isReverseMode(target) {
                let reverseMode = false;
                if (
                    target.parents(".tab_bottom_side").find(".tab_positon_btn").hasClass("down") &&
                    target.parents(".tab_bottom_side").find(".tabs_container").hasClass("reverse")
                ) {
                    reverseMode = true;
                }

                return reverseMode;
            }

            return tabs;
        }

        // Update the tab changes and save into db
        updateTabChanges() {
            let data = this.collectData();

            if (!data.length) {
                this.call_alert(
                    "Warning &#9888;&#65039;",
                    "<b>No table is found inside tab to update. if you want to delete it than delete it from tab dashboard</b>",
                    "warning",
                    4
                );
                return;
            }

            data[0].tabID = parseInt($(".tab_bottom_side").attr("data-tabID"));

            $.ajax({
                url: file_url.admin_ajax,

                data: {
                    action: "gswpts_tab_changes",
                    type: "update",
                    data,
                },
                type: "post",

                beforeSend: () => {
                    $(".gswpts_save_tab_changes").attr("disabled", true);
                },

                success: (res) => {
                    if (
                        JSON.parse(res).response_type == "invalid_action" ||
                        JSON.parse(res).response_type == "invalid_request" ||
                        JSON.parse(res).response_type == "error"
                    ) {
                        this.call_alert("Error &#128683;", JSON.parse(res).output, "error", 4);
                    }

                    if (JSON.parse(res).response_type == "success") {
                        this.call_alert("Successfull &#128077;", JSON.parse(res).output, "success", 3);
                    }
                },

                complete: () => {
                    $(".gswpts_save_tab_changes").attr("disabled", false);
                },

                error: (err) => {
                    $(".gswpts_save_tab_changes").attr("disabled", false);
                    this.call_alert("Error &#128683;", "<b>Something went wrong</b>", "error", 3);
                },
            });
        }

        // Toggle the tab postion to up or down if toggle button is clicked
        toggleTabPosition(e) {
            let target = $(e.currentTarget);

            target.toggleClass("down");

            // Add reverse class to the tabs container to change its position
            target.parents(".tab_bottom_side").find(".tabs_container").toggleClass("reverse");
        }
    }
    new ManageTab();
});
