const path = require('path')
const glob = require('glob')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const { CleanWebpackPlugin } = require('clean-webpack-plugin')
const CopyWebpackPlugin = require('copy-webpack-plugin')

// One bundle per ACF block.
const blocksJs = glob.sync('./template-parts/blocks/**/*.js').reduce((acc, path) => {
    const entry = path.split('/').pop().replace('.js', '')
    acc[entry] = path
    return acc
}, {});

module.exports = {
  entry: {
    ...blocksJs,
    'main': './src/js/index.js', 
    'cms': './src/js/cms.js',
    'styles': './src/scss/main.scss',
  },
  output: {
    path: path.resolve(__dirname, './dist'),
    filename: 'js/[name].bundle.js',
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: ['babel-loader'],
      },
      {
        test: /\.(scss|css)$/,
        use: [
          MiniCssExtractPlugin.loader,
          {
            loader: 'css-loader',
            options: {
              importLoaders: 2,
              sourceMap: false,
            },
          },
          'postcss-loader',
          'sass-loader',
        ],
      },
      {
        test: /\.(pdf|gif|png|jpe?g|svg)$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: '[name].[ext]',
              outputPath: 'images/',
            },
          },
        ],
      },
      {
        test: /\.(woff(2)?|ttf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: '[name].[ext]',
              outputPath: 'fonts/',
              publicPath: './../fonts',
            }
          }
        ]
      },
    ],
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: 'css/[name].css',
      chunkFilename: '[id].css',
    }),
    new CleanWebpackPlugin(), 
    new CopyWebpackPlugin({
      patterns: [
        { from: './src/images/', to: 'images/' },
      ],
    }),  
  ],
}
