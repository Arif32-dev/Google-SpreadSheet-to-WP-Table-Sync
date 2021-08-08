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
const config = (env, options) => {
    // scss loader based on prod or dev mode
    let scssLoader = () => {
        if (options.mode == "production") {
            return {
                loader: MiniCssExtractPlugin.loader,
            };
        } else {
            return {
                loader: "style-loader",
            };
        }
    };

    let productionPlugins = () => {
        if (options.mode == "production") {
            return [
                new PurgecssPlugin({
                    paths: glob.sync(
                        [`${PATHS.Src}/**/*`, `${PATHS.Includes}/**/*`, `${PATHS.Public}/**/*`],
                        { nodir: true }
                    ),
                }),
                new MiniCssExtractPlugin({
                    filename: "Styles/[name].min.css",
                }),
            ];
        } else {
            return [
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
            ];
        }
    };

    return {
        mode: options.mode,
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
                        scssLoader(),
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
        plugins: productionPlugins(),
        devtool: "source-map",
        watch: true,
        watchOptions: {
            ignored: ["node_modules/**"],
        },
    };
};

module.exports = config;
