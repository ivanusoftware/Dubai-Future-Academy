// eslint-disable-next-line
import { h, render } from 'preact';

import SearchApp from './Components/SearchApp';

export default () => {
  const element = document.querySelector('.page-head--sticky .page-headSearch');
  const returnFocusTo = document.querySelector(
    '.page-head--sticky .page-headActions [data-toggle-search]',
  );
  const closeButton = document.querySelector('.page-head--sticky [data-toggle-search="close"]');

  render(
    <SearchApp
      returnFocusTo={returnFocusTo}
      handleClose={() => {
        element.setAttribute('aria-hidden', 'true');
        returnFocusTo.focus();
        closeButton.setAttribute('tabindex', '-1');
      }}
    />,
    element,
  );
};
