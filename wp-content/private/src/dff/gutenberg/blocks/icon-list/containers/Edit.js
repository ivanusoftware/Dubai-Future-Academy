import classnames from 'classnames';
import { set, cloneDeep, remove } from 'lodash-es';
import { PostMediaSelector } from '../../../components';

const { useEffect } = wp.element;
const { InspectorControls } = wp.editor;
const { RichText } = wp.blockEditor;
const { __ } = wp.i18n;
const { Button, PanelBody, PanelRow, ToggleControl } = wp.components;

const defaultItem = {
  icon: {},
  title: '',
  content: '',
};

const Edit = ({ attributes, setAttributes }) => {
  const { items } = attributes;

  const createUpdateItemAttribute = (index, key) => value => {
    const itemsCopy = cloneDeep(items);
    set(itemsCopy, [index, key], value);
    setAttributes({ items: itemsCopy });
  };

  const createItem = () => {
    const itemsCopy = [...cloneDeep(items), cloneDeep(defaultItem)];
    setAttributes({ items: itemsCopy });
  };

  const removeItem = nth => () => {
    const itemsCopy = cloneDeep(items);
    setAttributes({
      items: remove(itemsCopy, (item, index) => index !== nth),
    });
  };

  useEffect(() => {
    if (items.length === 0) {
      createItem();
    }
  }, [true]);

  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Dark Mode Settings', 'dff')}>
          <PanelRow>
            <ToggleControl
              label={__('Enable Dark Mode Invert', 'dff')}
              help={__('This will invert icon colours for dark mode only', 'dff')}
              checked={attributes.isInverted}
              onChange={() => setAttributes({ isInverted: !attributes.isInverted })}
            />
          </PanelRow>
          <PanelRow>
            <ToggleControl
              label={__('Show Dark Mode Preview', 'dff')}
              help={__('A preview of how the icon will potentially look in dark mode', 'dff')}
              checked={attributes.isPreview}
              onChange={() => setAttributes({ isPreview: !attributes.isPreview })}
            />
          </PanelRow>
        </PanelBody>
      </InspectorControls>
      <ul
        className={classnames('icon-list', {
          [`has-${items.length}-items`]: true,
          isInverted: attributes.isInverted,
          isPreview: attributes.isPreview,
        })}
      >
        {items.map((item, index) => (
          <li>
            <Button icon="trash" help={__('Remove Item', 'dff')} onClick={removeItem(index)} />
            <PostMediaSelector
              onUpdate={createUpdateItemAttribute(index, 'image')}
              mediaId={item?.image?.id}
              labels={{
                set: __('Set Icon', 'dff'),
              }}
              size="featured-post"
            />
            <RichText
              tagName="h3"
              onChange={createUpdateItemAttribute(index, 'title')}
              value={item.title}
              placeholder={__('Title', 'dff')}
              keepPlaceholderOnFocus
            />
            <RichText
              tagName="p"
              onChange={createUpdateItemAttribute(index, 'content')}
              value={item.content}
              placeholder={__('Content', 'dff')}
              keepPlaceholderOnFocus
            />
          </li>
        ))}
      </ul>
      <Button isPrimary onClick={createItem}>
        {__('Add Item', 'dff')}
      </Button>
    </>
  );
};

export default Edit;
