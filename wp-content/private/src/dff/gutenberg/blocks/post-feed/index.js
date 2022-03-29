import './styles';
import Edit from './containers/Edit';

// Required components
const { registerBlockType } = wp.blocks;
const { InnerBlocks } = wp.blockEditor;

// Other components
const { __ } = wp.i18n;

registerBlockType('dff/post-feed', {
  title: __('Post Feed', 'dff'),
  description: __('Post Feed', 'dff'),
  category: 'layout',
  keywords: [__('post', 'dff'), __('feed', 'dff')],
  attributes: {
    ariaLabel: {
      type: 'string',
    },
    postTypes: {
      type: 'array',
      default: [],
    },
    taxonomies: {
      type: 'object',
      default: {},
    },
    style: {
      type: 'string',
      default: 'banner',
    },
    hasFeaturedPost: {
      type: 'bool',
      default: false,
    },
    sliderEnabled: {
      type: 'bool',
      default: false,
    },
    backgroundUrl: {
      type: 'string',
      default: '',
    },
    backgroundId: {
      type: 'number',
      default: null,
    },
    headingTag: {
      type: 'string',
      default: 'h3',
    },
    isSelectionMode: {
      type: 'boolean',
      default: false,
    },
    selectedPosts: {
      type: 'array',
      default: [],
    },
    isPreview: {
      type: 'boolean',
      default: false,
    },
  },
  edit: Edit,
  save: () => <InnerBlocks.Content />,
});
