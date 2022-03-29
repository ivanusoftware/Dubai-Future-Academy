import Save from './containers/Save';
import Edit from './containers/Edit';

// Required components
const { registerBlockType } = wp.blocks;

// Other components
const { __ } = wp.i18n;

registerBlockType('dff/columns', {
  title: __('Columns', 'dff'),
  description: __('Column builder', 'dff'),
  category: 'layout',
  keywords: [__('columns', 'dff'), __('column', 'dff')],
  attributes: {
    selectedColumns: {
      type: 'string',
    },
    selectedLayout: {
      type: 'string',
    },
    isSpaced: {
      type: 'bool',
      default: false,
    },
    columnAlignment: {
      type: 'object',
      default: '',
    },
    isReversedMobile: {
      type: 'boolean',
      default: false,
    },
  },
  edit: Edit,
  save: Save,
});
