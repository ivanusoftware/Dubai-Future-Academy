import Save from './containers/Save';
import Edit from './containers/Edit';

// Required components
const { registerBlockType, registerBlockStyle } = wp.blocks;

// Other components
const { __ } = wp.i18n;

registerBlockStyle('dff/button', {
  name: 'cta',
  label: 'Call To Action',
});

registerBlockType('dff/button', {
  title: __('Button', 'dff'),
  description: __('Button', 'dff'),
  category: 'layout',
  keywords: [__('button', 'dff'), __('link', 'dff')],
  attributes: {
    className: {
      type: 'string',
    },
    text: {
      type: 'text',
      default: '',
    },
    href: {
      type: 'text',
      default: '',
    },
    customBackgroundColour: {
      type: 'string',
      default: '',
    },
    customTextColour: {
      type: 'string',
      default: '',
    },
    isOpenInNewTab: {
      type: 'boolean',
      default: false,
    },
  },
  edit: Edit,
  save: Save,
});
