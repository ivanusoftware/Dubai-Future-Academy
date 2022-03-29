<?php

add_action( 'rest_api_init', function () {
	register_rest_field( get_post_types_by_support( 'thumbnail' ), 'featuredImage', [
		'get_callback' => 'dff_get_all_featured_media_sizes',
	] );

	register_rest_field( 'attachment', 'alt', [
		'get_callback' => 'dff_rest_get_alt_tag',
	] );
} );

add_action( 'init', function () {
	foreach ( get_post_types() as $post_type ) {
		add_filter( 'rest_prepare_' . $post_type, 'remove_yoast_head', 999 );
	}
}, 999 );

// Remove yoast_head from attachment response as its not needed and causes unnecessary errors.
function remove_yoast_head( WP_REST_Response $result ) {
	if ( isset( $result->data['yoast_head'] ) ) {
		unset( $result->data['yoast_head'] );
	}

	return $result;
}

function dff_get_media_metadata( $post_id ) {
	$thumbnail_id = get_post_thumbnail_id( $post_id );
	return wp_get_attachment_metadata( $thumbnail_id );
}

function dff_get_all_featured_media_sizes( $post ) {
	$sizes  = array_keys( get_theme_image_sizes() );
	$images = [];
	$meta   = dff_get_media_metadata( $post['id'] );


	foreach ( $sizes as $size ) {
		$images[ $size ] = [
			'source_url' => dff_featured_image( $post['id'], $size ),
			'height'     => $meta['sizes'][ $size ]['height'] ?? false,
			'width'      => $meta['sizes'][ $size ]['width'] ?? false,
		];
	}

	$images['full'] = dff_featured_image( $post['id'], 'full' );

	return $images;
}

function dff_rest_get_alt_tag( $post ) {
	return dff_get_alt_tag( $post['id'] );
}



function dff_get_alt_tag( $id ) {
	$alt = get_post_meta ( $id, '_wp_attachment_image_alt', true );

	if ( ! $alt ) {
		$alt = wp_get_attachment_caption( $id );
	}

	return $alt;
}
