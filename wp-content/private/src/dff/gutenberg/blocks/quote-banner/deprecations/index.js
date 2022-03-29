import classnames from 'classnames';

import QuoteSave from './Components/QuoteSave';
import CallToActionSave from './Components/CallToActionSave';

export default [
  {
    attributes: {
      text: {
        type: 'string',
      },
      cite: {
        type: 'text',
        default: '',
      },
      citeJob: {
        type: 'text',
        default: '',
      },
      isDark: {
        type: 'bool',
        default: false,
      },
      image: {
        type: 'object',
        default: {},
      },
      styleSelected: {
        type: 'string',
        default: 'quote',
      },
      isCite: {
        type: 'boolean',
        default: true,
      },
      isCiteJob: {
        type: 'boolean',
        default: true,
      },
      ctaButtonText: {
        type: 'string',
        default: '',
      },
      ctaButtonHref: {
        type: 'string',
        default: '',
      },
    },
    save: ({ attributes }) => {
      let backgroundImage = false;

      if (attributes?.image?.id) {
        backgroundImage = attributes.image.source_url; // eslint-disable-line camelcase
      }

      return (
        <section
          className={classnames('section', {
            'section--dark': attributes.isDark,
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
    },
  },
];
