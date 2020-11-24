export default class Base_Class {
    constructor() {
        this.sheet_form = $('#gswpts_create_table');
        this.sheet_details = $('#sheet_details');
        this.sheet_container = $('#spreadsheet_container');
    }

    call_alert(title, description, type, time, pos = 'bottom-right') {
        $.suiAlert({
            title: title,
            description: description,
            type: type,
            time: time,
            position: pos,
        });
    }
    html_loader() {
        let loader = `
                <div class="ui segment" style="width: 100%; min-height: 400px; margin-left: -18px;">
                    <div class="ui active inverted dimmer">
                        <div class="ui large text loader">Loading</div>
                    </div>
                    <p></p>
                    <p></p>
                    <p></p>
                </div>
            `
        return loader;
    }

    sheet_details_html(res) {
        let details = `
                <div id="sheet_ui_card" class="ui card" style="width: 60%; min-width: 400px;">
                    <div class="content">
                        <div class="row">
                            <div class="col-12 d-flex align-items-center justify-content-start mb-3">
                                <h4 class="m-0">Sheet Name: </h4>
                                <h5 class="m-0 ml-2">${res.sheet_data.sheet_name}</h5>
                            </div>
                            <div class="col-12 d-flex align-items-center justify-content-start mb-3">
                                <h4 class="m-0">Total Rows: </h4>
                                <h5 class="m-0 ml-2">${res.sheet_data.total_rows}</h5>
                            </div>
                            <div class="col-12 d-flex align-items-center justify-content-start mb-3">
                                <h4 class="m-0">Total Result: </h4>
                                <h5 class="m-0 ml-2">${res.sheet_data.sheet_total_result}</h5>
                            </div>
                            <div class="col-12 d-flex align-items-center justify-content-start">
                                <h4 class="m-0">Author Mail: </h4>
                                <h5 class="m-0 ml-2">${res.sheet_data.author_info[0].email.$t}</h5>
                            </div>
                            <div id="shortcode_container" style="display: none !important;" class="col-12 d-flex mt-3 align-items-center justify-content-start transition hidden">
                                <h4 class="m-0">Table Shortcode: </h4>
                                <h5 class="m-0 ml-2">
                                    <div class="ui action input">
                                        <input id="sortcode_value" type="text" class="copyInput" value="">
                                        <button id="sortcode_copy" type="button" name="copyToken" value="copy" class="copyToken ui right icon button">
                                            <i class="clone icon"></i>
                                        </button>
                                    </div>
                                </h5>
                            </div>
                        </div>
                    </div>
            </div>
            `
        return details;
    }

    show_shortcode(shortcode_id) {
        $('#shortcode_container').removeAttr("style");
        $('#shortcode_container').transition('scale');
        $('#sortcode_value').val(`[gswpts_table id=${shortcode_id}]`)
    }

    btn_changer(submit_button, text, reqType) {
        submit_button.html(`
                            ${text}
                        `);

        submit_button.attr('req-type', reqType)
    }
    get_slug_parameter(slug) {
        let url = new URL(window.location);
        let params = new URLSearchParams(url.search);
        let retrieve_param = params.get(slug);
        if (retrieve_param) {
            return retrieve_param
        } else {
            return false;
        }
    }
}
