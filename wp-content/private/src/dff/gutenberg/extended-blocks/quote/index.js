const { registerBlockStyle } = wp.blocks;
const { __ } = wp.i18n;

registerBlockStyle('core/quote', [
  {
    name: 'stylisedQuote',
    label: __('Stylised', 'dff'),
  },
  {
    name: 'simpleStyle',
    label: __('Simple', 'dff'),
  },
]);
