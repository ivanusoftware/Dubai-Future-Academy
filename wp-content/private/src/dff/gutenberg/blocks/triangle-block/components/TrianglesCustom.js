/* eslint-disable camelcase */
export const TriangleOne = ({ image, prefix }) => (
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    className="triangle-custom-one"
    viewBox="0 0 473.912 406.211"
    alt={image?.alt || ''}
  >
    <defs>
      <pattern
        id={`${prefix}-a`}
        preserveAspectRatio="xMidYMid slice"
        width="100%"
        height="100%"
        viewBox={`0 0 ${image?.media_details?.width} ${image?.media_details?.height}`}
      >
        <image
          width={image?.media_details?.width}
          height={image?.media_details?.height}
          href={image?.source_url}
        />
      </pattern>
    </defs>
    <path
      d="M236.956 512 473.912 105.789H0Z"
      transform="translate(0, -106)"
      fill={`url(#${prefix}-a)`}
    />
  </svg>
);
export const TriangleTwo = ({ image, prefix }) => (
  <svg
    className="triangle-custom-two"
    xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    viewBox="0 0 236.456 202.147"
    alt={image?.alt || ''}
  >
    <defs>
      <pattern
        id={`${prefix}-b`}
        preserveAspectRatio="xMidYMid slice"
        width="100%"
        height="100%"
        viewBox={`0 0 ${image?.media_details?.width} ${image?.media_details?.height}`}
      >
        <image
          width={image?.media_details?.width}
          height={image?.media_details?.height}
          href={image?.source_url}
        />
      </pattern>
    </defs>
    <path d="M118.228,0,236.456,202.147H0Z" fill={`url(#${prefix}-b)`} />
  </svg>
);
/* eslint-enable camelcase */
