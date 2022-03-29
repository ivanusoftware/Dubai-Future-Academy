// eslint-disable-next-line
import { h, render } from 'preact';

import AuthedNav from './Components/AuthedNav';

export default () => {
  const element = document.querySelector('.page-head--sticky .button--futureId');

  render(<AuthedNav />, element);
};
