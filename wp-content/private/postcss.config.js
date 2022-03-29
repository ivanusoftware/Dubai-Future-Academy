const autoprefixer = require('autoprefixer');
const pxtorem = require('postcss-pxtorem');
const pseudoEnter = require('postcss-pseudo-class-enter');
const reporter = require('postcss-reporter');
const msUnit = require('postcss-ms-unit');
const cssnano = require('cssnano');
const cssvariables = require('postcss-css-variables');

module.exports = context => {
  const env = context.webpack.options.mode;

  const plugins = [
    autoprefixer({
      grid: 'autoplace',
    }),
    msUnit(),
    pxtorem({
      prop_white_list: ['font', 'font-size', 'line-height', 'letter-spacing'],
    }),
    pseudoEnter(),
    cssvariables({
      preserve: true,
    }),
    reporter({ clearMessages: true }),
  ];

  if (env === 'production') {
    plugins.push(cssnano());
  }

  // Banner handled by webpack.config.js.
  // Make sure banner is last.
  // plugins.push(
  //   banner({
  //     banner: `Copyright (c) ${new Date().getFullYear()} Big Bite Creative | bigbitecreative.com | @bigbitecreative`,
  //   })
  // );

  return { plugins };
};
