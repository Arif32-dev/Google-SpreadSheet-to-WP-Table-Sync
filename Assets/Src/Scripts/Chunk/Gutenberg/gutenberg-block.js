import MultiSelect from "./Components/dropdown";

const { registerBlockType } = wp.blocks;
const { InspectorControls } = wp.blockEditor;

const {
    Panel,
    PanelBody,
    PanelRow,
    SelectControl,
    ToggleControl
} = wp.components;


$(document).ready(function () {

    registerBlockType(
        'gswpts/spreadsheet-to-wp-table-sync',
        {
            title: ('Spreadsheet to WP Table Sync'),
            description: ('Syncronization Google spreadsheet data to WP table'),
            category: 'common',
            icon: 'media-text',
            keywords: [('spreadsheet'), ('google'), ('table')],
            attributes: {

                sortcode_id: {
                    type: 'integer',
                    default: null
                },

                innerHTML: {
                    type: 'string',
                    default: '<h4>Choose saved table from block settings</h4>'
                },

                saved_tables: {
                    type: 'object',
                    default: gswpts_gutenberg_block.table_details
                },

                show_settings: {
                    type: 'boolean',
                    default: false
                }

            },
            edit: ({ attributes, setAttributes }) => {
                const { sortcode_id, innerHTML, saved_tables, show_settings } = attributes;

                function get_table_name_and_data() {
                    let select_options = [
                        { value: 'no_selection', label: 'Select a table' },
                    ];
                    if (saved_tables) {
                        saved_tables.forEach(table => {
                            select_options.push(
                                {
                                    value: parseInt(table.id),
                                    label: table.table_name
                                }
                            )
                        });
                    }
                    return select_options;
                }


                function fetch_data_by_id(id) {

                    if (typeof id != 'string') {
                        setAttributes({ innerHTML: '<h4>Choose saved table from block settings</h4>' });
                        return;
                    }

                    $.ajax({
                        url: gswpts_gutenberg_block.admin_ajax,
                        data: {
                            action: 'gswpts_sheet_fetch',
                            id: parseInt(id)
                        },
                        type: 'POST',

                        success: res => {

                            if (JSON.parse(res).response_type == 'invalid_action' || JSON.parse(res).response_type == 'invalid_request') {
                                setAttributes({ innerHTML: JSON.parse(res).output });

                                call_alert('Error &#128683;', JSON.parse(res).output, 'error', 4)
                            }

                            if (JSON.parse(res).response_type == 'success') {

                                setAttributes({ innerHTML: JSON.parse(res).output });

                                $('#' + id + ' > table').DataTable();

                            }


                        },

                        error: err => {
                            call_alert('Error &#128683;', '<b>Something went wrong</b>', 'error', 3)
                        },

                        complete: (res) => {

                            if (JSON.parse(res.responseText).response_type == 'success') {

                                setTimeout(() => {

                                    call_alert('Successfull &#128077;', '<b>Google Sheet data fetched successfully</b>', 'success', 3)

                                }, 700);
                            }

                        },
                    })
                }

                function call_alert(title, description, type, time, pos = 'bottom-right') {
                    $.suiAlert({
                        title: title,
                        description: description,
                        type: type,
                        time: time,
                        position: pos,
                    });
                }

                function add_select_box_style() {
                    $('#table_exporting').dropdown();
                }


                let loader = `
                                    <div class="ui segment gswpts_table_loader">
                                                <div class="ui active inverted dimmer">
                                                    <div class="ui large text loader">Loading</div>
                                                </div>
                                                <p></p>
                                                <p></p>
                                                <p></p>
                                        </div>
                                    `;


                return (
                    [
                        <InspectorControls style="margin-top: 40px">

                            <Panel header="Spreadsheet to WP Table Sync"
                            >
                                <PanelBody
                                    title="Choose Table"
                                    icon="media-text"
                                    initialOpen={true}
                                >
                                    <SelectControl
                                        label="Select Table"
                                        value='no_selection'
                                        onChange={(val) => {
                                            setAttributes({ sortcode_id: typeof val == 'string' ? parseInt(val) : null })
                                            setAttributes({ innerHTML: loader })
                                            setAttributes({ show_settings: !show_settings })
                                            // fetch_data_by_id(val)
                                        }
                                        }
                                        options={
                                            get_table_name_and_data()
                                        }
                                    />

                                </PanelBody>


                                <PanelBody
                                    title="Display Settings"
                                    icon="admin-settings"
                                    initialOpen={false}
                                >
                                    <PanelRow class="gswpts_panal">
                                        <ToggleControl
                                            label="Show Title"
                                            help='Enable this to show the table title in h3 tag above the table'
                                            checked={true}
                                            onChange={() => { alert('hello') }}
                                        />
                                        <br />
                                    </PanelRow>

                                    <PanelRow class="gswpts_panal">
                                        <SelectControl
                                            label="Default rows per page"
                                            value='10'
                                            onChange={(val) => { }}

                                        />
                                        <MultiSelect
                                            placeholder="Default rows per page"
                                            multiple={false}
                                            options={
                                                [
                                                    { key: 1, value: 1, text: '1' },
                                                    { key: 5, value: 5, text: '5' },
                                                    { key: 10, value: 10, text: '10' },
                                                    { key: 15, value: 15, text: '15' },
                                                    { key: 25, value: 25, text: '25' },
                                                    { key: 50, value: 50, text: '50' },
                                                    { key: 100, value: 100, text: '100' },
                                                    { key: 'all', value: 'all', text: 'All' },
                                                ]
                                            }
                                        />
                                        <br />
                                    </PanelRow>


                                    <PanelRow class="gswpts_panal">
                                        <ToggleControl
                                            label="Show info block"
                                            help='Show Showing X to Y of Z entries block below the table'
                                            checked={true}
                                            onChange={() => { alert('hello') }}
                                        />
                                        <br />
                                    </PanelRow>


                                    <PanelRow class="gswpts_panal">
                                        <ToggleControl
                                            label="Resposive table"
                                            help='Allow collapsing on mobile and tablet screen'
                                            checked={true}
                                            onChange={() => { alert('hello') }}
                                        />
                                        <br />
                                    </PanelRow>

                                    <PanelRow class="gswpts_panal">
                                        <ToggleControl
                                            label="Show X entries"
                                            help='Show X entries per page dropdown'
                                            checked={true}
                                            onChange={() => { alert('hello') }}
                                        />
                                        <br />
                                    </PanelRow>

                                    <PanelRow class="gswpts_panal">
                                        <ToggleControl
                                            label="Swap Filters"
                                            help='Swap the places of X entries dropdown and search filter input'
                                            checked={true}
                                            onChange={() => { alert('hello') }}
                                        />
                                        <br />
                                    </PanelRow>

                                    <PanelRow class="gswpts_panal">
                                        <ToggleControl
                                            label="Swap Bottom Elements"
                                            help='Swap the places of Showing X to Y of Z entries with table pagination filter'
                                            checked={true}
                                            onChange={() => { alert('hello') }}
                                        />
                                        <br />
                                    </PanelRow>


                                </PanelBody>

                                <PanelBody
                                    title="Sort and Filter"
                                    icon="filter"
                                    initialOpen={false}
                                >
                                    <PanelRow class="gswpts_panal">
                                        <ToggleControl
                                            label="Allow Sorting"
                                            help='Enable this feature to sort table data for frontend.'
                                            checked={true}
                                            onChange={() => { alert('hello') }}
                                        />
                                        <br />
                                    </PanelRow>

                                    <PanelRow class="gswpts_panal">
                                        <ToggleControl
                                            label="Search Bar"
                                            help='Enable this feature to show a search bar in for the table. It will help user to search data in the table'
                                            checked={true}
                                            onChange={() => { alert('hello') }}
                                        />
                                        <br />
                                    </PanelRow>

                                </PanelBody>

                                <PanelBody
                                    title="Table Tools"
                                    icon="admin-tools"
                                    initialOpen={true}
                                    onToggle={(e) => { console.log(e) }}
                                >
                                    <PanelRow class="gswpts_panal">
                                        <MultiSelect
                                            placeholder="Select Type"
                                            multiple={true}
                                            options={
                                                [
                                                    { key: 'json', value: 'json', text: 'JSON' },
                                                    { key: 'pdf', value: 'pdf', text: 'PDF' },
                                                    { key: 'csv', value: 'csv', text: 'CSV' },
                                                    { key: 'excel', value: 'excel', text: 'Excel' },
                                                    { key: 'print', value: 'print', text: 'Print' },
                                                    { key: 'copy', value: 'copy', text: 'Copy' },
                                                ]
                                            }
                                        />
                                        <br />
                                    </PanelRow>
                                </PanelBody>

                            </Panel>

                        </InspectorControls>,
                        <div
                            class="gswpts_create_table_container" id={sortcode_id}
                            style={{ marginRight: '0' }}
                            dangerouslySetInnerHTML={{ __html: innerHTML }}>
                        </div>
                    ]
                )
            },
            save: ({ attributes }) => {
                const { sortcode_id } = attributes

                return (
                    <>
                        {
                            sortcode_id == null ? (
                                <div></div>
                            ) : (
                                    <>
                                        [gswpts_table id ={sortcode_id}]
                                    </>
                                )
                        }
                    </>
                )
            }
        }
    );


})
