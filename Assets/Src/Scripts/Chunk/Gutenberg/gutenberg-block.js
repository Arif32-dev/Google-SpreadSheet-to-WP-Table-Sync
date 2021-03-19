import { Dropdown } from 'semantic-ui-react'
import Block_Logo from './logo';

const { registerBlockType } = wp.blocks;
const { InspectorControls, URLInput } = wp.blockEditor;
const { useEffect, useRef } = wp.element;

const {
    Panel,
    PanelBody,
    PanelRow,
    SelectControl,
    ToggleControl
} = wp.components;


registerBlockType(
    'gswpts/google-sheets-to-wp-tables',
    {
        title: ('Live Google Sheets to WordPress Tables'),
        description: ('Syncronization of Google spreadsheet data into WP table'),
        category: 'common',
        icon: Block_Logo,
        keywords: [('spreadsheet'), ('google'), ('table')],
        attributes: {

            sortcode_id: {
                type: 'integer',
                default: null
            },

            block_init: {
                type: 'boolean',
                default: false
            },

            initializer_button_action: {
                type: 'string',
                default: ''
            },

            show_choose_table: {
                type: 'boolean',
                default: false
            },

            btn_text: {
                type: 'string',
                default: 'Fetch Data'
            },

            req_type: {
                type: 'string',
                default: 'fetch'
            },

            init_table_name: {
                type: 'string',
                default: 'GSWPTS Table'
            },

            sheet_url: {
                type: 'string',
                default: ''
            },

            is_table_saved_to_db: {
                type: 'boolean',
                default: false
            },

            table_selection: {
                type: 'string',
                default: 'no_selection'
            },

            innerHTML: {
                type: 'string',
                default: '<h4>Choose table from block settings</h4>'
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
                    // responsiveTable: false,
                    showXEntries: true,
                    swapFilterInputs: false,
                    swapBottomOptions: false,
                    allowSorting: true,
                    searchBar: true,
                    // tableExport: null
                }
            },


        },
        edit: ({ attributes, setAttributes }) => {

            useEffect(() => {
                if (attributes.sortcode_id) {
                    fetch_data_by_id(attributes.sortcode_id)
                    setAttributes({ table_selection: attributes.sortcode_id })
                    setAttributes({ show_choose_table: true });
                    setAttributes({ initializer_button_action: 'choose_table' })
                }
            }, [])

            const spreadsheet_container = useRef(null)

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

            function fetch_data_by_url(url) {

                $.ajax({

                    url: gswpts_gutenberg_block.admin_ajax,

                    data: {
                        action: 'gswpts_sheet_create',
                        type: attributes.req_type,
                        table_name: attributes.init_table_name,
                        table_settings: attributes.table_settings,
                        file_input: url,
                        source_type: 'spreadsheet',
                        gutenberg_req: true
                    },

                    type: 'POST',

                    beforeSend: () => {
                        setAttributes({ show_settings: false });
                        if (attributes.req_type != 'save') {
                            setAttributes({ innerHTML: loader })
                            setAttributes({ btn_text: 'Fetch Data' });
                        }
                        setAttributes({ req_type: 'fetch' });

                    },

                    success: res => {
                        console.log(res);
                        if (JSON.parse(res).response_type == 'invalid_action' || JSON.parse(res).response_type == 'invalid_request') {
                            call_alert('Error &#128683;', JSON.parse(res).output, 'error', 4)
                            setAttributes({ req_type: 'fetch' });
                            setAttributes({ btn_text: 'Fetch Data' });
                            setAttributes({ show_settings: false });
                            setAttributes({ innerHTML: JSON.parse(res).output })
                        }

                        if (JSON.parse(res).response_type == 'empty_field') {
                            call_alert('Warning &#9888;&#65039;', JSON.parse(res).output, 'warning', 3)
                        }

                        if (JSON.parse(res).response_type == 'success') {

                            setAttributes({ req_type: 'save' });
                            setAttributes({ btn_text: 'Save Table' });
                            setAttributes({ innerHTML: JSON.parse(res).output });
                            setAttributes({ show_settings: true });

                        }

                        if (JSON.parse(res).response_type == 'saved') {
                            let id = (Object.values(JSON.parse(res).id)[0]);
                            setAttributes({ sortcode_id: parseInt(id) });
                            setAttributes({ is_table_saved_to_db: true });
                            call_alert('Successfull &#128077;', JSON.parse(res).output, 'success', 3)
                        }

                        if (JSON.parse(res).response_type == 'sheet_exists') {
                            call_alert('Warning &#9888;&#65039;', JSON.parse(res).output, 'warning', 3)
                        }
                    },

                    complete: (res) => {

                        if (JSON.parse(res.responseText).response_type == 'success') {

                            let default_settings = table_default_settings();
                            let defaultRowsPerPage = default_settings.defaultRowsPerPage;
                            let allowSorting = default_settings.allowSorting;
                            // let dom = 'B<"#filtering_input"lf>rt<"#bottom_options"ip>';
                            let dom = '<"#filtering_input"lf>rt<"#bottom_options"ip>';

                            $('#' + spreadsheet_container.current.id + ' #create_tables').DataTable(
                                table_object(
                                    defaultRowsPerPage,
                                    allowSorting,
                                    dom
                                )
                            );

                            setTimeout(() => {

                                call_alert('Successfull &#128077;', '<b>Google Sheet data fetched successfully</b>', 'success', 3)

                            }, 700);
                        }

                    },

                    error: err => {
                        call_alert('Error &#128683;', '<b>Something went wrong</b>', 'error', 3)
                        setAttributes({ show_settings: false });
                        setAttributes({ innerHTML: '<b>Something went wrong</b>' })
                    }
                })
            }

            function table_default_settings() {
                let default_settings = {
                    table_title: false,
                    defaultRowsPerPage: 10,
                    showInfoBlock: true,
                    // responsiveTable: false,
                    showXEntries: true,
                    swapFilterInputs: false,
                    swapBottomOptions: false,
                    allowSorting: true,
                    searchBar: true,
                    // tableExport: null
                }
                return default_settings;
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

                            call_alert('Successfull &#128077;', '<b>Google Sheet data fetched successfully</b>', 'success', 3)

                        }


                    },

                    error: err => {
                        call_alert('Error &#128683;', '<b>Something went wrong</b>', 'error', 3)
                        setAttributes({ innerHTML: '' });
                        setAttributes({ btn_text: 'Fetch Data' });
                    },

                    complete: (res) => {

                        if (JSON.parse(res.responseText).response_type == 'success') {

                            let table_settings = JSON.parse(JSON.parse(res.responseText).table_data.table_settings)

                            let table_name = JSON.parse(res.responseText).table_data.table_name;
                            // let dom = `B<"#filtering_input"${table_settings.show_x_entries == 'true' ? 'l' : ''}${table_settings.search_bar == 'true' ? 'f' : ''}>rt<"#bottom_options"${table_settings.show_info_block == 'true' ? 'i' : ''}p>`;
                            let dom = `<"#filtering_input"${table_settings.show_x_entries == 'true' ? 'l' : ''}${table_settings.search_bar == 'true' ? 'f' : ''}>rt<"#bottom_options"${table_settings.show_info_block == 'true' ? 'i' : ''}p>`;
                            let defaultRowsPerPage = table_settings.default_rows_per_page;
                            let allowSorting = table_settings.allow_sorting == 'true' ? true : false;

                            setAttributes({ table_name: table_name });

                            setTimeout(() => {

                                $('#' + id + ' #create_tables').DataTable(
                                    table_object(defaultRowsPerPage, allowSorting, dom)
                                );

                                update_default_attibutes(table_settings)

                                let swap_filter_state = table_settings.swap_filter_inputs == 'true' ? true : false;
                                let swap_bottom_state = table_settings.swap_bottom_options == 'true' ? true : false;

                                swap_input_filter(id, swap_filter_state);
                                swap_bottom_options(id, swap_bottom_state);

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
                // prevSettingObj.responsiveTable = ajax_table_settings.responsive_table == 'true' ? true : false;
                prevSettingObj.showXEntries = ajax_table_settings.show_x_entries == 'true' ? true : false;
                prevSettingObj.swapFilterInputs = ajax_table_settings.swap_filter_inputs == 'true' ? true : false;
                prevSettingObj.swapBottomOptions = ajax_table_settings.swap_bottom_options == 'true' ? true : false;
                prevSettingObj.allowSorting = ajax_table_settings.allow_sorting == 'true' ? true : false;
                prevSettingObj.searchBar = ajax_table_settings.search_bar == 'true' ? true : false;
                setAttributes({ table_settings: prevSettingObj });
            }

            function table_changer(id = null, prevSettingObj) {
                // let dom = `B<"#filtering_input"${prevSettingObj.showXEntries ? 'l' : ''}${prevSettingObj.searchBar ? 'f' : ''}>rt<"#bottom_options"${prevSettingObj.showInfoBlock ? 'i' : ''}p>`;
                let dom = `<"#filtering_input"${prevSettingObj.showXEntries ? 'l' : ''}${prevSettingObj.searchBar ? 'f' : ''}>rt<"#bottom_options"${prevSettingObj.showInfoBlock ? 'i' : ''}p>`;
                if (id == null) {
                    $('#' + spreadsheet_container.current.id + ' #create_tables').DataTable(
                        table_object(
                            prevSettingObj.defaultRowsPerPage,
                            prevSettingObj.allowSorting,
                            dom
                        )
                    );
                } else {

                    $('#' + id + ' #create_tables').DataTable(
                        table_object(
                            prevSettingObj.defaultRowsPerPage,
                            prevSettingObj.allowSorting,
                            dom
                        )
                    );
                }
            }

            function swap_input_filter(table_id, filter_state) {

                let selector = null;

                if (table_id == null) {
                    selector = spreadsheet_container.current.id;
                } else {
                    selector = table_id;
                }

                /* If checkbox is checked then swap filter */

                if (filter_state) {
                    $('#' + selector + ' #filtering_input').css('flex-direction', 'row-reverse');
                    $('#' + selector + ' #create_tables_length').css({
                        'margin-right': '0',
                        'margin-left': 'auto'
                    });
                    $('#' + selector + ' #create_tables_filter').css({
                        'margin-left': '0',
                        'margin-right': 'auto',
                    });
                } else {
                    /* Set back to default position */
                    $('#' + selector + ' #filtering_input').css('flex-direction', 'row');
                    $('#' + selector + ' #create_tables_length').css({
                        'margin-right': 'auto',
                        'margin-left': '0'
                    });
                    $('#' + selector + ' #create_tables_filter').css({
                        'margin-left': 'auto',
                        'margin-right': '0',
                    });
                }
            }

            function swap_bottom_options(table_id, bottom_state) {

                let selector = null;

                if (table_id == null) {
                    selector = spreadsheet_container.current.id
                } else {
                    selector = table_id;
                }

                let pagination_menu = $('#' + selector + ' #bottom_options .pagination.menu')

                let style = {
                    flex_direction: 'row-reverse',
                    table_info_style: {
                        margin_right: 0,
                        margin_left: 'auto'
                    },
                    table_paginate_style: {
                        margin_right: 'auto',
                        margin_left: 0
                    }
                }

                if (bottom_state) {

                    if (pagination_menu.children().length > 5) {
                        overflow_menu_style(selector)
                    } else {
                        bottom_option_style(style, selector)
                    }

                } else {
                    if (pagination_menu.children().length > 5) {
                        overflow_menu_style(selector)
                    } else {

                        style['flex_direction'] = 'row'

                        style.table_info_style['margin_left'] = 0
                        style.table_info_style['margin_right'] = 'auto'

                        style.table_paginate_style['margin_left'] = 'auto'
                        style.table_paginate_style['margin_right'] = 0

                        bottom_option_style(style, selector)
                    }
                }
            }

            function overflow_menu_style(selector) {
                $('#' + selector + ' #bottom_options').css('flex-direction', 'column');
                $('#' + selector + ' #create_tables_info').css({
                    'margin': '5px auto',
                });
                $('#' + selector + ' #create_tables_paginate').css({
                    'margin': '5px auto',
                });
            }

            function bottom_option_style($arg, selector) {
                $('#' + selector + ' #bottom_options').css('flex-direction', $arg['flex_direction']);
                $('#' + selector + ' #create_tables_info').css({
                    'margin-left': $arg['table_info_style']['margin_left'],
                    'margin-right': $arg['table_info_style']['margin_right']
                });
                $('#' + selector + ' #create_tables_paginate').css({
                    'margin-left': $arg['table_paginate_style']['margin_left'],
                    'margin-right': $arg['table_paginate_style']['margin_right'],
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
                    // "lengthMenu": [
                    //     [1, 5, 10, 15, 25, 50, 100, -1],
                    //     [1, 5, 10, 15, 25, 50, 100, "All"]
                    // ],
                    "lengthMenu": [
                        [1, 5, 10, 15],
                        [1, 5, 10, 15]
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

                            {
                                attributes.show_choose_table ? (
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
                                ) : (
                                    <></>
                                )
                            }


                            {
                                attributes.show_settings ? (
                                    <>
                                        <PanelBody
                                            title="Display Settings"
                                            icon="admin-settings"
                                            initialOpen={false}
                                        >
                                            <PanelRow>
                                                <ToggleControl
                                                    label="Show Title"
                                                    help='Enable this to show the table title in h3 tag above the table'
                                                    checked={attributes.table_settings.table_title}
                                                    onChange={() => {
                                                        const prevSettingObj = { ...attributes.table_settings };
                                                        prevSettingObj.table_title = !prevSettingObj.table_title;
                                                        setAttributes({ table_settings: prevSettingObj });
                                                        if (attributes.initializer_button_action == 'choose_table') {
                                                            save_changes_to_db(attributes.sortcode_id, prevSettingObj)
                                                        }
                                                    }}
                                                />
                                                <br />
                                            </PanelRow>

                                            <PanelRow>
                                                <div class="default_rows">
                                                    <h4 class="header">Default rows per page</h4>
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
                                                            ]
                                                        }
                                                        onChange={(e, { value }) => {
                                                            const prevSettingObj = { ...attributes.table_settings };
                                                            prevSettingObj.defaultRowsPerPage = value;
                                                            setAttributes({ table_settings: prevSettingObj });
                                                            if (attributes.initializer_button_action == 'choose_table') {
                                                                save_changes_to_db(attributes.sortcode_id, prevSettingObj)
                                                            }
                                                            table_changer(attributes.sortcode_id, prevSettingObj)

                                                            swap_input_filter(attributes.sortcode_id, prevSettingObj.swapFilterInputs)
                                                            swap_bottom_options(attributes.sortcode_id, prevSettingObj.swapBottomOptions)

                                                        }}
                                                    />
                                                </div>
                                                <br />
                                            </PanelRow>


                                            <PanelRow>
                                                <ToggleControl
                                                    label="Show info block"
                                                    help='Show Showing X to Y of Z entries block below the table'
                                                    checked={attributes.table_settings.showInfoBlock}
                                                    onChange={() => {
                                                        const prevSettingObj = { ...attributes.table_settings };
                                                        prevSettingObj.showInfoBlock = !prevSettingObj.showInfoBlock;
                                                        setAttributes({ table_settings: prevSettingObj });
                                                        if (attributes.initializer_button_action == 'choose_table') {
                                                            save_changes_to_db(attributes.sortcode_id, prevSettingObj)
                                                        }
                                                        table_changer(attributes.sortcode_id, prevSettingObj)

                                                        swap_input_filter(attributes.sortcode_id, prevSettingObj.swapFilterInputs)
                                                        swap_bottom_options(attributes.sortcode_id, prevSettingObj.swapBottomOptions)
                                                    }}
                                                />
                                                <br />
                                            </PanelRow>


                                            {/* <PanelRow>
                                                <ToggleControl
                                                    label="Resposive table"
                                                    help='Allow collapsing on mobile and tablet screen'
                                                    checked={attributes.table_settings.responsiveTable}
                                                    onChange={() => {
                                                        const prevSettingObj = { ...attributes.table_settings };
                                                        prevSettingObj.responsiveTable = !prevSettingObj.responsiveTable;
                                                        if (attributes.initializer_button_action == 'choose_table') {
                                                            save_changes_to_db(attributes.sortcode_id, prevSettingObj)
                                                        }
                                                        setAttributes({ table_settings: prevSettingObj });
                                                    }}
                                                />
                                                <br />
                                            </PanelRow> */}

                                            <PanelRow>
                                                <ToggleControl
                                                    label="Show X entries"
                                                    help='Show X entries per page dropdown'
                                                    checked={attributes.table_settings.showXEntries}
                                                    onChange={() => {
                                                        const prevSettingObj = { ...attributes.table_settings };
                                                        prevSettingObj.showXEntries = !prevSettingObj.showXEntries;
                                                        setAttributes({ table_settings: prevSettingObj });
                                                        if (attributes.initializer_button_action == 'choose_table') {
                                                            save_changes_to_db(attributes.sortcode_id, prevSettingObj)
                                                        }
                                                        table_changer(attributes.sortcode_id, prevSettingObj)
                                                        swap_input_filter(attributes.sortcode_id, prevSettingObj.swapFilterInputs)
                                                        swap_bottom_options(attributes.sortcode_id, prevSettingObj.swapBottomOptions)
                                                    }}
                                                />
                                                <br />
                                            </PanelRow>

                                            <PanelRow>
                                                <ToggleControl
                                                    label="Swap Filters"
                                                    help='Swap the places of X entries dropdown and search filter input'
                                                    checked={attributes.table_settings.swapFilterInputs}
                                                    onChange={() => {
                                                        const prevSettingObj = { ...attributes.table_settings };
                                                        prevSettingObj.swapFilterInputs = !prevSettingObj.swapFilterInputs;
                                                        setAttributes({ table_settings: prevSettingObj });
                                                        swap_input_filter(attributes.sortcode_id, prevSettingObj.swapFilterInputs)
                                                        if (attributes.initializer_button_action == 'choose_table') {
                                                            save_changes_to_db(attributes.sortcode_id, prevSettingObj)
                                                        }
                                                    }}
                                                />
                                                <br />
                                            </PanelRow>

                                            <PanelRow>
                                                <ToggleControl
                                                    label="Swap Bottom Elements"
                                                    help='Swap the places of Showing X to Y of Z entries with table pagination filter'
                                                    checked={attributes.table_settings.swapBottomOptions}
                                                    onChange={() => {
                                                        const prevSettingObj = { ...attributes.table_settings };
                                                        prevSettingObj.swapBottomOptions = !prevSettingObj.swapBottomOptions;
                                                        setAttributes({ table_settings: prevSettingObj });
                                                        swap_bottom_options(attributes.sortcode_id, prevSettingObj.swapBottomOptions)
                                                        if (attributes.initializer_button_action == 'choose_table') {
                                                            save_changes_to_db(attributes.sortcode_id, prevSettingObj)
                                                        }
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
                                            <PanelRow>
                                                <ToggleControl
                                                    label="Allow Sorting"
                                                    help='Enable this feature to sort table data for frontend.'
                                                    checked={attributes.table_settings.allowSorting}
                                                    onChange={() => {
                                                        const prevSettingObj = { ...attributes.table_settings };
                                                        prevSettingObj.allowSorting = !prevSettingObj.allowSorting;
                                                        setAttributes({ table_settings: prevSettingObj });
                                                        table_changer(attributes.sortcode_id, prevSettingObj)
                                                        swap_input_filter(attributes.sortcode_id, prevSettingObj.swapFilterInputs)
                                                        swap_bottom_options(attributes.sortcode_id, prevSettingObj.swapBottomOptions)
                                                        if (attributes.initializer_button_action == 'choose_table') {
                                                            save_changes_to_db(attributes.sortcode_id, prevSettingObj)
                                                        }
                                                    }}
                                                />
                                                <br />
                                            </PanelRow>

                                            <PanelRow>
                                                <ToggleControl
                                                    label="Search Bar"
                                                    help='Enable this feature to show a search bar in for the table. It will help user to search data in the table'
                                                    checked={attributes.table_settings.searchBar}
                                                    onChange={() => {
                                                        const prevSettingObj = { ...attributes.table_settings };
                                                        prevSettingObj.searchBar = !prevSettingObj.searchBar;
                                                        setAttributes({ table_settings: prevSettingObj });
                                                        table_changer(attributes.sortcode_id, prevSettingObj)
                                                        swap_input_filter(attributes.sortcode_id, prevSettingObj.swapFilterInputs)
                                                        swap_bottom_options(attributes.sortcode_id, prevSettingObj.swapBottomOptions)
                                                        if (attributes.initializer_button_action == 'choose_table') {
                                                            save_changes_to_db(attributes.sortcode_id, prevSettingObj)
                                                        }
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
                        {
                            attributes.block_init ? (
                                attributes.initializer_button_action == 'choose_table' ? (
                                    <div
                                        id="spreadsheet_container"
                                        dangerouslySetInnerHTML={{ __html: attributes.innerHTML }}
                                    >
                                    </div>
                                ) : (
                                    <>
                                        {
                                            attributes.is_table_saved_to_db == false ? (
                                                <div class="create_table_input">
                                                    <div class="ui icon input">
                                                        <input
                                                            required type="text"
                                                            name="table_name"
                                                            placeholder="Table Name"
                                                            value={attributes.init_table_name}
                                                            onChange={(e) => {
                                                                setAttributes({ init_table_name: e.target.value })
                                                            }}
                                                        />
                                                    </div>

                                                    <div class="ui icon input">
                                                        <input
                                                            required type="text"
                                                            name="file_input"
                                                            placeholder="Enter the google spreadsheet public url."
                                                            value={attributes.sheet_url}
                                                            onChange={(e) => {
                                                                setAttributes({ sheet_url: e.target.value })
                                                            }}
                                                        />
                                                        <i class="file icon"></i>
                                                    </div>

                                                    <button class="ui violet button" type="button" id="fetch_save_btn"
                                                        onClick={(e) => {
                                                            fetch_data_by_url(attributes.sheet_url)
                                                        }}
                                                    >
                                                        {attributes.btn_text}
                                                    </button>

                                                </div>
                                            ) : (
                                                <></>
                                            )
                                        }

                                        <div
                                            ref={spreadsheet_container}
                                            id="spreadsheet_container"
                                            dangerouslySetInnerHTML={{ __html: attributes.innerHTML }}
                                        >
                                        </div>
                                    </>
                                )
                            ) : (
                                <div class="block_initializer">

                                    <button id="create_button" class="positive ui button"
                                        onClick={(e) => {
                                            setAttributes({ block_init: true })
                                            setAttributes({ initializer_button_action: 'create_new' })
                                            setAttributes({ innerHTML: '' });
                                        }}
                                    >
                                        Create New &nbsp; <i class="plus icon"></i>
                                    </button>

                                    <button class="ui violet button" type="button"
                                        onClick={(e) => {
                                            setAttributes({ block_init: true })
                                            setAttributes({ initializer_button_action: 'choose_table' })
                                            setAttributes({ show_choose_table: true })
                                            document.querySelector('.interface-pinned-items > button').click()
                                        }}
                                    >
                                        Choose Table
                                        </button>

                                </div>
                            )
                        }

                    </div>
                ]
            )
        },
        save: ({ attributes }) => {
            const { sortcode_id } = attributes;
            return (
                <>
                    [gswpts_table id ={sortcode_id}]
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
            type: 'save_changes',
            gutenberg_req: true,
        },

        type: 'POST',

        success: res => {
            console.log(res);
            if (JSON.parse(res).response_type == 'invalid_action' || JSON.parse(res).response_type == 'invalid_request') {
                call_alert('Error &#128683;', JSON.parse(res).output, 'error', 4)
            }

            // if (JSON.parse(res).response_type == 'updated') {
            //     call_alert('Successfull &#128077;', JSON.parse(res).output, 'success', 3)
            // }
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

