const path = require("path");
const glob = require("glob-all");
const PATHS = {
    Src: path.join(__dirname, "Assets/Src"),
    Includes: path.join(__dirname, "Includes"),
    Public: path.join(__dirname, "Assets/Public"),
};
const BrowserSyncPlugin = require("browser-sync-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const PurgecssPlugin = require("purgecss-webpack-plugin");
module.exports = {
    mode: "development",
    entry: {
        admin: {
            import: path.resolve(__dirname, "Assets/Src/Scripts/admin.js"),
            filename: "Scripts/Backend/[name].min.js",
        },
        frontend: {
            import: path.resolve(__dirname, "Assets/Src/Scripts/frontend.js"),
            filename: "Scripts/Frontend/[name].min.js",
        },
    },
    output: {
        path: path.resolve(__dirname, "Assets/Public/"),
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
                            presets: ["@babel/preset-env"],
                        },
                    },
                ],
            },
            {
                test: /.s?css$/,
                use: [
                    // {
                    //     loader: MiniCssExtractPlugin.loader,
                    // },
                    {
                        loader: "style-loader",
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
        ],
    },
    plugins: [
        new BrowserSyncPlugin({
            host: "localhost",
            port: 4040,
            injectChanges: true,
            watch: true,
            reloadOnRestart: true,
            reloadDelay: 300,
            files: ["./**/*.php"],
            watchEvents: ["change", "add", "unlink", "addDir", "unlinkDir"],
            proxy: "http://localhost/wordpress/wp-admin",
        }),
        // new PurgecssPlugin({
        //     paths: glob.sync(
        //         [`${PATHS.Src}/**/*`, `${PATHS.Includes}/**/*`, `${PATHS.Public}/**/*`],
        //         { nodir: true }
        //     ),
        // }),
        // new MiniCssExtractPlugin({
        //     filename: "Styles/[name].min.css",
        // }),
    ],
    devtool: "source-map",
    watch: true,
    watchOptions: {
        ignored: ["node_modules/**"],
    },
};
