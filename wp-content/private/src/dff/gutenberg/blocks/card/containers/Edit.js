import { PostMediaSelector, IconPicker } from '../../../components';

const { RichText, URLInputButton, InspectorControls } = wp.blockEditor;
const { ToggleControl, PanelRow, PanelBody, RadioControl, SelectControl } = wp.components;
const { __ } = wp.i18n;

const Edit = ({ attributes, setAttributes }) => {
  const createUpdateAttribute = key => value => setAttributes({ [key]: value });
  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Settings', 'dff')}>
          <PanelRow>
            <RadioControl
              selected={attributes.cardAlignment}
              label={__('Layout', 'dff')}
              options={[
                {
                  label: __('Default', 'dff'),
                  value: '',
                },
                {
                  label: __('Image Left', 'dff'),
                  value: 'articleCard--alignLeft',
                },
                {
                  label: __('Image Right', 'dff'),
                  value: 'articleCard--alignRight',
                },
              ]}
              onChange={createUpdateAttribute('cardAlignment')}
            />
          </PanelRow>
          {attributes.cardAlignment !== '' && (
            <PanelRow>
              <ToggleControl
                checked={attributes.cardAlignmentSecondary}
                label={__('Align content to top', 'dff')}
                onChange={createUpdateAttribute('cardAlignmentSecondary')}
              />
            </PanelRow>
          )}
          <PanelRow>
            <SelectControl
              value={attributes.titleLevel}
              label={__('Please select a heading level: (h1 to h4)', 'dff')}
              options={[{ label: 'h1' }, { label: 'h2' }, { label: 'h3' }, { label: 'h4' }]}
              onChange={createUpdateAttribute('titleLevel')}
            />
          </PanelRow>
          <PanelRow>
            <ToggleControl
              checked={attributes.isIcon}
              label={__('Enable Category Icon', 'dff')}
              onChange={createUpdateAttribute('isIcon')}
            />
          </PanelRow>
          <PanelRow>
            <ToggleControl
              checked={attributes.isCategory}
              label={__('Enable Category Text', 'dff')}
              onChange={createUpdateAttribute('isCategory')}
            />
          </PanelRow>
          <PanelRow>
            <ToggleControl
              checked={attributes.isButton}
              label={__('Enable Button', 'dff')}
              onChange={createUpdateAttribute('isButton')}
            />
          </PanelRow>
          <PanelRow>
            <ToggleControl
              checked={attributes.isPadding}
              label={__('Padding', 'dff')}
              onChange={createUpdateAttribute('isPadding')}
            />
          </PanelRow>
          <PanelRow>
            <ToggleControl
              checked={attributes.hasBorder}
              label={__('Border', 'dff')}
              onChange={createUpdateAttribute('hasBorder')}
            />
          </PanelRow>
        </PanelBody>
      </InspectorControls>
      <article
        className={`articleCard ${attributes.cardAlignment}${
          attributes.cardAlignmentSecondary && attributes.cardAlignment !== ''
            ? ' articleCard--alignTop'
            : ''
        }${attributes.hasBorder ? ' hasBorder' : ''}`}
      >
        <figure>
          <PostMediaSelector
            size="card"
            mediaId={attributes.media?.id}
            onUpdate={createUpdateAttribute('media')}
          />
        </figure>
        <div
          className={`articleCard-contentContainer${
            !attributes.isPadding ? ' articleCard--noPadding' : ''
          }`}
        >
          <header className="articleCard-header">
            {(attributes.isIcon || attributes.isCategory) && (
              <span className="articleCard-category">
                {attributes.isIcon && (
                  <IconPicker value={attributes.icon} onChange={createUpdateAttribute('icon')} />
                )}
                {attributes.isCategory && (
                  <RichText
                    tagName="span"
                    placeholder={__('(Category)', 'dff')}
                    keepPlaceholderOnFocus
                    onChange={createUpdateAttribute('category')}
                    value={attributes.category}
                  />
                )}
              </span>
            )}
            <RichText
              className="articleCard-title"
              tagName={attributes.titleLevel}
              placeholder={__('(Card Title)', 'dff')}
              keepPlaceholderOnFocus
              onChange={createUpdateAttribute('title')}
              value={attributes.title}
            />
          </header>
          <RichText
            className="articleCard-content"
            tagName="p"
            placeholder={__('(Content)', 'dff')}
            keepPlaceholderOnFocus
            onChange={createUpdateAttribute('content')}
            value={attributes.content}
          />
          {attributes.isButton && (
            <>
              <RichText
                tagName="span"
                className="btn button--ghost"
                placeholder={__('(Button text)', 'dff')}
                keepPlaceholderOnFocus
                onChange={createUpdateAttribute('btnText')}
                value={attributes.btnText}
              />
              <URLInputButton
                onChange={createUpdateAttribute('btnHref')}
                url={attributes.btnHref}
              />
            </>
          )}
        </div>
      </article>
    </>
  );
};

export default Edit;
