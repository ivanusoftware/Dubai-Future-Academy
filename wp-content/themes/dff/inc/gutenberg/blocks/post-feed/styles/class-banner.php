<?php
declare(strict_types = 1);
namespace DFF\Gutenberg\Blocks\PostFeed\Styles;

use DFF\Gutenberg\Blocks\PostFeed\Base_Style;
use DFF\Gutenberg\Blocks\PostFeed\Styles;

class Banner extends Base_Style {
	public $name = 'Banner';

	public function render( array $attributes, string $content ): ?string {
		if ( empty( $attributes['postTypes'] ) && empty( $attributes['taxonomies'] ) ) {
			return null;
		}

		$items = $this->get_items( $attributes );

		if ( empty( $items ) ) {
			return null;
		}

		ob_start();

		$background_image      = false;
		$has_featured_post     = $attributes['hasFeaturedPost'] ?? false;
		$has_custom_background = $attributes['backgroundId'];
		$tag_element           = $this->tagGenerator( $attributes['headingTag'] ?? 'h3' );


		if ( $has_featured_post ) {
			$featured_post    = array_shift( $items );
			$background_image = $featured_post['image'];

		} elseif ( ! empty( $has_custom_background ) ) {
			$background_image = $attributes['backgroundUrl'];

		} else {
			$background_image = $items[0]['image'] ?? false;
		}

		if ( count( $items ) > 4 ) {
			array_pop( $items );
		}
		?>
		<section
			class="postFeed postFeed--featured"
			<?php $background_image && printf( 'style="background-image: url(%s)"', esc_url( $background_image ) ); ?>
		>
			<div class="container">
				<?php if ( $has_featured_post ) : ?>
					<article class="postFeed-feature" aria-labelledby="article-<?php echo esc_attr( $featured_post['id'] ); ?>">
						<header>
						<?php if ( $featured_post['term'] ) : ?>
							<a href="<?php echo esc_url( $featured_post['term']['link'] ); ?>" class="postFeed-tag"><?php echo esc_html( $featured_post['term']['name'] ); ?></a>
						<?php endif; ?>
						<h1 id="article-<?php echo esc_attr( $featured_post['id'] ); ?>"><a href="<?php echo esc_url( $featured_post['permalink'] ); ?>"><?php echo esc_html( $featured_post['title'] ); ?></a></h1>
						</header>
						<?php if ( ! empty( $featured_post['excerpt'] ) ) : ?>
							<p><?php echo esc_html( $featured_post['excerpt'] ); ?></p>
						<?php endif; ?>
					</article>
				<?php else : ?>
					<div class="postFeed-feature">
						<?php echo wp_kses_post( $content ); ?>
					</div>
				<?php endif; ?>
			<?php if ( ! empty( $items ) ) : ?>
				<ul class="postFeed-list"
					<?php
						! empty( $attributes['ariaLabel'] ) && printf( 'aria-label="%s"', esc_attr( $attributes['ariaLabel'] ) );
					?>
				>
					<?php
					foreach ( $items as $item ) :
						?>
							<li>
								<article class="postFeed-item" aria-labelledby="article-<?php echo esc_attr( $item['id'] ); ?>">
									<header>
									<?php if ( $item['term'] ) : ?>
										<a href="<?php echo esc_url( $item['term']['link'] ); ?>" class="postFeed-tag"><?php echo esc_html( $item['term']['name'] ); ?></a>
									<?php endif; ?>
									<?php
										printf( '<%s id="article-%s" class="postFeed-title"><a href="%s">%s</a></%s>',
										esc_attr( $tag_element ),
										esc_attr( $item['id'] ),
										esc_url( $item['permalink'] ),
										esc_html( $item['title'] ),
										esc_attr( $tag_element )
									);
									?>
									</header>
								</article>
							</li>
						<?php
					endforeach;
					?>
				</ul>
			<?php endif; ?>
			</div>
		</section>
		<?php
		return ob_get_clean();
	}
}


Styles::register( 'banner', new Banner(), true );
