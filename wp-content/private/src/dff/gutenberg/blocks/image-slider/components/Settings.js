const { InspectorControls, MediaUploadCheck, MediaUpload } = wp.blockEditor;
const { PanelBody, PanelRow, RangeControl, Button } = wp.components;
const { __ } = wp.i18n;

const Settings = ({ attributes, setAttributes }) => {
  return (
    <InspectorControls>
      <PanelBody title={__('Settings', 'dff')}>
        <PanelRow>
          <RangeControl
            label={__('Number of items visible', 'dff')}
            value={attributes.itemsVisible}
            min="1"
            max="3"
            onChange={value => setAttributes({ itemsVisible: value })}
          />
        </PanelRow>
        <MediaUploadCheck>
          <PanelRow>
            <MediaUpload
              title={__('Please select your images', 'dff')}
              allowedTypes={['image/gif', 'image/jpeg', 'image/png']}
              onSelect={selectedImages => {
                setAttributes({
                  images: selectedImages,
                });
              }}
              render={props => (
                <Button onClick={props.open} isSecondary>
                  {attributes.images ? __('Edit Images', 'dff') : __('Add Images', 'dff')}
                </Button>
              )}
              value={attributes.images ? attributes.images.map(image => image.id) : false}
              multiple
              gallery
            />
          </PanelRow>
        </MediaUploadCheck>
      </PanelBody>
    </InspectorControls>
  );
};

export default Settings;
