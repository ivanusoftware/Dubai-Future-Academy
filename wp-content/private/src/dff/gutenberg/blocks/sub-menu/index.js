import Edit from './containers/Edit';

const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('dff/sub-menu', {
  title: __('Page Sub Menu', 'dff'),
  description: __('Sub menu page block which will display a menu of sub pages', 'dff'),
  category: 'layout',
  keywords: [__('sub pages menu', 'dff')],
  attributes: {
    pageId: {
      type: 'string',
      default: '',
    },
    subPages: {
      type: 'array',
      default: [],
    },
    pageSelectOptions: {
      type: 'array',
      default: [],
    },
    orderBy: {
      type: 'string',
      default: 'menu_order',
    },
    order: {
      type: 'string',
      default: 'asc',
    },
    isLoading: {
      type: 'boolean',
      default: false,
    },
  },
  edit: Edit,
  save: () => null,
});
