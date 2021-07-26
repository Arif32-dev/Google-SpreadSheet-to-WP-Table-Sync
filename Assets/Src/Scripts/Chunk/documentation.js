import Base_Class from "../Base/base_class";

jQuery(document).ready(function ($) {
    class Dashboard extends Base_Class {
        constructor() {
            super($);
            this.sortcode_copy_btn = $(".dashboard_sortcode_copy_btn");
            this.form = $("#subscriber-form");
            this.events();
        }
        events() {
            this.sortcode_copy_btn.on("click", (e) => {
                this.copy_shorcode(e);
            });

            if (this.get_slug_parameter("page") == "gswpts-recommendation") {
                this.retrieve_other_products();
            }
        }

        retrieve_other_products() {
            $.ajax({
                url: file_url.admin_ajax,

                data: {
                    action: "gswpts_product_fetch",
                },
                type: "post",

                beforeSend: () => {
                    $(".other_products_section").html(`
                             <div class="ui segment gswpts_loader" style="width: 100%;">
                                    <div class="ui active inverted dimmer">
                                        <div class="ui massive text loader"></div>
                                    </div>
                                    <p></p>
                                    <p></p>
                                    <p></p>
                            </div>
                    `);
                },
                success: (res) => {
                    console.log(res);
                    $(".other_products_section").html(res);
                },

                error: (err) => {
                    $(".other_products_section").html("");
                    this.call_alert(
                        "Error &#128683;",
                        "<b>Something went wrong on fetching related products</b>",
                        "error",
                        3
                    );
                },
            });
        }
    }
    new Dashboard();
});
