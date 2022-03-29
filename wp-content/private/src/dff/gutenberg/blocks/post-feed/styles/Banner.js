import striptags from 'striptags';
import he from 'he';
import { PostSelector } from '../../../components';

const { InnerBlocks } = wp.blockEditor;
const { ToggleControl, PanelBody } = wp.components;
const { __ } = wp.i18n;

const Banner = ({ attributes, posts, setAttributes }) => {
  const [featuredPost, ...feed] = posts;
  const backgroundImage = featuredPost?.featuredImage?.full || false;

  let items = feed;

  if (!attributes.hasFeaturedPost) {
    items = posts;

    if (feed.length > 4) {
      items.pop();
    }
  }

  const styleGenerator = () => {
    const styleObject = {};

    if (backgroundImage) {
      styleObject.backgroundImage = `url(${backgroundImage})`;
    }

    if (attributes.backgroundId) {
      styleObject.backgroundImage = `url(${attributes.backgroundUrl})`;
    }

    if (attributes.hasFeaturedPost && !attributes.isSelectionMode) {
      styleObject.backgroundImage = `url(${backgroundImage})`;
    }
    return styleObject;
  };

  const Tag = attributes.headingTag;

  return (
    <section className="postFeed" style={styleGenerator()}>
      <div className="container">
        <article className="postFeed-feature">
          {attributes.hasFeaturedPost && !attributes.isSelectionMode && (
            <>
              <header>
                {featuredPost.category && (
                  <span className="postFeed-tag">{featuredPost.category.name}</span>
                )}
                <h1>{featuredPost.title.rendered}</h1>
              </header>
              {featuredPost?.excerpt?.rendered && (
                <p>{he.decode(striptags(featuredPost.excerpt.rendered))}</p>
              )}
            </>
          )}
          {(!attributes.hasFeaturedPost || attributes.isSelectionMode) && <InnerBlocks />}
        </article>
        <ul className="postFeed-list">
          {!attributes.isSelectionMode && (
            <>
              {items.map(post => (
                <li>
                  <article className="postFeed-item">
                    <header>
                      {post.category && <span className="postFeed-tag">{post.category.name}</span>}
                      <Tag className="postFeed-title">{post.title.rendered}</Tag>
                    </header>
                  </article>
                </li>
              ))}
            </>
          )}

          {attributes.isSelectionMode && (
            <>
              <PostSelector
                selectedPosts={attributes.selectedPosts}
                onChange={value => {
                  if (!attributes.selectedPosts.includes(value[value.length - 1])) {
                    setAttributes({ selectedPosts: value });
                  }
                  if (value.length < attributes.selectedPosts.length) {
                    setAttributes({ selectedPosts: value });
                  }
                  if (value.length === attributes.selectedPosts.length) {
                    setAttributes({ selectedPosts: value });
                  }
                }}
                previewing={attributes.isPreview}
                maxPosts={5}
                preview={selectedPostList => (
                  <>
                    {selectedPostList.map(item => (
                      <li>
                        <article className="postFeed-item">
                          <header>
                            {item.category && (
                              <span className="postFeed-tag">{item.category.name}</span>
                            )}
                            <Tag className="postFeed-title">{item.title}</Tag>
                          </header>
                        </article>
                      </li>
                    ))}
                  </>
                )}
              />
            </>
          )}
        </ul>
      </div>
    </section>
  );
};

Banner.itemsPerPage = 5;

Banner.Options = ({ attributes, createUpdateAttribute }) => (
  <>
    {!attributes.isSelectionMode && (
      <PanelBody>
        <ToggleControl
          label={__('Has featured post', 'dff')}
          checked={attributes.hasFeaturedPost}
          onChange={createUpdateAttribute('hasFeaturedPost')}
        />
      </PanelBody>
    )}
  </>
);

export default Banner;
