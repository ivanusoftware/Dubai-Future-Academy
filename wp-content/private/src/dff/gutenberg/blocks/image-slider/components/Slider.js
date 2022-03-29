import SliderItem from './SliderItem';

const { Fragment } = wp.element;
const { __ } = wp.i18n;

const Slider = ({ attributes, className }) => {
  return (
    <Fragment>
      <div className={`${className} imageSlider imageSlider--items-${attributes.itemsVisible}`}>
        {!attributes.images && __('No images selected, cannot display slider.', 'dff')}
        {attributes.images && (
          <div className="imageSlider-imageList">
            {attributes.images.map(image => (
              <SliderItem image={image} />
            ))}
          </div>
        )}
      </div>
    </Fragment>
  );
};

export default Slider;
