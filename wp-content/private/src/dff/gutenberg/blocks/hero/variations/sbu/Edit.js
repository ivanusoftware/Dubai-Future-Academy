/* eslint-disable camelcase */
import classnames from 'classnames';
import { PostMediaSelector } from '../../../../components';

const { __ } = wp.i18n;
const { RichText, InspectorControls } = wp.blockEditor;
const { PanelBody } = wp.components;

const Edit = ({ attributes, createUpdateAttribute }) => (
  <>
    <InspectorControls>
      <PanelBody>
        <PostMediaSelector
          onUpdate={createUpdateAttribute('logo')}
          mediaId={attributes?.logo?.id}
          labels={{
            set: __('Set Logo', 'dff'),
          }}
        />
      </PanelBody>
    </InspectorControls>
    <section
      className={classnames('hero', 'hero--sbu', {
        [`is-positioned-${attributes.titleHorizontalPosition}`]: !!attributes.titleHorizontalPosition,
        [`is-positioned-${attributes.titleVerticalPosition}`]: !!attributes.titleVerticalPosition,
        [`is-clickOption--${attributes.clickOption}`]: attributes.clickOption,
        'is-dark': attributes.darkBackground,
      })}
      style={{
        backgroundImage: attributes.backgroundImage
          ? `url(${attributes.backgroundImage.source_url})`
          : false,
      }}
    >
      {attributes?.backgroundVideo?.id && (
        <video
          class="hero-backgroundVideo"
          autoplay="true"
          loop="true"
          muted="true"
          poster={attributes?.backgroundImage?.source_url}
        >
          <source type="video/mp4" src={attributes?.backgroundVideo?.source_url} />
        </video>
      )}

      <div className="container">
        <div
          className={classnames('hero-main', {
            [`is-aligned-${attributes.titleTextAlignment}`]: !!attributes.titleTextAlignment,
          })}
        >
          <div>
            {attributes?.logo?.id && <img className="hero-logo" src={attributes.logo.source_url} />}
            <h1
              className={classnames('hero-title hero-title--small', {
                [`is-aligned-${attributes.titleTextAlignment}`]: !!attributes.titleTextAlignment,
              })}
            >
              {!attributes?.logo?.id && (
                <RichText
                  tagName="span"
                  placeholder="Hero Prefix"
                  className="hero-titlePrefix"
                  keepPlaceholderOnFocus
                  value={attributes.titlePrefix}
                  onChange={createUpdateAttribute('titlePrefix')}
                />
              )}
              <RichText
                tagName="span"
                placeholder="Hero Title"
                keepPlaceholderOnFocus
                value={attributes.title}
                onChange={createUpdateAttribute('title')}
              />
            </h1>
            <RichText
              placeholder="Hero Content"
              tagName="p"
              keepPlaceholderOnFocus
              value={attributes.content}
              onChange={createUpdateAttribute('content')}
            />
          </div>
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
  </>
);

export default Edit;
/* eslint-enable camelcase */
