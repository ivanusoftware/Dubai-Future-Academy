import classnames from 'classnames';

const { InnerBlocks } = wp.blockEditor;

const Save = ({ attributes }) => (
  <div
    className={classnames(attributes.className, {
      [`is-aligned--${attributes?.columnAlignment?.vertical}`]: attributes.columnAlignment
        ?.vertical,
      'has-pad-left': attributes.paddingLeft,
      'has-pad-right': attributes.paddingRight,
    })}
  >
    <InnerBlocks.Content />
  </div>
);

export default Save;
