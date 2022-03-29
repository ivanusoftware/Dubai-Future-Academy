import classnames from 'classnames';
import { getIcons } from './icons/getIcons';

const { useState } = wp.element;

const IconPicker = ({ value = '', onChange = () => {} }) => {
  const [isOpen, setIsOpen] = useState(false);
  const icons = getIcons();
  const createOnChange = key => () => {
    const icon = key === value ? '' : key;
    onChange(icon);
    setIsOpen(false);
  };

  const current = value && icons?.[value] ? icons?.[value]?.render : 'Empty';

  return (
    <div className="icon-picker">
      <button
        className={classnames('icon-pickerToggle', {
          'is-open': isOpen,
        })}
        onClick={() => setIsOpen(!isOpen)}
      >
        {current}
      </button>
      {isOpen && (
        <ul className="icon-pickerList">
          {Object.keys(icons).map(key => {
            return (
              <li>
                <button
                  className={classnames({ 'is-active': key === value })}
                  onClick={createOnChange(key)}
                >
                  {icons[key].render}
                </button>
              </li>
            );
          })}
        </ul>
      )}
    </div>
  );
};

export default IconPicker;
