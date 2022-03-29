import './styles/header.scss';
import './scripts/polyfills';

import setupMobileMenu from './scripts/modules/mobile-nav';
import setupDarkmode from './scripts/modules/darkmode-toggle';
import setupStickyHeader from './scripts/modules/sticky-header';

setupMobileMenu();
setupDarkmode();
setupStickyHeader();
