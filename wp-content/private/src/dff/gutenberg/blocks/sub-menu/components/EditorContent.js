import MenuItem from './MenuItem';

const { __ } = wp.i18n;
const { Spinner } = wp.components;

const EditorContent = props => {
  const {
    attributes: { subPages, isLoading, pageId },
  } = props;

  // display loading state
  if (isLoading) {
    return (
      <p>
        <Spinner /> {__('Getting pages...', 'dff')}
      </p>
    );
  }

  // display if sub pages empty
  if (subPages.length === 0 && pageId !== '') {
    return (
      <nav className="subMenu">
        <p>{__('Current page selected has no sub pages, please select a new page.', 'dff')}</p>
      </nav>
    );
  }

  // if page hasn't been selected yet
  if (pageId === '') {
    return (
      <nav className="subMenu">
        <p>{__('Please select a page from the settings menu.', 'dff')}</p>
      </nav>
    );
  }

  return (
    <nav className="subMenu">
      <ul className="subMenu-ul">
        <li>
          <a href="#">{__('Overview', 'dff')}</a>
        </li>
        {subPages.map(page => (
          <MenuItem details={page} key={page.id} />
        ))}
      </ul>
    </nav>
  );
};

export default EditorContent;
