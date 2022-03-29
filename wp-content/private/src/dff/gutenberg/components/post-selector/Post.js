const { Button } = wp.components;

/**
 * Post Component.
 *
 * @param {string} postTitle - Current post title.
 * @param {function} clickHandler - this is the handling function for the add/remove function
 * @param {Integer} postId - Current post ID
 * @param {string|boolean} featured_image - Posts featured image
 * @param icon
 * @returns {*} Post HTML.
 */
export const Post = ({
  title: { rendered: postTitle } = {},
  clickHandler,
  id: postId,
  featured_image: featuredImage = false,
  icon,
  movePost = false,
}) => (
  <article className="post">
    {movePost && (
      <div className="button-directions">
        <Button icon="arrow-up-alt2" onClick={movePost(-1)} />
        <Button icon="arrow-down-alt2" onClick={movePost(1)} />
      </div>
    )}
    <figure className="post-figure" style={{ backgroundImage: `url(${featuredImage})` }}></figure>
    <div className="post-body">
      <h3 className="post-title">{postTitle}</h3>
    </div>
    {icon && (
      <button className="action-button" onClick={() => clickHandler(postId)}>
        {icon}
      </button>
    )}
  </article>
);

export default Post;
