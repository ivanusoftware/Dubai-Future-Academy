import IconGroup from './IconGroup';

const TextAlign = ({ value, onChange }) => (
  <IconGroup
    options={[
      { value: 'left', icon: 'editor-alignleft', label: 'Start' },
      { value: 'center', icon: 'editor-aligncenter', label: 'Center' },
      { value: 'right', icon: 'editor-alignright', label: 'End' },
    ]}
    value={value}
    onChange={onChange}
  />
);

export default TextAlign;
