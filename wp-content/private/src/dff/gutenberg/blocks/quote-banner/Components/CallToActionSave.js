const { Fragment } = wp.element;
const { RichText } = wp.blockEditor;

const CallToActionSave = ({ attributes }) => {
  return (
    <Fragment>
      <div className="callToAction">
        <h3>
          <RichText.Content
            className="callToAction-headingText"
            tagName="span"
            value={attributes.text}
          />
        </h3>
        <p>
          <RichText.Content
            tagName="a"
            className="btn button--cta"
            href={attributes.ctaButtonHref}
            value={attributes.ctaButtonText}
          />
        </p>
      </div>
    </Fragment>
  );
};

export default CallToActionSave;
