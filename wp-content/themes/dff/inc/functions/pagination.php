<?php

/**
 * Custom Pagination
 *
 */
if ( ! function_exists( 'dff_pagination' ) ) {
	function dff_pagination() {
		global $wp_query;
		$big = 999999999;

		echo wp_kses_post(
			paginate_links( array(
				'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format'    => '?paged=%#%',
				'current'   => max( 1, get_query_var( 'paged' ) ),
				'total'     => $wp_query->max_num_pages,
				'prev_next' => false,
			)
		) );
	}
}
