const spacingOptions = [0, 1, 2, 3, 4, 5].map(option => <option value={option}>{option}</option>);

const SpacingSelector = ({
  label,
  directions: { top = true, bottom = true, left = true, right = true, all = true } = {},
  value: spacing = {},
  onChange = () => {},
}) => {
  const handleChange = side => event => {
    if (side === 'all') {
      onChange({
        ...spacing,
        left: left ? event.target.value : 0,
        right: right ? event.target.value : 0,
        top: top ? event.target.value : 0,
        bottom: bottom ? event.target.value : 0,
        all: event.target.value,
      });

      return;
    }

    onChange({
      ...spacing,
      [side]: event.target.value,
      all: '',
    });
  };

  return (
    <div className="spacing-selector">
      {label && <label class="components-base-control__label">{label}</label>}

      <div className="spacing-selectorRow">
        {top && (
          <select value={spacing.top} onChange={handleChange('top')}>
            <option value="">-</option>
            {spacingOptions}
          </select>
        )}
      </div>
      <div className="spacing-selectorRow">
        {left && (
          <select value={spacing.left} onChange={handleChange('left')}>
            <option value="">-</option>
            {spacingOptions}
          </select>
        )}
        <div
          className="spacing-selectorVisual"
          style={{
            padding: `${(spacing.top || 0) * 2}px ${(spacing.right || 0) * 2}px ${(spacing.bottom ||
              0) * 2}px ${(spacing.left || 0) * 2}px`,
          }}
        >
          {all && (
            <select
              className="spacing-selectorAll"
              value={spacing.all}
              onChange={handleChange('all')}
            >
              <option value="">-</option>
              {spacingOptions}
            </select>
          )}
          <div />
        </div>
        {right && (
          <select value={spacing.right} onChange={handleChange('right')}>
            <option value="">-</option>
            {spacingOptions}
          </select>
        )}
      </div>
      <div className="spacing-selectorRow">
        {bottom && (
          <select value={spacing.bottom} onChange={handleChange('bottom')}>
            <option value="">-</option>
            {spacingOptions}
          </select>
        )}
      </div>
    </div>
  );
};

/**
 * Generates the spacing classes as object ot be used in `classnames` package.
 * Outputs classes in the format of:
 *
 * .u-mt--1
 * .u-ml--2
 *
 * See
 *
 * @param {String} type   // use `padding` or `margin`
 * @param {Object} values // e.g. { top: 1, bottom: 1, left: 1, right: 1}
 */
export const generateSpacingClassnames = (type, values) => ({
  ...Object.keys(values).reduce((carry, key) => {
    return {
      ...carry,
      [`u-${type.charAt(0)}${key.charAt(0)}${values[key]}`]: !!values[key],
    };
  }, {}),
});

export default SpacingSelector;
