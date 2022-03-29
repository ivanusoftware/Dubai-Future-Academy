import { addClass, removeClass, containsClass } from '../utils/classes';

const toggleClasses = () => {
  if (containsClass('nav-active')) {
    addClass('nav-inactive');
    removeClass('nav-active');
    return;
  }

  removeClass('nav-inactive');
  addClass('nav-active');
};

const toggleMenuClasses = parent => {
  // Array.from(parent.querySelectorAll('.menu-item')).forEach(item =>
  //   item.classList.remove('is-active'),
  // );

  parent.classList.toggle('is-active');
};

const handleClick = event => {
  const { target } = event;

  if (target.closest('.menuButton-toggle')) {
    event.preventDefault();
    toggleClasses();
  }

  if (target.closest('[data-toggle-children]')) {
    event.preventDefault();
    toggleMenuClasses(target.closest('.menu-item'));
  }

  if (target.closest('.button--futureId')) {
    target.closest('.button--futureId').classList.toggle('is-active');
  }
};

const setup = () => {
  document.documentElement.addEventListener('click', handleClick);
};

export default setup;
