import Edit from './containers/Edit';

const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('dff/image-slider', {
  title: __('Image Slider', 'dff'),
  description: __('Allows for the displaying of multiple images inline', 'dff'),
  keywords: [__('image', 'dff'), __('slider', 'dff'), __('scrolling', 'dff')],
  category: 'layout',
  attributes: {
    itemsVisible: {
      type: 'number',
      default: 2,
    },
    images: {
      type: 'array',
    },
  },
  edit: Edit,
  save: () => null,
});
