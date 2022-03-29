const { __ } = wp.i18n;
const { RichText, URLInputButton, InspectorControls } = wp.blockEditor;
const { ColorPalette, PanelBody, ToggleControl } = wp.components;

const Edit = ({ attributes, setAttributes, className }) => {
  const createUpdateAttribute = key => value =>
    setAttributes({
      [key]: value,
    });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Custom Button Settings', 'dff')} initialOpen={false}>
          <p>Button background color:</p>
          <ColorPalette
            value={attributes.customBackgroundColour}
            colors={[
              { name: __('Burgundy', 'dff'), color: '#55162e' },
              { name: __('Black', 'dff'), color: '#000' },
              { name: __('Red', 'dff'), color: '#e3011b' },
            ]}
            onChange={createUpdateAttribute('customBackgroundColour')}
          />
          <hr />
          <p>Button text color:</p>
          <ColorPalette
            value={attributes.customTextColour}
            colors={[
              { name: __('Black', 'dff'), color: '#000' },
              { name: __('White', 'dff'), color: '#fff' },
            ]}
            onChange={createUpdateAttribute('customTextColour')}
            disableCustomColors={true}
          />
          <ToggleControl
            label={__('Open link in new tab', 'dff')}
            checked={attributes.isOpenInNewTab}
            onChange={createUpdateAttribute('isOpenInNewTab')}
          />
        </PanelBody>
      </InspectorControls>
      <RichText
        tagName="span"
        className={`btn button--ghost ${className}`}
        value={attributes.text}
        onChange={createUpdateAttribute('text')}
        placeholder={__('Button Text', 'dff')}
        keepPlaceholderOnFocus
        style={{
          backgroundColor: attributes.customBackgroundColour,
          color: attributes.customTextColour,
        }}
      />
      <URLInputButton onChange={createUpdateAttribute('href')} url={attributes.href} />
    </>
  );
};

export default Edit;
