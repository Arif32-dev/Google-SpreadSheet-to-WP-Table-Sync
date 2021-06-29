import Base_Class from "../Base/base_class";

jQuery(document).ready(function ($) {
    class General_settings extends Base_Class {
        constructor() {
            super($);
            this.settings_checkbox = $(".settings_row input[type=checkbox]");
            this.pro_settings = $(".pro_setting");
            this.large_promo_close = $(".large_promo_close");
            this.reveal_arrow = $(".reveal_btn");
            this.events();
        }
        events() {
            this.settings_checkbox.on("change", (e) => {
                let target = $(e.currentTarget);
                let rotate_arrow = target.parents(".content").find(".reveal_btn");
                let desc_box = target.parents(".card").find(".settings_desc");

                /* if it is not pro setting than do things*/
                if (!target.hasClass("pro_setting")) {
                    if (target.prop("checked")) {
                        /* if reveal button dont have reveal_btn_rotate class than add it */
                        if (!rotate_arrow.hasClass("reveal_btn_rotate")) {
                            this.rotate_arrow(rotate_arrow);
                        }

                        if (!desc_box.hasClass("visible")) {
                            this.show_settings_desc(target);
                        }
                    } else {
                        this.rotate_back(rotate_arrow);

                        if (!desc_box.hasClass("hidden")) {
                            this.show_settings_desc(target);
                        }
                    }
                }

                // remove the restriction of css code editor

                if (target.attr("id") == "custom_css") {
                    this.removeCodeEditorRestriction(target);
                }
            });

            this.reveal_arrow.on("click", (e) => {
                let target = $(e.currentTarget);
                target.toggleClass("reveal_btn_rotate");
                this.show_settings_desc(target);
            });

            this.pro_settings.on("click", (e) => {
                this.activate_promo(e);
            });

            this.large_promo_close.on("click", (e) => {
                this.close_promo(e);
            });

            if (this.isProPluginActive()) {
                if (this.get_slug_parameter("page") == "gswpts-general-settings") {
                    this.initiateCodeEditor();
                }
            }
        }

        removeCodeEditorRestriction(target) {
            let codeEditor = target.parents(".card").find("#gswptsCSSeditor");
            if (target.prop("checked")) {
                codeEditor.css({
                    opacity: "1",
                    "pointer-events": "all",
                });
            } else {
                console.log("non-checked");
                codeEditor.css({
                    opacity: "0.5",
                    "pointer-events": "none",
                });
            }
        }

        initiateCodeEditor() {
            var aceEditor = ace.edit("gswptsCSSeditor", {
                selectionStyle: "text",
            });

            let cssCodeValueContainer = $("#css_code_value");

            aceEditor.setOptions({
                enableBasicAutocompletion: true, // the editor completes the statement when you hit Ctrl + Space
                enableLiveAutocompletion: true, // the editor completes the statement while you are typing
                showPrintMargin: false, // hides the vertical limiting strip
                maxLines: Infinity,
                fontSize: "100%", // ensures that the editor fits in the environment
            });

            // defines the style of the editor
            aceEditor.setTheme("ace/theme/vibrant_ink");
            aceEditor.renderer.setOption("showLineNumbers", true);
            aceEditor.getSession().setUseWrapMode(true);
            aceEditor.setShowPrintMargin(false);
            aceEditor.setOptions({
                autoScrollEditorIntoView: true,
            });
            aceEditor.setOption("mergeUndoDeltas", "always");
            aceEditor.getSession().setMode("ace/mode/css");
            aceEditor.setValue(cssCodeValueContainer.val());
            aceEditor.clearSelection();

            aceEditor.on("change", function (event) {
                let cssValue = aceEditor.getValue();
                cssCodeValueContainer.val(cssValue);
            });
        }

        show_settings_desc(target) {
            if (target.hasClass("pro_setting")) return;
            let desc_box = target.parents(".card").find(".settings_desc");
            desc_box.transition("fade down");
        }

        activate_promo(e) {
            let target = $(e.currentTarget);
            target.prop("checked", false);
            this.changeButtonTextLinks(e);
            let promo = target.parents(".dash_boxes").find(".promo_large");
            promo.addClass("active");
        }

        changeButtonTextLinks(e) {
            let target = $(e.currentTarget);
            let promoText = target.parents(".dash_boxes").find(".promo_large .promo_inner h5");
            let promoBtn = target.parents(".dash_boxes").find(".promo_large .promo_inner a");
            if (!target.hasClass("upcoming_setting")) {
                promoText.text("Get this feature in Pro extension");
                promoBtn.html("Get Pro");
            } else {
                promoText.text("This feature is coming soon");
                promoBtn.html("Upcoming in Pro");
            }
        }

        close_promo(e) {
            let target = $(e.currentTarget);
            let promo = target.parents(".promo_large");
            promo.removeClass("active");
        }

        rotate_arrow(target) {
            target.addClass("reveal_btn_rotate");
        }

        rotate_back(target) {
            target.removeClass("reveal_btn_rotate");
        }
    }
    new General_settings();
});
