import { Dropdown } from 'semantic-ui-react'

const { registerBlockType } = wp.blocks;
const { InspectorControls } = wp.blockEditor;
const { useRef } = wp.element;

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

                table_name: {
                    type: 'string',
                    default: ''
                },

                show_settings: {
                    type: 'boolean',
                    default: false
                },

                table_settings: {
                    type: 'object',
                    default: {
                        table_title: false,
                        defaultRowsPerPage: '10',
                        showInfoBlock: true,
                        responsiveTable: false,
                        showXEntries: true,
                        swapFilterInputs: false,
                        swapBottomOptions: false,
                        allowSorting: true,
                        searchBar: true,
                        tableExport: null
                    }
                },


            },
            edit: ({ attributes, setAttributes }) => {
                const {
                    sortcode_id,
                    innerHTML,
                    saved_tables,
                    table_name,
                    show_settings,
                    table_settings,
                } = attributes;
                const table_container = useRef()


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

                        beforeSend: () => {
                            setAttributes({ show_settings: false });
                            setAttributes({ table_name: '' });
                        },

                        success: res => {

                            if (JSON.parse(res).response_type == 'invalid_action' || JSON.parse(res).response_type == 'invalid_request') {
                                setAttributes({ innerHTML: JSON.parse(res).output });
                                setAttributes({ show_settings: false });
                                setAttributes({ table_name: '' });


                                call_alert('Error &#128683;', JSON.parse(res).output, 'error', 4)
                            }

                            if (JSON.parse(res).response_type == 'success') {

                                setAttributes({ innerHTML: JSON.parse(res).output });
                                setAttributes({ show_settings: true });

                                let table_settings = JSON.parse(JSON.parse(res).table_data.table_settings)

                                let table_name = JSON.parse(res).table_data.table_name;
                                let dom = `B<"#filtering_input"${table_settings.show_x_entries == 'true' ? 'l' : ''}${table_settings.search_bar == 'true' ? 'f' : ''}>rt<"#bottom_options"${table_settings.show_info_block == 'true' ? 'i' : ''}p>`;
                                let defaultRowsPerPage = table_settings.default_rows_per_page;
                                let allowSorting = table_settings.allow_sorting;

                                setAttributes({ table_name: table_name });

                                $('#' + id + ' table').DataTable(
                                    table_object(defaultRowsPerPage, allowSorting, dom)
                                );

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

                function table_object(pageLength, ordering, dom) {
                    let obj = {
                        dom: dom,
                        "order": [],
                        "responsive": true,
                        "lengthMenu": [
                            [1, 5, 10, 15, 25, 50, 100, -1],
                            [1, 5, 10, 15, 25, 50, 100, "All"]
                        ],
                        "pageLength": parseInt(pageLength),
                        "lengthChange": true,
                        "ordering": ordering,
                        "destroy": true,
                        "scrollX": true

                    }
                    return obj;
                }


                return (
                    [
                        <InspectorControls style="margin-top: 40px">

                            <Panel header="Spreadsheet to WP Table Sync">
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
                                            fetch_data_by_id(val)
                                        }
                                        }
                                        options={
                                            get_table_name_and_data()
                                        }
                                    />

                                </PanelBody>

                                {
                                    show_settings ? (
                                        <>
                                            <PanelBody
                                                title="Display Settings"
                                                icon="admin-settings"
                                                initialOpen={false}
                                            >
                                                <PanelRow class="gswpts_panal">
                                                    <ToggleControl
                                                        label="Show Title"
                                                        help='Enable this to show the table title in h3 tag above the table'
                                                        checked={table_settings.table_title}
                                                        onChange={() => {
                                                            const prevSettingObj = { ...table_settings };
                                                            prevSettingObj.table_title = !prevSettingObj.table_title;
                                                            setAttributes({ table_settings: prevSettingObj });
                                                        }}
                                                    />
                                                    <br />
                                                </PanelRow>

                                                <PanelRow class="gswpts_panal">
                                                    <h4 class="header">Default rows per page</h4>
                                                    <br />
                                                    <Dropdown
                                                        placeholder="Default rows per page"
                                                        defaultValue={table_settings.defaultRowsPerPage}
                                                        fluid
                                                        selection
                                                        options={
                                                            [
                                                                { key: '1', value: '1', text: '1' },
                                                                { key: '5', value: '5', text: '5' },
                                                                { key: '10', value: '10', text: '10' },
                                                                { key: '15', value: '15', text: '15' },
                                                                { key: '25', value: '25', text: '25' },
                                                                { key: '50', value: '50', text: '50' },
                                                                { key: '100', value: '100', text: '100' },
                                                                { key: 'all', value: 'all', text: 'All' },
                                                            ]
                                                        }
                                                        onChange={(e, { value }) => {
                                                            const prevSettingObj = { ...table_settings };
                                                            prevSettingObj.defaultRowsPerPage = value;
                                                            setAttributes({ table_settings: prevSettingObj });
                                                        }}
                                                    />
                                                    <br />
                                                </PanelRow>


                                                <PanelRow class="gswpts_panal">
                                                    <ToggleControl
                                                        label="Show info block"
                                                        help='Show Showing X to Y of Z entries block below the table'
                                                        checked={table_settings.showInfoBlock}
                                                        onChange={() => {
                                                            const prevSettingObj = { ...table_settings };
                                                            prevSettingObj.showInfoBlock = !prevSettingObj.showInfoBlock;
                                                            setAttributes({ table_settings: prevSettingObj });
                                                        }}
                                                    />
                                                    <br />
                                                </PanelRow>


                                                <PanelRow class="gswpts_panal">
                                                    <ToggleControl
                                                        label="Resposive table"
                                                        help='Allow collapsing on mobile and tablet screen'
                                                        checked={table_settings.responsiveTable}
                                                        onChange={() => {
                                                            const prevSettingObj = { ...table_settings };
                                                            prevSettingObj.responsiveTable = !prevSettingObj.responsiveTable;
                                                            setAttributes({ table_settings: prevSettingObj });
                                                        }}
                                                    />
                                                    <br />
                                                </PanelRow>

                                                <PanelRow class="gswpts_panal">
                                                    <ToggleControl
                                                        label="Show X entries"
                                                        help='Show X entries per page dropdown'
                                                        checked={table_settings.showXEntries}
                                                        onChange={() => {
                                                            const prevSettingObj = { ...table_settings };
                                                            prevSettingObj.showXEntries = !prevSettingObj.showXEntries;
                                                            setAttributes({ table_settings: prevSettingObj });
                                                        }}
                                                    />
                                                    <br />
                                                </PanelRow>

                                                <PanelRow class="gswpts_panal">
                                                    <ToggleControl
                                                        label="Swap Filters"
                                                        help='Swap the places of X entries dropdown and search filter input'
                                                        checked={table_settings.swapFilterInputs}
                                                        onChange={() => {
                                                            const prevSettingObj = { ...table_settings };
                                                            prevSettingObj.swapFilterInputs = !prevSettingObj.swapFilterInputs;
                                                            setAttributes({ table_settings: prevSettingObj });
                                                        }}
                                                    />
                                                    <br />
                                                </PanelRow>

                                                <PanelRow class="gswpts_panal">
                                                    <ToggleControl
                                                        label="Swap Bottom Elements"
                                                        help='Swap the places of Showing X to Y of Z entries with table pagination filter'
                                                        checked={table_settings.swapBottomOptions}
                                                        onChange={() => {
                                                            const prevSettingObj = { ...table_settings };
                                                            prevSettingObj.swapBottomOptions = !prevSettingObj.swapBottomOptions;
                                                            setAttributes({ table_settings: prevSettingObj });
                                                        }}
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
                                                        checked={table_settings.allowSorting}
                                                        onChange={() => {
                                                            const prevSettingObj = { ...table_settings };
                                                            prevSettingObj.allowSorting = !prevSettingObj.allowSorting;
                                                            setAttributes({ table_settings: prevSettingObj });
                                                        }}
                                                    />
                                                    <br />
                                                </PanelRow>

                                                <PanelRow class="gswpts_panal">
                                                    <ToggleControl
                                                        label="Search Bar"
                                                        help='Enable this feature to show a search bar in for the table. It will help user to search data in the table'
                                                        checked={table_settings.searchBar}
                                                        onChange={() => {
                                                            const prevSettingObj = { ...table_settings };
                                                            prevSettingObj.searchBar = !prevSettingObj.searchBar;
                                                            setAttributes({ table_settings: prevSettingObj });
                                                        }}
                                                    />
                                                    <br />
                                                </PanelRow>

                                            </PanelBody>

                                        </>
                                    ) : (
                                            <>
                                            </>
                                        )
                                }

                            </Panel>

                        </InspectorControls >,
                        <div
                            class="gswpts_create_table_container" id={sortcode_id}
                            style={{ marginRight: '0' }}>
                            {
                                table_name != '' ?
                                    (<h3>{table_name}</h3>) :
                                    (<></>)
                            }
                            <div
                                id="spreadsheet_container"
                                ref={table_container}
                                dangerouslySetInnerHTML={{ __html: innerHTML }}
                            >

                            </div>
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
    )
})
