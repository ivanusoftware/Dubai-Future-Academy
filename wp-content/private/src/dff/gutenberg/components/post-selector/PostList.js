import { Post } from './Post';

const { __ } = wp.i18n;

/**
 * PostList Component
 * @param object props - Component props.
 * @returns {*}
 * @constructor
 */
export const PostList = props => {
  const {
    filtered = false,
    loading = false,
    posts = [],
    action = () => {},
    icon = null,
    movePost = () => {},
  } = props;

  if (loading) {
    return <p>{__('Loading Posts...', 'dff')}</p>;
  }

  if (filtered && posts.length < 1) {
    return (
      <div className="post-list">
        <p>{__('Your query yielded no results, please try again.', 'dff')}</p>
      </div>
    );
  }

  if (!posts || posts.length < 1) {
    return <p>{__('No Posts.', 'dff')}</p>;
  }

  return (
    <div className="post-list">
      {posts.map((post, index) => (
        <Post
          key={post.id}
          {...post}
          clickHandler={action}
          icon={icon}
          movePost={movePost(index) || false}
        />
      ))}
      {/* eslint-disable-next-line react/jsx-handler-names */}
      {props.canPaginate ? (
        <button onClick={props.doPagination} disabled={props.paging}>
          {props.paging ? __('Loading...', 'dff') : __('Load More', 'dff')}
        </button>
      ) : null}
    </div>
  );
};

export default PostList;
