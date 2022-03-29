import classnames from 'classnames';

const { RichText } = wp.blockEditor;

const Save = ({ attributes }) => {
  const backgroundColorMobileStyle =
    attributes.backgroundColorMobile && attributes.backgroundImageMobile?.id
      ? { backgroundColor: attributes.backgroundColorMobile }
      : {};

  return (
    <section
      className={classnames('hero', 'hero--quote-content', {
        [`is-positioned-${attributes.titleHorizontalPosition}`]: !!attributes.titleHorizontalPosition,
        [`is-positioned-${attributes.titleVerticalPosition}`]: !!attributes.titleVerticalPosition,
        [`u-bg-pos-y--${attributes.backgroundVerticalPosition}`]: attributes.backgroundVerticalPosition,
        'is-dark': attributes.darkBackground,
        'hero--has-mobile-img': attributes.backgroundImageMobile?.id,
      })}
      style={{
        ...backgroundColorMobileStyle,
        backgroundImage: attributes.backgroundImage
          ? `url(${attributes.backgroundImage.source_url})`
          : false,
      }}
    >
      {attributes.hasBreadcrumbs && `[breadcrumbs]`}
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
              <RichText.Content tagName="p" value={attributes.quoteText} />
              <cite>
                <RichText.Content
                  tagName="span"
                  className="hero-cite"
                  value={attributes.quoteCite}
                />
                <RichText.Content
                  tagName="span"
                  className="hero-citeTitle"
                  value={attributes.quoteCiteTitle}
                />
              </cite>
            </blockquote>
            {attributes.ctaText && (
              <RichText.Content
                tagName="a"
                href={attributes.ctaHref}
                value={attributes.ctaText}
                className="button button--ghost"
              />
            )}
          </div>
        </div>
      </div>
      {attributes.backgroundImageMobile?.id && (
        <div className="hero-mobileImgContainer">
          <img
            className="hero-mobileImg"
            src={attributes.backgroundImageMobile.source_url}
            alt={attributes.backgroundImageMobile.alt}
          />
        </div>
      )}
    </section>
  );
};

export default Save;
