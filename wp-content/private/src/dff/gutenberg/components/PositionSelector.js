import classnames from 'classnames';
import { isEqual } from 'lodash-es';

const { Tooltip } = wp.components;

const defaultOptions = [
  [
    { value: { vertical: 'top', horizontal: 'left' }, label: 'Top Left' },
    { value: { vertical: 'top', horizontal: 'center' }, label: 'Top Center' },
    { value: { vertical: 'top', horizontal: 'right' }, label: 'Top Right' },
  ],
  [
    { value: { vertical: 'middle', horizontal: 'left' }, label: 'Center Left' },
    { value: { vertical: 'middle', horizontal: 'center' }, label: 'Center Center' },
    { value: { vertical: 'middle', horizontal: 'right' }, label: 'Center Right' },
  ],
  [
    { value: { vertical: 'bottom', horizontal: 'left' }, label: 'Bottom Left' },
    { value: { vertical: 'bottom', horizontal: 'center' }, label: 'Bottom Center' },
    { value: { vertical: 'bottom', horizontal: 'right' }, label: 'Bottom Right' },
  ],
];

const PositionSelector = ({
  value = { vertical: 'middle', horizontal: 'center' },
  onChange,
  options = defaultOptions,
}) => (
  <div className="positionSelector">
    {options.map(rows => (
      <div className="positionSelector-row">
        {rows.map(option => (
          <Tooltip text={option.label}>
            <button
              onClick={() => onChange(option.value)}
              className={classnames(
                'positionSelector-item',
                `${option.value.vertical}-${option.value.horizontal}`,
                {
                  'is-selected': isEqual(option.value, value),
                },
              )}
            >
              <span className="u-hiddenVisually">{option.label}</span>
            </button>
          </Tooltip>
        ))}
      </div>
    ))}
  </div>
);

export default PositionSelector;
