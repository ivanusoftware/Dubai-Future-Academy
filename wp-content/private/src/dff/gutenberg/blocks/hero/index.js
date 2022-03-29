import Save from './containers/Save';
import Edit from './containers/Edit';

// Required components
const { registerBlockType } = wp.blocks;

// Other components
const { __ } = wp.i18n;

registerBlockType('dff/hero', {
  title: __('Hero', 'dff'),
  description: __('Hero', 'dff'),
  category: 'layout',
  keywords: [__('hero', 'dff'), __('display', 'dff')],
  attributes: {
    title: {
      type: 'string',
    },
    titlePrefix: {
      type: 'string',
    },
    content: {
      type: 'string',
    },
    backgroundImage: {
      type: 'object',
    },
    backgroundVerticalPosition: {
      type: 'string',
      default: '',
    },
    backgroundImageMobile: {
      type: 'object',
    },
    backgroundColorMobile: {
      type: 'string',
      default: '',
    },
    backgroundVideo: {
      type: 'object',
    },
    logo: {
      type: 'object',
    },
    hasTitle: {
      type: 'bool',
    },
    hasTitlePrefix: {
      type: 'bool',
    },
    hasContent: {
      type: 'bool',
    },
    hasCallToAction: {
      type: 'bool',
    },
    darkBackground: {
      type: 'bool',
    },
    titleTextAlignment: {
      type: 'string',
      default: 'right',
    },
    titleVerticalPosition: {
      type: 'string',
      default: 'middle',
    },
    titleHorizontalPosition: {
      type: 'string',
      default: 'center',
    },
    variation: {
      type: 'string',
      default: 'home',
    },
    scrollText: {
      type: 'string',
    },
    anchorTarget: {
      type: 'string',
    },
    anchorUrl: {
      type: 'string',
    },
    quoteText: {
      type: 'string',
    },
    quoteCite: {
      type: 'string',
    },
    quoteCiteTitle: {
      type: 'string',
    },
    ctaText: {
      type: 'string',
    },
    ctaHref: {
      type: 'string',
    },
    hasBreadcrumbs: {
      type: 'bool',
      default: true,
    },
    clickOption: {
      type: 'string',
      default: 'scroll',
    },
    isLinkNewTab: {
      type: 'boolean',
      default: false,
    },
    isHeroSideLeft: {
      type: 'boolean',
      default: false,
    },
    isAnimated: {
      type: 'boolean',
      default: false,
    },
  },
  edit: Edit,
  save: Save,
});
