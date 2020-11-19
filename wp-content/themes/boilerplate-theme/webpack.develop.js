const webpack = require('webpack')
const { merge } = require('webpack-merge')
const common = require('./webpack.common.js')
const BrowserSyncPlugin = require('browser-sync-webpack-plugin')

module.exports = merge(common, {
  mode: 'development',
  devtool: 'inline-source-map',
  module: {
    rules: [

    ],
  },
  plugins: [
    new BrowserSyncPlugin({
        proxy: {
            target: 'http://localhost:8888'
        },
        files:['**/*.php'],
        cors: true,
        reloadDelay: 0,
        open: false
    }),
  ],
})
