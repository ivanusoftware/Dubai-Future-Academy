import classnames from 'classnames';
import { PositionSelector } from '../../../components';

const { InspectorControls, InnerBlocks } = wp.blockEditor;
const { withSelect } = wp.data;
const { PanelBody, ToggleControl } = wp.components;

const columnAlgnmentOptions = [
  [{ value: { vertical: 'top', horizontal: 'center' }, label: 'Top' }],
  [{ value: { vertical: 'middle', horizontal: 'center' }, label: 'Center' }],
  [{ value: { vertical: 'bottom', horizontal: 'center' }, label: 'Bottom' }],
];

const Edit = ({ hasChildBlocks, attributes, setAttributes }) => {
  const createUpdateAttribute = key => value => setAttributes({ [key]: value });

  const updateColumnAlignment = columnAlignment => {
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

  return (
    <>
      <InspectorControls>
        <PanelBody>
          <p
            style={{
              margin: 0,
            }}
          >
            <em>If nothing is set then this column will be set to the defualt.</em>
          </p>
          <PositionSelector
            value={attributes.columnAlignment}
            onChange={updateColumnAlignment}
            options={columnAlgnmentOptions}
          />

          <hr />
          <ToggleControl
            label="Pad Left"
            checked={attributes.paddingLeft}
            onChange={createUpdateAttribute('paddingLeft')}
          />
          <ToggleControl
            label="Pad Right"
            checked={attributes.paddingRight}
            onChange={createUpdateAttribute('paddingRight')}
          />
        </PanelBody>
      </InspectorControls>
      <div
        className={classnames({
          'has-pad-left': attributes.paddingLeft,
          'has-pad-right': attributes.paddingRight,
        })}
      >
        <InnerBlocks
          templateLock={false}
          renderAppender={hasChildBlocks ? undefined : () => <InnerBlocks.ButtonBlockAppender />}
        />
      </div>
    </>
  );
};
export default withSelect((select, ownProps) => {
  const { clientId } = ownProps;
  const { getBlockOrder } = select('core/block-editor');

  return {
    hasChildBlocks: getBlockOrder(clientId).length > 0,
  };
})(Edit);
