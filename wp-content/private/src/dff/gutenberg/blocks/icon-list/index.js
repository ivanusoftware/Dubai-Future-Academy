import Save from './containers/Save';
import Edit from './containers/Edit';

// Required components
const { registerBlockType } = wp.blocks;

// Other components
const { __ } = wp.i18n;

registerBlockType('dff/icon-list', {
  title: __('Icon list', 'dff'),
  description: __('Icon list', 'dff'),
  category: 'layout',
  keywords: [__('list', 'dff'), __('icon', 'dff')],
  attributes: {
    items: {
      type: 'array',
      default: [],
    },
    isInverted: {
      type: 'boolean',
      default: false,
    },
    isPreview: {
      type: 'boolean',
      default: false,
    },
  },
  edit: Edit,
  save: Save,
});
