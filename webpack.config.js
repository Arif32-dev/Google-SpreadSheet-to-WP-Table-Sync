const path = require('path');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
module.exports = {
    mode: 'production',
    entry: {
        admin: {
            import: path.resolve(__dirname, 'Assets/Src/Scripts/admin.js'),
            filename: 'Scripts/Backend/[name].min.js'
        },
        frontend: {
            import: path.resolve(__dirname, 'Assets/Src/Scripts/frontend.js'),
            filename: 'Scripts/Frontend/[name].min.js'
        },
    },
    output: {
        path: path.resolve(__dirname, 'Assets/Public/'),
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: [
                    {
                        loader: 'babel-loader',
                        options: {
                            presets: ['@babel/preset-env']
                        }
                    },
                ],
            },
            {
                test: /.s?css$/,
                use:
                    [
                        {
                            loader: MiniCssExtractPlugin.loader
                        },
                        {
                            loader: "css-loader",
                            options: {
                                sourceMap: true,
                            },
                        },
                        {
                            loader: "postcss-loader",
                            options: {
                                postcssOptions: {
                                    plugins: [
                                        [
                                            "postcss-preset-env",
                                        ],
                                    ],
                                }
                            }
                        },
                        {
                            loader: "sass-loader",
                            options: {
                                sourceMap: true,
                            },
                        },
                    ],
            },
        ],
    },
    plugins: [
        // new BrowserSyncPlugin({
        //     host: 'localhost',
        //     port: 4040,
        //     injectChanges: true,
        //     watch: true,
        //     reloadOnRestart: true,
        //     reloadDelay: 400,
        //     files: ['./**/*.php', './Assets/Public/**/*.min.css', './Assets/Public/**/*.min.js'],
        //     watchEvents: ['change', 'add', 'unlink', 'addDir', 'unlinkDir'],
        //     proxy: 'http://localhost/wordpress/wp-admin',
        // }),
        new MiniCssExtractPlugin({
            filename: 'Styles/[name].min.css',
        })
    ],
    devtool: 'source-map',
    watch: true,
    watchOptions: {
        ignored: ['node_modules/**']
    }
};