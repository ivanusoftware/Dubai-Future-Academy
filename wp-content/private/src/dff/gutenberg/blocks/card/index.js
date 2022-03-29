import Save from './containers/Save';
import Edit from './containers/Edit';
import deprecated from './deprecations';

// Required components
const { registerBlockType } = wp.blocks;

// Other components
const { __ } = wp.i18n;

registerBlockType('dff/card', {
  title: __('Card', 'dff'),
  description: __('Card block for displaying an image with content below.', 'dff'),
  category: 'layout',
  keywords: [__('card', 'dff'), __('thumbnail', 'dff')],
  attributes: {
    category: {
      type: 'string',
      default: '',
    },
    title: {
      type: 'string',
      default: '',
    },
    content: {
      type: 'string',
      default: '',
    },
    btnText: {
      type: 'string',
      default: '',
    },
    btnHref: {
      type: 'string',
      default: '',
    },
    media: {
      type: 'object',
      default: {},
    },
    icon: {
      type: 'string',
      default: '',
    },
    isIcon: {
      type: 'boolean',
      default: false,
    },
    isCategory: {
      type: 'boolean',
      default: false,
    },
    isButton: {
      type: 'boolean',
      default: false,
    },
    isPadding: {
      type: 'boolean',
      default: true,
    },
    cardAlignment: {
      type: 'string',
      default: '',
    },
    cardAlignmentSecondary: {
      type: 'boolean',
      default: false,
    },
    titleLevel: {
      type: 'string',
      default: 'h1',
    },
    hasBorder: {
      type: 'boolean',
      default: false,
    },
  },
  edit: Edit,
  save: Save,
  deprecated,
});
