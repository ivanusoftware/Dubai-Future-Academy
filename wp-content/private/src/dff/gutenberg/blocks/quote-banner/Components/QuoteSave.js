import quote from './quote';

const { RichText } = wp.blockEditor;

const QuoteSave = ({ attributes }) => {
  return (
    <blockquote className="quoteBanner">
      <figure>{quote}</figure>
      <div>
        <RichText.Content tagName="p" value={attributes.text} />
        {(attributes.cite !== '' || attributes.citeJob !== '') && (
          <cite>
            <RichText.Content tagName="span" className="quoteBanner-cite" value={attributes.cite} />
            <RichText.Content
              tagName="span"
              className="quoteBanner-citeTitle"
              value={attributes.citeJob}
            />
          </cite>
        )}
      </div>
    </blockquote>
  );
};

export default QuoteSave;
