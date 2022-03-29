import classnames from 'classnames';
import striptags from 'striptags';
import { decode } from 'he';

import { PostSelector } from '../../../components';
import { getPostTypes, getPosts, getCategories } from '../../../components/post-selector/api';

const { PanelBody, ToggleControl, SelectControl } = wp.components;
const { __ } = wp.i18n;

const { InspectorControls } = wp.blockEditor;

const { useState, useEffect } = wp.element;

const typeOptions = [
  { value: 'dynamic', label: 'Feed Mode' },
  { value: 'selected', label: 'Selection Mode' },
  { value: 'category', label: 'Category Mode' },
];

const STATE = {
  LOADING: 'loading',
  IDLE: 'idle',
  ERROR: 'error',
};

const Post = ({ featuredImage, title, excerpt = '', category }) => (
  <article class="featured-post">
    <figure class="featured-postFigure">
      {/* eslint-disable-next-line camelcase */}
      {featuredImage?.['featured-post']?.source_url && (
        <>
          {/* eslint-disable-next-line camelcase */}
          <img src={featuredImage?.['featured-post']?.source_url} />
        </>
      )}
    </figure>
    <div class="featured-postContent">
      <header class="featured-postHeader">
        {category && <span class="featured-postTag">{category.name}</span>}
        <h1 class="featured-postTitle">{title}</h1>
      </header>
      {excerpt && <p>{striptags(decode(excerpt))}</p>}
    </div>
  </article>
);

const Edit = ({ attributes, setAttributes }) => {
  const createUpdateAttribute = key => value => setAttributes({ [key]: value });
  const [preview, setPreview] = useState(attributes.posts.length > 0);
  const [categories, setCategories] = useState([]);
  const [postTypes, setPostTypes] = useState(false);
  const [state, setState] = useState(STATE.LOADING);
  const [posts, setPosts] = useState([]);
  const { type = 'dynamic', postType = 'post' } = attributes;

  const fetchPosts = async () => {
    if (!postTypes) {
      return;
    }

    if (type === 'selected') {
      return;
    }

    setState(STATE.LOADING);

    const args = {
      restBase: postTypes[postType].rest_base,
      per_page: 3,
      // Exclude the current post fromm this api call
      exclude: wp.data.select('core/editor').getCurrentPostId(),
      order: 'desc',
    };

    if (type === 'category') {
      args.categories = attributes.category;
    }

    try {
      const p = await getPosts(args);
      setPosts(p);
      setState(STATE.IDLE);
    } catch (e) {
      console.error(e); // eslint-disable-line
    }
  };

  useEffect(async () => {
    // fetch types
    try {
      const types = await getPostTypes();
      delete types.attachment;
      delete types.wp_block;
      delete types.sidebar;
      setPostTypes(types);
    } catch (e) {
      console.error(e); // eslint-disable-line
    }
  }, []);

  useEffect(async () => {
    // fetch categories
    try {
      const categoryResponse = await getCategories();
      setCategories(categoryResponse);
    } catch (e) {
      console.error(e); // eslint-disable-line
    }
  }, []);

  useEffect(() => {
    fetchPosts();
  }, [type, postTypes, postType, attributes.perPage, attributes.category]);

  return (
    <>
      <InspectorControls>
        <PanelBody>
          <h3>Options</h3>
          <SelectControl
            label={__('Dynamic Type:', 'dff')}
            options={typeOptions}
            value={type}
            onChange={createUpdateAttribute('type')}
          />
          {type === 'category' && (
            <SelectControl
              label={__('Category', 'dff')}
              options={[
                { value: '', label: 'Select a category...' },
                ...categories.map(category => ({
                  value: category.id,
                  label: category.name,
                })),
              ]}
              value={attributes.category}
              onChange={createUpdateAttribute('category')}
            />
          )}
          {type !== 'selected' && (
            <>
              <SelectControl
                label={__('Post Type', 'dff')}
                options={Object.keys(postTypes).map(key => ({
                  value: key,
                  label: postTypes[key].name,
                }))}
                value={attributes.postType}
                onChange={createUpdateAttribute('postType')}
              />
            </>
          )}
          {type === 'selected' && (
            <ToggleControl
              label={__('Preview Posts', 'dff')}
              checked={preview}
              onChange={val => setPreview(val)}
            />
          )}
        </PanelBody>
      </InspectorControls>
      {attributes.type === 'category' && !attributes.category && <p>Select a category...</p>}
      {state === STATE.IDLE && !posts.length && <p>Can't find posts with the selection</p>}
      {(attributes.type === 'dynamic' || (attributes.type === 'category' && attributes.category)) &&
        posts.length > 0 &&
        state === STATE.IDLE && (
          <div
            className={classnames('featured-posts', {
              [`has-${posts.length}-posts`]: true,
            })}
          >
            {posts.map(post => (
              <Post
                key={post.id}
                title={post?.title?.rendered}
                excerpt={post?.excerpt?.rendered}
                featuredImage={post.featuredImage}
                category={post.category}
              />
            ))}
          </div>
        )}
      {type === 'selected' && (
        <PostSelector
          selectedPosts={attributes.posts}
          onChange={createUpdateAttribute('posts')}
          previewing={preview}
          maxPosts={3}
          preview={selectedPosts => (
            <div
              className={classnames('featured-posts', {
                [`has-${selectedPosts.length}-posts`]: true,
              })}
            >
              {selectedPosts.map(post => (
                <Post key={post.id} {...post} />
              ))}
            </div>
          )}
        />
      )}
    </>
  );
};

export default Edit;
