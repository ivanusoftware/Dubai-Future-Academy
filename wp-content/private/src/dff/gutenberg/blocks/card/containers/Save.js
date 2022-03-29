/* eslint-disable camelcase */
import { getIcon } from '../../../components/icons/getIcons';

const { RichText } = wp.blockEditor;

const Save = ({ attributes }) => {
  const icon = getIcon(attributes.icon);
  const labelTitle = btoa(encodeURI(attributes.title));

  return (
    <article
      className={`articleCard ${attributes.cardAlignment}${
        attributes.cardAlignmentSecondary && attributes.cardAlignment !== ''
          ? ' articleCard--alignTop'
          : ''
      }${attributes.hasBorder ? ' hasBorder' : ''}`}
      aria-labelledby={`article-${labelTitle}`}
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
            id={`article-${labelTitle}`}
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

export default Save;
/* eslint-enable camelcase */
