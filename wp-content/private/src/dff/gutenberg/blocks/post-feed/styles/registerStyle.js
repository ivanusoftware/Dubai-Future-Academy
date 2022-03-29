const styles = [];

const registerStyle = (name, component) => {
  styles[name] = component;
};

export const getDefaultStyle = () => {
  const defaultStyle = window.postFeedStyles.find(style => style.default);
  return defaultStyle?.style || false;
};

export const getStyle = name => {
  if (styles[name]) {
    return styles[name];
  }

  const defaultStyle = getDefaultStyle();

  if (defaultStyle && styles[defaultStyle]) {
    return styles[defaultStyle];
  }

  return false;
};

export const getStyleOptions = () =>
  window.postFeedStyles.map(({ name: label, style: value }) => ({ value, label }));

export default registerStyle;
