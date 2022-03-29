/* eslint-disable camelcase */
// eslint-disable-next-line
import { h, Fragment, Component, createRef } from 'preact';
import axios, { CancelToken } from 'axios';
import classnames from 'classnames';
import { decode } from 'he';
import { addQueryArgs, getQueryArg } from '@wordpress/url';
import { set, cloneDeep } from 'lodash-es';

const { i18n } = window;

const generateID = () =>
  `_${Math.random()
    .toString(36)
    .substr(2, 9)}`;

const processBooleans = value => {
  if (value === true) {
    return 1;
  }

  if (value === false) {
    return 0;
  }

  return value;
};

const defaultSorting = {
  'date-desc': i18n?.['Date (NEWEST)'],
  'date-asc': i18n?.['Date (OLDEST)'],
  // 'title-asc': 'Title (ASC)',
  // 'title-desc': 'Title (DESC)',
};

class Archive extends Component {
  constructor(args) {
    super(args);
    const allowedArgs = {
      showPast: 'showPast',
      postType: 'types',
      perPage: 'per_page',
      order: 'order',
      orderby: 'orderby',
      s: 's',
    };

    this.source = CancelToken.source();

    this.orderByRef = createRef();
    this.activeTermListRef = createRef();
    this.filterTermListRef = createRef();

    const queryArgs = Object.keys(args || {})
      .filter(k => Object.keys(allowedArgs).includes(k))
      .reduce((carry, key) => {
        if (!allowedArgs[key]) {
          return carry;
        }
        return { ...carry, [allowedArgs[key]]: processBooleans(args[key]) };
      }, {});

    const [baseUrl] = window.location.href.split('?');

    this.state = {
      status: 'INITIAL',
      id: generateID(),
      activeTerm: {},
      termFilter: {},
      activeTermBase: 'category',
      activeTermHideIndex: -1,
      termFilterBase: 'filter',
      showHidden: false,
      baseUrl,
      postTypeSetFilters: [],
      ...args,
      sortby: {
        ...(args?.sortby || {}),
        ...defaultSorting,
      },
      queryArgs: {
        paged: 1,
        relation: 'AND',
        ...queryArgs,
        ...Object.keys(args.filters || {})
          .filter(k => Object.keys(allowedArgs).includes(k))
          .reduce(
            (carry, key) => ({ ...carry, [allowedArgs[key]]: processBooleans(args.filters[key]) }),
            {},
          ),
      },
    };

    window.addEventListener('resize', this.resizeTags);
    window.addEventListener('popstate', this.handleUrlUpdate);
  }

  componentDidUpdate(prevProps, prevState) {
    const { status, activeTerm, termFilter } = this.state;
    const { innerChild } = this.props;

    innerChild.remove();

    if (prevState.status === 'INITIAL' && prevState.status !== status) {
      this.resizeTags();
    }

    if (
      (activeTerm?.id !== prevState.activeTerm?.id ||
        termFilter?.term_id !== prevState.termFilter?.term_id) &&
      window.location.href !== this.getUrl()
    ) {
      this.updateUrl();
    }
  }

  componentDidMount() {
    this.fetchPosts();
  }

  resizeTags = () => {
    const container = this.activeTermListRef.current;

    if (!container) {
      return;
    }

    const parentOffset = container.offsetTop;
    container.children.forEach(ele => ele.classList.remove('u-hiddenVisually'));

    let indexToHide = Array.from(container.children).reduce((carry, current, index) => {
      if (carry === -1 && current.offsetTop - parentOffset > 5) {
        return index;
      }

      return carry;
    }, -1);

    if (indexToHide !== -1) {
      container.children.forEach((ele, index) => {
        if (index >= indexToHide && !ele.matches('[data-dont-include]')) {
          ele.classList.add('u-hiddenVisually');
        }
      });

      if (container.lastChild.offsetTop - parentOffset > 5) {
        indexToHide -= 1;
        container.children[indexToHide].classList.add('u-hiddenVisually');
      }
    } else {
      container.lastChild.classList.add('u-hiddenVisually');
    }

    this.setState({
      activeTermHideIndex: indexToHide,
      showHidden: false,
    });
  };

  handleUrlUpdate = () => {
    const { activeTermBase, termFilterBase, terms, filterTerms } = this.state;
    // get query parameters
    const activeTermSlug = getQueryArg(window.location.href, activeTermBase);
    const filterTermSlug = getQueryArg(window.location.href, termFilterBase);
    // find activeTerm
    const activeTerm = terms.find(({ slug }) => slug === activeTermSlug);
    const termFilter = filterTerms.find(({ slug }) => slug === filterTermSlug);
    // update state.
    this.setState(
      {
        activeTerm,
        termFilter,
      },
      this.fetchPosts,
    );
  };

  getUrl = () => {
    const { baseUrl, activeTerm, activeTermBase, termFilter, termFilterBase } = this.state;
    return addQueryArgs(baseUrl, {
      [activeTermBase]: activeTerm?.slug,
      [termFilterBase]: termFilter?.slug,
    });
  };

  updateUrl = () => {
    window.history.pushState({}, '', this.getUrl());
  };

  fetchPosts = async () => {
    if (this.state.isSearchPage) {
      this.setState({
        status: 'LOADING',
      });
    }

    const { endpoint, queryArgs: args, activeTerm, termFilter, taxonomies } = this.state;

    const queryArgs = cloneDeep(args);

    if (activeTerm?.id) {
      set(queryArgs, activeTerm.taxonomy, activeTerm.id);
    }

    if (termFilter?.term_id) {
      set(queryArgs, termFilter.taxonomy, termFilter.term_id);
    }

    this.source.cancel();

    set(queryArgs, 'paged', 1);

    if (taxonomies && Object.keys(taxonomies).length > 0) {
      Object.keys(taxonomies).forEach(tax => {
        if (taxonomies[tax].length < 1) {
          return;
        }

        if (queryArgs[tax]) {
          set(queryArgs, tax, [queryArgs[tax], ...taxonomies[tax]].join(','));
          return;
        }
        set(queryArgs, tax, taxonomies[tax].join(','));
      });
    }

    try {
      this.source = CancelToken.source();
      const { data } = await axios.get(addQueryArgs(`${endpoint}/fetch`, queryArgs), {
        cancelToken: this.source.token,
      });

      if (activeTerm) {
        delete queryArgs[activeTerm.taxonomy];
      }

      if (termFilter) {
        delete queryArgs[termFilter.taxonomy];
      }

      if (taxonomies && Object.keys(taxonomies).length > 0) {
        Object.keys(taxonomies).forEach(tax => {
          delete queryArgs[tax];
        });
      }

      this.setState({
        posts: data.posts,
        totalItems: data.total,
        maxPages: Math.ceil(data.total / queryArgs.per_page),
        status: 'IDLE',
        queryArgs: {
          ...queryArgs,
          paged: 1,
          relation: 'AND',
        },
      });
    } catch (e) {
      // silence is golden
    }
  };

  paginatePosts = async () => {
    const { queryArgs: staleArgs, endpoint, activeTerm, termFilter, taxonomies } = this.state;

    const queryArgs = {
      ...staleArgs,
      paged: staleArgs.paged + 1,
    };

    this.setState({
      status: 'PAGINATING',
      queryArgs,
    });

    if (activeTerm) {
      set(queryArgs, activeTerm.taxonomy, activeTerm.id);
    }

    if (termFilter) {
      set(queryArgs, termFilter.taxonomy, termFilter.term_id);
    }

    if (taxonomies && Object.keys(taxonomies).length > 0) {
      Object.keys(taxonomies).forEach(tax => {
        if (taxonomies[tax].length < 1) {
          return;
        }

        if (queryArgs[tax]) {
          set(queryArgs, tax, [queryArgs[tax], ...taxonomies[tax]].join(','));
          return;
        }
        set(queryArgs, tax, taxonomies[tax].join(','));
      });
    }

    this.source.cancel();
    try {
      this.source = CancelToken.source();
      const { data } = await axios.get(addQueryArgs(`${endpoint}/fetch`, queryArgs), {
        cancelToken: this.source.token,
      });
      const { posts } = this.state;

      if (activeTerm) {
        delete queryArgs[activeTerm.taxonomy];
      }

      if (taxonomies && Object.keys(taxonomies).length > 0) {
        Object.keys(taxonomies).forEach(tax => {
          delete queryArgs[tax];
        });
      }

      this.setState({
        posts: [...posts, ...data.posts],
        totalItems: data.total,
        status: 'IDLE',
      });
    } catch (e) {
      // silence is golden
    }
  };

  changeOrderBy = () => {
    const [field, dir] = this.orderByRef.current.value.split('-');
    const { queryArgs } = this.state;
    this.setState(
      {
        queryArgs: {
          ...queryArgs,
          paged: 1,
          order: dir,
          orderby: field,
        },
      },
      this.fetchPosts,
    );
  };

  setFilter = term => () => {
    const { activeTerm: stale } = this.state;

    const activeTerm = term.id === stale?.id ? {} : term;

    this.setState(
      {
        paged: 1,
        activeTerm,
      },
      this.fetchPosts,
    );
  };

  postFilter = term => () => {
    const { activeTerm } = this.state;

    if (activeTerm && term.taxonomy === activeTerm.taxonomy && term?.term_id === activeTerm?.id) {
      return;
    }

    if (activeTerm && term.taxonomy === activeTerm.taxonomy) {
      this.setState(
        {
          activeTerm: term,
          termFilter: {},
          paged: 1,
        },
        this.fetchPosts,
      );
      return;
    }

    this.setState(
      {
        termFilter: term,
        paged: 1,
      },
      this.fetchPosts,
    );
  };

  clearFilter = () => {
    this.setState(
      {
        termFilter: {},
        paged: 1,
      },
      this.fetchPosts,
    );
  };

  clearTerm = () => {
    this.setState(
      {
        activeTerm: {},
        paged: 1,
      },
      this.fetchPosts,
    );
  };

  filterByPostType = event => {
    const { queryArgs, postTypeSetFilters } = this.state;
    const { target } = event;
    let types;

    // handle clear button press
    if (target.value === 'clear') {
      postTypeSetFilters.splice(0);
    }

    if (target.value !== 'clear') {
      // create post type filtering array on toggle
      if (postTypeSetFilters.includes(target.value)) {
        postTypeSetFilters.splice(
          postTypeSetFilters.findIndex(item => item === target.value),
          1,
        );
      } else {
        postTypeSetFilters.push(target.value);
      }
    }

    // setup filter string for allowed query argument
    if (postTypeSetFilters.length > 0) {
      types = postTypeSetFilters.join();
    } else {
      types = 'post,page,dffmain-events,program';
    }

    // set persistent state for selected post type filters and set new query argument
    this.setState(
      {
        postTypeSetFilters,
        queryArgs: {
          ...queryArgs,
          types,
        },
      },
      this.fetchPosts,
    );
  };

  buttonTogglePressed = target => {
    target.setAttribute('aria-pressed', target.getAttribute('aria-pressed') === 'false');
  };

  render() {
    const {
      status,
      title,
      posts = [],
      queryArgs,
      maxPages,
      showSortBy,
      showFilters,
      terms,
      filterTitle = false,
      activeTerm,
      termFilter,
      showCardTaxonomy,
      showCardDate,
      sortby,
      id,
      imageSize = 'post-feed-large',
      activeTermHideIndex,
      showHidden,
      filters,
      isSearchPage,
      postTypes,
      postTypeSetFilters,
    } = this.state;

    if (status === 'INITIAL') {
      return null;
    }

    return (
      <>
        {isSearchPage && (
          <div className="searchFilters">
            <header className="searchFilters-header">
              <h1 id={`searchFilters-heading-${id}`}>{i18n?.searchFilters}</h1>
            </header>
            <nav className="archivePosts-filters">
              <ul aria-labelledby={`searchFilters-heading-${id}`}>
                {Object.entries(postTypes)?.map(postTypesItem => (
                  <li>
                    <button
                      aria-pressed={postTypeSetFilters.includes(postTypesItem[0])}
                      value={postTypesItem[0]}
                      onClick={this.filterByPostType}
                      className={postTypeSetFilters.includes(postTypesItem[0]) ? 'is-active' : ''}
                    >
                      {postTypesItem[1].name}
                    </button>
                  </li>
                ))}
              </ul>
              {postTypeSetFilters.length > 0 && (
                <p>
                  <button class="is-ghost" value="clear" onClick={this.filterByPostType}>
                    {i18n?.['Clear Filter']}
                  </button>
                </p>
              )}
            </nav>
          </div>
        )}
        <div className="archivePosts-inner">
          <header className="archivePosts-header">
            <h1 id={`feedTitleDescription-${id}`}>{title}</h1>
            {showSortBy && (
              <div className="archivePosts-sortBy">
                <label for={`archive-sort-by-${id}`}>{i18n?.['Sort By']}</label>
                <select
                  id={`archive-sort-by-${id}`}
                  ref={this.orderByRef}
                  onChange={this.changeOrderBy}
                  value={`${queryArgs.orderby}-${queryArgs.order}`}
                >
                  {Object.keys(sortby).map(sortKey => (
                    <option key={sortKey} value={sortKey}>
                      {sortby[sortKey]}
                    </option>
                  ))}
                </select>
              </div>
            )}
          </header>

          {showFilters && terms.length > 0 && (
            <nav className="archivePosts-filters">
              {filterTitle && <span>{filterTitle}</span>}
              <ul ref={this.activeTermListRef}>
                {terms.map((term, index) => (
                  <li
                    className={classnames({
                      'u-hiddenVisually':
                        !showHidden && activeTermHideIndex !== -1 && index >= activeTermHideIndex,
                    })}
                  >
                    <button
                      className={classnames({
                        'is-active': term.id === activeTerm?.id || term.id === termFilter?.term_id,
                      })}
                      onClick={this.setFilter(term)}
                    >
                      {decode(term.name)}
                    </button>
                  </li>
                ))}
                <li
                  className={classnames({
                    'u-hiddenVisually': showHidden || activeTermHideIndex === -1,
                  })}
                  data-dont-include
                >
                  <button
                    aria-hidden
                    onClick={() =>
                      this.setState({
                        showHidden: true,
                      })
                    }
                  >
                    ...
                  </button>
                </li>
              </ul>
            </nav>
          )}
          {termFilter?.term_id && (
            <nav className="archivePosts-filters is-sub">
              <span>{i18n?.['Filtering By']}</span>
              <ul>
                {termFilter?.term_id && (
                  <li>
                    <button>{decode(termFilter.name)}</button>
                  </li>
                )}
                <li>
                  <button class="is-ghost" onClick={this.clearFilter}>
                    {i18n?.['Clear Filter']}
                  </button>
                </li>
              </ul>
            </nav>
          )}
          <ul
            aria-live="polite"
            aria-relevant="additions"
            aria-labelledby={`feedTitleDescription-${id}`}
            className="archivePosts-list"
          >
            {isSearchPage && status === 'LOADING' && (
              <li className="searchLoading">{i18n?.['Loading...']}</li>
            )}
            {isSearchPage && posts.length === 0 && status !== 'LOADING' && (
              <li>{i18n?.noSearchResults}</li>
            )}
            {posts.map(post => {
              const hasApplyButton = post.type === 'program' && filters?.showApplyNow;

              // excerpt
              if (isSearchPage && post?.excerpt?.rendered) {
                const excerptContainer = document.createElement('div');
                excerptContainer.innerHTML = `${post.excerpt.rendered.substring(0, 250)}...`;
                /* eslint-disable-next-line no-param-reassign */
                post.excerpt.newCustomExcerptText = excerptContainer.textContent;
              }

              return (
                <li>
                  <article aria-labelledby={`article-${post.id}`} className="archivePosts-item">
                    <div>
                      {post.featuredImage['post-card'] && !isSearchPage && (
                        <figure>
                          <a href={post.link}>
                            <span className="u-hiddenVisually">{post.title.rendered}</span>
                            <img
                              src={post.featuredImage[imageSize].source_url}
                              srcset={
                                post.featuredImage[`${imageSize}@2x`]
                                  ? `${post.featuredImage[`${imageSize}@2x`].source_url} 2x`
                                  : false
                              }
                              alt=""
                            />
                          </a>
                        </figure>
                      )}
                      <div className="archivePosts-content">
                        <header>
                          <h1 id={`article-${post.id}`} className="archivePosts-title">
                            <a href={post.link}>{decode(post.title.rendered)}</a>
                          </h1>
                          {showCardTaxonomy && !isSearchPage && post?.category?.name && (
                            <>
                              {showFilters ? (
                                <button
                                  className="archivePosts-tag"
                                  onClick={this.postFilter(post.category)}
                                >
                                  {decode(post.category.name)}
                                </button>
                              ) : (
                                <span className="archivePosts-tag">
                                  {decode(post.category.name)}
                                </span>
                              )}
                            </>
                          )}
                          {isSearchPage && (
                            <p>
                              <a href={post.link}>{post.link}</a>
                            </p>
                          )}
                          {showCardDate && post?.dateString && !isSearchPage && (
                            <span
                              class={classnames('archivePosts-meta', {
                                'is-link-style': hasApplyButton,
                              })}
                            >
                              {post.dateString}
                              {hasApplyButton && (
                                <>
                                  {' | '}
                                  <a href={post.link}>Apply Now {decode('&raquo;')}</a>
                                </>
                              )}
                            </span>
                          )}
                        </header>
                        {isSearchPage && post?.excerpt?.newCustomExcerptText && (
                          <div className="archivePosts-excerpt">
                            <p>{post.excerpt.newCustomExcerptText}</p>
                          </div>
                        )}
                        {isSearchPage && (
                          <button
                            className="archivePosts-tag"
                            aria-pressed={postTypeSetFilters.includes(post.type)}
                            value={post.type}
                            onClick={this.filterByPostType}
                          >
                            {postTypes?.[post.type]?.name}
                          </button>
                        )}
                      </div>
                    </div>
                  </article>
                </li>
              );
            })}
          </ul>
          {queryArgs.paged < maxPages && (
            <button
              disabled={status === 'PAGINATING'}
              className="button button--ghost"
              onClick={this.paginatePosts}
            >
              {status === 'PAGINATING' ? i18n?.['Loading...'] : i18n?.['Load More']}
            </button>
          )}
        </div>
      </>
    );
  }
}

export default Archive;
/* eslint-enable camelcase */
