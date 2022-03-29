<?php

add_action( 'rest_api_init', function () {
	register_rest_field( get_post_types(), 'category', [
		'get_callback' => 'dff_rest_get_category',
	] );
} );


function dff_rest_get_category( $post ) {
	return dff_get_category( $post['id'] );
}

function dff_get_category( $id ) {
	$taxonomy = 'category';

	$post_type = get_post_type( $id );
	$taxonomy  = apply_filters( "dff_get_category_{$post_type}_taxonomy", $taxonomy );

	if ( ! has_term( '', $taxonomy, $id ) ) {
		return false;
	}

	$terms = wp_get_post_terms( $id, $taxonomy );
	$term  = $terms[0] ?? false;

	if ( ! $term || 'uncategorized' === $term->slug ) {
		return false;
	}

	return $term;
}

function dff_get_posttype_category_name( string $post_type ) {
	$taxonomy = 'category';
	$taxonomy = apply_filters( "dff_get_category_{$post_type}_taxonomy", $taxonomy );

	return $taxonomy;
}

function dff_get_post_category_name( $id ) {
	$post_type = get_post_type( $id );

	return dff_get_posttype_category_name( $post_type );
}

function dff_get_posttype_archive( $post_type ) {
	$link = get_post_type_archive_link( $post_type );

	if ( $link ) {
		return $link;
	}

	$options = get_option( 'global_options', '' );

	switch ( $post_type ) {
		case 'event':
			return $options['events_archive_url'] ?? '';
		case 'programs':
			return $options['programs_archive_url'] ?? '';
	}
}

function dff_get_archive_query_parameters( $post_type, $term, $taxonomy ) {
	$active_term_taxonomy = apply_filters( "dff_get_{$post_type}_archive_filter_taxonomy", 'category' );

	if ( 'post' === $post_type ) {
		if ( 'article-type' === $taxonomy ) {
			return "article-type={$term}";
		}

		return "filter={$term}";
	}

	if ( $taxonomy === $active_term_taxonomy ) {
		return "category={$term}";
	}

	return "filter={$term}";
}

function dff_get_posttype_category_link( $post_type, $term, $taxonomy ) {
	$base_url     = dff_get_posttype_archive( $post_type );
	$query_params = dff_get_archive_query_parameters( $post_type, $term, $taxonomy );

	return "{$base_url}?{$query_params}";
}
