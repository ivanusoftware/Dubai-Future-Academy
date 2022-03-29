import { isString } from 'lodash-es';

/* eslint-disable camelcase */
const getImageSrc = (size, images) => {
  let url = '';

  if (!images[size] || (!isString(images[size]) && !images[size]?.source_url)) {
    return isString(images.full) ? images.full : images.full?.source_url;
  }

  url = isString(images[size]) ? images[size] : images[size]?.source_url;

  return url;
};
/* eslint-enable camelcase */

const Save = ({ attributes }) => {
  if (!attributes.image) {
    return null;
  }

  const { sizes } = attributes.image.media_details;
  const widths = {
    'full-size-image-small': 480,
    'full-size-image-medium': 600,
    'full-size-image-large': 940,
    'full-size-image-xlarge': 1300,
  };
  const images = Object.keys(widths)
    .map(size => {
      const url = getImageSrc(size, sizes);
      if (!url) {
        return false;
      }

      return `${url} ${widths[size]}w`;
    })
    .join(', ');

  return (
    <img
      src={attributes.image.source_url}
      srcset={images}
      sizes="100vw"
      alt={attributes.image.alt}
    />
  );
};

export default Save;
