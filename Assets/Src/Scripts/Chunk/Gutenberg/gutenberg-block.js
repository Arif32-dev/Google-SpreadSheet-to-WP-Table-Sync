import Block_Logo from "./logo";
import editFucntion from "./editFucntion";

const { registerBlockType } = wp.blocks;

registerBlockType("gswpts/google-sheets-to-wp-tables", {
    title: "Sheets To WP Table Live Sync",
    description:
        "Display Google Spreadsheet data to WordPress table in just a few clicks and keep the data always synced. Organize and display all your spreadsheet data in your WordPress quickly and effortlessly.",
    category: "common",
    example: {},
    icon: Block_Logo,
    keywords: ["spreadsheet", "google", "table"],
    attributes: {
        sortcode_id: {
            type: "integer",
            default: null,
        },

        block_init: {
            type: "boolean",
            default: false,
        },

        initializer_button_action: {
            type: "string",
            default: "",
        },

        show_choose_table: {
            type: "boolean",
            default: false,
        },

        btn_text: {
            type: "string",
            default: "Fetch Data",
        },

        req_type: {
            type: "string",
            default: "fetch",
        },

        init_table_name: {
            type: "string",
            default: "GSWPTS Table",
        },

        sheet_url: {
            type: "string",
            default: "",
        },

        is_table_saved_to_db: {
            type: "boolean",
            default: false,
        },

        table_selection: {
            type: "string",
            default: "no_selection",
        },

        innerHTML: {
            type: "string",
            default: "<h4>Choose table from block settings</h4>",
        },

        saved_tables: {
            type: "object",
            default: gswpts_gutenberg_block.table_details,
        },

        table_name: {
            type: "string",
            default: "",
        },

        show_settings: {
            type: "boolean",
            default: false,
        },

        table_settings: {
            type: "object",
            default: {
                table_title: false,
                defaultRowsPerPage: "10",
                showInfoBlock: true,
                responsiveTable: false,
                showXEntries: true,
                swapFilterInputs: false,
                swapBottomOptions: false,
                allowSorting: true,
                searchBar: true,
                verticalScroll: "400",
                cellFormat: "wrap",
                redirectionType: "_self",
            },
        },
    },

    edit: editFucntion,

    save: ({ attributes }) => {
        const { sortcode_id } = attributes;
        return <>[gswpts_table id = {sortcode_id}] </>;
    },
});
