<?php

namespace DFF\Integrations\Events;

class Rest_Events {
	public function __construct() {
		add_filter( 'dff_rest_post_feed_get_dffmain-events_query', [ $this, 'get_feed_parameters' ], 10, 2 );
		add_filter( 'dff_get_category_dffmain-events_taxonomy', function () {
			return 'events_categories';
		} );
		add_filter( 'dff_get_dffmain-events_archive_filter_taxonomy', function () {
			return 'events_tags';
		} );
		add_filter( 'dff_get_dffmain-events_archive_image_size', function () {
			return 'post-card';
		} );

		add_filter( 'post_type_archive_get_dffmain-events_permalink', function ( $link, $id ) {
			return $link;
			$base_url = trim( EVENT_SOURCE_URL, '/' ) . '/';
			$slug     = get_post_meta( $id, 'event_slug', true );

			if ( $slug ) {
				return $base_url . $slug;
			}

			$eid = get_post_meta( $id, 'eid', true );
			return $base_url . '?p=' . $eid;
		}, 10, 2 );

		add_filter( 'post_type_archive_get_dffmain-events_meta', function ( $dta, $id ) {
			$post_start = get_post_meta( $id, 'event_date_select', true );
			$post_end   = get_post_meta( $id, 'event_end_date_select', true );

			if ( ( strtotime( $post_start ) === strtotime( $post_end ) || empty( $post_end ) ) ) {
				return date( 'j M', strtotime( $post_start ) );
			}

			return date( 'j M', strtotime( $post_start ) ) . ' - ' . date( 'j M', strtotime( $post_end ) );
		}, 10, 2 );
	}

	public function get_feed_parameters( $query_args, $parameters = [] ) {
		if ( ! isset( $parameters['s'] ) ) {
			$show_past = 1 === (int) ( $parameters['showPast'] ?? 0 );

			$query_args['meta_query'] = [ // phpcs:ignore
				'date_query' => [
					// [
					// 	'key'     => 'upcoming',
					// 	'value'   => 'yes',
					// 	'compare' => $show_past ? '!=' : '=',
					// ],
					[
						'key'     => 'event_date_select',
						'value'   => date( 'Y-m-d' ),
						'type'    => 'DATE',
						'compare' => $show_past ? '<' : '>=',
					],
				],
			];
		}

		if ( ! isset( $query_args['orderby'] ) || 'date' === $query_args['orderby'] ) {

			if ( ! isset( $parameters['order'] ) ) {
				$query_args['order'] = $show_past ? 'DESC' : 'ASC';
			}

			$query_args['orderby']  = 'meta_value';
			$query_args['meta_key'] = 'event_date_select';
		}
		return $query_args;
	}
}

new Rest_Events();
