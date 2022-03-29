import classnames from 'classnames';

const { InnerBlocks, RichText } = wp.blockEditor;

const Save = ({ attributes }) => (
  <header
    className={classnames('section-header', {
      'is-alternate': attributes.isAlternate,
    })}
  >
    <InnerBlocks.Content />
    <RichText.Content
      tagName="p"
      className={classnames('section-subtitle', {
        'is-large': attributes.isLarge,
      })}
      value={attributes.content}
    />
  </header>
);

export default Save;
