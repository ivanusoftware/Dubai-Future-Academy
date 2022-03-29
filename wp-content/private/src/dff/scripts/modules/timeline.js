import Flickity from 'flickity';
import 'flickity-imagesloaded';
import 'flickity/css/flickity.css';
import { debounce } from 'lodash-es';

const createSlider = ele => {
  const sliderEle = ele.querySelector('.timeline');
  const indexChecker = () => {
    Array.from(sliderEle.querySelectorAll('[tabindex="-1"]')).forEach($e =>
      $e.removeAttribute('tabindex'),
    );
    Array.from(sliderEle.querySelectorAll('[aria-hidden] a')).forEach($e =>
      $e.setAttribute('tabindex', '-1'),
    );
    Array.from(sliderEle.querySelectorAll('[aria-hidden] button')).forEach($e =>
      $e.setAttribute('tabindex', '-1'),
    );
  };

  const flickityOptions = {
    groupCells: true,
    percentPosition: true,
    imagesLoaded: true,
    prevNextButtons: false,
    pageDots: false,
    cellAlign: document.body.classList.contains('rtl') ? 'right' : 'left',
    rightToLeft: document.body.classList.contains('rtl'),
    on: {
      ready() {
        indexChecker();
      },
    },
  };

  let flickity = new Flickity(sliderEle, flickityOptions);

  flickity.on('settle', indexChecker);
  flickity.on('scroll', indexChecker);

  window.addEventListener(
    'resize',
    debounce(() => {
      indexChecker();

      // Re-initialize flickty on resize to make sure it works when slcing down from large window.
      flickity.destroy();
      flickity = new Flickity(sliderEle, flickityOptions);
    }, 100),
  );

  flickity.on('change', indexChecker);
};

const setup = () => {
  const sliders = Array.from(document.querySelectorAll('.timeline-container'));
  sliders.forEach(createSlider);
};

export default setup;
