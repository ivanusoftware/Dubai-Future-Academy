<?php

namespace DFF\Integrations\Programs;

use DateTime;

class Rest_Feed {
	public function __construct() {
		add_filter( 'dff_rest_post_feed_get_program_query', [ $this, 'get_feed_parameters' ], 10, 2 );

		add_filter( 'dff_get_category_program_taxonomy', function () {
			return 'facilitator';
		} );
		add_filter( 'dff_get_program_archive_filter_taxonomy', function () {
			return 'challenge-owners';
		} );
		add_filter( 'dff_get_program_archive_image_size', function () {
			return 'post-card';
		} );

		add_filter( 'post_type_archive_get_program_meta', function ( $dta, $id ) {
			$post = get_post_meta( $id, '_application_deadline', true );
			return date( 'M j, Y', strtotime( $post ) );
		}, 10, 2 );
	}

	public function get_feed_parameters( $query_args, $parameters = [] ) {
		if ( ! isset( $parameters['s'] ) ) {
			$show_past = 1 === (int) ( $parameters['showPast'] ?? 0 );

			$query_args['meta_query'] = [ // phpcs:ignore
				'date_query' => [
					'key'     => '_application_deadline',
					'value'   => date( DateTime::ISO8601 ),
					'compare' => $show_past ? '<' : '>=',
					'type'    => 'DATE',
				],
			];
		}

		if ( ! isset( $query_args['orderby'] ) || 'date' === $query_args['orderby'] ) {

			if ( ! isset( $parameters['order'] ) ) {
				$query_args['order'] = $show_past ? 'DESC' : 'ASC';
			}

			$query_args['orderby'] = [ 'date_query' => $query_args['order'] ];
		}

		return $query_args;
	}
}

new Rest_Feed();
