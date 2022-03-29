import registerFeed from './archive-feed/index';

export default () => {
  const containers = Array.from(document.querySelectorAll('[data-posts-archive]'));
  containers.forEach(container => {
    const hiddenEle = container.querySelector('input[type="hidden"]');
    let parameters = hiddenEle.value;
    parameters = JSON.parse(parameters);
    hiddenEle.remove();
    registerFeed(container, parameters);
  });
};
