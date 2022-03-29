<?php

// phpcs:ignore
// add_filter( 'allowed_block_types', 'dff_allowed_block_types' );
add_filter( 'excerpt_length', 'dff_excerpt_length', 999 );
add_filter( 'body_class', 'dff_plugins_body_classes' );

add_filter( 'the_content', 'remove_empty_p', 20, 1 );

function remove_empty_p( $content ) {
	$content = force_balance_tags( $content );
	return preg_replace( '#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content );
}

function wpb_search_filter( $query ) {

	if ( $query->is_search && ! is_admin() ) {
		$options = get_option( 'global_options', '' );

		$exclude_from_search = $options['exclude_from_search'] ?? false;
		if ( $exclude_from_search ) {
			$exclude_from_search = explode( ',', $exclude_from_search );
			$exclude_from_search = array_map( function ( $id ) {
				return (int) $id;
			}, $exclude_from_search );

			$query->set( 'post__not_in', $exclude_from_search );
		}
	}

	return $query;
}
add_filter( 'pre_get_posts', 'wpb_search_filter' );
