<?php

namespace DFF\Gutenberg\Blocks\PostFeed\Styles;

use DFF\Gutenberg\Blocks\PostFeed\Base_Style;
use DFF\Gutenberg\Blocks\PostFeed\Styles;

class Simple extends Base_Style {
	public $name          = 'Simple';
	public $image_size    = 'post-feed';
	public $image_size_2x = 'post-feed@2x';

	public function __construct() {
		add_filter( 'dff_render_future-talk_simple_feed_item', [ $this, 'render_future_talk_item' ], 10, 2 );
	}

	public function get_no_of_posts( array $attributes ): int {
		return ( $attributes['sliderEnabled'] ?? false ) ? 10 : 5;
	}

	public function render_future_talk_item( string $current_output, array $item ): string {
		$timestamp = get_post_meta( $item['id'], 'video_length', true );
		ob_start();
		?>
		<article class="postFeed-item" aria-labelledby="article-<?php echo esc_attr( $item['id'] ); ?>">
			<?php if ( $item['term'] ) : ?>
				<a class="postFeed-tag" href="<?php echo esc_url( $item['term']['link'] ); ?>"><?php echo esc_html( $item['term']['name'] ); ?></a>
			<?php endif; ?>
			<?php if ( $item['image'] ) : ?>
				<figure class="postFeed-figure">
					<a
						href="<?php echo esc_url( $item['permalink'] ); ?>"
						<?php // phpcs:ignore ?>
						aria-label="<?php printf( __( 'Link to %s', 'dff' ), esc_attr( $item['title'] ) ); ?>"
						<?php // phpcs:ignore ?>
						title="<?php printf( __( 'Link to %s', 'dff' ), esc_attr( $item['title'] ) ); ?>"
					>
						<img src="<?php echo esc_url( $item['image'] ); ?>" srcset="<?php echo esc_url( $item['image@2x'] ); ?> 2x" alt="<?php echo esc_attr( $item['alt'] ?: $item['title'] ); ?>">
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
		<?php
		return ob_get_clean();
	}

	public function render_item( array $item, string $tag_element ): string {
		$type = $item['type'];

		ob_start();
		?>
		<article class="postFeed-item" aria-labelledby="article-<?php echo esc_attr( $item['id'] ); ?>">
			<?php if ( $item['image'] ) : ?>
				<figure class="postFeed-figure">
				<a
						href="<?php echo esc_url( $item['permalink'] ); ?>"
						<?php // phpcs:ignore ?>
						aria-label="<?php printf( __( 'Link to %s', 'dff' ), esc_attr( $item['title'] ) ); ?>"
						<?php // phpcs:ignore ?>
						title="<?php printf( __( 'Link to %s', 'dff' ), esc_attr( $item['title'] ) ); ?>"
					>
						<img src="<?php echo esc_url( $item['image'] ); ?>" srcset="<?php echo esc_url( $item['image@2x'] ); ?> 2x" alt="<?php echo esc_attr( $item['alt'] ?: $item['title'] ); ?>">
					</a>
				</figure>
			<?php endif; ?>
			<header class="postFeed-header">
				<?php
					printf( '<%s id="article-%s" class="postFeed-title"><a href="%s">%s</a></%s>',
						esc_attr( $tag_element ),
						esc_attr( $item['id'] ),
						esc_url( $item['permalink'] ),
						esc_html( $item['title'] ),
						esc_attr( $tag_element )
					);
				?>
				<?php if ( $item['term'] ) : ?>
					<a class="postFeed-tag" href="<?php echo esc_url( $item['term']['link'] ); ?>"><?php echo esc_html( $item['term']['name'] ); ?></a>
				<?php endif; ?>
			</header>
		</article>
		<?php
		return apply_filters( "dff_render_{$type}_simple_feed_item", ob_get_clean(), $item );
	}

	public function render( array $attributes, string $content ): ?string {
		if ( empty( $attributes['postTypes'] ) && empty( $attributes['taxonomies'] ) ) {
			return null;
		}

		$items = $this->get_items( $attributes );

		if ( empty( $items ) ) {
			return null;
		}

		$slider_enabled = $attributes['sliderEnabled'] ?? false;
		$slider_id      = uniqid( 'slider_' );
		$tag_element    = $this->tagGenerator( $attributes['headingTag'] ?? 'h3' );

		ob_start();
		?>
		<?php if ( $slider_enabled ) : ?>
			<div class="slider-container">
				<button class="slider-arrow prev" data-slider-prev="<?php echo esc_attr( $slider_id ); ?>" aria-label="<?php _e( 'Previous Posts', 'dff' ); ?>">
				<?php dff_asset( 'img/arrow.svg' ); ?>
				</button>
		<?php endif; ?>
			<div
				class="postFeed--simple<?php $slider_enabled && print ' has-slider'; ?>
				"<?php $slider_enabled && printf( ' data-slider="%s"', esc_attr( $slider_id ) ); ?>
				<?php
					! empty( $attributes['ariaLabel'] ) && printf( 'aria-label="%s"', esc_attr( $attributes['ariaLabel'] ) );
				?>
			>
				<?php foreach ( $items as $item ) : ?>
				<div class="postFeed-listItem">
					<?php echo wp_kses_post( $this->render_item( $item, $tag_element ) ); ?>
				</div>
				<?php endforeach; ?>
			</div>
		<?php if ( $slider_enabled ) : ?>
				<button class="slider-arrow next" data-slider-next="<?php echo esc_attr( $slider_id ); ?>" aria-label="<?php _e( 'Next Posts', 'dff' ); ?>">
				<?php dff_asset( 'img/arrow.svg' ); ?>
				</button>
			</div>
		<?php endif; ?>
		<?php
		return ob_get_clean();
	}
}

Styles::register( 'simple', new Simple() );
