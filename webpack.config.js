const webpack = require('webpack');
const path = require('path');
const BUILD_DIR = path.resolve(__dirname, 'public');
const SRC_DIR = path.resolve(__dirname, 'src');

const ExtractTextPlugin = require('extract-text-webpack-plugin');
let compileCSS = new ExtractTextPlugin('style.min.css');

module.exports = {
    context: SRC_DIR,
    entry: {
        app: ['./scss/main.scss', './js/index.js']
    },
    output: {
        path: path.join(BUILD_DIR, './assets'),
        filename: 'bundle.js'
    },
    module: {
        loaders: [
            {
                test: /\.jsx?$/,
                exclude: /(node_modules)/,
                include: SRC_DIR + '/js',
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['react', 'es2015']
                    }
                }
            },
            {
                test: /\.s?css$/,
                include: SRC_DIR + '/scss',
                loader: compileCSS.extract({fallback: 'style-loader', use: 'css-loader?minimize!sass-loader'})
            }
        ]
    },
    plugins: [
        new ExtractTextPlugin("[name].css")
    ],
    externals: {
        'react': 'React'
    }
};