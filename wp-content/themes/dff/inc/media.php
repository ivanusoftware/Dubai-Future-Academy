<?php
/**
 * Prevent WordPress from overly compressing .jpg files.
 *
 * @since 1.0.0
 */
function dff_image_quality() {
	return 100;
}

add_filter( 'jpeg_quality', 'dff_image_quality' );
add_filter( 'wp_editor_set_quality', 'dff_image_quality' );

/**
 * Return the image url of a posts featured image at the desired size.
 *
 * @since 1.0.0
 * @param int|bool $post_id The desired post's ID.
 * @param string   $size    The desired image size.
 * @return bool|string
 */
if ( ! function_exists( 'dff_featured_image' ) ) {
	function dff_featured_image( $post_id = false, $size = 'full' ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		$img_url = get_the_post_thumbnail_url( $post_id, $size );

		if ( ! $img_url ) {
			/**
			 * Sets the feature image to a default if non set
			 *
			 * @since 1.0.0
			 *
			 * @param string $image_path String path to default image.
			 */
			return false;
			// Need to add default image.
			// return apply_filters( 'dff_default_featured_image', '/wp-content/themes/dff/assets/images/default.png' );
		}

		return $img_url;
	}
}

/**
 * Return true/false if there is a featured image on the post
 *
 * @since 1.0.0
 * @param int|bool $post_id The desired post's ID.
 * @return bool
 */
if ( ! function_exists( 'dff_has_featured_image' ) ) {

	function dff_has_featured_image( $post_id = false ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		$post_thumbnail_id = get_post_thumbnail_id( $post_id );

		return ! ! $post_thumbnail_id;
	}
}

/**
 * Gets array of image sizes
 * 'example' => array( 376, 282, true ),
 *
 * @since 1.0.0
 * @return array
 */
if ( ! function_exists( 'get_theme_image_sizes' ) ) {
	function get_theme_image_sizes() {
		return [
			'thumb'                  => [ 200, 200, true ],
			'featured-post'          => [ 900, 510, true ],
			'featured-post@2x'       => [ 1800, 1020, true ],
			'post-feed'              => [ 236, 170, true ],
			'post-feed@2x'           => [ 472, 340, true ],
			'post-feed-large'        => [ 305, 168, true ],
			'post-feed-large@2x'     => [ 610, 336, true ],
			'post-card'              => [ 305, 400, true ],
			'post-card@2x'           => [ 610, 800, true ],
			'card'                   => [ 669, 376, true ],
			'card@2x'                => [ 1338, 752, true ],
			'full-size-image-small'  => [ 480, 0, false ],
			'full-size-image-medium' => [ 600, 0, false ],
			'full-size-image-large'  => [ 940, 0, false ],
			'full-size-image-xlarge' => [ 1320, 0, false ],
		];
	}
}

/**
 * Loop through and add all of the image sizes declared in `get_theme_image_sizes`.
 *
 * @since 1.0.0
 * @return void
 */
if ( ! function_exists( 'dff_theme_image_sizes' ) ) {
	function dff_theme_image_sizes() {
		foreach ( get_theme_image_sizes() as $label => $dims ) {
			add_image_size( $label, $dims[0], $dims[1], $dims[2] );
		}
	}
}

add_action( 'after_setup_theme', 'dff_theme_image_sizes' );

/**
 * Adds image size options to editor choice by taking size list
 * and making array with spaced words from hyphenated size names.
 *
 * @since 1.0.0
 * @param array $sizes List of sizes available.
 * @return array
 */
if ( ! function_exists( 'dff_image_size_select' ) ) {
	function dff_image_size_select( $sizes = [] ) {
		foreach ( array_keys( get_theme_image_sizes() ) as $size ) {
			$sizes[ $size ] = ucwords( preg_replace( '/[-@]/', ' ', $size ) );
		}

		ksort( $sizes );

		// Remove thumbnail image size from dropdown.
		unset( $sizes['thumbnail'] );

		// Move full size to bottom of dropdown.
		$value = $sizes['full'];
		unset( $sizes['full'] );
		$sizes['full'] = $value;

		return $sizes;
	}
}

add_filter( 'image_size_names_choose', 'dff_image_size_select' );

/**
 * Removes default WordPress image sizes.
 *
 * @since 1.0.0
 * @return void
 */
function dff_remove_default_images( $sizes ) {
	unset( $sizes['small'] );
	unset( $sizes['medium'] );
	unset( $sizes['large'] );
	unset( $sizes['medium_large'] );
	return $sizes;
}
add_filter( 'intermediate_image_sizes_advanced', 'dff_remove_default_images' );

/**
 * Get an attachment ID given a URL.
 *
 * @param string $url
 *
 * @return int Attachment ID on success, 0 on failure
 */
function get_attachment_id( $url ) {
	$attachment_id = 0;
	$dir           = wp_upload_dir();

	if ( false !== strpos( $url, $dir['baseurl'] . '/' ) ) {
		$file       = basename( $url );
		$query_args = array(
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'fields'      => 'ids',
			'meta_query'  => array( // phpcs:ignore
				array(
					'value'   => $file,
					'compare' => 'LIKE',
					'key'     => '_wp_attachment_metadata',
				),
			),
		);

		$query = new WP_Query( $query_args );

		if ( $query->have_posts() ) {
			foreach ( $query->posts as $post_id ) {
				$meta                = wp_get_attachment_metadata( $post_id );
				$original_file       = basename( $meta['file'] );
				$cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );

				if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) {
					$attachment_id = $post_id;
					break;
				}
			}
		}
	}
	return $attachment_id;
}

/**
 * Allows SVG's in the content.
 */
add_filter( 'wp_kses_allowed_html', function( $tags ) {
	$tags['svg']  = array(
		'xmlns'       => array(),
		'fill'        => array(),
		'viewbox'     => array(),
		'role'        => array(),
		'aria-hidden' => array(),
		'focusable'   => array(),
	);
	$tags['path'] = array(
		'd'    => array(),
		'fill' => array(),
	);
	return $tags;
}, 10, 2);
