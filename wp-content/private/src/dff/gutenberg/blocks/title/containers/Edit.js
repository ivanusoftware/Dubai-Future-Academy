import classnames from 'classnames';
import { TextAlign } from '../../../components';
import dfficon from '../decal/dff-icon';

const { RichText, InspectorControls } = wp.blockEditor;
const { __ } = wp.i18n;
const { PanelBody, ToggleControl } = wp.components;

const Edit = ({ attributes, setAttributes }) => {
  const createUpdateAttribute = key => value => setAttributes({ [key]: value });

  return (
    <>
      <InspectorControls>
        <PanelBody>
          <ToggleControl
            checked={attributes.hasDecal}
            onChange={createUpdateAttribute('hasDecal')}
            label={__('Has Graphic', 'dff')}
          />
          <span>Text Alignment</span>
          <TextAlign
            value={attributes.titleTextAlignment}
            onChange={createUpdateAttribute('titleTextAlignment')}
          />
        </PanelBody>
      </InspectorControls>
      <header>
        <h1
          className={classnames('section-title', {
            [`is-aligned-${attributes.titleTextAlignment}`]: !!attributes.titleTextAlignment,
            'has-decal': attributes.hasDecal,
          })}
        >
          <span>
            {attributes.hasDecal && dfficon}
            <RichText
              tagName="span"
              placeholder="Prefix"
              className="section-titlePrefix"
              keepPlaceholderOnFocus
              value={attributes.prefix}
              onChange={createUpdateAttribute('prefix')}
            />
            <RichText
              tagName="span"
              className="section-titleMain"
              placeholder="Title"
              keepPlaceholderOnFocus
              value={attributes.title}
              onChange={createUpdateAttribute('title')}
            />
          </span>
        </h1>
      </header>
    </>
  );
};

export default Edit;
