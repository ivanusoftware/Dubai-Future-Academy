<?php
/**
 * Returns excerpt of a post with desired character limit.
 *
 * @since 1.0.0
 * @param int $limit Character amount you want to limit by.
 * @return string
 */
if ( ! function_exists( 'dff_get_excerpt' ) ) {
	function dff_get_excerpt( $limit, $post_id = null ) {
		if ( isset( $post_id ) ) {
			$excerpt = get_the_excerpt( $post_id );
			if ( has_excerpt( $post_id ) ) {
				return $excerpt;
			}
		} else {
			$excerpt = get_the_excerpt();
			if ( has_excerpt() ) {
				return $excerpt;
			}
		}
		if ( strlen( $excerpt ) < $limit ) {
			$excerpt = preg_replace( '(\[.*?\])', '', $excerpt );
			$excerpt = $excerpt . '...';
		} else {
			$excerpt = preg_replace( '(\[.*?\])', '', $excerpt );
			$excerpt = strip_shortcodes( $excerpt );
			$excerpt = wp_strip_all_tags( $excerpt );
			$excerpt = substr( $excerpt, 0, strpos( $excerpt, ' ', $limit ) );
			$excerpt = trim( preg_replace( '/\s+/', ' ', $excerpt ) );
			$excerpt = $excerpt . '...';
		}

		if ( '...' === $excerpt ) {
			return '';
		} else {
			return $excerpt;
		}
	}
}

/**
 * Returns title of a post with desired character limit.
 *
 * @since 1.0.0
 * @param int $limit Character amount you want to limit by.
 * @return string
 */
if ( ! function_exists( 'dff_get_title' ) ) {
	function dff_get_title( $limit ) {
		$title = get_the_title();
		if ( strlen( $title ) < $limit ) {
			return $title;
		} else {
			$title = preg_replace( '(\[.*?\])', '', $title );
			$title = strip_shortcodes( $title );
			$title = wp_strip_all_tags( $title );
			$title = substr( $title, 0, $limit );
			$title = trim( preg_replace( '/\s+/', ' ', $title ) );
			$title = $title . '...';
			return $title;
		}
	}
}

/**
 * Returns readtime of a post based on words per min.
 *
 * @since 1.0.0
 * @param int $words_per_min Words per min.
 * @return string
 */
if ( ! function_exists( 'dff_get_readtime' ) ) {
	function dff_get_readtime( $words_per_min ) {
		$mycontent = get_the_content();
		$word      = str_word_count( wp_strip_all_tags( $mycontent ) );
		$m         = round( floor( $word / $words_per_min ) );
		if ( empty( $m ) ) {
			$est = '< 1 minute';
			return $est;
		} else {
			$est = $m . ' min' . ( 1 == $m ? '' : 's' ) . '';
			return $est;
		}
	}
}
