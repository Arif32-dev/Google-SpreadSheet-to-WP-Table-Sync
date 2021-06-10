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
