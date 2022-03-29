import Save from './containers/Save';
import Edit from './containers/Edit';

// Required components
const { registerBlockType } = wp.blocks;

// Other components
const { __ } = wp.i18n;

registerBlockType('dff/title', {
  title: __('Section title', 'dff'),
  description: __('Header block for a section that contains the heading and sub-content.', 'dff'),
  category: 'layout',
  keywords: [__('section', 'dff'), __('header', 'dff')],
  attributes: {
    title: {
      type: 'string',
    },
    prefix: {
      type: 'string',
    },
    titleTextAlignment: {
      type: 'string',
      default: 'left',
    },
    hasDecal: {
      type: 'bool',
    },
  },
  edit: Edit,
  save: Save,
});
