const removeMinHeight = element => {
  if (element.style.minHeight) {
    element.removeAttribute('style');
  }
};

const getHeight = listBlock => {
  const allHeaders = listBlock.querySelectorAll('header');

  const heights = Array.from(allHeaders).map(item => {
    removeMinHeight(item);
    return item.clientHeight;
  });

  return [Math.max(...heights), allHeaders];
};

const setHeight = (minHeight, allHeaders) => {
  const loadedHeight = window.innerWidth;

  allHeaders.forEach(header => {
    if (loadedHeight >= 600) {
      header.setAttribute('style', `min-height: ${minHeight}px`);
    } else {
      removeMinHeight(header);
    }
  });
};

const heightRunner = () => {
  const allHorizontalListBlocks = document.querySelectorAll('.wp-block-dff-horizontal-list');

  allHorizontalListBlocks.forEach(listBlock => {
    setHeight(...getHeight(listBlock));
  });
};

const setupHorizontalHeightResizer = () => {
  if (document.querySelectorAll('.wp-block-dff-horizontal-list').length > 0) {
    const loadedHeight = window.innerWidth;
    if (loadedHeight >= 600) {
      heightRunner();
    }
    window.addEventListener('resize', heightRunner);
  }
};

export default setupHorizontalHeightResizer;
