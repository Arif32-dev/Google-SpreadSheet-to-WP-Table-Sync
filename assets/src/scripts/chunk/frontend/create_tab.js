jQuery(document).ready(function ($) {
    class ManageTab {
        constructor() {
            this.events();
        }
        events() {
            $(document).on("click", ".tab_hidden_input", this.showContent);
        }

        showContent(e) {
            let target = $(e.currentTarget);

            target
                .parents(".tab_bottom_side")
                .find(`.tab_contents .tab-content`)
                .removeClass("active");

            let inputID = target.attr("id").match(/(\d+)/)[0];

            target
                .parents(".tab_bottom_side")
                .find(`.tab_contents #tab-content${inputID}`)
                .addClass("active");
        }
    }
    new ManageTab();
});
