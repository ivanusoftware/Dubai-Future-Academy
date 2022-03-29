import * as icons from './index';

const { __ } = wp.i18n;
const { applyFilters } = wp.hooks;

export const getIcons = () =>
  applyFilters('dff_icon_picker_get_icons', {
    'arrow-right': { title: __('Arrow Right', 'dff'), render: icons.arrowRight },
    sun: { title: __('Sun', 'dff'), render: icons.sun },
    hourglass: { title: __('Hourglass', 'dff'), render: icons.hourglass },
    people: { title: __('People', 'dff'), render: icons.people },
    moon: { title: __('Moon', 'dff'), render: icons.moon },
    search: { title: __('Search', 'dff'), render: icons.search },
    play: { title: __('Play', 'dff'), render: icons.playButton },
    'chevron-down': { title: __('Chevron Down', 'dff'), render: icons.chevronDown },
    'chevron-right': { title: __('Chevron Right', 'dff'), render: icons.chevronRight },
  });

export const getIcon = key => {
  const iconMap = getIcons();
  const icon = iconMap[key] || false;
  return icon;
};
