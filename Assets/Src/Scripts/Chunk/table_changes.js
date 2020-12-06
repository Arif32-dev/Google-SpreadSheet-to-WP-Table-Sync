import Base_Class from './../Base/base_class';

jQuery(document).ready(function ($) {
    class Table_Changes extends Base_Class {

        constructor() {
            super();
            this.table_settings = $('.tables_settings');
            this.events();
        }

        events() {
            this.table_settings.on('click', (e) => {
                this.change_btn_text(e);
            })
            this.add_select_box_style()
        }

        change_btn_text(e){
            let btn_text_value = $(e.currentTarget).attr('data-btn-text');
            let btn_attr_value = $(e.currentTarget).attr('data-attr-text');
            $('#fetch_save_btn').html(btn_text_value);
            $('#fetch_save_btn').attr('req-type', btn_attr_value);
        }

        add_select_box_style(){
            $('#rows_per_page').dropdown();
        }
     
    }
    new Table_Changes;
})