/* eslint-disable camelcase */
import { getIcon } from '../../../components/icons/getIcons';

const { RichText } = wp.blockEditor;

const save = ({ attributes }) => {
  const icon = getIcon(attributes.icon);
  return (
    <article
      className={`articleCard ${attributes.cardAlignment}${
        attributes.cardAlignmentSecondary && attributes.cardAlignment !== ''
          ? ' articleCard--alignTop'
          : ''
      }`}
      aria-labelledby={`article-${btoa(attributes.title)}`}
    >
      <figure className="articleCard-figure">
        <img
          src={attributes?.media?.media_details?.sizes?.card?.source_url}
          srcset={`${attributes?.media?.media_details?.sizes?.['card@2x']?.source_url} 2x`}
          alt={attributes.media.alt}
        />
      </figure>
      <div
        className={`articleCard-contentContainer${
          !attributes.isPadding ? ' articleCard--noPadding' : ''
        }`}
      >
        <header className="articleCard-header">
          {(attributes.isIcon || attributes.isCategory) && (
            <span className="articleCard-category">
              {attributes.isIcon && icon?.render}
              {attributes.isCategory && (
                <RichText.Content tagName="span" value={attributes.category} />
              )}
            </span>
          )}
          <RichText.Content
            id={`article-${btoa(attributes.title)}`}
            className={`articleCard-title ${attributes.titleLevel}`}
            tagName={attributes.titleLevel}
            value={attributes.title}
          />
        </header>
        <RichText.Content className="articleCard-content" tagName="p" value={attributes.content} />
        {attributes.isButton && (
          <RichText.Content
            tagName="a"
            className="btn button--ghost"
            href={attributes.btnHref}
            value={attributes.btnText}
          />
        )}
      </div>
    </article>
  );
};

export default {
  attributes: {
    category: {
      type: 'string',
      default: '',
    },
    title: {
      type: 'string',
      default: '',
    },
    content: {
      type: 'string',
      default: '',
    },
    btnText: {
      type: 'string',
      default: '',
    },
    btnHref: {
      type: 'string',
      default: '',
    },
    media: {
      type: 'object',
      default: {},
    },
    icon: {
      type: 'string',
      default: '',
    },
    isIcon: {
      type: 'boolean',
      default: false,
    },
    isCategory: {
      type: 'boolean',
      default: false,
    },
    isButton: {
      type: 'boolean',
      default: false,
    },
    isPadding: {
      type: 'boolean',
      default: true,
    },
    cardAlignment: {
      type: 'string',
      default: '',
    },
    cardAlignmentSecondary: {
      type: 'boolean',
      default: false,
    },
    titleLevel: {
      type: 'string',
      default: 'h1',
    },
  },
  save,
};
/* eslint-enable camelcase */
