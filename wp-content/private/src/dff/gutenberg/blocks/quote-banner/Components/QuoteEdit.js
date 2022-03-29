import quote from './quote';

const { RichText } = wp.blockEditor;

const QuoteEdit = ({ attributes, createUpdateAttribute }) => {
  return (
    <blockquote className="quoteBanner">
      <figure>{quote}</figure>
      <div>
        <RichText
          tagName="p"
          placeholder="Quote"
          keepPlaceholderOnFocus
          value={attributes.text}
          onChange={createUpdateAttribute('text')}
        />
        {(attributes.isCite || attributes.isCiteJob) && (
          <cite>
            {attributes.isCite && (
              <RichText
                tagName="span"
                placeholder="Citation"
                className="quoteBanner-cite"
                keepPlaceholderOnFocus
                value={attributes.cite}
                onChange={createUpdateAttribute('cite')}
              />
            )}
            {attributes.isCiteJob && (
              <RichText
                tagName="span"
                placeholder="Citation (Job Title)"
                className="quoteBanner-citeTitle"
                keepPlaceholderOnFocus
                value={attributes.citeJob}
                onChange={createUpdateAttribute('citeJob')}
              />
            )}
          </cite>
        )}
      </div>
    </blockquote>
  );
};

export default QuoteEdit;
