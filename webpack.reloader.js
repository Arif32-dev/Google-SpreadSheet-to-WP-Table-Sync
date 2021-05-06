const BrowserSyncPlugin = require('browser-sync-webpack-plugin');

module.exports = {
    mode: 'production',
    plugins: [
        new BrowserSyncPlugin({
            host: 'localhost',
            port: 4040,
            injectChanges: true,
            watch: true,
            reloadOnRestart: true,
            reloadDelay: 300,
            files: ['./**/*.php'],
            watchEvents: ['change', 'add', 'unlink', 'addDir', 'unlinkDir'],
            proxy: 'http://localhost/wordpress/wp-admin',
        }),
    ],
    devtool: 'source-map',
    watch: true,
    watchOptions: {
        ignored: ['node_modules/**']
    }
};