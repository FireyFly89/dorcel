const path = require('path');
const webpack = require('webpack');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const NonJsEntryCleanupPlugin = require('./hmr/non-js-entry-cleanup-plugin');
const FriendlyErrorsWebpackPlugin = require('friendly-errors-webpack-plugin');
const { context, entry, devtool, outputFolder, publicFolder } = require('./config');
const HMR = require('./hmr');
const getPublicPath = require('./publicPath');

module.exports = (options) => {
    const { dev } = options;
    const hmr = HMR.getClient();
    return {
        mode: dev ? 'development' : 'production',
        devtool: dev ? devtool : false,
        context: path.resolve(context),
        entry: {
            'styles/main': dev ? [hmr, entry.styles] : entry.styles,
            'scripts/main': dev ? [hmr, entry.scripts] : entry.scripts
        },
        output: {
            path: path.resolve(outputFolder),
            publicPath: getPublicPath(publicFolder),
            filename: '[name].js'
        },
        module: {
            rules: [
                {
                    test: /\.js$/,
                    exclude: /(node_modules|bower_components)/,
                    use: [
                        ...(dev ? [{ loader: 'cache-loader' }] : []),
                        { loader: 'babel-loader' }
                    ]
                },
                {
                    test: /\.less$/,
                    use: [
                        dev ? 'style-loader' : MiniCssExtractPlugin.loader,
                        {
                            loader: 'css-loader',
                            options: {
                                sourceMap: dev,
                                importLoaders: 1,
                            },
                        },
                        {
                            loader: 'postcss-loader',
                            options: {
                                ident: 'postcss',
                                sourceMap: dev,
                                config: {
                                    ctx: { dev },
                                    path: './'
                                }
                            }
                        },
                        {
                            loader: 'less-loader',
                            options: {
                                sourceMap: dev,
                                sourceMapContents: dev
                            }
                        }
                    ]
                },
                {
                    test: /\.(ttf|otf|eot|woff2?|png|jpe?g|gif|svg|ico|mp4|webm)$/,
                    use: [
                        {
                            loader: 'file-loader',
                            options: {
                                name: '[path][name].[ext]',
                            }
                        }
                    ]
                },
            ]
        },
        plugins: [
            ...(dev ? [
                new webpack.HotModuleReplacementPlugin(),
                new FriendlyErrorsWebpackPlugin()
            ] : [
                new MiniCssExtractPlugin({
                    filename: '[name].css',
                    chunkFilename: '[id].css',
                    ignoreOrder: false, // Enable to remove warnings about conflicting order
                }),
                new NonJsEntryCleanupPlugin({
                    context: 'styles',
                    extesion: 'js',
                    includeSubfolders: true
                }),
                new CopyWebpackPlugin([
                    path.resolve(outputFolder)
                ], {
                    allowExternal: true,
                    beforeEmit: true
                }),
                new CopyWebpackPlugin([
                    {
                        from: path.resolve(`${context}/**/*`),
                        to: path.resolve(outputFolder),
                    }
                ], {
                    ignore: ['*.js', '*.less', '*.css']
                })
            ])
        ]
    };
};
