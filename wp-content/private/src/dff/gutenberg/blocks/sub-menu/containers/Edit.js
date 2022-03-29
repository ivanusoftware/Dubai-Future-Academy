import Settings from '../components/Settings';
import EditorContent from '../components/EditorContent';

const { Fragment } = wp.element;

const Edit = props => {
  return (
    <Fragment>
      <Settings {...props} />
      <EditorContent {...props} />
    </Fragment>
  );
};

export default Edit;
