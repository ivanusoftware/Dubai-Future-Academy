<?php

namespace DFF\Gutenberg;

require_once __DIR__ . '/blocks/class-featured-posts.php';
require_once __DIR__ . '/blocks/post-feed.php';
require_once __DIR__ . '/blocks/class-tabbed-posts.php';
require_once __DIR__ . '/blocks/class-post-type-archive.php';
require_once __DIR__ . '/blocks/sub-menu/class-render.php';
require_once __DIR__ . '/blocks/image-slider/class-render.php';
require_once __DIR__ . '/blocks/triangle-block/class-render.php';

class Loader {
	public function __construct() {
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_assets' ] );
		add_filter( 'block_categories', [ $this, 'register_category' ], 100 );
		/**
		 * Removes the ability to add custom font sizes and colors in the backend.
		 *
		 */
		add_theme_support( 'disable-custom-font-sizes' );
		add_theme_support( 'disable-custom-colors' );

		/**
		 * Removes the color palette
		 *
		 */
		add_theme_support( 'editor-color-palette', [] );
	}

	public function enqueue_assets() {
		wp_enqueue_script(
			'dff-gutenberg-scripts',
			get_template_directory_uri() . '/dist/scripts/' . DFF_GUTENBERG_JS,
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-block-editor', 'wp-plugins', 'wp-edit-post' ),
			filemtime( get_template_directory() . '/dist/scripts/' . DFF_GUTENBERG_JS ),
			false
		);

		wp_enqueue_style(
			'dff-gutenberg-styles',
			get_template_directory_uri() . '/dist/styles/' . DFF_GUTENBERG_CSS,
			[],
			filemtime( get_template_directory() . '/dist/styles/' . DFF_GUTENBERG_CSS ),
			'all'
		);

		$options = get_option( 'global_options', '' );

		if ( $options ) {
			$gmaps_api_key = $options['google_maps_api_key'] ?? false;
		}

		if ( $gmaps_api_key ) {
			wp_enqueue_script( 'maps-scripts', 'https://maps.googleapis.com/maps/api/js?key=' . $gmaps_api_key . '&libraries=places', [], '1.1.0', false );
		}
	}

	public function register_category( array $categories = [] ) : array {
		array_splice( $categories, 1, 0, [
			[
				'slug'  => 'dff',
				'title' => __( 'DFF', 'dff' ),
			],
		] );

		return $categories;
	}
}

new Loader();
