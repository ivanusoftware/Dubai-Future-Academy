/* eslint-disable jsx-a11y/media-has-caption */
/* eslint-disable react/no-unused-state */
/* eslint-disable react/destructuring-assignment */
/* eslint-disable camelcase */

import classnames from 'classnames';

const { Component } = wp.element;
const { __ } = wp.i18n;
const { Button, Spinner } = wp.components;
const { MediaUpload } = wp.blockEditor;

const DEFAULT_SET_MEDIA_LABEL = __('Set Image', 'dff');
const DEFAULT_REPLACE_MEDIA_LABEL = __('Replace Image', 'dff');
const DEFAULT_REMOVE_MEDIA_LABEL = __('Remove Image', 'dff');

export default class PostMediaSelector extends Component {
  constructor(...args) {
    super(...args);

    this.state = {
      media: false,
      loading: false,
    };
  }

  componentDidMount() {
    if (this.props.mediaId && !this.state.media) {
      this.fetchMediaObject();
    }
  }

  componentDidUpdate(prevProps) {
    if (this.props.mediaId !== prevProps.mediaId) {
      this.fetchMediaObject();
    }
  }

  getMediaObjectPromise = mediaId =>
    wp.apiRequest({
      path: `/wp/v2/media/${mediaId}`,
    });

  handleUpdate = async media => {
    if (!media) {
      this.setState({
        media: false,
      });

      this.props.onUpdate();
      return;
    }

    const response = await this.getMediaObjectPromise(media.id);

    this.setState({
      response,
    });

    this.props.onUpdate({
      ...response,
    });
  };

  handleRemove = () => this.handleUpdate();

  fetchMediaObject() {
    const { mediaId } = this.props;

    if (!mediaId) {
      return;
    }

    this.setState({
      loading: true,
    });

    this.getMediaObjectPromise(mediaId).then(resp => {
      this.setState({
        media: { ...resp },
        loading: false,
      });
    });
  }

  render() {
    const {
      mediaId,
      labels: {
        set: setMediaLabel = DEFAULT_SET_MEDIA_LABEL,
        remove: removeMediaLabel = DEFAULT_REMOVE_MEDIA_LABEL,
        replace: replaceMediaLabel = DEFAULT_REPLACE_MEDIA_LABEL,
      } = {},
      type: mediaType = 'image',
      className = '',
      size = 'full',
      isFluid = false,
      actions = false,
    } = this.props;
    const { media, loading } = this.state;

    return (
      <div className={classnames(['editor-post-featured-image', className])}>
        {!!mediaId && (media || loading) && (
          <MediaUpload
            title={setMediaLabel}
            onSelect={this.handleUpdate}
            allowedTypes={[mediaType]}
            modalClass="editor-post-featured-image__media-modal"
            render={({ open }) => (
              <div>
                <div
                  className={classnames({
                    'postMediaSelect-mediaContainer': true,
                    'is-fluid': isFluid,
                    'is-loading': loading || media.isLoading,
                  })}
                >
                  {!loading && mediaType === 'video' && (
                    <video>
                      <source src={media.source_url || media.url} />
                    </video>
                  )}
                  {!loading && mediaType === 'image' && (
                    <img
                      alt=""
                      src={
                        media?.media_details?.sizes?.[size]?.source_url ||
                        media?.media_details?.sizes?.full?.source_url ||
                        media?.source_url
                      }
                    />
                  )}
                  {(loading || media.isLoading) && <Spinner />}
                </div>
                {actions ? (
                  actions({ replace: open, remove: this.handleRemove })
                ) : (
                  <div className="postMediaSelect-mediaActions">
                    <Button onClick={open} isDefault isLarge>
                      {replaceMediaLabel}
                    </Button>
                    <Button onClick={this.handleRemove} isLink isDestructive>
                      {removeMediaLabel}
                    </Button>
                  </div>
                )}
              </div>
            )}
          />
        )}
        {!mediaId && (
          <div>
            <MediaUpload
              title={setMediaLabel}
              onSelect={this.handleUpdate}
              allowedTypes={[mediaType]}
              modalClass="editor-post-featured-image__media-modal"
              render={({ open }) => (
                <Button className="editor-post-featured-image__toggle" onClick={open}>
                  {setMediaLabel}
                </Button>
              )}
            />
          </div>
        )}
      </div>
    );
  }
}
