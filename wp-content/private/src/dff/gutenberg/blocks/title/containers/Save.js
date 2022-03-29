import classnames from 'classnames';
import dfficon from '../decal/dff-icon';

const { RichText } = wp.blockEditor;

const Save = ({ attributes }) => (
  <h1
    className={classnames('section-title', {
      [`is-aligned-${attributes.titleTextAlignment}`]: !!attributes.titleTextAlignment,
      'has-decal': attributes.hasDecal,
    })}
  >
    <span>
      {attributes.hasDecal && dfficon}
      <RichText.Content tagName="span" className="section-titlePrefix" value={attributes.prefix} />
      <RichText.Content tagName="span" className="section-titleMain" value={attributes.title} />
    </span>
  </h1>
);

export default Save;
