import classnames from 'classnames';
import { cloneDeep, set, remove } from 'lodash-es';
import { PostMediaSelector } from '../../../components';
import { arrow } from '../../../components/icons';
import { id } from '../../../utils';

const { useEffect, useState } = wp.element;
const { RichText, URLInputButton } = wp.blockEditor;
const { Button } = wp.components;
const { __ } = wp.i18n;

const defaultSlide = {
  title: '',
  content: '',
  image: {},
  href: '',
  button: '',
};

const Edit = ({ attributes, setAttributes }) => {
  const { items = [], key } = attributes;
  const [currentSlide, setCurrentSlide] = useState(0);

  const createUpdateItemAttribute = (index, k) => value => {
    const clone = cloneDeep(items);
    set(clone, [index, k], value);
    setAttributes({ items: clone });
  };

  const createDestroyItem = index => () => {
    const clone = cloneDeep(items);
    remove(clone, (item, n) => n === index);
    setAttributes({ items: clone });

    if (currentSlide >= clone.length - 1) {
      setCurrentSlide(clone.length - 1);
    }
  };

  const createItem = () => {
    setAttributes({
      items: [...items, cloneDeep(defaultSlide)],
    });

    setCurrentSlide(items.length);
  };

  useEffect(() => {
    if (items.length === 0) {
      createItem();
    }

    if (!key) {
      setAttributes({
        key: id(),
      });
    }
  }, [true]);

  const item = items[currentSlide] ?? false;

  return (
    <div className="container">
      <div className="content-slider">
        <nav className="content-sliderNav">
          <ul>
            {items.length > 0 &&
              items.map((i, index) => (
                <li>
                  <button
                    className={classnames({
                      'is-active': index === currentSlide,
                    })}
                    onClick={() => setCurrentSlide(index)}
                  >
                    {index + 1}
                  </button>
                </li>
              ))}
          </ul>
        </nav>
        <ul className="content-sliderSlides">
          {item && (
            <li key={currentSlide} className="content-sliderItem">
              <figure className="content-sliderFigure">
                <PostMediaSelector
                  onUpdate={createUpdateItemAttribute(currentSlide, 'image')}
                  mediaId={item?.image?.id}
                  labels={{
                    set: __('Set Background Image', 'dff'),
                  }}
                  size="featured-post"
                />
              </figure>
              <div className="content-sliderContent">
                <RichText
                  tagName="h3"
                  placeholder={__('(Title)', 'dff')}
                  onChange={createUpdateItemAttribute(currentSlide, 'title')}
                  value={item.title}
                />
                <RichText
                  tagName="p"
                  placeholder={__('(Content)', 'dff')}
                  value={item.content}
                  onChange={createUpdateItemAttribute(currentSlide, 'content')}
                />

                <div className="content-sliderActions">
                  <div>
                    <RichText
                      tagName="span"
                      className="btn button--ghost"
                      onChange={createUpdateItemAttribute(currentSlide, 'button')}
                      placeholder={__('Button Text', 'dff')}
                      keepPlaceholderOnFocus
                      value={item.button}
                    />
                    <URLInputButton
                      onChange={createUpdateItemAttribute(currentSlide, 'href')}
                      url={item.href}
                    />
                  </div>
                  <div className="content-sliderPagination">
                    <button
                      className="content-sliderPaginationButton previous"
                      disabled={currentSlide === 0}
                      onClick={() => setCurrentSlide(currentSlide - 1)}
                    >
                      {arrow}
                    </button>
                    <span>
                      {currentSlide + 1} / {items.length}
                    </span>
                    <button
                      className="content-sliderPaginationButton next"
                      disabled={currentSlide === items.length - 1}
                      onClick={() => setCurrentSlide(currentSlide + 1)}
                    >
                      {arrow}
                    </button>
                  </div>
                </div>

                <hr />

                <Button isLink isDestructive onClick={createDestroyItem(currentSlide)}>
                  Remove
                </Button>
              </div>
            </li>
          )}
        </ul>
      </div>
      <Button isPrimary onClick={createItem}>
        {__('Add Item', 'dff')}
      </Button>
    </div>
  );
};

export default Edit;
