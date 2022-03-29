import Display from '../components/Display';
import Options from '../components/Options';
import { getStyle } from '../styles/registerStyle';

const Edit = ({ attributes, setAttributes }) => {
  const createUpdateAttribute = key => value => setAttributes({ [key]: value });

  const Style = getStyle(attributes.style);

  return (
    <>
      <Options
        attributes={attributes}
        setAttributes={setAttributes}
        createUpdateAttribute={createUpdateAttribute}
        StyleOptions={Style.Options}
      />
      <Display attributes={attributes} Style={Style} setAttributes={setAttributes} />
    </>
  );
};

export default Edit;
