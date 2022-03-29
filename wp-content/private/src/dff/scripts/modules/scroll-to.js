const scrollTo = target => {
  if (target === '#') {
    return;
  }

  const ele = document.querySelector(target);
  if (!ele) {
    return;
  }

  const header = document.querySelector('.page-head');
  const headerRect = header.getBoundingClientRect();
  const eleRect = ele.getBoundingClientRect();

  window.scrollTo({
    top: eleRect.top - headerRect.bottom,
    behavior: 'smooth',
  });
};

const handleClick = event => {
  const target = event.target.closest('a');
  if (
    target &&
    !target.matches('.skip-to-content') &&
    target.getAttribute('href').startsWith('#')
  ) {
    event.preventDefault();
    scrollTo(target.getAttribute('href'));
  }
};

const setup = () => {
  if (!window.scrollTo) {
    return;
  }

  document.documentElement.addEventListener('click', handleClick);
};

export default setup;
