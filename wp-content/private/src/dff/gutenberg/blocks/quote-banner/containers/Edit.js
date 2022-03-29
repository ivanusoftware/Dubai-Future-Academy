import classnames from 'classnames';
import { PostMediaSelector } from '../../../components';
import QuoteSettings from '../Components/QuoteSettings';
import QuoteEdit from '../Components/QuoteEdit';
import CallToActionSettings from '../Components/CallToActionSettings';
import CallToActionEdit from '../Components/CallToActionEdit';
import SpacingSelector, { generateSpacingClassnames } from '../../../components/SpacingSelector';

const { __ } = wp.i18n;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, ToggleControl, SelectControl } = wp.components;

const Edit = ({ attributes, setAttributes }) => {
  const createUpdateAttribute = key => value =>
    setAttributes({
      [key]: value,
    });

  let backgroundImage = false;

  if (attributes?.image?.id) {
    backgroundImage = attributes.image.source_url; // eslint-disable-line camelcase
  }

  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Settings', 'dff')}>
          <SelectControl
            label={__('Select banner style:', 'dff')}
            value={attributes.styleSelected}
            onChange={createUpdateAttribute('styleSelected')}
            options={[
              {
                value: 'quote',
                label: __('Quote', 'dff'),
              },
              {
                value: 'cta',
                label: __('Call To Action', 'dff'),
              },
            ]}
          />
          <PostMediaSelector
            onUpdate={createUpdateAttribute('image')}
            mediaId={attributes?.image?.id}
            labels={{
              set: __('Set Background Image', 'dff'),
            }}
          />
          <ToggleControl
            label="Image is dark"
            onChange={createUpdateAttribute('isDark')}
            checked={attributes.isDark}
          />
          <SpacingSelector
            label={__('Section margins', 'twoyou')}
            directions={{ left: false, right: false }}
            value={attributes.margin}
            onChange={createUpdateAttribute('margin')}
          />
        </PanelBody>
        <PanelBody title={__('Additional Settings', 'dff')}>
          {attributes.styleSelected === 'quote' && (
            <QuoteSettings
              isCite={attributes.isCite}
              isCiteJob={attributes.isCiteJob}
              createUpdateAttribute={createUpdateAttribute}
            />
          )}
          {attributes.styleSelected === 'cta' && (
            <CallToActionSettings
              ctaBtnHref={attributes.ctaBtnHref}
              createUpdateAttribute={createUpdateAttribute}
            />
          )}
        </PanelBody>
      </InspectorControls>
      <section
        className={classnames('section', {
          'section--dark': attributes.isDark,
          ...generateSpacingClassnames('margin', attributes.margin),
        })}
        style={
          backgroundImage && !attributes.containContentInside
            ? {
                backgroundImage: `url(${backgroundImage})`,
              }
            : {}
        }
      >
        <div className="container">
          {attributes.styleSelected === 'quote' && (
            <QuoteEdit attributes={attributes} createUpdateAttribute={createUpdateAttribute} />
          )}
          {attributes.styleSelected === 'cta' && (
            <CallToActionEdit
              attributes={attributes}
              createUpdateAttribute={createUpdateAttribute}
            />
          )}
        </div>
      </section>
    </>
  );
};

export default Edit;
