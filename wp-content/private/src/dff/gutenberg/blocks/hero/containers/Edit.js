import slugify from 'slugify';
import { PostMediaSelector, PositionSelector, TextAlign } from '../../../components';
import { getHeroComponent } from '../utils';

const { __ } = wp.i18n;
const { useEffect } = wp.element;
const { withSelect } = wp.data;
const { InspectorControls } = wp.blockEditor;
const {
  PanelBody,
  ToggleControl,
  SelectControl,
  TextControl,
  ColorPalette,
  Button,
} = wp.components;

const Edit = ({ attributes, setAttributes, anchors }) => {
  const createUpdateAttribute = key => value => setAttributes({ [key]: value });
  const createUpdateMedia = key => value => {
    // eslint-disable-next-line
    if (value?.yoast_head) {
      delete value.yoast_head; // eslint-disable-line
    }
    setAttributes({ [key]: value });
  };

  const setTitlePositon = ({
    vertical: titleVerticalPosition,
    horizontal: titleHorizontalPosition,
  }) =>
    setAttributes({
      titleHorizontalPosition,
      titleVerticalPosition,
    });

  useEffect(() => {
    if (
      !attributes.anchorTarget ||
      (attributes.anchorTarget && anchors.find(anchor => anchor.value === attributes.anchorTarget))
    ) {
      return;
    }

    setAttributes({
      anchorTarget: '',
    });
  }, [anchors]);

  const Component = getHeroComponent(attributes.variation);

  return (
    <>
      <InspectorControls>
        <PanelBody>
          <SelectControl
            label={__('Style', 'dff')}
            options={[
              // Options labels have been renamed. Values remain the same due to being
              // depended on in various places, and would require block deprecations.
              // Not in scope to adjust values and refactor at time of label rename.
              { value: 'home', label: 'Large Title' }, // Was: Home
              { value: 'quote', label: 'Quote' },
              { value: 'quote-content', label: 'Quote with button' }, // Was: Quote With Content
              { value: 'sbu', label: 'Large content' }, // Was: SUB
              { value: 'sbu-archive', label: 'Small content' }, // Was: SBU archive
            ]}
            value={attributes.variation}
            onChange={createUpdateAttribute('variation')}
          />

          <ToggleControl
            label={__('Show Breadcrumbs', 'dff')}
            checked={attributes.hasBreadcrumbs}
            onChange={createUpdateAttribute('hasBreadcrumbs')}
          />
        </PanelBody>
        <PanelBody
          title={
            attributes.variation !== 'sbu' ? __('Background Image', 'dff') : __('Background', 'dff')
          }
        >
          <PostMediaSelector
            onUpdate={createUpdateMedia('backgroundImage')}
            mediaId={attributes?.backgroundImage?.id}
            labels={{
              set: __('Set Background Image', 'dff'),
            }}
          />

          <hr />

          <SelectControl
            label={__('Background Vertical Position', 'dff')}
            options={[
              { value: '', label: 'Default (center)' },
              { value: 'top', label: 'Top' },
              { value: 'center', label: 'Center' },
              { value: 'bottom', label: 'Bottom' },
            ]}
            value={attributes.backgroundVerticalPosition}
            onChange={createUpdateAttribute('backgroundVerticalPosition')}
          />

          <hr />

          <label>Background Image Mobile</label>
          <PostMediaSelector
            onUpdate={createUpdateMedia('backgroundImageMobile')}
            mediaId={attributes?.backgroundImageMobile?.id}
            labels={{
              set: __('Set Background Image for Mobile', 'dff'),
            }}
          />

          {attributes.backgroundImageMobile?.id && (
            <div className="editor-backgroundColorMobile-container">
              <hr />

              <label>Background Color Mobile</label>
              <ColorPalette
                colors={[]}
                clearable={false}
                value={attributes.backgroundColorMobile}
                onChange={color => setAttributes({ backgroundColorMobile: color })}
              />

              <label className="editor-backgroundColorMobile-selectedLabel">
                {__('Selected Color', 'dff')}
              </label>
              <TextControl disabled value={attributes.backgroundColorMobile} />

              <Button isDefault onClick={() => setAttributes({ backgroundColorMobile: '' })}>
                {__('Clear Background Color', 'dff')}
              </Button>
            </div>
          )}

          <hr />

          <label>Background Video</label>
          <PostMediaSelector
            onUpdate={createUpdateMedia('backgroundVideo')}
            mediaId={attributes?.backgroundVideo?.id}
            labels={{
              set: __('Set Background Video', 'dff'),
            }}
            type="video"
          />

          <hr />
          <ToggleControl
            label={__('Dark Background', 'dff')}
            description={__('If the background is dark toggle this to flip the colors.', 'dff')}
            checked={attributes.darkBackground}
            onChange={createUpdateAttribute('darkBackground')}
          />
          {attributes.variation === 'home' && (
            <ToggleControl
              label={__('Home Shape Animation', 'dff')}
              checked={attributes.isAnimated}
              onChange={createUpdateAttribute('isAnimated')}
            />
          )}
        </PanelBody>
        <PanelBody title={__('Title Options', 'dff')}>
          <span>{__('Title Position', 'dff')}</span>
          <PositionSelector
            onChange={setTitlePositon}
            value={{
              vertical: attributes.titleVerticalPosition,
              horizontal: attributes.titleHorizontalPosition,
            }}
          />

          <span>Text Alignment</span>
          <TextAlign
            value={attributes.titleTextAlignment}
            onChange={createUpdateAttribute('titleTextAlignment')}
          />
        </PanelBody>
        {attributes.variation !== 'quote-content' && (
          <PanelBody title={__('Arrow Settings', 'dff')}>
            <SelectControl
              label={__('Click Option', 'dff')}
              options={[
                { label: __('Scroll', 'dff'), value: 'scroll' },
                { label: __('Link', 'dff'), value: 'link' },
              ]}
              value={attributes.clickOption}
              onChange={createUpdateAttribute('clickOption')}
            />

            {attributes.clickOption === 'scroll' && (
              <SelectControl
                label={__('Scroll To: ', 'dff')}
                options={[{ label: __('Below Hero', 'dff'), value: '' }, ...anchors]}
                value={attributes.anchorTarget}
                onChange={createUpdateAttribute('anchorTarget')}
              />
            )}

            {attributes.clickOption === 'link' && (
              <>
                <TextControl
                  label={__('Link To: ', 'dff')}
                  value={attributes.anchorUrl}
                  onChange={createUpdateAttribute('anchorUrl')}
                  type="url"
                />
                <ToggleControl
                  label={__('Open link in new tab', 'dff')}
                  checked={attributes.isLinkNewTab}
                  onChange={createUpdateAttribute('isLinkNewTab')}
                />
              </>
            )}

            {attributes.variation === 'quote' && (
              <ToggleControl
                label={__('Align Arrow Left', 'dff')}
                checked={attributes.isHeroSideLeft}
                onChange={createUpdateAttribute('isHeroSideLeft')}
              />
            )}
          </PanelBody>
        )}
      </InspectorControls>
      <Component attributes={attributes} createUpdateAttribute={createUpdateAttribute} />
    </>
  );
};
export default withSelect(select => {
  const blocks = select('core/block-editor').getBlocks();
  return {
    anchors: blocks
      .filter(block => block.name === 'dff/section' && block?.attributes?.isAnchor)
      .map(block => ({
        label: block.attributes.anchorName,
        value: slugify(block.attributes.anchorName, { lower: true }),
      })),
  };
})(Edit);
