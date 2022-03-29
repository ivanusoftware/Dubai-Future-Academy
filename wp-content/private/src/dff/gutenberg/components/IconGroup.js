import classnames from 'classnames';

const { Tooltip, Icon } = wp.components;

const IconGroup = ({ options, value, onChange }) => (
  <div className="iconGroup">
    {options.map(option => (
      <Tooltip text={option.label}>
        <button
          type="button"
          className={classnames('iconGroupItem', {
            'is-selected': value === option.value,
          })}
          onClick={() => onChange(option.value)}
        >
          <Icon icon={option.icon} />
        </button>
      </Tooltip>
    ))}
  </div>
);

export default IconGroup;
