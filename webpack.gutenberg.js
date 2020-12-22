const path = require('path');
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
module.exports = {
    ...defaultConfig,
    mode: 'production',
    entry: {
        gutenberg: {
            import: path.resolve(__dirname, 'Assets/Src/Scripts/gutenberg.js'),
            filename: 'Scripts/Backend/[name].min.js'
        },
    },
    output: {
        path: path.resolve(__dirname, 'Assets/Public/'),
    },
    module: {
        ...defaultConfig.module,
        rules: [
            ...defaultConfig.module.rules,
        ],
    },
    devtool: 'source-map',
    watch: true,
    watchOptions: {
        ignored: ['node_modules/**']
    }
};