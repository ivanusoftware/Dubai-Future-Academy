import classnames from 'classnames';

const { RichText } = wp.blockEditor;

const Save = ({ attributes: { items = [], isInverted } }) => (
  <ul
    className={classnames('icon-list', {
      [`has-${items.length}-items`]: true,
      isInverted,
    })}
  >
    {items.map(item => (
      <li>
        {item.image && (
          <figure>
            <img src={item.image.source_url} alt={item.image.alt} />
          </figure>
        )}
        {item.title && <RichText.Content tagName="h3" value={item.title} />}
        {item.content && <RichText.Content tagName="p" value={item.content} />}
      </li>
    ))}
  </ul>
);

export default Save;
