<?php
/**
 * Removes default placement of related posts.
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'jetpackme_remove_rp' ) ) {
	function jetpackme_remove_rp() {
		if ( class_exists( 'Jetpack_RelatedPosts' ) ) {
			$jprp     = Jetpack_RelatedPosts::init();
			$callback = array( $jprp, 'filter_add_target_to_dom' );
			remove_filter( 'the_content', $callback, 40 );
		}
	}
}
add_action( 'wp', 'jetpackme_remove_rp', 20 );

/**
 * Removes default headline from related posts.
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'jetpackme_related_posts_headline' ) ) {
	function jetpackme_related_posts_headline( $headline ) {
		$headline = '';
		return $headline;
	}
}
add_filter( 'jetpack_relatedposts_filter_headline', 'jetpackme_related_posts_headline' );

function dff_related_posts( $size = 5 ) {
	$posts = [];

	if (
		class_exists( 'Jetpack_RelatedPosts' )
		&& method_exists( 'Jetpack_RelatedPosts', 'init_raw' )
	) {
		$related = Jetpack_RelatedPosts::init_raw()
			->set_query_name( 'dff-related-posts' ) // Optional, name can be anything.
			->get_for_post_id(
				get_the_ID(),
				[ 'size' => $size ]
			);

		if ( $related ) {
			foreach ( $related as $result ) {
				$id      = $result['id'];
				$posts[] = [
					'id'        => $id,
					'title'     => get_the_title( $id ),
					'date'      => get_the_time( 'j F Y', $id ),
					'permalink' => get_the_permalink( $id ),
				];
			}
		}
	}

	// Return a list of post titles separated by commas.
	return $posts;
}
