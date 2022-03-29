import Save from './containers/Save';
import Edit from './containers/Edit';
import deprecated from './deprecations';

// Required components
const { registerBlockType } = wp.blocks;

// Other components
const { __ } = wp.i18n;

registerBlockType('dff/section', {
  title: __('Section', 'dff'),
  description: __('Section block for containing the content.', 'dff'),
  category: 'layout',
  keywords: [__('section', 'dff'), __('container', 'dff')],
  attributes: {
    color: {
      type: 'string',
      default: 'default',
    },
    size: {
      type: 'string',
      default: 'default',
    },
    border: {
      type: 'bool',
      default: false,
    },
    triangle: {
      type: 'string',
      default: 'none',
    },
    trianglePosition: {
      type: 'string',
      default: 'start',
    },
    isAnchor: {
      type: 'bool',
      default: false,
    },
    noPaddingOnMobile: {
      type: 'bool',
      default: false,
    },
    anchorName: {
      type: 'string',
      default: '',
    },
    background: {
      type: 'object',
      default: false,
    },
    imgOne: {
      type: 'object',
      default: false,
    },
    imgTwo: {
      type: 'object',
      default: false,
    },
    imgThree: {
      type: 'object',
      default: false,
    },
    key: {
      type: 'string',
    },
    containContentInside: {
      type: 'bool',
    },
    width: {
      type: 'string',
      default: '',
    },
    alignment: {
      type: 'string',
      ddefault: '',
    },
    backgroundColour: {
      type: 'string',
      default: '',
    },
    isNegative: {
      type: 'boolean',
      default: false,
    },
    negativeSpace: {
      type: 'number',
      default: 200,
    },
    margin: {
      type: 'object',
      default: {
        top: '',
        bottom: '',
        right: '',
        left: '',
      },
    },
    isBackgroundColourDarkMode: {
      type: 'boolean',
      default: false,
    },
    backgroundColourDark: {
      type: 'string',
      default: '',
    },
  },
  edit: Edit,
  save: Save,
  deprecated,
});
