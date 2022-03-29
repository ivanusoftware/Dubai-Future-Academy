<?php
// phpcs:disable Squiz.PHP.CommentedOutCode.Found
/**
 * Sets allowed blocks for theme.
 *
 * @param array $allowed_blocks - allowed blocks.
 * @return array
 */
if ( ! function_exists( 'dff_allowed_block_types' ) ) {
	function dff_allowed_block_types() {
		return apply_filters( 'dff_allowed_block_types', [
			'core/block',
			'core/image',
			'core/paragraph',
			'core/heading',
			'core/list',
			'core/separator',
			'core/shortcode',
			'core/columns',
			'core/html',
			'core/social-links',
			'core/social-link',
			'dff/hero',
			'dff/section',
			'dff/column-builder',
			'dff/columns',
			'dff/column',
			'core/reusableBlock',
			'core-embed/vimeo',
			'core/table',
			'dff/post-feed',
			'dff/featured-posts',
			'dff/tabbed-posts',
			'dff/timeline',
			'dff/button',
		] );
	}
}

/**
 * Sets custom excerpt length.
 *
 * @param array $length - character length.
 * @return number
 */
if ( ! function_exists( 'dff_excerpt_length' ) ) {
	function dff_excerpt_length( $length ) {
		return 100;
	}
}

/**
 * Adds class to body when Jetpack is active.
 *
 * @param array $classes - array of body classes.
 * @return array
 */
if ( ! function_exists( 'dff_plugins_body_classes' ) ) {
	function dff_plugins_body_classes( $classes ) {
		require_once realpath( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( is_plugin_active( 'jetpack/jetpack.php' ) ) {
			$classes[] = 'jetpack-is-active';
		}
		return $classes;
	}
}

if ( ! function_exists( 'dff_get_permalink' ) ) {
	function dff_get_permalink( $id = false ) {
		if ( ! $id ) {
			$id = get_the_ID();
		}

		$permalink = apply_filters( 'dff_get_permalink', get_the_permalink( $id ), $id );
		$type      = get_post_type( $id );

		return apply_filters( "dff_get_{$type}_permalink", $permalink, $id );
	}
}
