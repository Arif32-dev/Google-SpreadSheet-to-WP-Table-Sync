
jQuery(document).ready(function ($) {
    class Data_Tables_Frontend {
        constructor() {
            this.frontend_table = $('#create_tables');
            this.export_json_btn = $('#gswpts_export_json');
            this.export_csv_btn = $('#gswpts_export_csv');
            this.export_pdf_btn = $('#gswpts_export_pdf');
            this.events();
        }
        events() {
            this.show_manage_tables();
            this.export_json_btn.on('click', (e) => {
                this.export_to_json()
            })
            this.export_csv_btn.on('click', (e) => {
                this.export_to_csv()
            })
            this.export_pdf_btn.on('click', (e) => {
                this.export_to_pdf()
            })
        }
        show_manage_tables() {
            this.frontend_table.DataTable({
                "order": []
            });
        }
        export_to_json(e) {
            this.frontend_table.tableHTMLExport({ type: 'json', filename: 'sample.json' });
        }
        export_to_csv(e) {
            this.frontend_table.tableHTMLExport({ type: 'csv', filename: 'sample.csv' });
        }
        export_to_pdf(e) {
            // this.frontend_table.tableHTMLExport({ type: 'pdf', filename: 'sample.pdf' });
        }
    }
    new Data_Tables_Frontend;
})