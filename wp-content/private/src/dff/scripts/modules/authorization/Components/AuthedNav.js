import { h } from 'preact';
import { useEffect } from 'preact/hooks';

const { Cookies, searchParams, i18n } = window;

const AuthedNav = () => {
  const uid = Cookies.get('_fid_dff_uid');
  const isLoggedIn = Cookies.get('fid-is-loggedin');

  useEffect(() => {
    if (isLoggedIn && !uid) {
      window.location = `${window.location.origin}/oauth/login`;
    }
  }, [true]);

  if (isLoggedIn) {
    return (
      <ul className="futureId">
        <li>
          <a href={searchParams.dashboardUrl}>{i18n.profile}</a>
        </li>
        <li>
          <a href={searchParams.programsUrl}>{i18n.programsDashboard}</a>
        </li>
        {searchParams.menuItems.map(item => (
          <li>
            <a href={item.link}>{item.title}</a>
          </li>
        ))}
        <li>
          <a href={searchParams.logoutUrl}>{i18n.logout}</a>
        </li>
      </ul>
    );
  }

  return (
    <ul className="futureId">
      {searchParams.menuItems.map(item => (
        <li>
          <a href={item.link}>{item.title}</a>
        </li>
      ))}
      <li>
        <a href={searchParams.loginUrl}>{i18n.login}</a>
      </li>
    </ul>
  );
};
export default AuthedNav;
