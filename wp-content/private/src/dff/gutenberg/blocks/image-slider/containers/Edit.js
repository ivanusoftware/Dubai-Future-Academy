import Settings from '../components/Settings';
import Slider from '../components/Slider';

const { Fragment } = wp.element;

const Edit = ({ attributes, setAttributes, className }) => {
  return (
    <Fragment>
      <Settings attributes={attributes} setAttributes={setAttributes} />
      <Slider attributes={attributes} className={className} />
    </Fragment>
  );
};

export default Edit;
