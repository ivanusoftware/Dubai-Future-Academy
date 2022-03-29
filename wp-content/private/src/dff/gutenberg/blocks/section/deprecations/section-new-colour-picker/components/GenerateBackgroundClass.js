const GenerateBackgroundClass = (hex, colours) => {
  const colourLabel = colours.find(colour => colour.color === hex);
  return colourLabel?.name?.replace(' ', '');
};

export default GenerateBackgroundClass;
