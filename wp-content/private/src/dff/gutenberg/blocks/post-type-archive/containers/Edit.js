/* eslint-disable camelcase */
import { isEqual } from 'lodash-es';
import { decode } from 'he';

import TaxonomySelector from '../components/TaxonomySelector';

const { Component } = wp.element;
const { addQueryArgs } = wp.url;
const { InspectorControls } = wp.blockEditor;
const {
  PanelBody,
  PanelRow,
  RadioControl,
  ToggleControl,
  RangeControl,
  TextControl,
} = wp.components;
const { __ } = wp.i18n;
const { RichText } = wp.blockEditor;

const filtersOptions = {
  'dffmain-events': {
    prepareQuery: filters => ({
      showPast: filters.showPast ? 1 : 0,
    }),
    render: ({ filters, createUpdateAttribute }) => (
      <>
        <ToggleControl
          label={__('Past Events', 'dff')}
          help={__('When toggled this will only show events that have passed.', 'dff')}
          onChange={createUpdateAttribute('showPast')}
          checked={filters.showPast || false}
        />
      </>
    ),
  },
  program: {
    prepareQuery: filters => ({
      showPast: filters.showPast ? 1 : 0,
    }),
    render: ({ filters, createUpdateAttribute }) => (
      <>
        <ToggleControl
          label={__('Past Programmes', 'dff')}
          help={__(
            'When toggled this will only show programmes that have passed their deadline.',
            'dff',
          )}
          onChange={createUpdateAttribute('showPast')}
          checked={filters.showPast || false}
        />

        {!filters.showPast && (
          <ToggleControl
            label={__('Show Apply Now Link', 'dff')}
            onChange={createUpdateAttribute('showApplyNow')}
            checked={filters.showApplyNow || false}
          />
        )}
      </>
    ),
  },
};

class Edit extends Component {
  state = {
    postTypes: [],
    posts: [],
  };

  componentDidMount() {
    wp.apiFetch({ path: '/dff/v1/post-feed/types' }).then(postTypes =>
      this.setState({ postTypes }),
    );

    this.fetchPosts();
  }

  componentDidUpdate({ attributes: oldAttributes }) {
    const { attributes } = this.props;

    if (!isEqual(attributes, oldAttributes)) {
      this.fetchPosts();
    }
  }

  fetchPosts = () => {
    const { attributes } = this.props;
    const { posts } = this.state;

    if (posts[attributes.postType]) {
      return;
    }

    let args = {
      types: attributes.postType,
      per_page: attributes.perPage,
    };

    const filter = filtersOptions[attributes.postType];

    if (filter && filter.prepareQuery) {
      args = {
        ...(filter.prepareQuery(attributes.filters) || {}),
        ...args,
      };
    }

    if (attributes.taxonomies) {
      args = {
        ...args,
        ...Object.keys(attributes.taxonomies).reduce((carry, current) => {
          if (attributes.taxonomies[current].length === 0) {
            return carry;
          }

          return {
            ...carry,
            [current]: attributes.taxonomies[current].join(','),
          };
        }, {}),
      };
    }

    wp.apiFetch({
      path: addQueryArgs('/dff/v1/post-feed/fetch', args),
    }).then(response => {
      this.setState({
        posts: response.posts,
      });
    });
  };

  createUpdateAttribute = key => value => {
    const { setAttributes } = this.props;
    setAttributes({ [key]: value });
  };

  createUpdateFilters = key => value => {
    const { setAttributes, attributes } = this.props;
    setAttributes({ filters: { ...attributes.filters, [key]: value } });
  };

  createUpdateTaxonomy = taxonomy => ids => {
    const { setAttributes, attributes } = this.props;

    setAttributes({
      taxonomies: {
        ...attributes.taxonomies,
        [taxonomy]: ids,
      },
    });
  };

  render() {
    const { postTypes, posts } = this.state;
    const { attributes } = this.props;

    const postTypeOptions = Object.keys(postTypes).map(type => ({
      label: postTypes[type].name,
      value: type,
    }));

    const filters = filtersOptions[attributes.postType];

    const taxonomies = Object.keys(postTypes).reduce((carry, current) => {
      if (attributes.postType === current) {
        return [...new Set([...carry, ...postTypes[current].supports])];
      }

      return carry;
    }, []);

    return (
      <>
        <InspectorControls>
          <PanelBody title={__('Post Types', 'dff')}>
            <RadioControl
              options={postTypeOptions}
              selected={attributes.postType}
              onChange={this.createUpdateAttribute('postType')}
            />
          </PanelBody>
          {taxonomies.length > 0 && (
            <PanelBody title={__('Taxonomies', 'dff')}>
              {taxonomies.map(taxonomy => (
                <TaxonomySelector
                  key={taxonomy}
                  onUpdateTerms={this.createUpdateTaxonomy(taxonomy)}
                  terms={attributes?.taxonomies?.[taxonomy] || []}
                  slug={taxonomy}
                />
              ))}
            </PanelBody>
          )}
          <PanelBody title={__('Options', 'dff')}>
            <ToggleControl
              label={__('Show Category Filters', 'dff')}
              checked={attributes.showFilters}
              onChange={this.createUpdateAttribute('showFilters')}
            />
            {!attributes.showViewAllButton && (
              <ToggleControl
                label={__('Show Sort By Options', 'dff')}
                checked={attributes.showSortBy}
                onChange={this.createUpdateAttribute('showSortBy')}
              />
            )}
            {!attributes.showSortBy && (
              <ToggleControl
                label={__('Show View All Button', 'dff')}
                checked={attributes.showViewAllButton}
                onChange={this.createUpdateAttribute('showViewAllButton')}
              />
            )}
            {!attributes.showSortBy && (
              <TextControl
                label={__('View All Link', 'dff')}
                value={attributes.viewAllHref}
                onChange={this.createUpdateAttribute('viewAllHref')}
              />
            )}
            <ToggleControl
              label={__('Show Pagination', 'dff')}
              checked={attributes.showPagination}
              onChange={this.createUpdateAttribute('showPagination')}
            />
            <PanelRow>
              <RangeControl
                min={1}
                max={12}
                value={attributes.perPage}
                label={__('Number of posts', 'dff')}
                onChange={this.createUpdateAttribute('perPage')}
              />
            </PanelRow>
            <TextControl
              label={__('No Posts Found Text', 'dff')}
              value={attributes.noPostsText}
              onChange={this.createUpdateAttribute('noPostsText')}
            />
          </PanelBody>
          <PanelBody title={__('Card Options', 'dff')}>
            <ToggleControl
              label={__('Show Taxonomy', 'dff')}
              checked={attributes.showCardTaxonomy}
              onChange={this.createUpdateAttribute('showCardTaxonomy')}
            />
            <ToggleControl
              label={__('Show Date', 'dff')}
              checked={attributes.showCardDate}
              onChange={this.createUpdateAttribute('showCardDate')}
            />
          </PanelBody>
          {filters && (
            <PanelBody title={__('Filters', 'dff')}>
              {filters.render({
                filters: attributes.filters,
                createUpdateAttribute: this.createUpdateFilters,
              })}
            </PanelBody>
          )}
        </InspectorControls>
        <div className="archivePosts">
          <header className="archivePosts-header">
            <RichText
              tagName="h1"
              value={attributes.title}
              onChange={this.createUpdateAttribute('title')}
              placeholder={__('Archive Title', 'dff')}
              keepPlaceholderOnFocus
            />
          </header>
          <ul className="archivePosts-list">
            {posts?.length > 0 &&
              posts.map(post => {
                return (
                  <li>
                    <article className="archivePosts-item">
                      <div>
                        {post.featuredImage['post-card'] && (
                          <figure>
                            <img
                              src={post.featuredImage['post-card'].source_url}
                              srcset={
                                post.featuredImage['post-card@2x']
                                  ? `${post.featuredImage['post-card@2x'].source_url} 2x`
                                  : false
                              }
                              alt=""
                            />
                          </figure>
                        )}
                        <div className="archivePosts-content">
                          <header>
                            {attributes.showCardTaxonomy && post?.category?.name && (
                              <span className="archivePosts-tag">{decode(post.category.name)}</span>
                            )}
                            <h1 className="archivePosts-title">{decode(post.title.rendered)}</h1>
                            {attributes.showCardDate && post?.dateString && (
                              <span class="archivePosts-meta">{post.dateString}</span>
                            )}
                          </header>
                        </div>
                      </div>
                    </article>
                  </li>
                );
              })}
          </ul>
        </div>
      </>
    );
  }
}

export default Edit;
/* eslint-enable camelcase */
