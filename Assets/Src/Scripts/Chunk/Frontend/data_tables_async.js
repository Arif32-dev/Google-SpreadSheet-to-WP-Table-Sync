import Global_Table_Config from './common_func';


$(document).ready(function () {
    class Data_Tables_Async {
        constructor() {
            this.frontend_table = $('.gswpts_tables_container');
            if (front_end_data.asynchronous_loading == 'on') {
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
                let id = $(elem).attr('id');
                $.ajax({
                    url: front_end_data.admin_ajax,

                    data: {
                        action: 'gswpts_sheet_fetch',
                        id: id
                    },
                    type: 'post',
                    success: res => {

                        if (JSON.parse(res).response_type == 'success') {

                            let table_settings = JSON.parse(JSON.parse(res).table_data.table_settings);

                            if (table_settings.table_title == 'true') {
                                $(elem).find('h3').html(JSON.parse(res).table_data.table_name);
                            }

                            $(elem).find('.gswpts_tables_content').html(JSON.parse(res).output);

                            let table_name = JSON.parse(res).table_data.table_name

                            let table_obj = new Global_Table_Config();

                            table_obj.table_configuration(i, elem, table_name, table_settings)

                        } else {
                            alert('Table could not be loaded. Try again')
                        }
                    },

                    error: err => {
                        alert('Something went wrong')
                    },

                })
            });

        }

    }
    new Data_Tables_Async;
})