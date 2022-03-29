import Save from './containers/Save';
import Edit from './containers/Edit';

// Required components
const { registerBlockType } = wp.blocks;

// Other components
const { __ } = wp.i18n;

registerBlockType('dff/post-type-archive', {
  title: __('Post type archive', 'dff'),
  description: __('Post type archive', 'dff'),
  category: 'layout',
  keywords: [__('archive', 'dff'), __('feed', 'dff')],
  attributes: {
    postType: {
      type: 'string',
      default: 'post',
    },
    title: {
      type: 'string',
      default: '',
    },
    noPostsText: {
      type: 'string',
      default: '',
    },
    filters: {
      type: 'object',
      default: {},
    },
    showFilters: {
      type: 'bool',
      default: false,
    },
    showSortBy: {
      type: 'bool',
      default: false,
    },
    showViewAllButton: {
      type: 'bool',
      default: false,
    },
    showPagination: {
      type: 'bool',
      default: false,
    },
    perPage: {
      type: 'int',
      default: 4,
    },
    viewAllHref: {
      type: 'string',
    },
    showCardTaxonomy: {
      type: 'bool',
      default: true,
    },
    showCardDate: {
      type: 'bool',
      default: true,
    },
    taxonomies: {
      type: 'object',
      default: {},
    },
  },
  edit: Edit,
  save: Save,
});
