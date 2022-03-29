import Flickity from 'flickity';
import 'flickity-imagesloaded';
import 'flickity/css/flickity.css';

const sliders = {};

const handleOnClick = container => event => {
  event.preventDefault();
  const { target } = event;
  const groupId = target.dataset.tabbedPostsShow;
  const parentId = container.dataset.tabbedPosts;

  let items = Array.from(
    container.querySelectorAll(`[data-tabbed-posts-collection="${container.dataset.tabbedPosts}"]`),
  );

  items.forEach(item => item.classList.remove('is-selected'));
  items = Array.from(container.querySelectorAll(`[data-tabbed-posts-id="${groupId}"]`));
  items.forEach(item => item.classList.add('is-selected'));
  target.classList.add('is-selected');
  const slider = sliders?.[groupId];

  if (!slider) {
    return;
  }

  slider.select(0, false, true);

  const nextButton = document.querySelector(`[data-tabbed-posts-next="${parentId}"]`);
  const prevButton = document.querySelector(`[data-tabbed-posts-prev="${parentId}"]`);

  if (slider.selectedIndex === 0) {
    prevButton.classList.add('is-hidden');
  } else {
    prevButton.classList.remove('is-hidden');
  }

  if (slider.selectedIndex === slider.slides.length - 1) {
    nextButton.classList.add('is-hidden');
  } else {
    nextButton.classList.remove('is-hidden');
  }

  setTimeout(() => {
    slider.resize();
  }, 250);
};

const getCurrentActiveId = container => {
  const actions = Array.from(container.querySelectorAll('[data-tabbed-posts-show]'));
  const currentAction = actions.find(action => action.classList.contains('is-selected'));

  if (!currentAction) {
    return false;
  }

  return currentAction.dataset.tabbedPostsShow;
};

const setupTabs = container => action => {
  action.addEventListener('click', handleOnClick(container));
  const parentId = container.dataset.tabbedPosts;
  const groupId = action.dataset.tabbedPostsShow;
  const sliderElement = container.querySelector(
    `.tabbedPosts-list[data-tabbed-posts-id="${groupId}"]`,
  );
  const nextButton = document.querySelector(`[data-tabbed-posts-next="${parentId}"]`);
  const prevButton = document.querySelector(`[data-tabbed-posts-prev="${parentId}"]`);

  const indexChecker = () => {
    Array.from(sliderElement.querySelectorAll('[tabindex="-1"]')).forEach($e =>
      $e.removeAttribute('tabindex'),
    );
    Array.from(sliderElement.querySelectorAll('[aria-hidden] a')).forEach($e =>
      $e.setAttribute('tabindex', '-1'),
    );
  };

  const defaultAlignment = () => {
    return document.body.classList.contains('rtl') ? 'right' : 'left';
  };

  const slider = new Flickity(sliderElement, {
    groupCells: true,
    percentPosition: true,
    imagesLoaded: true,
    contain: true,
    prevNextButtons: false,
    rightToLeft: document.body.classList.contains('rtl'),
    pageDots: false,
    cellAlign:
      container.classList.contains('is-sidebarDisabled') &&
      container.classList.contains('is-tabbedSingle') &&
      sliderElement.children.length < 4
        ? container.dataset.alignment || defaultAlignment()
        : defaultAlignment(),
    on: {
      ready() {
        indexChecker();

        if (groupId !== getCurrentActiveId(container)) {
          return;
        }

        if (this.selectedIndex === 0) {
          prevButton.classList.add('is-hidden');
          prevButton.setAttribute('aria-hidden', true);
          prevButton.setAttribute('tabindex', -1);
        } else {
          prevButton.classList.remove('is-hidden');
          prevButton.removeAttribute('aria-hidden', true);
          prevButton.removeAttribute('tabindex', -1);
        }

        if (this.selectedIndex === this.slides.length - 1) {
          nextButton.classList.add('is-hidden');
          nextButton.setAttribute('aria-hidden', true);
          nextButton.setAttribute('tabindex', -1);
        } else {
          nextButton.classList.remove('is-hidden');
          nextButton.removeAttribute('aria-hidden', true);
          nextButton.removeAttribute('tabindex', -1);
        }
      },
    },
  });

  slider.on('settle', indexChecker);
  slider.on('scroll', indexChecker);
  document.addEventListener('resize', indexChecker);

  slider.on('change', index => {
    indexChecker();

    if (index === 0) {
      prevButton.classList.add('is-hidden');
      prevButton.setAttribute('aria-hidden', true);
      prevButton.setAttribute('tabindex', -1);
    } else {
      prevButton.classList.remove('is-hidden');
      prevButton.removeAttribute('aria-hidden', true);
      prevButton.removeAttribute('tabindex', -1);
    }

    if (index === slider.slides.length - 1) {
      nextButton.classList.add('is-hidden');
      nextButton.setAttribute('aria-hidden', true);
      nextButton.setAttribute('tabindex', -1);
    } else {
      nextButton.classList.remove('is-hidden');
      nextButton.removeAttribute('aria-hidden', true);
      nextButton.removeAttribute('tabindex', -1);
    }
  });

  sliders[groupId] = slider;
};

const setupTabbedPosts = container => {
  const actions = Array.from(container.querySelectorAll('[data-tabbed-posts-show]'));
  actions.forEach(setupTabs(container));

  const groupId = container.dataset.tabbedPosts;

  const nextButton = document.querySelector(`[data-tabbed-posts-next="${groupId}"]`);
  const prevButton = document.querySelector(`[data-tabbed-posts-prev="${groupId}"]`);

  nextButton.addEventListener('click', () => {
    const currentId = getCurrentActiveId(container);
    const slider = sliders[currentId];

    if (nextButton.classList.contains('is-hidden')) {
      return;
    }

    slider.next();
  });

  prevButton.addEventListener('click', () => {
    const currentId = getCurrentActiveId(container);
    const slider = sliders[currentId];

    if (prevButton.classList.contains('is-hidden')) {
      return;
    }

    slider.previous();
  });
  // prevButton.addEventListener('click', () => flickity.previous());
};

const setup = () => {
  const tabbedPosts = Array.from(document.querySelectorAll('[data-tabbed-posts]'));

  tabbedPosts.forEach(setupTabbedPosts);
};

export default setup;
