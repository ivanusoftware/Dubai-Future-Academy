import TaxonomySelector from './TaxonomySelector';
import { getStyleOptions, getDefaultStyle } from '../styles/registerStyle';

const { useEffect, useState } = wp.element;
const { InspectorControls, MediaUploadCheck, MediaUpload } = wp.blockEditor;
const {
  PanelBody,
  SelectControl,
  CheckboxControl,
  PanelRow,
  Button,
  TextControl,
  ToggleControl,
} = wp.components;
const { __ } = wp.i18n;

const CheckBoxGroup = ({ options, label, onChange, selected = [] }) => {
  const handleChange = key => checked => {
    if (checked) {
      onChange([...new Set([...selected, key])]);
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
            />
          </div>
        ))}
      </div>
    </>
  );
};

const Options = ({ attributes, createUpdateAttribute, setAttributes, StyleOptions }) => {
  const [postTypes, setPostTypes] = useState({});

  useEffect(() => {
    // fetch post types
    wp.apiFetch({ path: '/dff/v1/post-feed/types' }).then(response => setPostTypes(response));
  }, []);

  const postTypeOptions = Object.keys(postTypes).map(type => ({
    label: postTypes[type].name,
    value: type,
  }));

  const taxonomies = Object.keys(postTypes).reduce((carry, current) => {
    if (attributes.postTypes.includes(current)) {
      return [...new Set([...carry, ...postTypes[current].supports])];
    }

    return carry;
  }, []);

  const createUpdateTaxonomy = taxonomy => ids =>
    setAttributes({
      taxonomies: {
        ...attributes.taxonomies,
        [taxonomy]: ids,
      },
    });

  const styleOptions = getStyleOptions();

  return (
    <InspectorControls>
      <PanelBody>
        <SelectControl
          options={styleOptions}
          onChange={createUpdateAttribute('style')}
          value={attributes.style || getDefaultStyle()}
        />
      </PanelBody>
      {!attributes.isSelectionMode && (
        <>
          <PanelBody title={__('Post Types', 'dff')}>
            <CheckBoxGroup
              options={postTypeOptions}
              selected={attributes.postTypes}
              onChange={createUpdateAttribute('postTypes')}
            />
          </PanelBody>
          {taxonomies.length > 0 && (
            <PanelBody title={__('Taxonomies', 'dff')}>
              {taxonomies.map(taxonomy => (
                <TaxonomySelector
                  key={taxonomy}
                  onUpdateTerms={createUpdateTaxonomy(taxonomy)}
                  terms={attributes?.taxonomies?.[taxonomy] || []}
                  slug={taxonomy}
                />
              ))}
            </PanelBody>
          )}
        </>
      )}
      {StyleOptions && (
        <StyleOptions
          createUpdateAttribute={createUpdateAttribute}
          setAttributes={setAttributes}
          attributes={attributes}
        />
      )}
      <PanelBody title={__('Additional Settings', 'dff')}>
        <PanelRow>
          <ToggleControl
            label={__('Selection Mode', 'dff')}
            checked={attributes.isSelectionMode}
            onChange={createUpdateAttribute('isSelectionMode')}
          />
        </PanelRow>
        {attributes.isSelectionMode && (
          <PanelRow>
            <ToggleControl
              label={__('Show Preview', 'dff')}
              checked={attributes.isPreview}
              onChange={createUpdateAttribute('isPreview')}
            />
          </PanelRow>
        )}
        <PanelRow>
          <TextControl
            label={__('Feed name for accessibility', 'dff')}
            onChange={createUpdateAttribute('ariaLabel')}
            value={attributes.ariaLabel}
            help={__(
              'Please provide a title for this feed for accesibility reasons. E.g Latest from future talks.',
              'dff',
            )}
          />
        </PanelRow>
        <PanelRow>
          <SelectControl
            label={__('Please select a heading tag to be used for the title.', 'dff')}
            onChange={createUpdateAttribute('headingTag')}
            options={[
              { value: 'h1', label: 'h1' },
              { value: 'h2', label: 'h2' },
              { value: 'h3', label: 'h3' },
              { value: 'h4', label: 'h4' },
            ]}
            value={attributes.headingTag}
          />
        </PanelRow>
        {attributes.style === 'banner' && (
          <>
            {attributes.backgroundId && (
              <PanelRow>
                <TextControl
                  label={__('Current Background URL', 'dff')}
                  value={attributes.backgroundUrl}
                  disabled
                />
              </PanelRow>
            )}
            <PanelRow>
              <MediaUploadCheck>
                <MediaUpload
                  title={__('Please choose a background image', 'dff')}
                  allowedTypes={['image/jpeg', 'image/png']}
                  onSelect={image => {
                    setAttributes({
                      backgroundId: image.id,
                      backgroundUrl: image.url,
                    });
                  }}
                  render={props => (
                    <>
                      <Button onClick={props.open} isSecondary>
                        {attributes.backgroundId ? 'Edit Image' : 'Add Image'}
                      </Button>
                      <Button
                        islink
                        isDestructive
                        onClick={() => {
                          setAttributes({
                            backgroundId: '',
                            backgroundUrl: '',
                          });
                        }}
                      >
                        Remove Image
                      </Button>
                    </>
                  )}
                  value={attributes.backgroundId}
                />
              </MediaUploadCheck>
            </PanelRow>
          </>
        )}
      </PanelBody>
    </InspectorControls>
  );
};

export default Options;
