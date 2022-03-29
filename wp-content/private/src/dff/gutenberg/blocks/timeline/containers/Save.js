import { chevronRight } from '../../../components/icons';

const { RichText } = wp.blockEditor;

const Save = ({ attributes }) => (
  <>
    {attributes.items.length > 0 && (
      <div className="timeline-container">
        <div className="timeline">
          {attributes.items.map((item, index) => (
            <div key={index} className="timeline-item">
              <RichText.Content tagName="h3" value={item.title} />
              <RichText.Content tagName="p" value={item.content} />
            </div>
          ))}
        </div>
        <button className="timeline-action previous">
          <span class="u-hiddenVisually">Previous Slide</span>
          {chevronRight}
        </button>
        <button className="timeline-action next">
          <span class="u-hiddenVisually">Next Slide</span>
          {chevronRight}
        </button>
      </div>
    )}
  </>
);

export default Save;
