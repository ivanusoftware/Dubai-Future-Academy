const { RichText } = wp.blockEditor;

const Save = ({ attributes }) => {
  const properties = {
    tagName: 'a',
    href: attributes.href,
    className: 'btn button--ghost',
    value: attributes.text,
    style: {
      backgroundColor: attributes.customBackgroundColour,
      color: attributes.customTextColour,
    },
  };

  if (attributes.isOpenInNewTab) {
    properties.target = '_blank';
    properties.rel = 'noopener noreferrer';
  }

  return <RichText.Content {...properties} />;
};

export default Save;
