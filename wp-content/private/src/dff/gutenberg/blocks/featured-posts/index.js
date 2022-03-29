import Edit from './containers/Edit';

// Required components
const { registerBlockType } = wp.blocks;

// Other components
const { __ } = wp.i18n;

registerBlockType('dff/featured-posts', {
  title: __('Featured Posts', 'dff'),
  description: __('A grid to display a grid of posts', 'dff'),
  category: 'layout',
  keywords: [__('posts', 'dff'), __('feature', 'dff')],
  attributes: {
    type: {
      type: 'string',
      default: 'dynamic',
    },
    category: {
      type: 'string',
    },
    postType: {
      type: 'string',
    },
    posts: {
      type: 'array',
      default: [],
    },
  },
  edit: Edit,
  save: () => null,
});
