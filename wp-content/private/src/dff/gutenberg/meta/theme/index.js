const { useEffect } = wp.element;
const { registerPlugin } = wp.plugins;
const { PluginDocumentSettingPanel } = wp.editPost;
const { withSelect, withDispatch } = wp.data;
const { compose } = wp.compose;
const { __ } = wp.i18n;
const { ColorPalette } = wp.components;

const ThemeSettings = ({ postType, themePrimary = '#fff', createUpdateMeta }) => {
  if (postType !== 'page') {
    return null;
  }

  useEffect(() => {
    if (themePrimary === '#fff') {
      document.documentElement.classList.remove('has-theme');
    } else {
      document.documentElement.classList.add('has-theme');
    }
  }, [themePrimary]);

  return (
    <>
      <PluginDocumentSettingPanel title={__('Page Theme Options', 'dff')} icon="admin-customizer">
        <ColorPalette
          colors={[
            { name: 'Default', color: '#fff' },
            { name: 'Orange', color: '#E88806' },
            { name: 'Red', color: '#E3011B' },
          ]}
          value={themePrimary}
          onChange={createUpdateMeta('theme_primary')}
          disableAlpha
        />
      </PluginDocumentSettingPanel>
      <style>
        {`:root {
          --theme-primary: ${themePrimary};
        }`}
      </style>
    </>
  );
};

const composedThemeSettings = compose(
  withSelect(select => {
    const coreEditor = select('core/editor');
    const postMeta = coreEditor.getEditedPostAttribute('meta');
    const { theme_primary: themePrimary = '#fff' } = postMeta;

    return {
      postType: coreEditor.getCurrentPostType(),
      isLocked: coreEditor.isPostLocked,
      postMeta,
      themePrimary,
    };
  }),
  withDispatch(dispatch => {
    const coreEditor = dispatch('core/editor');
    return {
      createUpdateMeta: key => value =>
        coreEditor.editPost({
          meta: {
            [key]: value,
          },
        }),
      lockPost: key => {
        coreEditor.lockPostSaving(key);
        coreEditor.lockPostAutosaving(key);
      },
      unlockPost: key => {
        coreEditor.unlockPostSaving(key);
        coreEditor.unlockPostAutosaving(key);
      },
    };
  }),
)(ThemeSettings);

registerPlugin('dff-theme-options', { render: composedThemeSettings });
