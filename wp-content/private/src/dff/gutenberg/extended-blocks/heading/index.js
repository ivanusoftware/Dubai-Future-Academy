import classnames from 'classnames/dedupe';

const { addFilter } = wp.hooks;
const { createHigherOrderComponent } = wp.compose;
const { InspectorControls } = wp.editor;
const { PanelBody, SelectControl } = wp.components;
const { __ } = wp.i18n;

const extendHeadingBlock = (settings, name) => {
  if (name !== 'core/heading') {
    return settings;
  }

  const newSettings = {
    ...settings,
    attributes: {
      ...settings.attributes,
      fontWeight: {
        type: 'string',
        default: '',
      },
      fontFamily: {
        type: 'string',
        default: '',
      },
    },
  };

  return newSettings;
};

addFilter('blocks.registerBlockType', 'twoyou/extend-heading-block', extendHeadingBlock);

const addFields = createHigherOrderComponent(BlockEdit => props => {
  if (props.name !== 'core/heading') {
    return <BlockEdit {...props} />;
  }

  const { setAttributes, attributes } = props;
  const { fontFamily, fontWeight } = attributes;

  const classes = classnames(props.attributes.className, {
    [`u-weight--${fontWeight}`]: !!fontWeight,
    [`u-font--${fontFamily}`]: !!fontFamily,
  });

  const newProps = {
    ...props,
    attributes: {
      ...props.attributes,
      className: classes,
    },
  };

  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Font settings', 'dff')}>
          <SelectControl
            label={__('Font family', 'dff')}
            value={fontFamily}
            options={[
              { value: '', label: 'Default' },
              { value: 'primary', label: 'Primary' },
              { value: 'secondary', label: 'Secondary' },
              { value: 'tertiary', label: 'Tertiary' },
            ]}
            onChange={val => setAttributes({ fontFamily: val })}
          />
          <SelectControl
            label={__('Font weight', 'dff')}
            value={fontWeight}
            options={[
              { value: '', label: 'Default' },
              { value: 'light', label: 'Light' },
              { value: 'normal', label: 'Normal' },
              { value: 'bold', label: 'Bold' },
            ]}
            onChange={val => setAttributes({ fontWeight: val })}
          />
        </PanelBody>
      </InspectorControls>
      <BlockEdit {...newProps} />
    </>
  );
});

addFilter('editor.BlockEdit', 'twoyou/extend-heading-block', addFields);

const applyExtraClass = (extraProps, blockType, attributes) => {
  if (blockType.name !== 'core/heading') {
    return extraProps;
  }

  const { fontWeight, fontFamily } = attributes;
  const newExtraProps = { ...extraProps };

  const classesToAdd = classnames(newExtraProps.className, {
    [`u-weight--${fontWeight}`]: fontWeight,
    [`u-font--${fontFamily}`]: fontFamily,
  });

  // Make sure we have a class to add so we don't output empty classes on the elem.
  if (classesToAdd === '') {
    return extraProps;
  }

  newExtraProps.className = classesToAdd;

  return newExtraProps;
};

addFilter('blocks.getSaveContent.extraProps', 'twoyou/extend-heading-block', applyExtraClass);

/**
 * Add h styles as block styles to be able to make a heading have appaearcne of another heading.
 * E.g. style a h3 as a h1.
 */
const headingLevels = ['1', '2', '3', '4', '5', '6'];

headingLevels.forEach(level => {
  wp.blocks.registerBlockStyle('core/heading', {
    label: `H${level}`,
    name: `h${level}`,
  });
});

/**
 * Add additional display level styles.
 */
const displayLevels = ['1', '2'];

displayLevels.forEach(level => {
  wp.blocks.registerBlockStyle('core/heading', {
    label: `Display ${level}`,
    name: `d${level}`,
  });
});
