import classnames from 'classnames';
import slugify from 'slugify';
import TriangleOne from '../components/TriangleOne';
import TriangleTwo from '../components/TriangleTwo';
import TrianglesCustom from '../components/TrianglesCustom';
import { generateSpacingClassnames } from '../../../components/SpacingSelector';

const { InnerBlocks } = wp.blockEditor;

const Save = ({ attributes }) => {
  let backgroundImage = false;

  if (attributes.background?.id) {
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

  return (
    <section
      id={
        attributes.isAnchor && attributes.anchorName
          ? slugify(attributes.anchorName, {
              lower: true,
            })
          : false
      }
      className={classnames('section', {
        [`section--${attributes.size}`]: attributes.size && attributes.size !== 'default',
        [`section--${attributes.color}`]: attributes.color && attributes.color !== 'default',
        [`section--${attributes.width}columns`]: attributes.width,
        [`section--alignment-${attributes.alignment}`]: attributes.alignment,
        [`has-${attributes.triangle}-triangle`]: attributes.triangle !== 'none',
        [`has-no-mobile-padding`]: attributes.noPaddingOnMobile,
        [`section--border`]: attributes.color !== 'dark' && attributes.border,
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
      <div className="container">
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
                <InnerBlocks.Content />
              </div>
            </div>
          </>
        ) : (
          <div className="container">
            <InnerBlocks.Content />
          </div>
        )}
      </div>
      {attributes.backgroundColour && (
        <style>
          {`.section--${attributes.key} { background-color: ${attributes.backgroundColour}; }`}
          {attributes.backgroundColourDark &&
            attributes.isBackgroundColourDarkMode &&
            `html.dark-mode .section--${attributes.key} { background-color: ${attributes.backgroundColourDark}; }`}
        </style>
      )}
    </section>
  );
};
export default Save;
