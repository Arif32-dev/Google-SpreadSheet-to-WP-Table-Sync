
import '../Styles/Backend/admin.scss'

import './Chunk/data_tables'
import './Chunk/create_google_sheet'
import './Chunk/fetch_sheet_data'
import './Chunk/manage_tables'
import './Chunk/ud_tables'

jQuery(document).ready(function () {
    $('.gswpts_loader').transition('fade');
    $('.dashboard_content, .manage_table_content, .create_table_content').transition('fade');
})