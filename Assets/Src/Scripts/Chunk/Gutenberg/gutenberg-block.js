import { Dropdown } from 'semantic-ui-react'

const { registerBlockType } = wp.blocks;
const { InspectorControls } = wp.blockEditor;
const { useEffect } = wp.element;

const {
    Panel,
    PanelBody,
    PanelRow,
    SelectControl,
    ToggleControl
} = wp.components;


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

            table_selection: {
                type: 'string',
                default: 'no_selection'
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

            useEffect(() => {
                if (attributes.sortcode_id) {
                    fetch_data_by_id(attributes.sortcode_id)
                }
            }, [])

            function get_table_name_and_data() {
                let select_options = [
                    { value: 'no_selection', label: 'Select a table' },
                ];
                if (attributes.saved_tables) {
                    attributes.saved_tables.forEach(table => {
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

                if (typeof parseInt(id) != 'number') {
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
                        setAttributes({ innerHTML: loader })
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

                            update_default_attibutes(table_settings)

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

                            console.log($('#' + id + ' > #spreadsheet_container'));


                            setTimeout(() => {

                                call_alert('Successfull &#128077;', '<b>Google Sheet data fetched successfully</b>', 'success', 3)

                            }, 700);
                        }

                    },
                })
            }

            function update_default_attibutes(ajax_table_settings) {
                const prevSettingObj = { ...attributes.table_settings };
                prevSettingObj.table_title = ajax_table_settings.table_title == 'true' ? true : false;
                prevSettingObj.defaultRowsPerPage = ajax_table_settings.default_rows_per_page;
                prevSettingObj.showInfoBlock = ajax_table_settings.show_info_block == 'true' ? true : false;
                prevSettingObj.responsiveTable = ajax_table_settings.responsive_table == 'true' ? true : false;
                prevSettingObj.showXEntries = ajax_table_settings.show_x_entries == 'true' ? true : false;
                prevSettingObj.swapFilterInputs = ajax_table_settings.swap_filter_inputs == 'true' ? true : false;
                prevSettingObj.swapBottomOptions = ajax_table_settings.swap_bottom_options == 'true' ? true : false;
                prevSettingObj.allowSorting = ajax_table_settings.allow_sorting == 'true' ? true : false;
                prevSettingObj.searchBar = ajax_table_settings.search_bar == 'true' ? true : false;
                setAttributes({ table_settings: prevSettingObj });
            }

            function table_changer(id, prevSettingObj) {
                let dom = `B<"#filtering_input"${prevSettingObj.showXEntries ? 'l' : ''}${prevSettingObj.searchBar ? 'f' : ''}>rt<"#bottom_options"${prevSettingObj.showInfoBlock ? 'i' : ''}p>`;
                $('#' + id + ' table').DataTable(
                    table_object(
                        prevSettingObj.defaultRowsPerPage,
                        prevSettingObj.allowSorting,
                        dom
                    )
                );
            }

            function swap_input_filter(table_id, filter_state) {
                /* If checkbox is checked then swap filter */
                if (filter_state) {
                    $('#' + table_id + ' #filtering_input').css('flex-direction', 'row-reverse');
                    console.log($('#' + table_id + ' #create_tables_length'))
                    $('#' + table_id + ' #create_tables_length').css({
                        'margin-right': '0',
                        'margin-left': 'auto'
                    });
                    console.log($('#' + table_id + ' #create_tables_filter'))
                    $('#' + table_id + ' #create_tables_filter').css({
                        'margin-left': '0',
                        'margin-right': 'auto',
                    });
                } else {
                    /* Set back to default position */
                    $('#' + table_id + ' #filtering_input').css('flex-direction', 'row');
                    $('#' + table_id + ' #create_tables_length').css({
                        'margin-right': 'auto',
                        'margin-left': '0'
                    });
                    $('#' + table_id + ' #create_tables_filter').css({
                        'margin-left': 'auto',
                        'margin-right': '0',
                    });
                }
            }

            function swap_bottom_options(table_id, bottom_state) {
                if (bottom_state) {
                    $('#' + table_id + ' #bottom_options').css('flex-direction', 'row-reverse');
                    $('#' + table_id + ' #create_tables_info').css({
                        'margin-right': '0',
                        'margin-left': 'auto'
                    });
                    $('#' + table_id + ' #create_tables_paginate').css({
                        'margin-left': '0',
                        'margin-right': 'auto',
                    });
                } else {
                    $('#' + table_id + ' #bottom_options').css('flex-direction', 'row');
                    $('#' + table_id + ' #create_tables_info').css({
                        'margin-right': 'auto',
                        'margin-left': '0'
                    });
                    $('#' + table_id + ' #create_tables_paginate').css({
                        'margin-left': 'auto',
                        'margin-right': '0',
                    });
                }
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
                                    value={attributes.table_selection}
                                    onChange={(val) => {
                                        setAttributes({ table_selection: val })
                                        setAttributes({ sortcode_id: typeof val == 'string' ? parseInt(val) : null })
                                        fetch_data_by_id(val)
                                    }
                                    }
                                    options={
                                        get_table_name_and_data()
                                    }
                                />

                            </PanelBody>

                            {
                                attributes.show_settings ? (
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
                                                    checked={attributes.table_settings.table_title}
                                                    onChange={() => {
                                                        const prevSettingObj = { ...attributes.table_settings };
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
                                                    defaultValue={attributes.table_settings.defaultRowsPerPage}
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
                                                            { key: '-1', value: '-1', text: 'All' },
                                                        ]
                                                    }
                                                    onChange={(e, { value }) => {
                                                        const prevSettingObj = { ...attributes.table_settings };
                                                        prevSettingObj.defaultRowsPerPage = value;
                                                        setAttributes({ table_settings: prevSettingObj });
                                                        table_changer(attributes.sortcode_id, prevSettingObj)
                                                    }}
                                                />
                                                <br />
                                            </PanelRow>


                                            <PanelRow class="gswpts_panal">
                                                <ToggleControl
                                                    label="Show info block"
                                                    help='Show Showing X to Y of Z entries block below the table'
                                                    checked={attributes.table_settings.showInfoBlock}
                                                    onChange={() => {
                                                        const prevSettingObj = { ...attributes.table_settings };
                                                        prevSettingObj.showInfoBlock = !prevSettingObj.showInfoBlock;
                                                        setAttributes({ table_settings: prevSettingObj });
                                                        table_changer(attributes.sortcode_id, prevSettingObj)
                                                    }}
                                                />
                                                <br />
                                            </PanelRow>


                                            <PanelRow class="gswpts_panal">
                                                <ToggleControl
                                                    label="Resposive table"
                                                    help='Allow collapsing on mobile and tablet screen'
                                                    checked={attributes.table_settings.responsiveTable}
                                                    onChange={() => {
                                                        const prevSettingObj = { ...attributes.table_settings };
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
                                                    checked={attributes.table_settings.showXEntries}
                                                    onChange={() => {
                                                        const prevSettingObj = { ...attributes.table_settings };
                                                        prevSettingObj.showXEntries = !prevSettingObj.showXEntries;
                                                        setAttributes({ table_settings: prevSettingObj });
                                                        table_changer(attributes.sortcode_id, prevSettingObj)
                                                    }}
                                                />
                                                <br />
                                            </PanelRow>

                                            <PanelRow class="gswpts_panal">
                                                <ToggleControl
                                                    label="Swap Filters"
                                                    help='Swap the places of X entries dropdown and search filter input'
                                                    checked={attributes.table_settings.swapFilterInputs}
                                                    onChange={() => {
                                                        const prevSettingObj = { ...attributes.table_settings };
                                                        prevSettingObj.swapFilterInputs = !prevSettingObj.swapFilterInputs;
                                                        setAttributes({ table_settings: prevSettingObj });
                                                        swap_input_filter(attributes.sortcode_id, prevSettingObj.swapFilterInputs)
                                                    }}
                                                />
                                                <br />
                                            </PanelRow>

                                            <PanelRow class="gswpts_panal">
                                                <ToggleControl
                                                    label="Swap Bottom Elements"
                                                    help='Swap the places of Showing X to Y of Z entries with table pagination filter'
                                                    checked={attributes.table_settings.swapBottomOptions}
                                                    onChange={() => {
                                                        const prevSettingObj = { ...attributes.table_settings };
                                                        prevSettingObj.swapBottomOptions = !prevSettingObj.swapBottomOptions;
                                                        setAttributes({ table_settings: prevSettingObj });
                                                        swap_bottom_options(attributes.sortcode_id, prevSettingObj.swapBottomOptions)
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
                                                    checked={attributes.table_settings.allowSorting}
                                                    onChange={() => {
                                                        const prevSettingObj = { ...attributes.table_settings };
                                                        prevSettingObj.allowSorting = !prevSettingObj.allowSorting;
                                                        setAttributes({ table_settings: prevSettingObj });
                                                        table_changer(attributes.sortcode_id, prevSettingObj)
                                                    }}
                                                />
                                                <br />
                                            </PanelRow>

                                            <PanelRow class="gswpts_panal">
                                                <ToggleControl
                                                    label="Search Bar"
                                                    help='Enable this feature to show a search bar in for the table. It will help user to search data in the table'
                                                    checked={attributes.table_settings.searchBar}
                                                    onChange={() => {
                                                        const prevSettingObj = { ...attributes.table_settings };
                                                        prevSettingObj.searchBar = !prevSettingObj.searchBar;
                                                        setAttributes({ table_settings: prevSettingObj });
                                                        table_changer(attributes.sortcode_id, prevSettingObj)
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
                        class="gswpts_create_table_container" id={attributes.sortcode_id}
                        style={{ marginRight: '0' }}>
                        {
                            attributes.table_name != '' && attributes.table_settings.table_title ?
                                (<h3>{attributes.table_name}</h3>) :
                                (<></>)
                        }
                        <div
                            id="spreadsheet_container"
                            dangerouslySetInnerHTML={{ __html: attributes.innerHTML }}
                        >
                        </div>
                    </div>
                ]
            )
        },
        save: ({ attributes }) => {
            const { sortcode_id, table_settings } = attributes;
            // save_changes_to_db(sortcode_id, table_settings)
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
    },
)

function save_changes_to_db(id, table_settings) {

    $.ajax({

        url: gswpts_gutenberg_block.admin_ajax,

        data: {
            action: 'gswpts_sheet_create',
            table_settings: table_settings,
            id: parseInt(id),
            type: 'save_changes'
        },

        type: 'POST',

        success: res => {
            console.log(res);
            if (JSON.parse(res).response_type == 'invalid_action' || JSON.parse(res).response_type == 'invalid_request') {
                call_alert('Error &#128683;', JSON.parse(res).output, 'error', 4)
            }

            if (JSON.parse(res).response_type == 'updated') {
                call_alert('Successfull &#128077;', JSON.parse(res).output, 'success', 3)
            }
        },
        error: err => {
            call_alert('Error &#128683;', '<b>Something went wrong</b>', 'error', 3)
        }
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
