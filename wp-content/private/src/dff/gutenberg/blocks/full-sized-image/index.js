import Save from './containers/Save';
import Edit from './containers/Edit';

// Required components
const { registerBlockType } = wp.blocks;

// Other components
const { __ } = wp.i18n;

registerBlockType('dff/image', {
  title: __('Full sized image', 'dff'),
  description: __(
    'An image to fill the entire container, this is mainly used for stylistic purposes',
    'dff',
  ),
  category: 'layout',
  keywords: [__('image', 'dff'), __('visual', 'dff')],
  attributes: {
    image: {
      type: 'object',
    },
  },
  edit: Edit,
  save: Save,
});
