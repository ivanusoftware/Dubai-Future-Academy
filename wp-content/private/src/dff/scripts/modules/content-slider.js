import Flickity from 'flickity';
import 'flickity-imagesloaded';
import 'flickity/css/flickity.css';

const createSlider = container => {
  const ele = container.querySelector('.content-sliderSlides');

  const indexChecker = () => {
    Array.from(ele.querySelectorAll('[tabindex="-1"]')).forEach($e =>
      $e.removeAttribute('tabindex'),
    );
    Array.from(ele.querySelectorAll('[aria-hidden] a')).forEach($e =>
      $e.setAttribute('tabindex', '-1'),
    );
    Array.from(ele.querySelectorAll('[aria-hidden] button')).forEach($e =>
      $e.setAttribute('tabindex', '-1'),
    );
  };

  const flickity = new Flickity(ele, {
    imagesLoaded: true,
    prevNextButtons: false,
    pageDots: false,
    cellAlign: 'left',
    rightToLeft: document.body.classList.contains('rtl'),
    draggable: false,
    on: {
      ready() {
        indexChecker();
      },
    },
  });

  const nextAction = () => flickity.next();
  const previousAction = () => flickity.previous();
  const gotoAction = slide => flickity.select(slide);

  container.addEventListener('click', event => {
    const { target } = event;

    if (target.closest('[data-slide-next]')) {
      event.preventDefault();
      nextAction();
    }

    if (target.closest('[data-slide-previous]')) {
      event.preventDefault();
      previousAction();
    }

    if (target.closest('[data-slider-goto]')) {
      event.preventDefault();
      const $target = target.closest('[data-slider-goto]');
      gotoAction($target.dataset.sliderGoto);
    }
  });

  flickity.on('change', indexChecker);
  flickity.on('settle', indexChecker);
  flickity.on('scroll', indexChecker);

  const $gotoButttons = Array.from(container.querySelectorAll('[data-slider-goto]'));

  flickity.on('change', index => {
    $gotoButttons.forEach($ele => $ele.classList.remove('is-active'));
    const item = container.querySelector(`[data-slider-goto="${index}"]`);

    if (item) {
      item.classList.add('is-active');
    }
  });
};

const setup = () => {
  const sliders = Array.from(document.querySelectorAll('.content-slider'));
  sliders.forEach(createSlider);
};

export default setup;
