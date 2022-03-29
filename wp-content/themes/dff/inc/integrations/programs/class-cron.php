<?php

namespace DFF\Integrations\Programs;

use WP_Query;

class Cron {
	public function __construct() {
		add_action( 'dff_sync_programs', [ $this, 'sync' ] );
		add_filter( 'cron_schedules', [ $this, 'schedules' ] );

		if ( ! wp_next_scheduled( 'dff_sync_programs' ) ) {
			wp_schedule_event( time(), 'every-1-hours', 'dff_sync_programs' );
		}

	}

	public function sync() {
		$programs = Api_Wrapper::get_programs();

		if ( is_wp_error( $programs ) ) {
			return;
		}

		$lang = get_locale();
		$lang = explode( '_', $lang, 2 );
		$lang = array_shift( $lang );

		$collection = [];

		foreach ( $programs as $program ) {
			if ( ! in_array( $lang, $program['availableLanguages'], true ) ) {
				continue;
			}

			$collection[] = $program['id'];

			new ProgramModel( $program['id'], $program );
		}

		$q = new WP_Query( [
			'post_type'      => 'program',
			'posts_per_page' => -1,
			'meta_query'     => [ // phpcs:ignore
				[
					'key'     => '_external_id',
					'value'   => $collection,
					'compare' => 'NOT IN',
				],
			],
		] );

		if ( $q->have_posts() ) {
			while ( $q->have_posts() ) {
				$q->the_post();
				wp_delete_post( get_the_ID() );
			}
		}
	}

	public function schedules( $schedules ) {
		$schedules['every-1-hours'] = [
			'interval' => HOUR_IN_SECONDS,
			'display'  => __( 'Every hour', 'dff' ),
		];

		return $schedules;
	}
}

new Cron();
