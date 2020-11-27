
$(document).ready(function () {
    class Data_Tables_Frontend {
        constructor() {
            this.frontend_table = $('#create_tables');
            this.events();
        }
        events() {
            this.show_manage_tables();
        }

        show_manage_tables() {
            this.frontend_table.DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        text: 'JSON',
                        className: 'ui inverted yellow button',
                        action: function (e, dt, button, config) {
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
                    { extend: 'print', className: 'ui inverted secondary button' }

                ],

                "order": [],
            });
        }
    }
    new Data_Tables_Frontend;
})