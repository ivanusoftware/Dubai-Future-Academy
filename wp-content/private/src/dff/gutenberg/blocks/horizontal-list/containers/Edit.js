import classnames from 'classnames';
import { set, cloneDeep, remove } from 'lodash-es';
import arrowRight from '../../../components/icons/arrow-right';

const { RichText, URLInputButton, InspectorControls } = wp.blockEditor;
const { __ } = wp.i18n;
const { Button, PanelBody, PanelRow, SelectControl, RangeControl, ToggleControl } = wp.components;

const defaultItem = {
  title: '',
  content: '',
  href: '',
};

const Edit = ({ attributes, setAttributes }) => {
  const { items, alignment, isColumnControl, columns, isFixedArrow } = attributes;

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

  const alignmentOptions = [
    {
      label: __('None', 'dff'),
      value: '',
    },
    {
      label: __('Left', 'dff'),
      value: 'left',
    },
    {
      label: __('Center', 'dff'),
      value: 'center',
    },
    {
      label: __('Right', 'dff'),
      value: 'right',
    },
  ];

  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Settings', 'dff')}>
          <PanelRow>
            <SelectControl
              label={__('Block alignment: ', 'dff')}
              value={alignment}
              onChange={value => setAttributes({ alignment: value })}
              options={alignmentOptions}
            />
          </PanelRow>
          <PanelRow>
            <ToggleControl
              label={__('Toggle Column Control', 'dff')}
              checked={isColumnControl}
              onChange={value => setAttributes({ isColumnControl: value })}
            />
          </PanelRow>
        </PanelBody>
        {isColumnControl && (
          <PanelBody title={__('Column Control Settings', 'dff')}>
            <PanelRow>
              <RangeControl
                label={__('Items per row: ', 'dff')}
                value={columns}
                max="5"
                min="1"
                onChange={value => setAttributes({ columns: value })}
              />
            </PanelRow>
            <PanelRow>
              <ToggleControl
                label={__('Fix Arrow to Bottom', 'dff')}
                checked={isFixedArrow}
                onChange={value => setAttributes({ isFixedArrow: value })}
              />
            </PanelRow>
          </PanelBody>
        )}
      </InspectorControls>
      <ol
        className={classnames('horizontal-list', {
          [`${alignment}`]: alignment,
          'horizontal-list--columnControlled': isColumnControl,
          'horizontal-list--fixedArrow': isFixedArrow,
        })}
      >
        {items.map((item, index) => (
          <li className={`horizontal-list-item--columns-${columns}`}>
            <Button icon="trash" help={__('Remove Item', 'dff')} onClick={removeItem(index)} />
            <span>{`${index + 1}`.padStart(2, '0')}</span>
            <header>
              <RichText
                tagName="h1"
                onChange={createUpdateItemAttribute(index, 'title')}
                value={item.title}
                placeholder={__('List Title', 'dff')}
                keepPlaceholderOnFocus
              />
            </header>
            <RichText
              tagName="p"
              onChange={createUpdateItemAttribute(index, 'content')}
              value={item.content}
              placeholder={__('List Content', 'dff')}
              keepPlaceholderOnFocus
            />
            {arrowRight}
            <URLInputButton onChange={createUpdateItemAttribute(index, 'href')} url={item.href} />
          </li>
        ))}
      </ol>
      <Button isPrimary onClick={createItem}>
        {__('Add Item', 'dff')}
      </Button>
    </>
  );
};

export default Edit;
