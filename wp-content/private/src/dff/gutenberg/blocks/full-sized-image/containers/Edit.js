import { PostMediaSelector } from '../../../components';

const { __ } = wp.i18n;

const Edit = ({ attributes, setAttributes }) => {
  const createUpdateAttribute = key => value =>
    setAttributes({
      [key]: value,
    });

  return (
    <PostMediaSelector
      onUpdate={createUpdateAttribute('image')}
      mediaId={attributes?.image?.id}
      labels={{
        set: __('Set Image', 'dff'),
      }}
    />
  );
};

export default Edit;
