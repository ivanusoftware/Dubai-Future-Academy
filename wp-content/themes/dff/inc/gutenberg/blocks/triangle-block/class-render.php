<?php

namespace DFF\Gutenberg\Blocks\TriangleBlock;

class Triangle_Block {

	public function __construct() {
		register_block_type( 'dff/triangle-block', [
			'render_callback' => [ $this, 'render' ],
			'editor_script'   => 'dff-gutenberg-scripts',
		] );
	}

	public function render( array $attributes, string $content ) {

		$text_column_one      = $attributes['textColumnOne'] ?? '';
		$text_column_two      = $attributes['textColumnTwo'] ?? '';
		$text_column_three    = $attributes['textColumnThree'] ?? '';
		$triangle_image_large = $attributes['triangleImageLarge'] ?? '';
		$triangle_image_small = $attributes['triangleImageSmall'] ?? '';
		$prefix               = $attributes['key'] ?? '';


		ob_start();
		?>
		<div class="triangleBlock">
			<div class="triangle-left">
				<header class="triangle-heading">
					<?php echo wp_kses_post( $content ); ?>
				</header>
				<div class="triangle-columnOne">
					<?php echo $text_column_one ? '<p>' . esc_html( $text_column_one ) . '</p>' : ''; ?>
				</div>
				<div class="triangle-columnTwo">
					<?php echo $text_column_two ? '<p>' . esc_html( $text_column_two ) . '</p>' : ''; ?>
				</div>
				<div class="triangle-columnThree">
					<?php echo $text_column_three ? '<p>' . esc_html( $text_column_three ) . '</p>' : ''; ?>
				</div>
			</div>
			<div class="triangle-right">
				<?php if ( ! empty( $triangle_image_large ) ) : ?>
				<div class="triangle-image triangle-image--large">
					<?php
					printf(
						'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" nclassName="triangle-custom-one" viewBox="0 0 473.912 406.211" alt="%s">
							<defs>
							<pattern
								id="%s-a"
								preserveAspectRatio="xMidYMid slice"
								width="%s"
								height="%s"
								viewBox="0 0 %s %s"
							>
								<image
								width="%s"
								height="%s"
								href="%s"
								/>
							</pattern>
							</defs>
							<path
							d="M236.956 512 473.912 105.789H0Z"
							transform="translate(0, -106)"
							fill="url(#%s-a)"
							/>
						</svg>',
						esc_attr( $triangle_image_large['alt'] ?? '' ),
						esc_attr( $prefix ),
						'100%',
						'100%',
						esc_attr( $triangle_image_large['media_details']['width'] ),
						esc_attr( $triangle_image_large['media_details']['height'] ),
						esc_attr( $triangle_image_large['media_details']['width'] ),
						esc_attr( $triangle_image_large['media_details']['height'] ),
						esc_attr( $triangle_image_large['source_url'] ),
						esc_attr( $prefix ),
					);
					?>
				</div>
				<?php endif; ?>

				<?php if ( ! empty( $triangle_image_small ) ) : ?>
				<div class="triangle-image triangle-image--small">
					<?php
					printf(
						'<svg className="triangle-custom-two" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 236.456 202.147" alt="%s">
						<defs>
						  <pattern
							id="%s-b"
							preserveAspectRatio="xMidYMid slice"
							width="%s"
							height="%s"
							viewBox="0 0 %s %s"
						  >
							<image
							  width="%s"
							  height="%s"
							  href="%s"
							/>
						  </pattern>
						</defs>
						<path d="M118.228,0,236.456,202.147H0Z" fill="url(#%s-b)" />
					  </svg>',
						esc_attr( $triangle_image_small['alt'] ?? '' ),
						esc_attr( $prefix ),
						'100%',
						'100%',
						esc_attr( $triangle_image_small['media_details']['width'] ),
						esc_attr( $triangle_image_small['media_details']['height'] ),
						esc_attr( $triangle_image_small['media_details']['width'] ),
						esc_attr( $triangle_image_small['media_details']['height'] ),
						esc_attr( $triangle_image_small['source_url'] ),
						esc_attr( $prefix ),
					);
					?>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}

new Triangle_Block();
