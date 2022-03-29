import classnames from 'classnames';
import HomeHeroShape from './components/home-hero-shape';

const { __ } = wp.i18n;
const { RichText } = wp.blockEditor;

const Edit = ({ attributes, createUpdateAttribute }) => (
  <section
    className={classnames('hero hero--home', {
      [`is-positioned-${attributes.titleHorizontalPosition}`]: !!attributes.titleHorizontalPosition,
      [`is-positioned-${attributes.titleVerticalPosition}`]: !!attributes.titleVerticalPosition,
      [`is-clickOption--${attributes.clickOption}`]: attributes.clickOption,
      'is-dark': attributes.darkBackground,
      'is-animated': attributes.isAnimated,
    })}
    style={{
      backgroundImage:
        attributes.backgroundImage && !attributes.isAnimated
          ? `url(${attributes.backgroundImage.source_url})`
          : false,
    }}
  >
    <div className="container">
      <div className="hero-main">
        <h1
          className={classnames('hero-title', {
            [`is-aligned-${attributes.titleTextAlignment}`]: !!attributes.titleTextAlignment,
          })}
        >
          <RichText
            tagName="span"
            placeholder="Hero Prefix"
            className="hero-titlePrefix"
            keepPlaceholderOnFocus
            value={attributes.titlePrefix}
            onChange={createUpdateAttribute('titlePrefix')}
          />
          <RichText
            tagName="span"
            placeholder="Hero Title"
            keepPlaceholderOnFocus
            value={attributes.title}
            onChange={createUpdateAttribute('title')}
          />
        </h1>
      </div>
      <div className="hero-side">
        <RichText
          placeholder="Hero Content"
          tagName="p"
          keepPlaceholderOnFocus
          value={attributes.content}
          onChange={createUpdateAttribute('content')}
        />
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
      {attributes.isAnimated && (
        <div class="hero-animated">
          <HomeHeroShape />
        </div>
      )}
    </div>
  </section>
);

export default Edit;
