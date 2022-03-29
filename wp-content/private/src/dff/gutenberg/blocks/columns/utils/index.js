export const getTemplate = layout =>
  layout.columns.map(column => [
    'dff/column',
    {
      className: column.className,
    },
  ]);

export default {
  getTemplate,
};
