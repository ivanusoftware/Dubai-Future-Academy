import Save from './containers/Save';
import Edit from './containers/Edit';

// Required components
const { registerBlockType } = wp.blocks;

// Other components
const { __ } = wp.i18n;

registerBlockType('dff/tabbed-posts', {
  title: __('Tabbed Posts', 'dff'),
  description: __('Tabbed Posts', 'dff'),
  category: 'layout',
  keywords: [__('tabs', 'dff'), __('tab', 'dff')],
  attributes: {
    mode: {
      type: 'string',
      default: 'feed',
    },
    postTypes: {
      type: 'array',
      default: [],
    },
    postTypesContent: {
      type: 'object',
      default: {},
    },
    restrictToPageParent: {
      type: 'boolean',
      default: false,
    },
    pageId: {
      type: 'string',
      default: '',
    },
    orderBy: {
      type: 'string',
      default: 'menu_order',
    },
    order: {
      type: 'string',
      default: 'asc',
    },
    isOnlyCards: {
      type: 'boolean',
      default: false,
    },
    isPreview: {
      type: 'boolean',
      default: false,
    },
    posts: {
      type: 'array',
      default: [],
    },
    selectedModeTitle: {
      type: 'string',
      default: '',
    },
    itemAlignment: {
      type: 'string',
      default: '',
    },
    customURL: {
      type: 'string',
      default: '',
    },
    isMeta: {
      type: 'boolean',
      default: true,
    },
    isCallToAction: {
      type: 'boolean',
      default: false,
    },
  },
  edit: Edit,
  save: Save,
});
