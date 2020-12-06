import Base_Class from './../Base/base_class';

jQuery(document).ready(function($) {
    class Fetch_Sheet_Data extends Base_Class {
        constructor() {
            super();
            this.events();
        }
        events() {
            this.fetch_data_by_id();
        }
        fetch_data_by_id() {
            if (!this.get_slug_parameter('id')) {
                return;
            }
            $.ajax({
                url: file_url.admin_ajax,

                data: {
                    action: 'gswpts_sheet_fetch',
                    id: this.get_slug_parameter('id')
                },
                type: 'post',

                success: res => {

                    if (JSON.parse(res).response_type == 'invalid_action' || JSON.parse(res).response_type == 'invalid_request') {
                        this.sheet_container.html('');
                        this.call_alert('Error &#128683;', JSON.parse(res).output, 'error', 4)
                    }

                    if (JSON.parse(res).response_type == 'success') {

                        this.sheet_details.addClass('mt-4 p-0');
                        setTimeout(() => {
                            $('.edit_table_name').siblings('input[name=table_name]').val(JSON.parse(res).table_data.table_name);
                            $('.edit_table_name').parent().transition('fade up');
                            $("#table_type").dropdown('set selected', JSON.parse(res).table_data.source_type);

                            this.sheet_form.find('input[name=file_input]').val(JSON.parse(res).table_data.source_url)
                            this.sheet_details.html(this.sheet_details_html(JSON.parse(res)));
                            this.sheet_details.transition('scale');
                            this.show_shortcode(this.get_slug_parameter('id'));
                        }, 400);
                        this.sheet_container.html(JSON.parse(res).output)
                    }
                },

                error: err => {
                    this.call_alert('Error &#128683;', '<b>Something went wrong</b>', 'error', 3)
                    this.sheet_container.html('');
                },

                complete: (res) => {

                    if (JSON.parse(res.responseText).response_type == 'success') {
                        $('#create_tables').DataTable({
                            dom: 'lBfrtip',
                            buttons: [{
                                    text: 'JSON',
                                    className: 'ui inverted yellow button',
                                    action: function(e, dt, button, config) {
                                        var data = dt.buttons.exportData();

                                        $.fn.dataTable.fileSave(
                                            new Blob([JSON.stringify(data)]),
                                            'Export.json'
                                        );
                                    }
                                },
                                { extend: 'pdf', className: 'ui inverted red button' },
                                { extend: 'csv', className: 'ui inverted green button' },
                                { extend: 'excel', className: 'ui inverted green button' },
                                { extend: 'print', className: 'ui inverted secondary button' },
                                { extend: 'copy', className: 'ui inverted violet button' }

                            ],
                            "order": [],
                            responsive: true
                        });

                        setTimeout(() => {

                            this.call_alert('Successfull &#128077;', '<b>Google Sheet data fetched successfully</b>', 'success', 3)

                        }, 700);
                    }

                },
            })
        }
    }
    new Fetch_Sheet_Data;
})