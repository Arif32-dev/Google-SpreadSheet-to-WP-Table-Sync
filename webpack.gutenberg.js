const path = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

module.exports = {
    mode: "production",
    entry: {
        gutenberg: {
            import: path.resolve(__dirname, "assets/src/scripts/gutenberg.js"),
            filename: "scripts/backend/gutenberg/[name].min.js",
        },
    },
    output: {
        path: path.resolve(__dirname, "assets/public/"),
    },

    externals: {
        react: "React",
        "react-dom": "ReactDOM",
    },

    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: [
                    {
                        loader: "babel-loader",
                        options: {
                            presets: ["@babel/preset-env", "@babel/preset-react"],
                        },
                    },
                ],
            },
            {
                test: /.s?css$/,
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader,
                    },
                    {
                        loader: "css-loader",
                        options: {
                            sourceMap: true,
                            url: true,
                        },
                    },
                    {
                        loader: "postcss-loader",
                        options: {
                            postcssOptions: {
                                plugins: [["postcss-preset-env"]],
                            },
                        },
                    },
                    {
                        loader: "sass-loader",
                        options: {
                            sourceMap: true,
                        },
                    },
                ],
            },
            {
                test: /\.svg$/,
                use: ["@svgr/webpack"],
            },
        ],
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: "styles/[name].min.css",
        }),
    ],
    devtool: "source-map",
    watch: true,
    watchOptions: {
        ignored: ["node_modules/**"],
    },
};
