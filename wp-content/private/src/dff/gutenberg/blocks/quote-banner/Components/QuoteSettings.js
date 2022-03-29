const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { PanelRow, ToggleControl } = wp.components;

const QuoteSettings = ({ createUpdateAttribute, isCite, isCiteJob }) => {
  return (
    <Fragment>
      <PanelRow>
        <ToggleControl
          label={__('Cite', 'dff')}
          checked={isCite}
          onChange={createUpdateAttribute('isCite')}
        />
      </PanelRow>
      <PanelRow>
        <ToggleControl
          label={__('Cite Job', 'dff')}
          checked={isCiteJob}
          onChange={createUpdateAttribute('isCiteJob')}
        />
      </PanelRow>
    </Fragment>
  );
};

export default QuoteSettings;
