import Save from './containers/Save';
import Edit from './containers/Edit';

// Required components
const { registerBlockType } = wp.blocks;

// Other components
const { __ } = wp.i18n;

registerBlockType('dff/timeline', {
  title: __('Timeline', 'dff'),
  description: __('Timeline block for displaying content.', 'dff'),
  category: 'layout',
  keywords: [__('timeline', 'dff'), __('about', 'dff')],
  attributes: {
    items: {
      type: 'array',
      default: [],
    },
  },
  edit: Edit,
  save: Save,
});
