import impactEdit from './edit';
import attributes from './map-attributes';

(function (blocks, i18n, element, editor, components) {
    const { __ } = wp.i18n;
    const { registerBlockType } = wp.blocks;
    const { MediaUpload, AlignmentToolbar, InspectorControls, InnerBlocks, PanelColorSettings, BlockAlignmentToolbar, RichText } = wp.editor;
    const { PanelBody, TextControl, Button, SelectControl, RangeControl, ToggleControl, ServerSideRender } = wp.components;
    const { Fragment } = wp.element;

    registerBlockType('imap/imap', {
        title: __('Project Map'),
        icon: 'location-alt',
        category: 'widgets',
        keywords: [__('row'), __('project'), __('map')],
        supports: {
            anchor: true,
        },
        attributes,
        edit: impactEdit,
        save() {
            return null;
        }
    });

})(window.wp.blocks, window.wp.i18n, window.wp.element, window.wp.editor, window.wp.components);
