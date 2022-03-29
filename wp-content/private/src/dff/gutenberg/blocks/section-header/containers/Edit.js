import classnames from 'classnames';

const { InnerBlocks, RichText, InspectorControls } = wp.blockEditor;
const { __ } = wp.i18n;
const { PanelBody, ToggleControl } = wp.components;

const Edit = ({ attributes, setAttributes }) => {
  const createUpdateAttribute = key => value => setAttributes({ [key]: value });

  return (
    <>
      <InspectorControls>
        <PanelBody>
          <ToggleControl
            checked={attributes.isLarge}
            onChange={createUpdateAttribute('isLarge')}
            label={__('Larger content area', 'dff')}
          />
          <ToggleControl
            checked={attributes.isAlternate}
            onChange={createUpdateAttribute('isAlternate')}
            label={__('Alternate Style', 'dff')}
          />
        </PanelBody>
      </InspectorControls>
      <header
        className={classnames('section-header', {
          'is-alternate': attributes.isAlternate,
        })}
      >
        <InnerBlocks
          template={[
            [
              'core/heading',
              { className: 'section-titleHeading', placeholder: __('Section title', 'dff') },
            ],
          ]}
          templateLock="all"
        />
        <RichText
          tagName="p"
          className={classnames('section-subtitle', {
            'is-large': attributes.isLarge,
          })}
          value={attributes.content}
          onChange={createUpdateAttribute('content')}
          placeholder={__('(Section Content)', 'dff')}
          keepPlacholderOnFocus
        />
      </header>
    </>
  );
};

export default Edit;
