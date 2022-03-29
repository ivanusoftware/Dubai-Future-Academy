import classnames from 'classnames';
import arrowRight from '../../../components/icons/arrow-right';

const { RichText } = wp.blockEditor;

export default {
  attributes: {
    items: {
      type: 'array',
      default: [],
    },
    alignment: {
      type: 'string',
      default: '',
    },
    isFixedArrow: {
      type: 'boolean',
      default: false,
    },
    isColumnControl: {
      type: 'boolean',
      default: false,
    },
    columns: {
      type: 'number',
      default: 5,
    },
  },
  save: ({ attributes: { items = [], alignment, isColumnControl, isFixedArrow, columns } }) => (
    <ol
      className={classnames('horizontal-list', {
        [`${alignment}`]: alignment,
        'horizontal-list--columnControlled': isColumnControl,
        'horizontal-list--fixedArrow': isFixedArrow,
      })}
    >
      {items.map((item, index) => (
        <li className={`horizontal-list-item--columns-${columns}`}>
          <span>{`${index + 1}`.padStart(2, '0')}</span>
          {item.href ? (
            <h3>
              <RichText.Content tagName="a" href={item.href} value={item.title} />
            </h3>
          ) : (
            <RichText.Content tagName="h3" value={item.title} />
          )}

          <RichText.Content tagName="p" value={item.content} />
          {item.href && <a href={item.href}>{arrowRight}</a>}
        </li>
      ))}
    </ol>
  ),
};
