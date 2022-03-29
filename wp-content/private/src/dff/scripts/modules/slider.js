import Flickity from 'flickity';
import 'flickity-imagesloaded';
import 'flickity/css/flickity.css';

const createSlider = ele => {
  const sliderKey = ele.dataset.slider;

  const indexChecker = () => {
    Array.from(ele.querySelectorAll('[tabindex="-1"]')).forEach($e =>
      $e.removeAttribute('tabindex'),
    );
    Array.from(ele.querySelectorAll('[aria-hidden] a')).forEach($e =>
      $e.setAttribute('tabindex', '-1'),
    );
  };

  const flickity = new Flickity(ele, {
    percentPosition: true,
    imagesLoaded: true,
    prevNextButtons: false,
    pageDots: false,
    cellAlign: document.body.classList.contains('rtl') ? 'right' : 'left',
    groupCells: true,
    contain: true,
    accessibility: true,
    rightToLeft: document.body.classList.contains('rtl'),
    on: {
      ready() {
        indexChecker();
      },
    },
  });

  const nextButton = document.querySelector(`[data-slider-next="${sliderKey}"]`);
  const prevButton = document.querySelector(`[data-slider-prev="${sliderKey}"]`);

  prevButton.classList.add('not-active');

  const hideArrows = () => {
    const index = flickity.selectedIndex;

    if (index > 0) {
      prevButton.classList.remove('not-active');
    } else {
      prevButton.classList.add('not-active');
    }

    if (index === flickity.slides.length - 1) {
      nextButton.classList.add('not-active');
    } else {
      nextButton.classList.remove('not-active');
    }
  };

  flickity.on('change', () => {
    indexChecker();
    hideArrows();
  });
  flickity.on('settle', indexChecker);
  flickity.on('scroll', indexChecker);
  document.addEventListener('resize', indexChecker);

  nextButton.addEventListener('click', () => {
    flickity.next();
    hideArrows();
  });
  prevButton.addEventListener('click', () => {
    flickity.previous();
    hideArrows();
  });
};

const setup = () => {
  const sliders = Array.from(document.querySelectorAll('[data-slider]'));
  sliders.forEach(createSlider);
};

export default setup;
