<?php
	get_header();
	the_post();

	$posttype = get_post_type();
?>
<div class="article-triangle">
	<?php dff_asset( 'img/triangle-single.svg' ); ?>
</div>
<?php
	dff_breadcrumbs();
?>
<article class="article" aria-labelledby="article-title">
	<div class="container">
		<main class="article-main">
			<header class="article-header">
				<span class="article-meta"><?php the_date( 'j F Y' ); ?></span>
				<h1 id="article-title" class="article-title"><?php the_title(); ?></h1>
				<a href="#content" class="hero-scrollTo">
					<div class="hero-scrollToIcon is-dark">
						<svg xmlns="http://www.w3.org/2000/svg" width="10" height="26" viewBox="0 0 10 26">
							<path
								id="Union_1"
								data-name="Union 1"
								d="M-6020,20h4V0h2V20h4l-5,6Z"
								transform="translate(6020)"
								fill="currentColor"
							/>
						</svg>
						<span class="u-hiddenVisually"><?php _e( 'Scroll down to post content', 'dff' ); ?></span>
					</div>
				</a>
			</header>
			<?php if ( dff_has_featured_image() && 'future-talk' !== $posttype ) : ?>
				<figure class="article-figure">
					<?php
						$featured_image    = dff_featured_image( get_the_ID(), 'featured-post' );
						$featured_image_2x = dff_featured_image( get_the_ID(), 'featured-post@2x' );
						$featured_image_id = get_post_thumbnail_id();
						$alt_text          = dff_get_alt_tag( $featured_image_id );
					?>

					<img
						src="<?php echo esc_url( $featured_image ); ?>"
						<?php $featured_image_2x && printf( 'srcset="%s 2x"', esc_url( $featured_image_2x ) ); ?>
						alt="<?php echo esc_attr( $alt_text ); ?>"
					>
				</figure>
			<?php endif; ?>
			<?php
			if ( 'future-talk' === $posttype ) :
				$youtube_url = get_post_meta( get_the_ID(), 'video_url', true );
				preg_match( "/^https?:\/\/(www\.)?youtu(\.be\/|be\.com\/watch\?v=)(?'videoid'[\w-]+)$/", $youtube_url, $matches );
				$youtube_id = $matches['videoid'] ?? false;

				if ( $youtube_id ) :
					?>

				<figure class="article-figure">
					<div class="video-wrapper">
					<iframe src="https://www.youtube.com/embed/<?php echo esc_attr( $youtube_id ); ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
					</div>
				</figure>

					<?php
				endif;
			endif;
			?>
			<div class="article-outerContent" id="content">
				<div class="article-content">
					<?php the_content(); ?>
				</div>
				<div class="article-share">
					<div class="article-shareInner">
						<header>
							<h1 id="article-share" class="article-shareTitle"><?php _e( 'Share', 'dff' ); ?></h1>
						</header>
						<ul aria-labelledby="article-share">
							<li>
								<a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank" rel="noopener noreferrer">
									<span class="u-hiddenVisually"><?php _e( 'Share to Facebook', 'dff' ); ?></span>
									<?php dff_asset( 'img/ico-facebook.svg' ); ?>
								</a>
							</li>
							<li>
								<a href="http://twitter.com/share?text=<?php the_title(); ?>&url=<?php the_permalink(); ?>" target="_blank" rel="noopener noreferrer">
									<span class="u-hiddenVisually"><?php _e( 'Share to Twitter', 'dff' ); ?></span>
									<?php dff_asset( 'img/ico-twitter.svg' ); ?>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</main>
			<?php
			$related_posts = dff_related_posts();

			if ( $related_posts ) :
				?>
		<aside class="article-aside" aria-labelledby="article-relatedPosts">
			<header>
				<h1 id="article-relatedPosts" class="article-asideTitle"><?php _e( 'Related Posts', 'dff' ); ?></h1>
			</header>
			<ul class="article-relatedPosts">
				<?php
				foreach ( $related_posts as $related_post ) :
					?>
					<li>
						<article aria-labelledby="article-<?php echo esc_attr( $item['id'] ); ?>" class="article-relatedPost">
							<span class="article-relatedPostMeta"><?php echo esc_html( $related_post['date'] ); ?></span>
							<h1 id="article-<?php echo esc_attr( $item['id'] ); ?>" class="article-relatedPostTitle">
								<a href="<?php echo esc_url( $related_post['permalink'] ); ?>">
									<?php echo esc_html( $related_post['title'] ); ?>
								</a>
							</h1>
						</article>
					</li>
					<?php
				endforeach;
				?>
			</ul>
		</aside>
			<?php endif; ?>
	</div>
</article>

<?php


get_footer(); ?>
