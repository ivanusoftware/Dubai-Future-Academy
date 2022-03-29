import { set } from 'lodash-es';

const { useEffect, useState } = wp.element;
const { addQueryArgs } = wp.url;

const Display = ({ attributes = {}, Style, setAttributes }) => {
  if (attributes?.postTypes?.length && attributes?.postTypes?.length < 1) {
    return <p>Select a post type.</p>;
  }

  const [posts, setPosts] = useState([]);
  const [state, setState] = useState('LOADING');

  const fetchFeed = () => {
    const terms = Object.keys(attributes.taxonomies).reduce((carry, current) => {
      if (!carry[current] && attributes.taxonomies[current].length > 0) {
        set(carry, current, attributes.taxonomies[current].join(','));
      }

      return carry;
    }, {});

    setState('LOADING');

    wp.apiFetch({
      path: addQueryArgs('/dff/v1/post-feed/fetch', {
        ...terms,
        types: attributes.postTypes.join(','),
      }),
    }).then(response => {
      setState('DONE');
      setPosts(response.posts);
    });
  };

  useEffect(() => {
    if (!attributes.isSelectionMode) {
      fetchFeed();
      return;
    }
    setPosts([]);
  }, [attributes.taxonomies, attributes.postTypes]);

  useEffect(() => {
    fetchFeed();
  }, []);

  if (state === 'LOADING') {
    return <p>Loading</p>;
  }

  if (posts.length > 0 || attributes.isSelectionMode) {
    if (Style) {
      return <Style posts={posts} attributes={attributes} setAttributes={setAttributes} />;
    }

    return <p>Invalid Style</p>;
  }

  return <p>No posts found, try changing your selected options</p>;
};

export default Display;
