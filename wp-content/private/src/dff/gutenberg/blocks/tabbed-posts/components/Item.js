/* eslint-disable camelcase */
import arrowRight from '../../../components/icons/arrow-right';

const Item = ({ post, meta, isMeta, isCallToAction }) => (
  <div className="tabbedPost-listItem" key={post.id}>
    <article
      className="tabbedPost-item"
      style={{
        backgroundImage: post?.featuredImage?.['post-card@2x']
          ? `url(${post?.featuredImage?.['post-card@2x']?.source_url})`
          : false,
      }}
    >
      <div className="tabbedPost-content">
        <header>
          <h1 className="tabbedPost-title">{post.title.rendered || post.title}</h1>
        </header>
        {isMeta && meta && <span className="tabbedPost-tag">{meta}</span>}
        {!isMeta && isCallToAction && <span className="tabbedPost-arrow">{arrowRight}</span>}
      </div>
    </article>
  </div>
);

export default Item;
