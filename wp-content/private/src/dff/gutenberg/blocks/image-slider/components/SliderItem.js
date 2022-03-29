const SliderItem = ({ image }) => {
  return (
    <div key={image.id} className="imageList-imageItem">
      <img src={image.url} alt={image.alt} />
    </div>
  );
};

export default SliderItem;
