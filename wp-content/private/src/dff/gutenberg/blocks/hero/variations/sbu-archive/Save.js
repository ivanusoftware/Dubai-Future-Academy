import classnames from 'classnames';

const { RichText } = wp.blockEditor;

const Save = ({ attributes, hrefGenerator }) => {
  const backgroundColorMobileStyle =
    attributes.backgroundColorMobile && attributes.backgroundImageMobile?.id
      ? { backgroundColor: attributes.backgroundColorMobile }
      : {};

  return (
    <section
      className={classnames('hero', 'hero--sbu-archive', {
        [`is-positioned-${attributes.titleHorizontalPosition}`]: !!attributes.titleHorizontalPosition,
        [`is-positioned-${attributes.titleVerticalPosition}`]: !!attributes.titleVerticalPosition,
        [`is-clickOption--${attributes.clickOption}`]: attributes.clickOption,
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
          <h1
            className={classnames('hero-title', 'hero-title--small', {
              [`is-aligned-${attributes.titleTextAlignment}`]: !!attributes.titleTextAlignment,
            })}
          >
            <RichText.Content tagName="span" value={attributes.title} />
          </h1>
        </div>
        <div className="hero-side">
          <RichText.Content tagName="p" value={attributes.content} />
          <a
            href={hrefGenerator(attributes)}
            target={attributes.isLinkNewTab && attributes.clickOption === 'link' ? '_blank' : ''}
            className="hero-scrollTo"
            rel="noopener noreferrer"
          >
            <div className="hero-scrollToIcon">
              <svg xmlns="http://www.w3.org/2000/svg" width="10" height="26" viewBox="0 0 10 26">
                <path
                  id="Union_1"
                  data-name="Union 1"
                  d="M-6020,20h4V0h2V20h4l-5,6Z"
                  transform="translate(6020)"
                  fill="currentColor"
                />
              </svg>
            </div>
            {attributes.scrollText ? (
              <RichText.Content
                tagName="span"
                className="hero-scrollToLabel"
                value={attributes.scrollText}
              />
            ) : (
              <span className="u-hiddenVisually">Scroll to content</span>
            )}
          </a>
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
