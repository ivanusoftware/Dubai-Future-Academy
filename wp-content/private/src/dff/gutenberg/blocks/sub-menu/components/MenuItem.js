const MenuItem = props => {
  const {
    details: { link, title },
  } = props;

  return (
    <li>
      <a href={link} title={title.rendered}>
        {title.rendered}
      </a>
    </li>
  );
};

export default MenuItem;
