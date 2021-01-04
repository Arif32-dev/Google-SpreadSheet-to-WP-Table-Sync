
class Frontend_Global {

    table_obj(table_name, table_settings, dom) {

        let table_object = {
            dom: dom,
            buttons: [{
                text: 'JSON { }',
                className: 'ui inverted yellow button export_btns json_btn',
                action: function (e, dt, button, config) {
                    var data = dt.buttons.exportData();

                    $.fn.dataTable.fileSave(
                        new Blob([JSON.stringify(data)]),
                        `${table_name}.json`
                    );
                }
            },
            {
                text: 'PDF &nbsp;<i class="fas fa-file-pdf"></i>',
                extend: 'pdf',
                className: 'ui inverted red button export_btns pdf_btn',
                title: `${table_name}`
            },
            {
                text: 'CSV &nbsp; <i class="fas fa-file-csv"></i>',
                extend: 'csv',
                className: 'ui inverted green button export_btns csv_btn',
                title: `${table_name}`
            },
            {
                text: 'Excel &nbsp; <i class="fas fa-file-excel"></i>',
                extend: 'excel',
                className: 'ui inverted green button export_btns excel_btn',
                title: `${table_name}`
            },
            {
                text: 'Print &nbsp; <i class="fas fa-print"></i>',
                extend: 'print',
                className: 'ui inverted secondary button export_btns print_btn',
                title: `${table_name}`
            },
            {
                text: 'Copy &nbsp; <i class="fas fa-copy"></i>',
                extend: 'copy',
                className: 'ui inverted violet button export_btns copy_btn',
                title: `${table_name}`
            }

            ],

            "order": [],
            "responsive": true,
            "lengthMenu": [
                [1, 5, 10, 15, 25, 50, 100, -1],
                [1, 5, 10, 15, 25, 50, 100, "All"]
            ],
            "pageLength": parseInt(table_settings.default_rows_per_page),
            "lengthChange": true,
            "ordering": table_settings.allow_sorting == 'true' ? true : false,
            "destroy": true,
            "scrollX": true
        }

        return table_object;
    }

    html_loader() {
        let loader = `
               <div class="ui segment gswpts_table_loader">
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
}

// window.gswpts_frontend = new Frontend_Global()
window.Window.abc = 'hello world'