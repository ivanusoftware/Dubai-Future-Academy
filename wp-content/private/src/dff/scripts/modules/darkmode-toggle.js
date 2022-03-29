import getApexDomain from 'get-apex-domain';

const { Cookies } = window;

const setupToggleDarkMode = () => {
  if (!window.CSS || !CSS.supports('color', 'var(--test-var)')) {
    Array.from(document.querySelectorAll('[data-toggle-darkmode]')).forEach(ele =>
      ele.parentElement.removeChild(ele),
    );
    return;
  }

  document.documentElement.addEventListener('click', event => {
    if (event.target.closest('[data-toggle-darkmode]')) {
      document.documentElement.classList.toggle('dark-mode');
      Cookies.set(
        'color-scheme',
        document.documentElement.classList.contains('dark-mode') ? 'dark' : 'light',
        {
          domain: getApexDomain(),
          expires: 30,
        },
      );
    }
  });
};

export default setupToggleDarkMode;
