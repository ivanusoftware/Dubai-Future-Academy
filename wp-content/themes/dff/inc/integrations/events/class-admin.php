<?php

namespace DFF\Integrations\Events;

class Admin {
	public function __construct() {
		add_filter( 'manage_dffmain-events_posts_columns', [ $this, 'register_column' ] );
		add_action( 'manage_dffmain-events_posts_custom_column', [ $this, 'add_column' ], 10, 2 );
		add_filter( 'manage_edit-dffmain-events_sortable_columns', [ $this, 'make_sortable' ] );

		add_action( 'load-edit.php', [ $this, 'add_sort_request' ] );
	}

	public function register_column( array $columns ): array {
		$columns['event_date'] = 'Event Date';
		return $columns;
	}

	public function add_column( string $column, $post_id ): void {
		switch ( $column ) {
			case 'event_date':
				echo esc_html( get_post_meta( $post_id, 'event_date_select', true ) );
				break;
		}
	}

	public function make_sortable( array $columns ): array {
		$columns['event_date'] = 'event_date';
		return $columns;
	}

	public function add_sort_request(): void {
		add_filter( 'request', [ $this, 'do_sortable' ] );
	}

	public function do_sortable( array $vars ): array {
		// check if post type is being viewed
		if ( isset( $vars['post_type'] ) && 'dffmain-events' === $vars['post_type'] ) {

			// check if sorting has been applied
			if ( isset( $vars['orderby'] ) && 'event_date' === $vars['orderby'] ) {

				// apply the sorting to the post list
				$vars = array_merge(
					$vars,
					array(
						'meta_key' => 'event_date_select',
						'orderby'  => 'meta_value',
					)
				);
			}
		}

		return $vars;
	}
}

new Admin();
