import { getHeroComponent } from '../utils';

const Save = ({ attributes }) => {
  const Component = getHeroComponent(attributes.variation);

  const hrefGenerator = options => {
    if (options.clickOption === 'scroll') {
      return options.anchorTarget ? `#${attributes.anchorTarget}` : '#content';
    }
    return options.anchorUrl || '#';
  };
  return (
    <>
      <Component.Save attributes={attributes} hrefGenerator={hrefGenerator} />
      <div id="content"></div>
    </>
  );
};

export default Save;
