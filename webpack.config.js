const path = require("path");
const glob = require("glob-all");
const PATHS = {
    src: path.join(__dirname, "assets/src"),
    includes: path.join(__dirname, "includes"),
    public: path.join(__dirname, "assets/public"),
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
                    paths: glob.sync([`${PATHS.src}/**/*`, `${PATHS.includes}/**/*`, `${PATHS.public}/**/*`], { nodir: true }),
                }),
                new MiniCssExtractPlugin({
                    filename: "styles/[name].min.css",
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
                import: path.resolve(__dirname, "assets/src/scripts/admin.js"),
                filename: "scripts/backend/[name].min.js",
            },
            frontend: {
                import: path.resolve(__dirname, "assets/src/scripts/frontend.js"),
                filename: "scripts/frontend/[name].min.js",
            },
        },
        output: {
            path: path.resolve(__dirname, "assets/public/"),
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
