import { PostSelector } from '../../../components';

const { __ } = wp.i18n;
const { PanelBody, ToggleControl } = wp.components;

const Simple = ({
  posts = [],
  attributes: { headingTag, isSelectionMode, selectedPosts, isPreview },
  setAttributes,
}) => {
  const Tag = headingTag;
  return (
    <>
      {!isSelectionMode && (
        <ul className="postFeed--simple">
          {posts.map(post => (
            <li key={post.id}>
              <article className="postFeed-item">
                <span className="postFeed-tag">{post.category.name}</span>
                {post?.featuredImage?.['post-feed'] && (
                  <figure className="postFeed-figure">
                    <img src={post.featuredImage['post-feed'].source_url} />
                  </figure>
                )}
                <header>
                  <Tag className="postFeed-title">{post.title.rendered}</Tag>
                </header>
              </article>
            </li>
          ))}
        </ul>
      )}
      {isSelectionMode && (
        <PostSelector
          selectedPosts={selectedPosts}
          onChange={value => {
            if (!selectedPosts.includes(value[value.length - 1])) {
              setAttributes({ selectedPosts: value });
            }
            if (value.length < selectedPosts.length) {
              setAttributes({ selectedPosts: value });
            }
            if (value.length === selectedPosts.length) {
              setAttributes({ selectedPosts: value });
            }
          }}
          previewing={isPreview}
          maxPosts={20}
          preview={selectedPostList => (
            <ul className="postFeed--simple">
              {selectedPostList.map(item => (
                <li key={item.id}>
                  <article className="postFeed-item">
                    <span className="postFeed-tag">{item.category.name}</span>
                    {item?.featuredImage?.['post-feed'] && (
                      <figure className="postFeed-figure">
                        <img src={item.featuredImage['post-feed'].source_url} />
                      </figure>
                    )}
                    <header>
                      <Tag className="postFeed-title">{item.title}</Tag>
                    </header>
                  </article>
                </li>
              ))}
            </ul>
          )}
        />
      )}
    </>
  );
};

Simple.Options = ({ attributes, createUpdateAttribute }) => (
  <PanelBody>
    <ToggleControl
      checked={attributes.sliderEnabled}
      onChange={createUpdateAttribute('sliderEnabled')}
      label={__('Enable Slider', 'dff')}
    />
  </PanelBody>
);

export default Simple;
