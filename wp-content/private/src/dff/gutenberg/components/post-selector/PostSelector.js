import { PostList } from './PostList';

const { __ } = wp.i18n;
const { BlockIcon } = wp.editor;

const PostSelector = props => {
  const { state = {} } = props;
  const isFiltered = state.filtering;
  const postList =
    isFiltered && !state.filterLoading
      ? state.filterPosts
      : state.posts.filter(post => post.type === state.type);
  const pageKey = state.filter ? 'filter' : state.type;
  const canPaginate = (state.pages[pageKey] || 1) < state.pagesTotal[pageKey];

  const addIcon =
    props.maxPosts && props.getSelectedPosts().length >= props.maxPosts ? null : (
      <BlockIcon icon="plus" />
    );
  const removeIcon = <BlockIcon icon="minus" />;

  return (
    <div className="wp-block-bigbite-postlist">
      <div className="post-selector">
        <div className="post-selectorHeader">
          <div className="searchbox">
            <label htmlFor="searchinput">
              <BlockIcon icon="search" />
              <input
                id="searchinput"
                type="search"
                placeholder={__('Please enter your search query...', 'dff')}
                value={state.filter}
                onChange={props.onInputFilterChange}
              />
            </label>
          </div>
          <div className="filter">
            {/* eslint-disable-line react/jsx-one-expression-per-line */}
            <label htmlFor="options">{__('Post Type:', 'dff')}</label>
            <select name="options" id="options" onChange={props.onPostTypeChange}>
              {state.types.length < 1 ? (
                <option value="">{__('Loading...', 'dff')}</option>
              ) : (
                Object.keys(state.types).map(key => (
                  <option key={key} value={key}>
                    {state.types[key].name}
                  </option>
                ))
              )}
            </select>
          </div>
        </div>
        <div className="post-selectorContainer">
          <PostList
            posts={postList}
            loading={state.initialLoading || state.loading || state.filterLoading}
            filtered={isFiltered}
            action={props.addPost}
            paging={state.paging}
            canPaginate={canPaginate}
            doPagination={props.doPagination}
            icon={addIcon}
          />
          <PostList
            posts={props.getSelectedPosts()}
            loading={state.initialLoading}
            action={props.removePost}
            icon={removeIcon}
            movePost={props.movePost}
          />
        </div>
      </div>
    </div>
  );
};

export default PostSelector;
