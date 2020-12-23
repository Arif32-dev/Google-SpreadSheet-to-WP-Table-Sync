const { registerBlockType } = wp.blocks;
const {
    RichText,
    ColorPalette,
    InspectorControls,
    MediaUpload
} = wp.blockEditor;
const {
    Panel,
    PanelBody,
    PanelRow,
    Button
} = wp.components;

registerBlockType(
    'custom/gutenberg-practice',
    {
        title: ('Spreadsheet to WP Table Sync'),
        description: (''),
        category: 'common',
        icon: 'admin-generic',
        keywords: [('spreadsheet'), ('google'), ('table')],
        attributes: {
            content: {
                type: String,
            },
            color: {
                type: String,
                default: '#000'
            },
            image: {
                type: String,
                default: null
            }
        },
        edit: ({ attributes, setAttributes }) => {
            const { content, color, image } = attributes

            // return (

            // )
        },
        save: ({ attributes }) => {
            const { content, color, image } = attributes

            return (
                <div>
                    <h3 style={{ color: color }}>
                        {content}
                    </h3>
                    <div>
                        {image ? <img src={image} alt="uploaded" /> : null}
                    </div>
                </div>
            )
        }
    }
);
