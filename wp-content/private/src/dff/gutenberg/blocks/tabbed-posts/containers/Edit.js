/* eslint-disable camelcase */
import { isEqual } from 'lodash-es';
import classnames from 'classnames';
import { PostSelector } from '../../../components';
import Item from '../components/Item';

const { Component } = wp.element;
const { addQueryArgs } = wp.url;
const { InspectorControls, URLInputButton } = wp.blockEditor;
const { PanelBody, PanelRow, CheckboxControl, ToggleControl, SelectControl } = wp.components;
const { __ } = wp.i18n;
const { RichText } = wp.blockEditor;
const { applyFilters } = wp.hooks;

const CheckBoxGroup = ({ options, label, onChange, selected = [], max = 0 }) => {
  const handleChange = key => checked => {
    if (checked) {
      if ((max > 0 && selected.length < max) || max === 0) {
        onChange([...new Set([...selected, key])]);
      }
      return;
    }

    onChange([...selected].filter(item => item !== key));
  };

  return (
    <>
      <h3>{label}</h3>
      <div className="editor-post-taxonomies__hierarchical-terms-list">
        {options.map(option => (
          <div className="editor-post-taxonomies__hierarchical-terms-choice">
            <CheckboxControl
              key={option.value}
              label={option.label}
              checked={selected.includes(option.value)}
              onChange={handleChange(option.value)}
              disabled={max > 0 && selected.length >= max && !selected.includes(option.value)}
            />
          </div>
        ))}
      </div>
      {max > 0 && selected.length >= max && (
        <p>{__('Maximum of two post types can be selected', 'dff')}</p>
      )}
    </>
  );
};

class Edit extends Component {
  state = {
    postTypes: [],
    posts: {},
    selected: '',
  };

  componentDidMount() {
    wp.apiFetch({ path: '/dff/v1/post-feed/types' }).then(postTypes =>
      this.setState({ postTypes }),
    );

    this.fetchPosts();
  }

  componentDidUpdate({ attributes: oldAttributes }) {
    const { attributes, setAttributes } = this.props;
    const { selected, allPages } = this.state;

    if (
      (!selected && attributes.postTypes.length > 0) ||
      (attributes.postTypes.length > 0 && !attributes.postTypes.includes(selected))
    ) {
      this.setState({
        selected: attributes.postTypes[0],
      });
    }

    if (attributes.mode !== oldAttributes.mode) {
      setAttributes({
        postTypes: [],
        postTypesContent: {},
        restrictToPageParent: false,
        pageId: '',
        isOnlyCards: false,
        isPreview: false,
        posts: [],
        selectedModeTitle: '',
        itemAlignment: '',
      });
    }

    if (!isEqual(attributes, oldAttributes)) {
      this.fetchPosts();
    }

    // add another run of fetchPosts here for page restriction
    if (attributes.restrictToPageParent && allPages === undefined) {
      wp.apiFetch({
        path: addQueryArgs('/dff/v1/post-feed/fetch', {
          types: 'page',
          per_page: 99,
          orderby: 'title',
          order: 'desc',
        }),
      }).then(optionPages => {
        const pageSelectOptionsData = optionPages.posts.map(page => {
          return {
            value: page.id,
            label: page.title.rendered,
          };
        });

        pageSelectOptionsData.splice(0, 0, {
          value: '',
          label: __('Please select a page', 'dff'),
          disabled: true,
        });

        this.setState({ allPages: pageSelectOptionsData });
      });
    }
  }

  fetchPosts = () => {
    const { attributes } = this.props;

    attributes.postTypes.forEach(postType => {
      const options = {
        per_page: 3,
      };
      options.types = postType;

      if (
        postType === 'page' &&
        attributes.pageId !== '' &&
        attributes.restrictToPageParent === true
      ) {
        options.per_page = 99;
        options.post_parent = attributes.pageId;
        options.orderby = attributes.orderBy;
        options.order = attributes.order;
      }

      wp.apiFetch({
        path: addQueryArgs('/dff/v1/post-feed/fetch', options),
      }).then(response => {
        const { posts: newPosts } = this.state;
        this.setState({
          posts: {
            ...newPosts,
            [postType]: response.posts,
          },
        });
      });
    });
  };

  createUpdateAttribute = key => value => {
    const { setAttributes } = this.props;
    if (key === 'mode' && value === 'select') {
      setAttributes({
        postTypes: [],
        postTypesContent: {},
      });
    }
    setAttributes({ [key]: value });
  };

  setSelected = selected => () =>
    this.setState({
      selected,
    });

  createUpdatePostTypeContent = type => field => value => {
    const {
      setAttributes,
      attributes: { postTypesContent = {}, mode },
    } = this.props;
    if (mode === 'select') {
      setAttributes({
        postTypesContent: {
          text: value,
        },
      });
      return;
    }

    setAttributes({
      postTypesContent: {
        ...postTypesContent,
        [type]: {
          ...(postTypesContent?.[type] || {}),
          [field]: value,
        },
      },
    });
  };

  generateTabName = postType => {
    const {
      attributes: { restrictToPageParent, pageId },
    } = this.props;
    const { allPages, postTypes } = this.state;
    if (
      postTypes?.[postType]?.name === 'Pages' &&
      restrictToPageParent &&
      pageId !== '' &&
      allPages !== undefined
    ) {
      return allPages.find(page => parseInt(page.value, 10) === parseInt(pageId, 10)).label;
    }
    return postTypes?.[postType]?.name;
  };

  render() {
    const { postTypes, posts, selected } = this.state;
    const { attributes } = this.props;

    const postTypeOptions = Object.keys(postTypes).map(type => ({
      label: postTypes[type].name,
      value: type,
    }));

    const updatePostTypeContent = this.createUpdatePostTypeContent(selected);

    return (
      <>
        <InspectorControls>
          <PanelBody title={__('Settings', 'dff')}>
            <SelectControl
              label={__('Please select a mode type:', 'dff')}
              options={[
                {
                  value: 'feed',
                  label: 'Feed',
                },
                {
                  value: 'select',
                  label: 'Select',
                },
              ]}
              value={attributes.mode}
              onChange={this.createUpdateAttribute('mode')}
            />

            {attributes.mode === 'select' && (
              <ToggleControl
                label={__('Enable preview', 'dff')}
                help={__('Toggles the preview card option for the select feed.', 'dff')}
                checked={attributes.isPreview}
                onChange={this.createUpdateAttribute('isPreview')}
              />
            )}

            {attributes.mode === 'feed' && (
              <CheckBoxGroup
                options={postTypeOptions}
                selected={attributes.postTypes}
                onChange={this.createUpdateAttribute('postTypes')}
                max={2}
              />
            )}
          </PanelBody>
          <PanelBody title={__('Additional Settings', 'dff')}>
            <PanelRow>
              <ToggleControl
                label={__('No Sidebar ', 'dff')}
                help={__('Disables the sidebar', 'dff')}
                checked={attributes.isOnlyCards}
                onChange={this.createUpdateAttribute('isOnlyCards')}
              />
            </PanelRow>
            <PanelRow>
              <ToggleControl
                label={__('Enable Meta Information ', 'dff')}
                help={__('Enables the display of meta information for each item', 'dff')}
                checked={attributes.isMeta}
                onChange={this.createUpdateAttribute('isMeta')}
              />
            </PanelRow>
            {!attributes.isMeta && (
              <PanelRow>
                <ToggleControl
                  label={__('Enable Call To Action ', 'dff')}
                  help={__(
                    'Enables the display of an arrow icon where meta information would usually go',
                    'dff',
                  )}
                  checked={attributes.isCallToAction}
                  onChange={this.createUpdateAttribute('isCallToAction')}
                />
              </PanelRow>
            )}
            {attributes.isOnlyCards && (
              <PanelRow>
                <SelectControl
                  label={__('Please an alignment', 'dff')}
                  options={[
                    {
                      value: '',
                      label: __('Left', 'dff'),
                    },
                    {
                      value: 'center',
                      label: __('Center', 'dff'),
                    },
                    {
                      value: 'right',
                      label: __('Right', 'dff'),
                    },
                  ]}
                  value={attributes.itemAlignment}
                  onChange={this.createUpdateAttribute('itemAlignment')}
                />
              </PanelRow>
            )}
            {selected === 'page' && attributes.mode === 'feed' && (
              <PanelRow>
                <ToggleControl
                  label={__('Restrict To Sub Pages ', 'dff')}
                  help={__('Enables the selection of a parent page to be used.', 'dff')}
                  checked={attributes.restrictToPageParent}
                  onChange={this.createUpdateAttribute('restrictToPageParent')}
                />
              </PanelRow>
            )}
            {attributes.restrictToPageParent && selected === 'page' && attributes.mode === 'feed' && (
              <>
                <PanelRow>
                  <SelectControl
                    label={__('Please select a page:', 'dff')}
                    value={attributes.pageId}
                    onChange={this.createUpdateAttribute('pageId')}
                    options={this.state.allPages}
                  />
                </PanelRow>
                <PanelRow>
                  <SelectControl
                    label={__('Order by: ', 'dff')}
                    value={attributes.orderBy}
                    onChange={this.createUpdateAttribute('orderBy')}
                    options={[
                      {
                        value: 'id',
                        label: 'ID',
                      },
                      {
                        value: 'post_title',
                        label: 'Title',
                      },
                      {
                        value: 'menu_order',
                        label: 'Menu Order',
                      },
                    ]}
                  />
                </PanelRow>
                <PanelRow>
                  <SelectControl
                    label={__('Order: ', 'dff')}
                    value={attributes.order}
                    onChange={this.createUpdateAttribute('order')}
                    options={[
                      {
                        value: 'desc',
                        label: 'Desc',
                      },
                      {
                        value: 'asc',
                        label: 'Asc',
                      },
                    ]}
                  />
                </PanelRow>
              </>
            )}
          </PanelBody>
        </InspectorControls>
        <div
          className={classnames('tabbedPosts', {
            'is-tabbedSingle': attributes.postTypes.length === 1 || attributes.mode === 'select',
            'is-sidebarDisabled': attributes.isOnlyCards,
          })}
          data-alignment={attributes.itemAlignment !== '' ? attributes.itemAlignment : null}
        >
          <div className="tabbedPosts-aside">
            {attributes.mode === 'feed' &&
              attributes.postTypes.map(
                postType =>
                  postTypes?.[postType]?.name && (
                    <button
                      onClick={this.setSelected(postType)}
                      className={classnames('tabbedPost-action', {
                        'is-selected': postType === selected,
                      })}
                    >
                      {this.generateTabName(postType)}
                    </button>
                  ),
              )}

            {attributes.mode === 'select' && (
              <RichText
                tagName="h2"
                placeholder={__('Enter title', 'dff')}
                value={attributes.selectedModeTitle}
                onChange={this.createUpdateAttribute('selectedModeTitle')}
              />
            )}
            <RichText
              tagName="p"
              placeholder={__('Enter description', 'dff')}
              value={
                attributes?.postTypesContent?.[selected]?.text ||
                attributes?.postTypesContent?.text ||
                ''
              }
              onChange={updatePostTypeContent('text')}
            />

            <span className="btn button--ghost">View All</span>
            {attributes.mode === 'select' && (
              <span>
                <URLInputButton
                  onChange={this.createUpdateAttribute('customURL')}
                  url={attributes.customURL}
                />
              </span>
            )}
          </div>
          <div class="tabbedPosts-listContainer">
            {attributes.mode === 'feed' && (
              <div className="tabbedPosts-list">
                {posts?.[selected]?.length > 0 &&
                  posts[selected].map(post => {
                    const meta = applyFilters(
                      `dff_post_card_${post.type}_meta`,
                      post.category.name,
                      post,
                    );
                    return (
                      <Item
                        post={post}
                        meta={meta}
                        isMeta={attributes.isMeta}
                        isCallToAction={attributes.isCallToAction}
                      />
                    );
                  })}
              </div>
            )}

            {attributes.mode === 'select' && (
              <PostSelector
                selectedPosts={attributes.posts}
                onChange={this.createUpdateAttribute('posts')}
                previewing={attributes.isPreview}
                maxPosts={10}
                preview={selectedPosts => (
                  <ul className="tabbedPosts-list">
                    {selectedPosts.map(post => {
                      const meta = applyFilters(
                        `dff_post_card_${post.type}_meta`,
                        post.category.name,
                        post,
                      );
                      return (
                        <Item
                          post={post}
                          meta={meta}
                          isMeta={attributes.isMeta}
                          isCallToAction={attributes.isCallToAction}
                        />
                      );
                    })}
                  </ul>
                )}
              />
            )}
          </div>
        </div>
      </>
    );
  }
}

export default Edit;
/* eslint-enable camelcase */
