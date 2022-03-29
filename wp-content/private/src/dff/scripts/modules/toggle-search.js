import { toggleClass, containsClass } from '../utils/classes';

const toggleClasses = () => {
  const container = document.querySelector('.page-head--sticky .page-headSearch');
  const closeButton = document.querySelector('.page-head--sticky [data-toggle-search="close"]');

  if (!containsClass('search-active')) {
    const input = document.querySelector('.page-head--sticky [data-search-input]');

    if (container) {
      container.removeAttribute('aria-hidden');
    }

    if (closeButton) {
      closeButton.removeAttribute('tabindex');
    }

    if (input) {
      input.focus();
    }
  } else {
    container.setAttribute('aria-hidden', 'true');
    closeButton.setAttribute('tabindex', '-1');
  }

  toggleClass('search-active');
  document.dispatchEvent(new Event('class-change'));
};

const handleClick = event => {
  const { target } = event;

  if (target.closest('[data-toggle-search]')) {
    event.preventDefault();
    toggleClasses();
  }
};

const setup = () => {
  document.documentElement.addEventListener('click', handleClick);
};

export default setup;
