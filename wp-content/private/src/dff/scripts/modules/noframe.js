import { debounce } from 'lodash-es';

/**
 * From https://github.com/yowainwright/reframe.js/blob/master/src/noframe.ts
 * with tweak to work for resizing.
 */
const noframe = (target, container) => {
  const frames = [...(typeof target === 'string' ? document.querySelectorAll(target) : target)];
  for (let i = 0; i < frames.length; i += 1) {
    const frame = frames[i];

    // Remove these styles so resize event will update.
    frame.style.width = '';
    frame.style.maxWidth = '';
    frame.style.height = '';
    frame.style.maxHeight = '';

    const isContainerElement =
      typeof container !== 'undefined' && document.querySelector(container);
    const parent = isContainerElement ? document.querySelector(container) : frame.parentElement;
    const h = frame.offsetHeight;
    const w = frame.offsetWidth;
    const styles = frame.style;

    // => If a targeted <container> element is defined
    if (isContainerElement) {
      // gets/sets the height/width ratio
      const maxW = window.getComputedStyle(parent, null).getPropertyValue('max-width');
      styles.width = '100%';
      // calc is needed here b/c the maxW measurement type is unknown
      styles.maxHeight = `calc(${maxW} * ${h} / ${w})`;
    } else {
      // gets/sets the height/width ratio
      // => if a targeted <element> closest parent <element> is NOT defined
      styles.display = 'block';
      styles.marginLeft = 'auto';
      styles.marginRight = 'auto';
      const fullW = w > parent.offsetWidth ? parent.offsetWidth : `${w}px`;
      const maxH = w > parent.offsetWidth ? (fullW * h) / w : w * (h / w);
      // if targeted <element> width is > than it's parent <element>
      // => set the targeted <element> maxheight/fullwidth to it's parent <element>
      styles.maxHeight = `${maxH}px`;
      styles.width = fullW;
    }
    // set a calculated height of the targeted <element>
    const cssHeight = (100 * h) / w; // eslint-disable-line no-mixed-operators
    styles.height = `${cssHeight}vw`;
    styles.maxWidth = '100%';
  }
};

const setupNoframe = () => {
  const elem = '.wp-block-embed.is-type-video  iframe';
  noframe(elem);

  window.addEventListener(
    'resize',
    debounce(() => {
      noframe(elem);
    }, 250),
  );
};

export default setupNoframe;
