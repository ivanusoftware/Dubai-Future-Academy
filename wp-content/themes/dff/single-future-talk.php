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
<article class="article article--large" aria-labelledby="article-title">
	<div class="container">
		<main class="article-main">
			<header class="article-header">
				<span class="article-meta"><?php the_date( 'j F Y' ); ?></span>
				<h1 id="article-title" class="article-title"><?php the_title(); ?></h1>
			</header>
			<div class="article-outerContent" id="content">
				<div class="article-content">
					<?php
					$youtube_url = get_post_meta( get_the_ID(), 'video_url', true );
					preg_match( "/^https?:\/\/(www\.)?youtu(\.be\/|be\.com\/watch\?v=)(?'videoid'[\w-]+)$/", $youtube_url, $matches );
					$youtube_id = $matches['videoid'] ?? false;

					if ( $youtube_id ) :
						?>

						<div class="video-wrapper">
							<iframe src="https://www.youtube.com/embed/<?php echo esc_attr( $youtube_id ); ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
						</div>

						<?php
					endif;
					?>
				</div>
				<div class="article-share">
					<div class="article-shareInner">
						<h3 class="article-shareTitle"><?php _e( 'Share', 'dff' ); ?></h3>
						<ul>
							<li>
								<a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank" rel="noopener noreferrer">
									<?php dff_asset( 'img/ico-facebook.svg' ); ?>
								</a>
							</li>
							<li>
								<a href="http://twitter.com/share?text=<?php the_title(); ?>&url=<?php the_permalink(); ?>" target="_blank" rel="noopener noreferrer">
									<?php dff_asset( 'img/ico-twitter.svg' ); ?>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</main>
		<aside class="article-aside">
			<?php
			$related_posts = dff_related_posts( 10 );
			$slider_id     = uniqid( 'slider_' );
			if ( $related_posts ) :
				?>
			<h3 class="article-asideTitle"><?php _e( 'Related Posts', 'dff' ); ?></h3>

			<div class="slider-container">
				<button class="slider-arrow prev" data-slider-prev="<?php echo esc_attr( $slider_id ); ?>">
				<?php dff_asset( 'img/arrow.svg' ); ?>
				</button>
					<ul class="postFeed--simple has-slider" data-slider="<?php echo esc_attr( $slider_id ); ?>">
					<?php
					foreach ( $related_posts as $item ) :
						$timestamp = get_post_meta( $item['id'], 'video_length', true );
						$image     = dff_featured_image( $item['id'], 'post-feed' );
						$image_2x  = dff_featured_image( $item['id'], 'post-feed@2x' );
						?>
						<li>
						<article class="postFeed-item" aria-labelledby="article-<?php echo esc_attr( $item['id'] ); ?>">
							<?php if ( $image ) : ?>
								<figure class="postFeed-figure">
									<a href="<?php echo esc_url( $item['permalink'] ); ?>">
										<img src="<?php echo esc_url( $image ); ?>" srcset="<?php echo esc_url( $image_2x ); ?> 2x" alt="">
									</a>
								</figure>
							<?php endif; ?>
							<div class="postFeed-content">
								<div class="postFeed-icon">
									<?php dff_asset( 'img/play-button.svg' ); ?>
								</div>
								<div class="postFeed-inner">
									<header class="postFeed-header">
										<h1 id="article-<?php echo esc_attr( $item['id'] ); ?>" class="postFeed-title"><a href="<?php echo esc_url( $item['permalink'] ); ?>"><?php echo esc_html( $item['title'] ); ?></a></h1>
									</header>
									<?php if ( $timestamp ) : ?>
										<span class="postFeed-meta"><?php echo esc_html( $timestamp ); ?></span>
									<?php endif; ?>
								</div>
							</div>
						</article>
						</li>
						<?php
					endforeach;
					?>
					</ul>
					<button class="slider-arrow next" data-slider-next="<?php echo esc_attr( $slider_id ); ?>">
						<?php dff_asset( 'img/arrow.svg' ); ?>
					</button>
			</div>
		</aside>
			<?php endif; ?>
	</div>
</article>
<?php get_footer(); ?>
