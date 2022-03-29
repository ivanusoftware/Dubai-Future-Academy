const { useEffect } = wp.element;
const { registerPlugin } = wp.plugins;
const { PluginDocumentSettingPanel } = wp.editPost;
const { withSelect, withDispatch } = wp.data;
const { compose } = wp.compose;
const { __ } = wp.i18n;
const { TextControl } = wp.components;

const isValidVideo = url =>
  url.match(/^https?:\/\/(www\.)?youtu(\.be\/|be\.com\/watch\?v=)[\w-]+$/);
const isValidTimestamp = time => time.match(/^(\d{2}:){1,2}\d{2}$/);

const VideoSettings = ({
  postType,
  postMeta: { video_length: videoLength = '', video_url: videoUrl = '' },
  createUpdateMeta,
  lockPost,
  unlockPost,
}) => {
  if (postType !== 'future-talk') {
    return null;
  }

  useEffect(() => {
    if (isValidVideo(videoUrl)) {
      unlockPost('video_url');
      return;
    }

    lockPost('video_url');
  }, [videoUrl]);

  useEffect(() => {
    if (isValidTimestamp(videoLength)) {
      unlockPost('video_length');
      return;
    }

    lockPost('video_length');
  }, [videoLength]);

  return (
    postType === 'future-talk' && (
      <PluginDocumentSettingPanel title={__('Video Settings', 'dff')} icon="video-alt2">
        <TextControl
          label={__('Video URL', 'dff')}
          type="url"
          onChange={createUpdateMeta('video_url', 'dff')}
          value={videoUrl}
        />
        {!isValidVideo(videoUrl) && videoUrl && <p>Please enter a valid youtube link</p>}
        <TextControl
          label={__('Video Length', 'dff')}
          onChange={createUpdateMeta('video_length', 'dff')}
          value={videoLength}
          help="E.g 3:09"
        />
        {!isValidTimestamp(videoLength) && videoLength && (
          <p>Please enter a valid timestampe in the format MM:SS or HH:MM:SS</p>
        )}
      </PluginDocumentSettingPanel>
    )
  );
};

const composedVideoSettings = compose(
  withSelect(select => {
    const coreEditor = select('core/editor');

    return {
      postType: coreEditor.getCurrentPostType(),
      postMeta: coreEditor.getEditedPostAttribute('meta'),
      isLocked: coreEditor.isPostLocked,
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
)(VideoSettings);

registerPlugin('dff-video-options', { render: composedVideoSettings });
