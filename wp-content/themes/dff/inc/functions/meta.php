<?php

/**
 * The same as get_post_meta but switches the arguments order round
 * so only the meta key is required.
 *
 * @since 1.0.0
 * @param string   $field   Desired meta key.
 * @param bool|int $post_id Post id.
 * @param bool     $single  Should this return a single piece of meta data.
 * @return mixed
 */
if ( ! function_exists( 'twoyou_get_meta_field' ) ) {
	function twoyou_get_meta_field( $field, $post_id = false, $single = true ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();

			if ( is_home() ) {
				$post_id = get_option( 'page_for_posts' );
			}

			if ( is_front_page() ) {
				$post_id = get_option( 'page_on_front' );
			}
		}

		return get_post_meta( $post_id, $field, $single );
	}
}
