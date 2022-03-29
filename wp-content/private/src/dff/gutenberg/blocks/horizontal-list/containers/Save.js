import classnames from 'classnames';
import arrowRight from '../../../components/icons/arrow-right';

const { RichText } = wp.blockEditor;

const Save = ({
  attributes: { items = [], alignment, isColumnControl, isFixedArrow, columns },
}) => (
  <ol
    className={classnames('horizontal-list', {
      [`${alignment}`]: alignment,
      'horizontal-list--columnControlled': isColumnControl,
      'horizontal-list--fixedArrow': isFixedArrow,
    })}
  >
    {items.map((item, index) => (
      <li className={`horizontal-list-item--columns-${columns}`}>
        <span aria-hidden="true">{`${index + 1}`.padStart(2, '0')}</span>
        <header>
          {item.href ? (
            <h1>
              <RichText.Content tagName="a" href={item.href} value={item.title} />
            </h1>
          ) : (
            <RichText.Content tagName="h1" value={item.title} />
          )}
        </header>
        <RichText.Content tagName="p" value={item.content} />
        {item.href && (
          <a href={item.href}>
            <RichText.Content className="u-hiddenVisually" tagName="span" value={item.title} />
            {arrowRight}
          </a>
        )}
      </li>
    ))}
  </ol>
);

export default Save;
