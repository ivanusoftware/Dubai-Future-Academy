/* eslint-disable camelcase */
import classnames from 'classnames';
import { arrow } from '../../../components/icons';

const { RichText } = wp.blockEditor;

export default {
  attributes: {
    items: {
      type: 'array',
      default: [],
    },
    key: {
      type: 'string',
      default: '',
    },
  },
  save: ({ attributes }) => {
    const { items, key } = attributes;

    return (
      <div className="content-slider">
        <nav className="content-sliderNav">
          <ul>
            {items.length > 0 &&
              items.map((item, index) => (
                <li>
                  <button
                    className={classnames({
                      'is-active': index === 0,
                    })}
                    data-key={key}
                    data-slider-goto={index}
                  >
                    {index + 1}
                  </button>
                </li>
              ))}
          </ul>
        </nav>
        <div className="content-sliderSlides">
          {items.length > 0 &&
            items.map((item, index) => (
              <div className="content-sliderItem" data-key={key} data-slide={index}>
                <figure className="content-sliderFigure">
                  <img src={item?.image?.media_details?.sizes?.['featured-post']?.source_url} />
                </figure>
                <div className="content-sliderContent">
                  <RichText.Content tagName="h3" value={item.title} />
                  <RichText.Content tagName="p" value={item.content} />

                  <div className="content-sliderActions">
                    <div>
                      <RichText.Content
                        tagName="a"
                        href={item.href}
                        className="btn button--ghost"
                        value={item.button}
                      />
                    </div>
                    <div className="content-sliderPagination">
                      <button
                        className="content-sliderPaginationButton previous"
                        disabled={index === 0}
                        data-slide-previous
                      >
                        <span class="u-hiddenVisually">Previous Slide</span>
                        {arrow}
                      </button>
                      <span>
                        {index + 1} / {items.length}
                      </span>
                      <button
                        className="content-sliderPaginationButton next"
                        disabled={index === items.length - 1}
                        data-slide-next
                      >
                        <span class="u-hiddenVisually">Next Slide</span>
                        {arrow}
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            ))}
        </div>
      </div>
    );
  },
};
