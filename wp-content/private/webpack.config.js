const path = require('path');
const fs = require('fs');
const webpack = require('webpack');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const StyleLintPlugin = require('stylelint-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const ReplaceHashInFileWebpackPlugin = require('replace-hash-in-file-webpack-plugin');

const config = (env, { mode, theme = 'dff' }) => {
  const withHash = (file, ext, hash) => {
    return mode === 'production' ? `${file}${hash}.${ext}` : `${file}.${ext}`;
  };

  // Project paths.
  const SRC_PATH = `src/${theme}`;
  const OUT_PATH = `../themes/${theme}/dist`;
  const CHANGE_PATH = `../themes/${theme}`;
  const pathsToClean = [
    path.resolve(__dirname, `${OUT_PATH}/styles/*`),
    path.resolve(__dirname, `${OUT_PATH}/scripts/*`),
  ];
  const cleanOptions = { verbose: true };
  // Toggle certain plugins depending on project requirements.
  const USE_PLUGIN = {
    CLEAN: true,
    COPY: true,
  };

  // Bail out if no entry file found. Webpack tolerates it otherwise and contrinues to try build.
  if (!fs.existsSync(path.resolve(__dirname, `${SRC_PATH}/index.js`))) {
    return { bail: true };
  }

  const extraEntries = {};

  return {
    entry: {
      bundle: path.resolve(__dirname, `${SRC_PATH}/index.js`),
      // If creating Gutenberg blocks, uncomment this.
      gutenberg: path.resolve(__dirname, `${SRC_PATH}/gutenberg/index.js`),
      header: path.resolve(__dirname, `${SRC_PATH}/header.js`),
      ...extraEntries,
    },
    output: {
      filename: mode === 'production' ? '[name][hash].js' : '[name].js',
      path: path.resolve(__dirname, `${OUT_PATH}/scripts`),
    },
    watchOptions: {
      ignored: ['node_modules'],
    },
    performance: {
      assetFilter: function(assetFilename) {
        return /\.(js|css)$/.test(assetFilename);
      },
      maxEntrypointSize: 20000000, // Large entry point size as we only need asset size. (2mb)
      maxAssetSize: 500000, // Set max size to 500kb.
    },
    stats: {
      builtAt: true,
      entrypoints: false,
      modules: false,
      children: false,
      excludeAssets: 'static', // Hide the copied static files from the output:
    },
    plugins: [
      new webpack.ExtendedAPIPlugin(),

      new ReplaceHashInFileWebpackPlugin([
        {
          dir: `${CHANGE_PATH}/inc/`,
          files: ['asset-settings.php'],
          rules: [
            {
              search: /(gutenberg.*?\.js)/g,
              replace: withHash('gutenberg', 'js', '[hash]'),
            },
          ],
        },
        {
          dir: `${CHANGE_PATH}/inc/`,
          files: ['asset-settings.php'],
          rules: [
            {
              search: /(gutenberg.*?\.css)/g,
              replace: withHash('gutenberg', 'css', '[hash]'),
            },
          ],
        },
        {
          dir: `${CHANGE_PATH}/inc/`,
          files: ['asset-settings.php'],
          rules: [
            {
              search: /bundle.*?\.js/g,
              replace: withHash('bundle', 'js', '[hash]'),
            },
          ],
        },
        {
          dir: `${CHANGE_PATH}/inc/`,
          files: ['asset-settings.php'],
          rules: [
            {
              search: /search.*?\.js/g,
              replace: withHash('search', 'js', '[hash]'),
            },
          ],
        },
        {
          dir: `${CHANGE_PATH}/inc/`,
          files: ['asset-settings.php'],
          rules: [
            {
              search: /bundle.*?\.css/g,
              replace: withHash('bundle', 'css', '[hash]'),
            },
          ],
        },
        {
          dir: `${CHANGE_PATH}/inc/`,
          files: ['asset-settings.php'],
          rules: [
            {
              search: /header.*?\.js/g,
              replace: withHash('header', 'js', '[hash]'),
            },
          ],
        },
        {
          dir: `${CHANGE_PATH}/inc/`,
          files: ['asset-settings.php'],
          rules: [
            {
              search: /header.*?\.css/g,
              replace: withHash('header', 'css', '[hash]'),
            },
          ],
        },
      ]),
      new webpack.BannerPlugin(
        `Copyright (c) ${new Date().getFullYear()} Big Bite® | bigbite.net | @bigbite`,
      ),

      // Global vars for checking dev environment.
      new webpack.DefinePlugin({
        __DEV__: JSON.stringify(mode === 'development'),
        __PROD__: JSON.stringify(mode === 'production'),
        __TEST__: JSON.stringify(process.env.NODE_ENV === 'test'),
      }),

      // Sets mode so we can access it in `postcss.config.js`.
      new webpack.LoaderOptionsPlugin({
        options: {
          mode: mode,
        },
      }),

      // Extract CSS to own bundle, filenmae relative to output.path.
      new MiniCssExtractPlugin({
        filename: mode === 'production' ? '../styles/[name][hash].css' : '../styles/[name].css', // or ../styles/[name].css for dynamic name
        chunkFilename: '[name].css',
      }),

      // Lint SCSS.
      new StyleLintPlugin({
        syntax: 'scss',
      }),

      // Clean the dist file, only in production if enabled.
      // Currently scripts only, as it uses output.path.Uses output.path.

      // USE_PLUGIN.CLEAN &&
      //   new CleanWebpackPlugin({
      //     verbose: true,
      //     cleanOnceBeforeBuildPatterns: pathsToClean,
      //     dangerouslyAllowCleanPatternsOutsideProject: true,
      //     dry: false,
      //   }),

      // Copy any static assets.
      USE_PLUGIN.COPY &&
        new CopyWebpackPlugin([
          {
            from: 'static/**/*',
            to: path.resolve(__dirname, OUT_PATH),
            cache: false,
            context: path.resolve(__dirname, SRC_PATH),
          },
        ]),
    ].filter(Boolean),
    devtool: mode === 'production' ? 'source-map' : 'inline-cheap-module-source-map',
    module: {
      rules: [
        {
          exclude: [/node_modules\/(?!(swiper|dom7|get-apex-domain)\/).*/, /\.test\.jsx?$/],
          use: [{ loader: 'babel-loader' }],
        },
        {
          test: /\.(png|woff|woff2|eot|ttf|svg)(\?v=[0-9]\.[0-9]\.[0-9])?$/,
          loader: 'file-loader',
          options: {
            name: '[path][name].[ext]',
            emitFile: false, // Don't emit, using copy function to copy files over.
            outputPath: '../', // or // publicPath: '../'.
            context: path.resolve(__dirname, SRC_PATH),
          },
        },
        {
          test: /\.(sa|sc|c)ss$/,
          use: [
            MiniCssExtractPlugin.loader,
            {
              loader: 'css-loader',
              options: {
                sourceMap: true,
              },
            },
            {
              loader: 'postcss-loader',
              options: {
                sourceMap: true,
              },
            },
            {
              loader: 'resolve-url-loader',
              options: {
                debug: false,
                sourceMap: true,
              },
            },
            {
              loader: 'sass-loader',
              options: {
                sourceMap: true,
              },
            },
          ],
        },
        {
          test: /\.(js|jsx)$/,
          exclude: /node_modules/,
          use: ['babel-loader', 'eslint-loader'],
        },
      ],
    },
  };
};

module.exports = (env, argv) => config(env, argv);
