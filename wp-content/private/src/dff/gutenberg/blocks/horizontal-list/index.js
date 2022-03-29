import Save from './containers/Save';
import Edit from './containers/Edit';
import deprecated from './deprecations';

// Required components
const { registerBlockType } = wp.blocks;

// Other components
const { __ } = wp.i18n;

registerBlockType('dff/horizontal-list', {
  title: __('Horizontal list', 'dff'),
  description: __('Horizontal list.', 'dff'),
  category: 'layout',
  keywords: [__('list', 'dff'), __('horizontal', 'dff')],
  attributes: {
    items: {
      type: 'array',
      default: [],
    },
    alignment: {
      type: 'string',
      default: '',
    },
    isFixedArrow: {
      type: 'boolean',
      default: false,
    },
    isColumnControl: {
      type: 'boolean',
      default: false,
    },
    columns: {
      type: 'number',
      default: 5,
    },
  },
  edit: Edit,
  save: Save,
  deprecated,
});
