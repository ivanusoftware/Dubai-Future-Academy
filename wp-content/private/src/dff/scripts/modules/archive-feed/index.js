// eslint-disable-next-line
import { h, render } from 'preact';

import Archive from './Components/Archive';

export default (container, props = {}) => {
  const containerInner = container.querySelector('.archivePosts-inner');
  render(<Archive {...props} innerChild={containerInner} />, container, containerInner);
};
