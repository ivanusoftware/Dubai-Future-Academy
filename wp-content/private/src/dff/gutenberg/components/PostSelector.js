import { cloneDeep } from 'lodash-es';
import PostSelect from './post-selector/PostSelector';
import * as api from './post-selector/api';

const { Component } = wp.element;
const { dateI18n, __experimentalGetSettings } = wp.date;

/**
 * Returns a unique array of objects based on a desired key.
 * @param {array} arr - array of objects.
 * @param {string|int} key - key to filter objects by
 */
export const uniqueBy = (arr, key) => {
  const keys = [];
  return arr.filter(item => {
    if (keys.indexOf(item[key]) !== -1) {
      return false;
    }

    return keys.push(item[key]);
  });
};

/**
 * Returns a unique array of objects based on the id property.
 * @param {array} arr - array of objects to filter.
 * @returns {*}
 */
export const uniqueById = arr => uniqueBy(arr, 'id');

/**
 * Debounce a function by limiting how often it can run.
 * @param {function} func - callback function
 * @param {Integer} wait - Time in milliseconds how long it should wait.
 * @returns {Function}
 */
export const debounce = (func, wait) => {
  let timeout = null;

  return (...args) => {
    const context = this;

    const later = () => {
      func.apply(context, args);
    };

    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
};

/**
 * PostSelector Component
 */
class PostSelector extends Component {
  /**
   * Constructor for PostSelector Component.
   * Sets up state, and creates bindings for functions.
   * @param array args - All arguments.
   */
  constructor(...args) {
    super(...args);

    this.state = {
      posts: [],
      loading: false,
      type: 'post',
      types: [],
      filter: '',
      filterLoading: false,
      filterPosts: [],
      pages: {},
      pagesTotal: {},
      paging: false,
      initialLoading: false,
    };
  }

  /**
   * When the component mounts it calls this function.
   * Fetches posts types, selected posts then makes first call for posts
   */
  componentDidMount() {
    this.setState({
      loading: true,
      initialLoading: true,
    });

    api.getPostTypes().then(data => {
      const types = data;
      delete types.attachment;
      delete types.wp_block;
      delete types.sidebar;

      this.setState(
        {
          types,
        },
        () => {
          this.retrieveSelectedPosts().then(() => {
            this.setState({
              initialLoading: false,
            });
            this.getPosts().then(() => {
              this.setState({ loading: false });
            });
          });
        },
      );
    });
  }

  /**
   * GetPosts wrapper, builds the request argument based state and parameters passed/
   * @param {object} args - desired arguments (can be empty).
   * @returns {Promise<T>}
   */
  getPosts = (args = {}) => {
    const pageKey = this.state.filter ? false : this.state.type;

    const defaultArgs = {
      per_page: 10,
      type: this.state.type,
      search: this.state.filter,
      page: this.state.pages[pageKey] || 1,
    };

    const requestArguments = {
      ...defaultArgs,
      ...args,
    };

    requestArguments.restBase = this.state.types[requestArguments.type].rest_base;

    return api
      .getPosts(requestArguments)
      .then((data = [], i, xhr) => {
        const posts = data.map(p => {
          if (!p.featured_media || p.featured_media < 1) {
            return {
              ...p,
              featured_image: false,
            };
          }

          return {
            ...p,
            // eslint-disable-next-line camelcase
            featured_image: p?.featuredImage?.thumb?.source_url || false,
          };
        });

        return {
          xhr,
          data: posts,
        };
      })
      .then(({ data = [], xhr }) => {
        if (requestArguments.search) {
          this.setState({
            filterPosts:
              requestArguments.page > 1 ? uniqueById([...this.state.filterPosts, ...data]) : data,
            pages: {
              ...this.state.pages,
              filter: requestArguments.page,
            },
            pagesTotal: {
              ...this.state.pagesTotal,
              filter: xhr.getResponseHeader('x-wp-totalpages'),
            },
          });

          return { data, xhr };
        }

        this.setState({
          posts: uniqueById([...this.state.posts, ...data]),
          pages: {
            ...this.state.pages,
            [pageKey]: requestArguments.page,
          },
          pagesTotal: {
            ...this.state.pagesTotal,
            [pageKey]: xhr.getResponseHeader('x-wp-totalpages'),
          },
        });

        // return response to continue the chain
        return { data, xhr };
      });
  };

  /**
   * Gets the selected posts by id from the `posts` state object and
   * sorts them by their position in the selected array.
   *
   * @returns Array of objects.
   */
  getSelectedPosts = () => {
    const { selectedPosts = [] } = this.props;
    return this.state.posts
      .filter(({ id }) => selectedPosts.indexOf(id) !== -1)
      .sort((a, b) => {
        const aIndex = this.props.selectedPosts.indexOf(a.id);
        const bIndex = this.props.selectedPosts.indexOf(b.id);

        if (aIndex > bIndex) {
          return 1;
        }

        if (aIndex < bIndex) {
          return -1;
        }

        return 0;
      });
  };

  /**
   * Makes the necessary api calls to fetch the selected posts and returns a promise.
   * @returns {*}
   */
  retrieveSelectedPosts = () => {
    const { selectedPosts: selected = [] } = this.props;
    const { types } = this.state;

    if (!selected.length > 0) {
      // return a fake promise that auto resolves.
      return new Promise(resolve => resolve());
    }

    return Promise.all(
      Object.keys(types).map(type =>
        this.getPosts({
          include: selected.join(','),
          per_page: 100,
          type,
        }),
      ),
    );
  };

  /**
   * Adds desired post id to the selectedPosts List
   * @param {Integer} post_id
   */
  addPost = postId => {
    if (this.state.filter) {
      const post = this.state.filterPosts.filter(p => p.id === postId);
      const posts = uniqueById([...this.state.posts, ...post]);

      this.setState({
        posts,
      });
    }

    this.updateSelectedPosts([...this.props.selectedPosts, postId]);
  };

  /**
   * Removes desired post id to the selectedPosts List
   * @param {Integer} postId
   */
  removePost = postId => {
    this.updateSelectedPosts([...this.props.selectedPosts].filter(id => id !== postId));
  };

  /**
   * Update the selected posts attributes.
   * @param posts
   * @returns {*}
   */
  updateSelectedPosts = posts => this.props.onChange([...posts]);

  /**
   * Event handler for when the post type select box changes in value.
   * @param string type - comes from the event object target.
   */
  handlePostTypeChange = ({ target: { value: type = '' } = {} } = {}) => {
    this.setState({ type, loading: true }, () => {
      // fetch posts, then set loading = false
      this.getPosts().then(() => this.setState({ loading: false }));
    });
  };

  /**
   * Handles the search box input value
   * @param string type - comes from the event object target.
   */
  handleInputFilterChange = ({ target: { value: filter = '' } = {} } = {}) =>
    this.setState(
      {
        filter,
      },
      () => {
        if (!filter) {
          // remove filtered posts
          return this.setState({ filteredPosts: [], filtering: false });
        }

        return this.doPostFilter();
      },
    );

  /**
   * Actual api call for searching for query, this function is debounced in constructor.
   */
  doPostFilter = () => {
    const { filter = '' } = this.state;

    if (!filter) {
      return;
    }

    this.setState({
      filtering: true,
      filterLoading: true,
    });

    this.getPosts().then(() => {
      this.setState({
        filterLoading: false,
      });
    });
  };

  /**
   * Handles the pagination of post types.
   */
  doPagination = () => {
    this.setState({
      paging: true,
    });

    const pageKey = this.state.filter ? 'filter' : this.state.type;
    const page = parseInt(this.state.pages[pageKey], 10) + 1 || 2;

    this.getPosts({ page }).then(() =>
      this.setState({
        paging: false,
      }),
    );
  };

  /**
   * Strip html tag from the content
   * @param {string} html - Current html content
   * @returns {string}
   */
  strip = html => {
    const doc = new DOMParser().parseFromString(html, 'text/html');
    return doc.body.textContent || '';
  };

  getPreviewPosts() {
    const selectedPosts = this.getSelectedPosts();
    if (!selectedPosts) {
      return [];
    }

    const results = selectedPosts.map(resp => {
      // eslint-disable-next-line no-underscore-dangle
      const tags = (resp?._embedded?.['wp:term'] || [])
        .reduce((prev, curr) => [...prev, ...curr], [])
        .map(tag => ({
          title: tag.name,
          link: tag.link,
        }));

      let featuredImage = false;

      if (resp.featured_media || resp.featured_media > 0) {
        featuredImage = resp.featuredImage || false;
      }

      let excerpt = this.strip(resp.excerpt.rendered);
      excerpt = excerpt.length > 250 ? `${excerpt.slice(0, 250)}...` : excerpt;

      const dateFormat = __experimentalGetSettings().formats.date;
      const publishedDate = dateI18n(dateFormat, resp.date);

      return {
        id: resp.id,
        title: resp.title.rendered,
        link: resp.link,
        tag: tags.shift(),
        excerpt,
        featuredImage,
        date: publishedDate,
        type: resp.type,
        category: resp.category,
      };
    });

    return results;
  }

  movePost = index => difference => () => {
    const { selectedPosts = [] } = this.props;
    const newIndex = index + difference;

    if (selectedPosts.length === newIndex || newIndex < 0) {
      return;
    }

    const newSelectedPosts = cloneDeep(selectedPosts);

    const temp = newSelectedPosts[newIndex];
    newSelectedPosts[newIndex] = newSelectedPosts[index];
    newSelectedPosts[index] = temp;

    this.updateSelectedPosts(newSelectedPosts);
  };

  /**
   * Renders the PostSelector component.
   */
  render() {
    const { previewing, preview = () => null, maxPosts = false } = this.props;
    const posts = this.getPreviewPosts();

    if (previewing && preview) {
      return preview(posts);
    }

    return (
      <PostSelect
        state={this.state}
        onInputFilterChange={this.handleInputFilterChange}
        onPostTypeChange={this.handlePostTypeChange}
        getSelectedPosts={this.getSelectedPosts}
        removePost={this.removePost}
        addPost={this.addPost}
        doPagination={this.doPagination}
        maxPosts={maxPosts}
        movePost={this.movePost}
      />
    );
  }
}

export default PostSelector;
