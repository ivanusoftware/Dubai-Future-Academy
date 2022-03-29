const { RichText, URLInputButton } = wp.blockEditor;
const { Fragment } = wp.element;
const { __ } = wp.i18n;

const CallToActionEdit = ({ attributes, createUpdateAttribute }) => {
  return (
    <Fragment>
      <div className="callToAction">
        <h3>
          <RichText
            tagName="span"
            placeholder={__('Text', 'dff')}
            className="callToAction-headingText"
            value={attributes.text}
            onChange={createUpdateAttribute('text')}
          />
        </h3>
        <p>
          <URLInputButton
            onChange={createUpdateAttribute('ctaButtonHref')}
            url={attributes.ctaButtonHref}
          />
          <RichText
            tagName="span"
            className="btn button--cta"
            placeholder={__('(Button text)', 'dff')}
            onChange={createUpdateAttribute('ctaButtonText')}
            value={attributes.ctaButtonText}
          />
        </p>
      </div>
    </Fragment>
  );
};

export default CallToActionEdit;
