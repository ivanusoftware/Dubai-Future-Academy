import classnames from 'classnames';

import QuoteSave from '../Components/QuoteSave';
import CallToActionSave from '../Components/CallToActionSave';
import { generateSpacingClassnames } from '../../../components/SpacingSelector';

const Save = ({ attributes }) => {
  let backgroundImage = false;

  if (attributes?.image?.id) {
    backgroundImage = attributes.image.source_url; // eslint-disable-line camelcase
  }

  return (
    <section
      className={classnames('section', {
        'section--dark': attributes.isDark,
        ...generateSpacingClassnames('margin', attributes.margin),
      })}
      style={
        backgroundImage && !attributes.containContentInside
          ? {
              backgroundImage: `url(${backgroundImage})`,
            }
          : {}
      }
    >
      <div className="container">
        {attributes.styleSelected === 'quote' && <QuoteSave attributes={attributes} />}
        {attributes.styleSelected === 'cta' && <CallToActionSave attributes={attributes} />}
      </div>
    </section>
  );
};

export default Save;
