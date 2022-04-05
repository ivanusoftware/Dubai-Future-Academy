<?php

require_once __DIR__ . '/vendor/autoload.php';

// Load text domains
add_action('after_setup_theme', function () {
	load_theme_textdomain( 'dff', get_template_directory() . '/languages' );
});

require_once realpath( __DIR__ . '/inc/asset-settings.php' );

// Global Options
require_once realpath( __DIR__ . '/inc/options/index.php' );

// Gutenberg
require_once realpath( __DIR__ . '/inc/theme-support.php' );
require_once realpath( __DIR__ . '/inc/gutenberg/class-loader.php' );

// Frontend
require_once realpath( __DIR__ . '/inc/frontend/loader.php' );
require_once realpath( __DIR__ . '/inc/frontend/theme-support.php' );

// Functions
require_once realpath( __DIR__ . '/inc/functions/assets.php' );
require_once realpath( __DIR__ . '/inc/functions/pagination.php' );
require_once realpath( __DIR__ . '/inc/functions/meta.php' );
// Filters
require_once realpath( __DIR__ . '/inc/filter-functions.php' );
require_once realpath( __DIR__ . '/inc/filters.php' );


// Custom post media function
require_once realpath( __DIR__ . '/inc/media.php' );

// Custom function to get excerpts and titles
require_once realpath( __DIR__ . '/inc/get-post-meta.php' );
require_once realpath( __DIR__ . '/inc/post-meta.php' );

// String manipulation helpers
require_once realpath( __DIR__ . '/inc/string-manipulation-helpers.php' );
require_once realpath( __DIR__ . '/inc/array-helpers.php' );

require_once realpath( __DIR__ . '/inc/mlp-helpers.php' );

// Nav
require_once realpath( __DIR__ . '/inc/navigation.php' );
require_once realpath( __DIR__ . '/inc/class-nav-megamenu.php' );
require_once realpath( __DIR__ . '/inc/class-nav-megamenu-walker.php' );

require_once realpath( __DIR__ . '/inc/allowed-content.php' );
require_once realpath( __DIR__ . '/inc/jetpack.php' );

require_once realpath( __DIR__ . '/inc/post-types/index.php' );

require_once realpath( __DIR__ . '/inc/integrations/index.php' );

require_once realpath( __DIR__ . '/inc/rest-api/index.php' );
require_once realpath( __DIR__ . '/includes/template-functions.php' );

/**
 * Simulate non-empty content to enable Gutenberg editor in posts page.
 *
 * @param bool replace Whether to replace the editor.
 * @param WP_Post $post Post object.
 * @return bool
 */
function enable_gutenberg_editor_for_blog_page( $replace, $post ) {
	if ( ! $replace && absint( get_option( 'page_for_posts' ) ) === $post->ID && empty( $post->post_content ) ) {
		$post->post_content = '<!--non-empty-content-->';
	}
	return $replace;
}

add_filter( 'replace_editor', 'enable_gutenberg_editor_for_blog_page', 10, 2 );

if ( class_exists( 'WP_CLI' ) ) {
	require_once __DIR__ . '/inc/cli/index.php';
}

add_filter( 'wpcf7_autop_or_not', '__return_false' );






