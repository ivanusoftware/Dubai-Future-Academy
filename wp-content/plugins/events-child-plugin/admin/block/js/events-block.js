import edit from "./edit";
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
registerBlockType("dff/events", {
  title: "DFF Events",
  icon: "calendar",
  category: "dff",
  description: "Display DFF Events in your website using this block.",
  attributes: {
    titleColor: {
      type: "string",
      default: "#000",
    },
    textColor: {
      type: "string",
      default: "#000",
    },
    bgColor: {
      type: "string",
      default: "transparent",
    },
    orderBy: {
      type: "string",
      default: "date",
    },
    totalEvents: {
      type: "int",
      default: 12,
    },
    eLayout: {
      type: "string",
      default: "list-view",
    },
    checkedTags: {
      type: "array",
      default: [],
    },
    checkedCats: {
      type: "array",
      default: [],
    },
    featureImageToggle: {
      type: "boolean",
      default: true,
    },
    paginationToggle: {
      type: "boolean",
      default: true,
    },
    openUpcomingToggle: {
      type: "boolean",
      default: true,
    },
    openNewTabToggle: {
      type: "boolean",
      default: true,
    },
    catsToggle: {
      type: "boolean",
      default: true,
    },
    tagsToggle: {
      type: "boolean",
      default: true,
    },
    dateTimeToggle: {
      type: "boolean",
      default: true,
    },
  },
  edit: edit,
  save: (props) => {
    return null;
  },
});
