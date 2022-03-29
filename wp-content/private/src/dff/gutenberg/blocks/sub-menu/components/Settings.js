const { useEffect } = wp.element;

const { InspectorControls } = wp.blockEditor;
const { PanelBody, PanelRow, SelectControl } = wp.components;
const { __ } = wp.i18n;

const Settings = props => {
  const {
    attributes: { pageId, pageSelectOptions, orderBy, order },
    setAttributes,
  } = props;

  // on page selection change
  const pageSelector = async () => {
    setAttributes({
      isLoading: true,
    });

    // get sub pages
    if (pageId) {
      const response = await wp.apiFetch({
        path: `/wp/v2/pages?parent=${pageId}&orderby=${orderBy}&order=${order}&status=publish,private`,
      });
      setAttributes({
        subPages: response,
      });
    }

    setAttributes({
      isLoading: false,
    });
  };

  // populate settings select box with all pages
  useEffect(async () => {
    const response = await wp.apiFetch({
      path: '/wp/v2/pages?orderby=title&order=asc&per_page=99&status=publish,private',
    });

    const pageSelectOptionsData = response.map(item => {
      return {
        value: item.id,
        label: item.title.rendered,
      };
    });

    pageSelectOptionsData.splice(0, 0, {
      value: '',
      label: __('Please select a page', 'dff'),
      disabled: true,
    });

    setAttributes({
      pageSelectOptions: pageSelectOptionsData,
    });
  }, []);

  // watch for selection option changes
  useEffect(() => {
    if (pageId !== '') {
      pageSelector();
    }
  }, [pageId, orderBy, order]);

  return (
    <InspectorControls>
      <PanelBody title={__('Settings', 'dff')}>
        <PanelRow>
          <SelectControl
            label={__('Please select a page:', 'dff')}
            value={pageId}
            onChange={value => setAttributes({ pageId: value })}
            options={pageSelectOptions}
          />
        </PanelRow>
        <PanelRow>
          <SelectControl
            label={__('Order by: ', 'dff')}
            value={orderBy}
            onChange={value => setAttributes({ orderBy: value })}
            options={[
              {
                value: 'id',
                label: 'ID',
              },
              {
                value: 'title',
                label: 'Title',
              },
              {
                value: 'menu_order',
                label: 'Menu Order',
              },
              {
                value: 'date',
                label: 'Date',
              },
              {
                value: 'modified',
                label: 'Modified',
              },
            ]}
          />
        </PanelRow>
        <PanelRow>
          <SelectControl
            label={__('Order: ', 'dff')}
            value={order}
            onChange={value => setAttributes({ order: value })}
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
      </PanelBody>
    </InspectorControls>
  );
};

export default Settings;
