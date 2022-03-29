import { id } from '../../../utils';
import { TriangleOne, TriangleTwo } from '../components/TrianglesCustom';
import { PostMediaSelector } from '../../../components';

const { __ } = wp.i18n;
const { Fragment, useEffect } = wp.element;
const { InnerBlocks, RichText, InspectorControls } = wp.blockEditor;
const { PanelBody } = wp.components;

const Edit = ({ className, attributes, setAttributes }) => {
  const createUpdateAttribute = key => value => {
    setAttributes({
      [`${key}`]: value,
    });
  };

  useEffect(() => {
    if (!attributes.key) {
      setAttributes({
        key: id(),
      });
    }
  }, [true]);

  return (
    <Fragment>
      <InspectorControls>
        <PanelBody title={__('Settings', 'dff')}>
          <h3>Triangle One</h3>
          <PostMediaSelector
            onUpdate={createUpdateAttribute('triangleImageLarge')}
            mediaId={attributes?.triangleImageLarge?.id}
            labels={{
              set: __('Set Image Triangle Large', 'dff'),
            }}
          />
          <h3>Triangle Two</h3>
          <PostMediaSelector
            onUpdate={createUpdateAttribute('triangleImageSmall')}
            mediaId={attributes?.triangleImageSmall?.id}
            labels={{
              set: __('Set Image Triangle Small', 'dff'),
            }}
          />
        </PanelBody>
      </InspectorControls>
      <div className={`${className} triangleBlock`}>
        <div className="triangle-left">
          <header className="triangle-heading">
            <InnerBlocks templateLock="all" template={[['dff/title']]} />
          </header>
          <div className="triangle-columnOne">
            <RichText
              tagName="p"
              placeholder={__('Please enter your text', 'dff')}
              value={attributes.textColumnOne}
              onChange={value => setAttributes({ textColumnOne: value })}
            />
          </div>
          <div className="triangle-columnTwo">
            <RichText
              tagName="p"
              placeholder={__('Please enter your text', 'dff')}
              value={attributes.textColumnTwo}
              onChange={value => setAttributes({ textColumnTwo: value })}
            />
          </div>
          <div className="triangle-columnThree">
            <RichText
              tagName="p"
              placeholder={__('Please enter your text', 'dff')}
              value={attributes.textColumnThree}
              onChange={value => setAttributes({ textColumnThree: value })}
            />
          </div>
        </div>
        <div className="triangle-right">
          {attributes?.triangleImageLarge?.id && (
            <div class="triangle-image triangle-image--large">
              <TriangleOne image={attributes.triangleImageLarge} prefix={attributes.key} />
            </div>
          )}
          {attributes?.triangleImageSmall?.id && (
            <div class="triangle-image triangle-image--small">
              <TriangleTwo image={attributes.triangleImageSmall} prefix={attributes.key} />
            </div>
          )}
        </div>
      </div>
    </Fragment>
  );
};

export default Edit;
