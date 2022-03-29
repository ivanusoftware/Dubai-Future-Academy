import classnames from 'classnames';
import config from '../config';
import { getTemplate } from '../utils';
import { PositionSelector } from '../../../components';

const { Component } = wp.element;
const { InnerBlocks, InspectorControls } = wp.blockEditor;
const { PanelBody, ToggleControl, Button, ButtonGroup, Placeholder, Tooltip } = wp.components;
const { dispatch, select } = wp.data;
const { __ } = wp.i18n;

const columnAlgnmentOptions = [
  [{ value: { vertical: 'top', horizontal: 'center' }, label: 'Top' }],
  [{ value: { vertical: 'middle', horizontal: 'center' }, label: 'Center' }],
  [{ value: { vertical: 'bottom', horizontal: 'center' }, label: 'Bottom' }],
];

class Edit extends Component {
  constructor(...args) {
    super(...args);
    const { selectedColumns } = this.props;
    this.state = {
      selectedColumns,
    };
  }

  createUpdateAttribute = key => value => {
    const { setAttributes } = this.props;
    setAttributes({
      [key]: value,
    });
  };

  updateColumns = selectedColumns => () => {
    this.setState({ selectedColumns });
  };

  updateLayout = selectedLayout => () => {
    const { setAttributes, attributes } = this.props;
    const { selectedColumns = attributes.selectedColumns } = this.state;

    setAttributes({ selectedLayout, selectedColumns });
    this.setBlockClasses();
  };

  setBlockClasses = () => {
    const { clientId } = this.props;
    const allInnerBlocks = select('core/block-editor').getBlock(clientId).innerBlocks;
    const layout = this.getLayout();

    for (let i = 0; i < allInnerBlocks.length; i += 1) {
      const { className = '' } = layout.columns[i];

      dispatch('core/block-editor').updateBlockAttributes(allInnerBlocks[i].clientId, {
        className,
      });
    }
  };

  getLayout = () => {
    const {
      attributes: { selectedColumns, selectedLayout },
    } = this.props;

    return config[selectedColumns].layouts.find(({ name }) => name === selectedLayout);
  };

  updateColumnAlignment = columnAlignment => {
    const { attributes, setAttributes } = this.props;

    if (columnAlignment === attributes.columnAlignment) {
      setAttributes({
        columnAlignment: null,
      });
      return;
    }

    setAttributes({
      columnAlignment,
    });
  };

  render() {
    const { attributes, clientId } = this.props;
    const { selectedLayout } = attributes;
    const { selectedColumns = attributes.selectedColumns } = this.state;

    if (!selectedColumns) {
      return (
        <Placeholder label={__('Columns', 'dff')} className="dff-select-columns">
          <div>
            <p>{__('Select number of columns your row requires', 'dff')}</p>
            <ButtonGroup>
              {Object.keys(config).map(column => (
                <Tooltip text={config[column].title}>
                  <Button key={column} onClick={this.updateColumns(column)}>
                    {column}
                  </Button>
                </Tooltip>
              ))}
            </ButtonGroup>
          </div>
        </Placeholder>
      );
    }

    if (!selectedLayout) {
      return (
        <Placeholder label={__('Layout', 'dff')} className="dff-select-columns">
          <div>
            <p>{__('Select the desired layout.', 'dff')}</p>
            <ButtonGroup>
              {config[selectedColumns].layouts.map(layout => (
                <Button key={layout.name} onClick={this.updateLayout(layout.name)}>
                  {layout.name}
                </Button>
              ))}
            </ButtonGroup>
          </div>
        </Placeholder>
      );
    }

    const chosenLayout = this.getLayout();

    return (
      <>
        <InspectorControls>
          <PanelBody>
            <div className="dff-select-columns">
              <h3>{__('Columns', 'dff')}</h3>
              <ButtonGroup>
                {Object.keys(config).map(column => (
                  <Tooltip text={config[column].title}>
                    <Button key={column} onClick={this.updateColumns(column)}>
                      {column}
                    </Button>
                  </Tooltip>
                ))}
              </ButtonGroup>
            </div>
            <hr />
            <div className="dff-select-columns is-list">
              <h3>{__('Layout', 'dff')}</h3>
              {config[selectedColumns].layouts.map(layout => (
                <Button key={layout.name} onClick={this.updateLayout(layout.name)}>
                  {layout.name}
                </Button>
              ))}
            </div>
            <hr />
            <ToggleControl
              label={__('Space columns', 'dff')}
              checked={attributes.isSpaced}
              onChange={this.createUpdateAttribute('isSpaced')}
            />
            <ToggleControl
              label={__('Mobile column reverse', 'dff')}
              checked={attributes.isReversedMobile}
              onChange={this.createUpdateAttribute('isReversedMobile')}
            />

            <h3
              style={{
                margin: 0,
              }}
            >
              {__('Vertical column alignment', 'dff')}
            </h3>
            <p
              style={{
                margin: 0,
              }}
            >
              <em>If nothing is set then the columns will be equal height.</em>
            </p>
            <PositionSelector
              value={attributes.columnAlignment}
              onChange={this.updateColumnAlignment}
              options={columnAlgnmentOptions}
            />
          </PanelBody>
        </InspectorControls>
        <div
          className={classnames('flexy-wrapper', {
            'is-spaced': attributes.isSpaced,
          })}
        >
          <style>
            {`
              #block-${clientId}  > .flexy-wrapper > .block-editor-inner-blocks > .block-editor-block-list__layout {
                 display: flex;
              }
            `}
            {chosenLayout.columns.map(
              (column, index) =>
                `#block-${clientId} > .flexy-wrapper > .block-editor-inner-blocks > .block-editor-block-list__layout > .wp-block:nth-child(${index +
                  1}) { width: ${column.width}%; }`,
            )}
          </style>
          <InnerBlocks
            template={getTemplate(chosenLayout)}
            allowedBlocks={['dff/column']}
            templateLock="all"
          />
        </div>
      </>
    );
  }
}

export default Edit;
