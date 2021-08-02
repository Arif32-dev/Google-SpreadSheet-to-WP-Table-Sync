import Global_Table_Config from "./common_func";

if (front_end_data.isProActive) {
    $(document).ready(function () {
        class Data_Tables_Default {
            constructor() {
                this.frontend_table = $(".gswpts_tables_container");
                if (front_end_data.asynchronous_loading == "off") {
                    this.events();
                } else {
                    return;
                }
            }
            events() {
                this.get_frontend_table();
            }

            get_frontend_table() {
                $.each(this.frontend_table, function (i, elem) {
                    let table_settings = JSON.parse($(elem).attr("data-table_settings"));

                    let table_name = $(elem).attr("data-table_name");

                    let table_obj = new Global_Table_Config();

                    table_obj.table_configuration($, i, elem, table_name, table_settings);
                });
            }
        }

        new Data_Tables_Default();
    });
} else {
    jQuery(document).ready(function ($) {
        class Data_Tables_Default {
            constructor() {
                this.frontend_table = $(".gswpts_tables_container");
                if (front_end_data.asynchronous_loading == "off") {
                    this.events();
                } else {
                    return;
                }
            }
            events() {
                this.get_frontend_table();
            }

            get_frontend_table() {
                $.each(this.frontend_table, function (i, elem) {
                    let table_settings = JSON.parse($(elem).attr("data-table_settings"));

                    let table_name = $(elem).attr("data-table_name");

                    let table_obj = new Global_Table_Config();

                    table_obj.table_configuration($, i, elem, table_name, table_settings);
                });
            }
        }

        new Data_Tables_Default();
    });
}
