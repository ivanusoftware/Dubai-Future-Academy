import Save from './containers/Save';
import Edit from './containers/Edit';
import deprecated from './deprecations';

// Required components
const { registerBlockType } = wp.blocks;

// Other components
const { __ } = wp.i18n;

registerBlockType('dff/content-slider', {
  title: __('Content Slider', 'dff'),
  description: __('Slider block for displaying content.', 'dff'),
  category: 'layout',
  keywords: [__('slider', 'dff')],
  attributes: {
    items: {
      type: 'array',
      default: [],
    },
    key: {
      type: 'string',
      default: '',
    },
  },
  edit: Edit,
  deprecated,
  save: Save,
});
