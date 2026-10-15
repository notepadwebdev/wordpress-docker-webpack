const path = require('path')
const glob = require('glob')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const { CleanWebpackPlugin } = require('clean-webpack-plugin')
const CopyWebpackPlugin = require('copy-webpack-plugin')

// One bundle per ACF block.
const blocksJs = glob.sync('./template-parts/blocks/**/*.js').reduce((acc, filePath) => {
  const entry = path.basename(filePath, '.js')
  acc[entry] = `./${filePath}`
  return acc
}, {});

// One bundle per shared module.
const modulesJs = glob.sync('./src/js/modules/*.js').reduce((acc, filePath) => {
  const entry = path.basename(filePath, '.js')
  acc[entry] = `./${filePath}`
  return acc
}, {});

module.exports = {
  entry: {
    ...blocksJs,
    ...modulesJs,
    'main': './src/js/index.js', 
    'cms': './src/js/cms.js',
    'styles': './src/scss/main.scss',
  },
  output: {
    path: path.resolve(__dirname, './dist'),
    filename: 'js/[name].bundle.js',
    assetModuleFilename: 'assets/[hash][ext][query]'
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
              presets: [
                ['@babel/preset-env'],
                [
                  '@babel/preset-react',
                  {
                    runtime: 'classic',
                    pragma: 'wp.element.createElement',
                    pragmaFrag: 'wp.element.Fragment',
                  },
                ],
              ],
            },
          },
        ],
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
          {
            loader: "sass-loader",
            options: {
              api: "modern",
              sassOptions: {
                // Your sass options
              },
            },
          },
        ],
      },
      {
        test: /\.(png|svg|jpg|gif)$/,
        type: "asset/resource",
        generator: {
          filename: 'assets/[name][ext]', 
        },
      },
      {
        test: /\.(woff(2)?|ttf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/,
        type: 'asset/resource',
        dependency: { not: ['url'] },
        generator: {
          filename: 'assets/[hash][ext][query]'
        }
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
        { from: './src/assets/', to: 'assets/' },
        { from: './src/js/vendor/', to: 'js/vendor/' },
      ],
    }),  
  ],
}
