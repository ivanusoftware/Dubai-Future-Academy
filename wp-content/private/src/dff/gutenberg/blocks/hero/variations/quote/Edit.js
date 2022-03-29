import classnames from 'classnames';

const { __ } = wp.i18n;
const { RichText } = wp.blockEditor;

const Edit = ({ attributes, createUpdateAttribute }) => (
  <section
    className={classnames('hero', 'hero--quote', {
      [`is-positioned-${attributes.titleHorizontalPosition}`]: !!attributes.titleHorizontalPosition,
      [`is-positioned-${attributes.titleVerticalPosition}`]: !!attributes.titleVerticalPosition,
      [`is-clickOption--${attributes.clickOption}`]: attributes.clickOption,
      'is-dark': attributes.darkBackground,
      'is-heroSideLeft': attributes.isHeroSideLeft,
    })}
    style={{
      backgroundImage: attributes.backgroundImage
        ? `url(${attributes.backgroundImage.source_url})`
        : false,
    }}
  >
    <div className="container">
      <div className="hero-main">
        <blockquote
          className={classnames({
            [`is-aligned-${attributes.titleTextAlignment}`]: !!attributes.titleTextAlignment,
          })}
        >
          <RichText
            tagName="p"
            placeholder="Hero Quote"
            keepPlaceholderOnFocus
            value={attributes.quoteText}
            onChange={createUpdateAttribute('quoteText')}
          />
          <cite>
            <RichText
              tagName="span"
              placeholder="Hero Cite"
              className="hero-cite"
              keepPlaceholderOnFocus
              value={attributes.quoteCite}
              onChange={createUpdateAttribute('quoteCite')}
            />
            <RichText
              tagName="span"
              placeholder="Hero Cite (Job Title)"
              className="hero-citeTitle"
              keepPlaceholderOnFocus
              value={attributes.quoteCiteTitle}
              onChange={createUpdateAttribute('quoteCiteTitle')}
            />
          </cite>
        </blockquote>
      </div>
      <div className="hero-side">
        <div className="hero-scrollTo">
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
          <RichText
            tagName="span"
            className="hero-scrollToLabel"
            keepPlaceholderOnFocus
            value={attributes.scrollText}
            placeholder={
              attributes.clickOption === 'scroll'
                ? __('Scroll down text', 'dff')
                : __('Link text', 'dff')
            }
            onChange={createUpdateAttribute('scrollText')}
          />
        </div>
      </div>
    </div>
  </section>
);

export default Edit;
