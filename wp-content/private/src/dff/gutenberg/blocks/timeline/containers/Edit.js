import { cloneDeep, set, remove } from 'lodash-es';

const { useEffect, useRef } = wp.element;
const { RichText } = wp.blockEditor;
const { Button } = wp.components;
const { __ } = wp.i18n;

const timelineItem = {
  title: '',
  content: '',
};

const Edit = ({ attributes, setAttributes }) => {
  const containerRef = useRef();

  const createUpdateItemAttribute = (index, key) => value => {
    const clone = cloneDeep(attributes.items);
    set(clone, [index, key], value);
    setAttributes({ items: clone });
  };

  const createDestroyItem = index => () => {
    const clone = cloneDeep(attributes.items);
    remove(clone, (item, n) => n === index);
    setAttributes({ items: clone });
  };

  const createItem = () => {
    setAttributes({
      items: [...attributes.items, cloneDeep(timelineItem)],
    });

    setTimeout(() => {
      if (containerRef.current) {
        containerRef.current.scrollIntoView({
          behavior: 'smooth',
          block: 'nearest',
          inline: 'end',
        });
      }
    }, 10);
  };

  useEffect(() => {
    if (attributes.items.length === 0) {
      createItem();
    }
  }, [true]);

  return (
    <>
      <ul className="timeline">
        {attributes.items.length > 0 &&
          attributes.items.map((item, index) => (
            <li key={index} className="timeline-item">
              <RichText
                tagName="h3"
                placeholder={__('(Year)', 'dff')}
                onChange={createUpdateItemAttribute(index, 'title')}
                value={item.title}
              />
              <RichText
                tagName="p"
                placeholder={__('(Content)', 'dff')}
                value={item.content}
                onChange={createUpdateItemAttribute(index, 'content')}
              />
              <Button isLink isDestructive onClick={createDestroyItem(index)}>
                Remove
              </Button>
            </li>
          ))}
        <li ref={containerRef} className="timeline-anchor"></li>
      </ul>
      <Button isPrimary onClick={createItem}>
        {__('Add Item', 'dff')}
      </Button>
    </>
  );
};

export default Edit;
