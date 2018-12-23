// webpack v4

const path = require('path');
const ExtractTextPlugin = require('extract-text-webpack-plugin');

module.exports = {
    entry: { 
        back: './assets/src/js/mailbomb.js',
        front: './assets/src/js/mailbomb_front.js',
    },
    output: {
        path: path.resolve(__dirname, "dist"),
        filename: '[name]_mailbomb.pack.js'
    },
    devtool: "cheap-module-eval-source-map ",
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: "babel-loader"
                }
            },
            {
                test: /\.scss$/,
                use: ExtractTextPlugin.extract(
                {
                    fallback: 'style-loader',
                    use: ['css-loader', 'sass-loader']
                })
            }
        ]
    },
    plugins: [ 
        new ExtractTextPlugin({
            filename: '[name]_mailbomb.css'
        })
    ]
};