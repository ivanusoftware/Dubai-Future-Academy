import classnames from 'classnames';

const { InnerBlocks } = wp.blockEditor;

const Save = ({ attributes }) => (
  <div
    className={classnames('flexy-wrapper', {
      'is-spaced': attributes.isSpaced,
      [`is-aligned--${attributes?.columnAlignment?.vertical}`]: attributes.columnAlignment
        ?.vertical,
      'is-reversed-mobile': attributes.isReversedMobile,
    })}
  >
    <InnerBlocks.Content />
  </div>
);

export default Save;
