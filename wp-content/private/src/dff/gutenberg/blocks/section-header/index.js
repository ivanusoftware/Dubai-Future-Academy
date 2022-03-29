import Save from './containers/Save';
import Edit from './containers/Edit';

// Required components
const { registerBlockType } = wp.blocks;

// Other components
const { __ } = wp.i18n;

registerBlockType('dff/section-header', {
  title: __('Section Header', 'dff'),
  description: __('Header block for a section that contains the heading and sub-content.', 'dff'),
  category: 'layout',
  keywords: [__('section', 'dff'), __('header', 'dff')],
  attributes: {
    title: {
      type: 'string',
    },
    content: {
      type: 'string',
    },
    isLarge: {
      type: 'bool',
    },
    isAlternate: {
      type: 'bool',
    },
  },
  edit: Edit,
  save: Save,
});
