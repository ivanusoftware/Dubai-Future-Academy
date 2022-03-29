import classnames from 'classnames';

const { __ } = wp.i18n;
const { RichText, URLInputButton } = wp.blockEditor;

const Edit = ({ attributes, createUpdateAttribute }) => (
  <section
    className={classnames('hero', 'hero--quote-content', {
      [`is-positioned-${attributes.titleHorizontalPosition}`]: !!attributes.titleHorizontalPosition,
      [`is-positioned-${attributes.titleVerticalPosition}`]: !!attributes.titleVerticalPosition,
      'is-dark': attributes.darkBackground,
    })}
    style={{
      backgroundImage: attributes.backgroundImage
        ? `url(${attributes.backgroundImage.source_url})`
        : false,
    }}
  >
    <div className="container">
      <div className="hero-main">
        <div
          className={classnames({
            [`is-aligned-${attributes.titleTextAlignment}`]: !!attributes.titleTextAlignment,
          })}
        >
          <blockquote
            className={classnames({
              [`is-aligned-${attributes.titleTextAlignment}`]: !!attributes.titleTextAlignment,
            })}
          >
            <RichText
              tagName="p"
              placeholder={__('Hero Quote', 'dff')}
              keepPlaceholderOnFocus
              value={attributes.quoteText}
              onChange={createUpdateAttribute('quoteText')}
            />
            <cite>
              <RichText
                tagName="span"
                placeholder={__('Hero Cite', 'dff')}
                className="hero-cite"
                keepPlaceholderOnFocus
                value={attributes.quoteCite}
                onChange={createUpdateAttribute('quoteCite')}
              />
              <RichText
                tagName="span"
                placeholder={__('Hero Cite (Job Title)', 'dff')}
                className="hero-citeTitle"
                keepPlaceholderOnFocus
                value={attributes.quoteCiteTitle}
                onChange={createUpdateAttribute('quoteCiteTitle')}
              />
            </cite>
          </blockquote>
          <RichText
            tagName="span"
            className="btn button--ghost"
            value={attributes.ctaText}
            onChange={createUpdateAttribute('ctaText')}
            placeholder={__('Call to action text', 'dff')}
            keepPlaceholderOnFocus
          />
          <URLInputButton onChange={createUpdateAttribute('ctaHref')} url={attributes.ctaHref} />
        </div>
      </div>
    </div>
  </section>
);

export default Edit;
