const path = require('path');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin')
module.exports = {
    mode: 'production',
    entry: {
        admin: path.resolve(__dirname, 'Assets/Src/Scripts/admin.js'),
        frontend: path.resolve(__dirname, 'Assets/Src/Scripts/frontend.js'),
    },
    output: {
        filename: '[name].min.js',
        path: path.resolve(__dirname, 'Assets/Public/Scripts/'),
    },
    module: {
        rules: [{
            test: /\.js$/,
            exclude: /node_modules/,
            use: [
                {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env']
                    }
                },
            ]
        }],
    },
    plugins: [
        new BrowserSyncPlugin({
            host: 'localhost',
            port: 4040,
            injectChanges: true,
            watch: true,
            reloadOnRestart: true,
            reloadDelay: 400,
            files: ['./**/*.php', './Assets/Public/**/*.min.css', './Assets/Public/**/*.min.js'],
            watchEvents: ['change', 'add', 'unlink', 'addDir', 'unlinkDir'],
            proxy: 'http://localhost/wordpress/wp-admin',
        })
    ],
    devtool: 'source-map',
    watch: true,
    watchOptions: {
        ignored: ['node_modules/**']
    }
};