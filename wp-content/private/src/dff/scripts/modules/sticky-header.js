const setupStickyHeader = () => {
  const header = document.querySelector('.page-head');

  if (!header) {
    return;
  }

  const sticky = header.cloneNode(true);
  sticky.classList.add('page-head--sticky');
  document.body.insertBefore(sticky, header.nextSibling);
  header.setAttribute('aria-hidden', 'true');
  header.setAttribute('tabindex', '-1');
  header.querySelectorAll('a, button').forEach(e => {
    e.setAttribute('tabindex', '-1');
  });

  const staticRect = header.getBoundingClientRect();

  const offset = staticRect.height - header.querySelector('.page-headActions').offsetTop;

  window.addEventListener('scroll', () => {
    if ((window.scrollY || window.pageYOffset) >= offset) {
      sticky.classList.add('is-scrolled');
      return;
    }

    sticky.classList.remove('is-scrolled');
  });
};

export default setupStickyHeader;
