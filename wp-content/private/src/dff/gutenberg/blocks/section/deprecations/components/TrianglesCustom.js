const TriangleOne = ({ image, prefix }) => (
  <svg
    xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    className="triangle-custom-one"
    viewBox="0 0 473.912 406.211"
  >
    <defs>
      <pattern
        id={`${prefix}-a`}
        preserveAspectRatio="xMidYMid slice"
        width="100%"
        height="100%"
        viewBox={`0 0 ${image.media_details.width} ${image.media_details.height}`}
      >
        <image
          width={image.media_details.width}
          height={image.media_details.height}
          href={image.source_url}
        />
      </pattern>
    </defs>
    <path
      d="M236.956,0,473.912,406.211H0Z"
      transform="translate(473.912 406.211) rotate(180)"
      fill={`url(#${prefix}-a)`}
    />
  </svg>
);
const TriangleTwo = ({ image, prefix }) => (
  <svg
    className="triangle-custom-two"
    xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    viewBox="0 0 236.456 202.147"
  >
    <defs>
      <pattern
        id={`${prefix}-b`}
        preserveAspectRatio="xMidYMid slice"
        width="100%"
        height="100%"
        viewBox={`0 0 ${image.media_details.width} ${image.media_details.height}`}
      >
        <image
          width={image.media_details.width}
          height={image.media_details.height}
          href={image.source_url}
        />
      </pattern>
    </defs>
    <path d="M118.228,0,236.456,202.147H0Z" fill={`url(#${prefix}-b)`} />
  </svg>
);
const TriangleThree = ({ image, prefix }) => (
  <svg
    className="triangle-custom-three"
    xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    viewBox="0 0 236.456 202.147"
  >
    <defs>
      <pattern
        id={`${prefix}-c`}
        preserveAspectRatio="xMidYMid slice"
        width="100%"
        height="100%"
        viewBox={`0 0 ${image.media_details.width} ${image.media_details.height}`}
      >
        <image
          width={image.media_details.width}
          height={image.media_details.height}
          href={image.source_url}
        />
      </pattern>
    </defs>
    <path d="M118.228,0,236.456,202.147H0Z" fill={`url(#${prefix}-c)`} />
  </svg>
);

const Triangles = ({ imgOne, imgTwo, imgThree, prefix }) => (
  <div className="section-custom-triangles">
    {imgOne && <TriangleOne image={imgOne} prefix={prefix} />}
    {imgTwo && <TriangleTwo image={imgTwo} prefix={prefix} />}
    {imgThree && <TriangleThree image={imgThree} prefix={prefix} />}
  </div>
);

export default Triangles;
