const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
module.exports = {
    mode: 'production',
    entry: {
        gutenberg: {
            import: path.resolve(__dirname, 'Assets/Src/Scripts/gutenberg.js'),
            filename: 'Scripts/Backend/Gutenberg/[name].min.js'
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
                            presets: ['@babel/preset-env', '@babel/preset-react']
                        }
                    }
                ],
            },
            {
                test: /.s?css$/,
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader
                    },
                    {
                        loader: "css-loader",
                        options: {
                            sourceMap: true,
                            url: true
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