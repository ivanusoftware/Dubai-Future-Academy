import classnames from 'classnames';
import TriangleOne from '../components/TriangleOne';
import TriangleTwo from '../components/TriangleTwo';
import TrianglesCustom from '../components/TrianglesCustom';
import BackgroundColours from '../components/BackgroundColours';
import { PostMediaSelector } from '../../../components';
import SpacingSelector, { generateSpacingClassnames } from '../../../components/SpacingSelector';
import { id } from '../../../utils';

const { useEffect } = wp.element;
const { InnerBlocks, InspectorControls } = wp.blockEditor;
const {
  SelectControl,
  ToggleControl,
  PanelBody,
  PanelRow,
  TextControl,
  ColorPalette,
  RangeControl,
  ColorIndicator,
} = wp.components;
const { __ } = wp.i18n;

const sizeOptions = [
  { label: __('Default', 'dff'), value: 'default' },
  { label: __('No Padding', 'dff'), value: 'no-padding' },
  { label: __('Large', 'dff'), value: 'large' },
];

const colorOptions = [
  { label: __('Default', 'dff'), value: 'default' },
  { label: __('Dark', 'dff'), value: 'dark' },
];

const triangleOptions = [
  { label: __('None', 'dff'), value: 'none' },
  { label: __('Small', 'dff'), value: 'small' },
  { label: __('Large', 'dff'), value: 'large' },
  { label: __('Custom', 'dff'), value: 'custom' },
];

const trianglePositionOptions = [
  { label: __('Start', 'dff'), value: 'start' },
  { label: __('End', 'dff'), value: 'end' },
];

const widthOptions = [
  { label: __('12 Columns', 'dff'), value: '' },
  { label: __('10 Columns', 'dff'), value: '10' },
  { label: __('8 Columns', 'dff'), value: '8' },
  { label: __('6 Columns', 'dff'), value: '6' },
];

const alignmentOptions = [
  { label: __('Left', 'dff'), value: '' },
  { label: __('Center', 'dff'), value: 'center' },
  { label: __('Right', 'dff'), value: 'right' },
];

const Edit = ({ attributes, setAttributes }) => {
  const createUpdateAttribute = key => value => setAttributes({ [key]: value });

  useEffect(() => {
    if (!attributes.key) {
      setAttributes({
        key: id(),
      });
    }
  }, [true]);

  let backgroundImage = false;

  if (attributes?.background?.id) {
    backgroundImage = attributes.background.source_url; // eslint-disable-line camelcase
  }

  const inlineStyleBuilder = () => {
    const inlineStyleObject = {};

    if (backgroundImage && !attributes.containContentInside) {
      inlineStyleObject.backgroundImage = `url(${backgroundImage})`;
    }

    if (attributes.isNegative) {
      inlineStyleObject.paddingBottom = `${attributes.negativeSpace}px`;
      inlineStyleObject.marginBottom = `-${attributes.negativeSpace}px`;
    }

    return inlineStyleObject;
  };

  const isAlreadyInPalette = () => {
    return BackgroundColours.some(item => item.color === attributes.backgroundColourDark);
  };

  return (
    <>
      <InspectorControls>
        <PanelBody>
          <SelectControl
            label={__('Content Width:', 'dff')}
            options={widthOptions}
            value={attributes.width}
            onChange={createUpdateAttribute('width')}
          />
          <SelectControl
            label={__('Content Alignment:', 'dff')}
            options={alignmentOptions}
            value={attributes.alignment}
            onChange={createUpdateAttribute('alignment')}
          />
          <SelectControl
            label={__('Size:', 'dff')}
            options={sizeOptions}
            value={attributes.size}
            onChange={createUpdateAttribute('size')}
          />
          <SpacingSelector
            label={__('Section margins', 'twoyou')}
            directions={{ left: false, right: false }}
            value={attributes.margin}
            onChange={createUpdateAttribute('margin')}
          />
          <SelectControl
            label={__('Color:', 'dff')}
            options={colorOptions}
            value={attributes.color}
            onChange={createUpdateAttribute('color')}
          />
          {attributes.color !== 'dark' && (
            <ToggleControl
              label={__('Has border', 'dff')}
              checked={attributes.border}
              onChange={createUpdateAttribute('border')}
            />
          )}
          <ToggleControl
            label={__('No Padding on Mobile', 'dff')}
            checked={attributes.noPaddingOnMobile}
            onChange={createUpdateAttribute('noPaddingOnMobile')}
          />
          <SelectControl
            label={__('Triangle Option:', 'dff')}
            options={triangleOptions}
            value={attributes.triangle}
            onChange={createUpdateAttribute('triangle')}
          />
          {attributes.triangle === 'small' && (
            <SelectControl
              label={__('Triangle Position:', 'dff')}
              options={trianglePositionOptions}
              value={attributes.trianglePosition}
              onChange={createUpdateAttribute('trianglePosition')}
            />
          )}
          <hr />
          <p>{__('Background Colour:', 'dff')}</p>
          <ColorPalette
            value={attributes.backgroundColour}
            colors={BackgroundColours}
            onChange={createUpdateAttribute('backgroundColour')}
          />
          {attributes.backgroundColour && (
            <>
              <hr />
              <ToggleControl
                label={__('Background Colour Dark Mode', 'dff')}
                help={__('Sets a different background colour for dark theme mode.', 'dff')}
                checked={attributes.isBackgroundColourDarkMode}
                onChange={createUpdateAttribute('isBackgroundColourDarkMode')}
              />
              {attributes.isBackgroundColourDarkMode && (
                <>
                  <ColorPalette
                    value={attributes.backgroundColourDark}
                    colors={BackgroundColours}
                    onChange={createUpdateAttribute('backgroundColourDark')}
                  />
                  {attributes.backgroundColourDark && !isAlreadyInPalette() && (
                    <p>
                      <ColorIndicator colorValue={attributes.backgroundColourDark} />
                    </p>
                  )}
                </>
              )}
            </>
          )}
          <hr />
          <PostMediaSelector
            onUpdate={createUpdateAttribute('background')}
            mediaId={attributes?.background?.id}
            labels={{
              set: __('Set Background Image', 'dff'),
            }}
          />
          <ToggleControl
            label="Contain content inside of image"
            onChange={createUpdateAttribute('containContentInside')}
            checked={attributes.containContentInside}
          />
        </PanelBody>
        <PanelBody title={__('Anchor', 'dff')}>
          <ToggleControl
            label={__('Is Anchor', 'dff')}
            help={__('Allow the hero to link to this section', 'dff')}
            checked={attributes.isAnchor}
            onChange={createUpdateAttribute('isAnchor')}
          />
          {attributes.isAnchor && (
            <TextControl
              label={__('Section Name', 'dff')}
              help={__(
                'This will be used to identify the section within the header options',
                'dff',
              )}
              value={attributes.anchorName}
              onChange={createUpdateAttribute('anchorName')}
            />
          )}
        </PanelBody>
        <PanelBody title={__('Negative Settings', 'dff')}>
          <ToggleControl
            label={__('Enable', 'dff')}
            help={__('This will enable the negative margin mode for this section', 'dff')}
            checked={attributes.isNegative}
            onChange={createUpdateAttribute('isNegative')}
          />
          {attributes.isNegative && (
            <PanelRow>
              <RangeControl
                label={__('Negative Space:', 'dff')}
                value={attributes.negativeSpace}
                min={0}
                max={1000}
                onChange={createUpdateAttribute('negativeSpace')}
              />
            </PanelRow>
          )}
        </PanelBody>
        {attributes.triangle === 'custom' && (
          <PanelBody title={__('Triangle Images', 'dff')}>
            <h3>Triangle One</h3>
            <PostMediaSelector
              onUpdate={createUpdateAttribute('imgOne')}
              mediaId={attributes?.imgOne?.id}
              labels={{
                set: __('Set Image Triangle One', 'dff'),
              }}
            />
            <h3>Triangle Two</h3>
            <PostMediaSelector
              onUpdate={createUpdateAttribute('imgTwo')}
              mediaId={attributes?.imgTwo?.id}
              labels={{
                set: __('Set Image Triangle Two', 'dff'),
              }}
            />
            <h3>Triangle Three</h3>
            <PostMediaSelector
              onUpdate={createUpdateAttribute('imgThree')}
              mediaId={attributes?.imgThree?.id}
              labels={{
                set: __('Set Image Triangle Three', 'dff'),
              }}
            />
          </PanelBody>
        )}
      </InspectorControls>
      {attributes.backgroundColour && (
        <style>
          {`.section--${attributes.key} { background-color: ${attributes.backgroundColour}; }`}
        </style>
      )}
      <section
        className={classnames('section', {
          [`section--${attributes.size}`]: attributes.size && attributes.size !== 'default',
          [`section--${attributes.color}`]: attributes.color && attributes.color !== 'default',
          [`section--border`]: attributes.color !== 'dark' && attributes.border,
          [`section--${attributes.width}columns`]: attributes.width,
          [`section--alignment-${attributes.alignment}`]: attributes.alignment,
          [`has-${attributes.triangle}-triangle`]: attributes.triangle !== 'none',
          [`align-triangle--${attributes.trianglePosition}`]:
            !attributes.containContentInside &&
            attributes.triangle === 'small' &&
            attributes.trianglePosition !== 'start',
          'content-is-contained': attributes.containContentInside,
          ...generateSpacingClassnames('margin', attributes.margin),
          [`section--${attributes.key}`]: attributes.key,
        })}
        style={inlineStyleBuilder()}
      >
        {!attributes.containContentInside && (
          <>
            {attributes.triangle === 'small' && <TriangleOne />}
            {attributes.triangle === 'large' && <TriangleTwo />}
            {attributes.triangle === 'custom' && (
              <TrianglesCustom
                imgOne={attributes.imgOne}
                imgTwo={attributes.imgTwo}
                imgThree={attributes.imgThree}
                prefix={attributes.key}
              />
            )}
          </>
        )}
        {attributes.containContentInside ? (
          <>
            {backgroundImage && (
              <figure
                className="section-background"
                style={{
                  backgroundImage: `url(${backgroundImage})`,
                }}
              ></figure>
            )}
            <div className="container">
              <div
                className={classnames('section-innerContent', {
                  'has-background': !!backgroundImage,
                })}
              >
                <InnerBlocks />
              </div>
            </div>
          </>
        ) : (
          <div className="container">
            <InnerBlocks />
          </div>
        )}
      </section>
    </>
  );
};

export default Edit;
