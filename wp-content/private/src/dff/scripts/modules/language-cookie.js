import getApexDomain from 'get-apex-domain';

const { Cookies } = window;

const setLanguageCookie = () => {
  const { code = null } = window?.searchParams?.site;

  if (code) {
    Cookies.set('dff-lang', code, {
      domain: getApexDomain(),
      expires: 30,
    });
  }
};

export default setLanguageCookie;
