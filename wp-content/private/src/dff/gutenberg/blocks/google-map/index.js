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

registerBlockType('dff/google-map', {
  title: __('Google Map', 'dff'),
  description: __('A google map integration', 'dff'),
  category: 'layout',
  keywords: [__('button', 'dff'), __('link', 'dff')],
  supports: {
    multiple: false,
    align: ['right', 'left', 'center'],
  },
  attributes: {
    zoom: {
      type: 'number',
      default: '10',
    },
    height: {
      type: 'number',
      default: '300',
    },
    addressOne: {
      type: 'string',
      default: '',
    },
    addresses: {
      type: 'array',
      default: [],
    },
    markers: {
      type: 'array',
      default: [],
    },
  },
  edit: Edit,
  save: Save,
});
