import Save from './containers/Save';
import Edit from './containers/Edit';
import deprecated from './deprecations';

// Required components
const { registerBlockType } = wp.blocks;

// Other components
const { __ } = wp.i18n;

registerBlockType('dff/quote-banner', {
  title: __('Quote Banner', 'dff'),
  description: __('Large quote with background image', 'dff'),
  category: 'layout',
  keywords: [__('quote', 'dff'), __('banner', 'dff')],
  attributes: {
    text: {
      type: 'string',
    },
    cite: {
      type: 'text',
      default: '',
    },
    citeJob: {
      type: 'text',
      default: '',
    },
    isDark: {
      type: 'bool',
      default: false,
    },
    image: {
      type: 'object',
      default: {},
    },
    styleSelected: {
      type: 'string',
      default: 'quote',
    },
    isCite: {
      type: 'boolean',
      default: true,
    },
    isCiteJob: {
      type: 'boolean',
      default: true,
    },
    ctaButtonText: {
      type: 'string',
      default: '',
    },
    ctaButtonHref: {
      type: 'string',
      default: '',
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
  },
  edit: Edit,
  save: Save,
  deprecated,
});
