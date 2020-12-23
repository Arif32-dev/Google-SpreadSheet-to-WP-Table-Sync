import Base_Class from './../Base/base_class';

jQuery(document).ready(function ($) {
    class Dashboard extends Base_Class {
        constructor() {
            super($);
            this.sortcode_copy_btn = $('.dashboard_sortcode_copy_btn');
            this.events();
        }
        events() {
            this.sortcode_copy_btn.on('click', (e) => {
                this.copy_shorcode(e)
            })
        }
    }
    new Dashboard;
})