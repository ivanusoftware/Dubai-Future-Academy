<?php
declare(strict_types = 1);

namespace DFF\Gutenberg\Blocks\PostFeed;

class Render {
	public function __construct() {
		register_block_type(
			'dff/post-feed',
			[
				'render_callback' => [ $this, 'render' ],
				'editor_script'   => 'dff-gutenberg-scripts',
			]
		);

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_block_styles' ] );
	}

	public function enqueue_block_styles(): void {
		$styles = Styles::get_all();

		wp_localize_script( 'dff-gutenberg-scripts', 'postFeedStyles', array_map( function( $value, $key ) {
			return [
				'name'    => $value->name,
				'style'   => $key,
				'default' => Styles::is_default( $key ),
			];
		}, $styles, array_keys( $styles ) ) );
	}

	public function render( array $attributes, $content ) : ?string {
		$class = Styles::get( $attributes['style'] ?? '' );

		if ( ! $class ) {
			return null;
		}

		return $class->render( $attributes, $content );
	}
}

new Render();
