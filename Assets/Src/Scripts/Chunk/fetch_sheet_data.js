jQuery(document).ready(function ($) {
    class Fetch_Sheet_Data {
        constructor() {
            this.events();
        }
        events() {
            this.fetch_data_by_id();
        }
        fetch_data_by_id() {
            if (!this.get_id_parameter()) {
                console.log(this.get_id_parameter());
                return;
            }
            $.ajax({
                url: file_url.admin_ajax,

                data: {
                    action: 'gswpts_sheet_fetch',
                    id: this.get_id_parameter()
                },

                type: 'post',
                success: res => {
                    console.log(res);
                },
                error: err => {
                }
            })
        }
        get_id_parameter() {
            let url = new URL(window.location);
            let params = new URLSearchParams(url.search);
            let id = params.get("id");
            if (id) {
                return id
            } else {
                return false;
            }
        }
    }
    new Fetch_Sheet_Data;
})