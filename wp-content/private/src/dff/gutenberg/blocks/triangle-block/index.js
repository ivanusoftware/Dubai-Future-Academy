import Edit from './containers/Edit';

const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;
const { InnerBlocks } = wp.blockEditor;

registerBlockType('dff/triangle-block', {
  title: __('Triangle Block', 'dff'),
  description: __('Block for displaying stylised content with custom triangle images', 'dff'),
  keywords: ['triangle', 'layout', 'custom'],
  category: 'layout',
  attributes: {
    textColumnOne: {
      type: 'string',
      default: '',
    },
    textColumnTwo: {
      type: 'string',
      default: '',
    },
    textColumnThree: {
      type: 'string',
      default: '',
    },
    triangleImageLarge: {
      type: 'object',
      default: {},
    },
    triangleImageSmall: {
      type: 'object',
      default: {},
    },
    key: {
      type: 'string',
      default: '',
    },
  },
  edit: Edit,
  save: () => <InnerBlocks.Content />,
});
