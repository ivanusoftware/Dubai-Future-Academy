import Save from './containers/Save';
import Edit from './containers/Edit';

// Required components
const { registerBlockType } = wp.blocks;

// Other components
const { __ } = wp.i18n;

registerBlockType('dff/column', {
  title: __('Column', 'dff'),
  description: __('Column', 'dff'),
  category: 'layout',
  supports: {
    inserter: false,
  },
  keywords: [__('columns', 'dff'), __('column', 'dff')],
  attributes: {
    className: {
      type: 'string',
    },
    columnAlignment: {
      type: 'object',
      default: '',
    },
    paddingLeft: {
      type: 'bool',
    },
    paddingRight: {
      type: 'bool',
    },
  },
  edit: Edit,
  save: Save,
});
