const Save = ({ attributes }) => {
  const { addressOne, height, zoom } = attributes;

  const buildString = () => {
    const addresses = [addressOne];
    return JSON.stringify(addresses);
  };

  const divStyle = {
    height: `${height}px`,
  };

  return (
    <section id={attributes.id}>
      <div className="section-wrapper">
        <div class="map" style={divStyle} data-zoom={zoom} data-locations={buildString()} />
      </div>
    </section>
  );
};

export default Save;
