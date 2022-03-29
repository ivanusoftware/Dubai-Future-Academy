<?php

namespace DFF\Gutenberg\Blocks\ImageSlider;

class Image_Slider {

	// register block type on construct
	public function __construct() {
		register_block_type( 'dff/image-slider', [
			'render_callback' => [ $this, 'render' ],
			'editor_script'   => 'dff-gutenberg-scripts',
		] );
	}

	public function render( array $attributes ) {

		$items_visible = $attributes['itemsVisible'] ?? '2';
		$images        = $attributes['images'] ?? [];
		$slider_id     = uniqid( 'slider_' );

		if ( count( $images ) === 0 ) {
			return;
		}

		ob_start();
		?>
			<div class="imageSlider imageSlider--items-<?php echo esc_attr ( $items_visible ); ?>">
				<button class="slider-arrow prev" data-slider-prev="<?php echo esc_attr( $slider_id ); ?>" aria-label="<?php _e( 'Previous Image', 'dff' ); ?>">
				<?php dff_asset( 'img/arrow.svg' ); ?>
				</button>
				<div class="imageSlider-imageList" data-slider="<?php echo esc_attr( $slider_id ); ?>">
					<?php
					foreach ( $images as $image ) {
						printf(
							'<div class="imageList-imageItem"><img src="%s" alt="%s" /></div>' . PHP_EOL,
							esc_attr( $image['sizes']['card@2x']['url'] ?? $image['url'] ),
							esc_attr( $image['alt'] )
						);
					}
					?>
				</div>
				<button class="slider-arrow next" data-slider-next="<?php echo esc_attr( $slider_id ); ?>" aria-label="<?php _e( 'Next Image', 'dff' ); ?>">
				<?php dff_asset( 'img/arrow.svg' ); ?>
				</button>
			</div>
		<?php
		return ob_get_clean();
	}
}

new Image_Slider();
