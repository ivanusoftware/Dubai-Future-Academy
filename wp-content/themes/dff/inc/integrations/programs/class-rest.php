<?php

namespace DFF\Integrations\Programs;

use WP_REST_Request;

class Rest {
	protected $namespace = 'dff/v1/programs';

	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	public function register_routes() {
		register_rest_route( $this->namespace, 'sync', [
			'methods'             => [ 'GET' ],
			'callback'            => [ $this, 'sync' ],
			'permission_callback' => [ $this, 'permission_callback' ],
		] );
	}

	public function sync( WP_REST_Request $request ) {
		do_action( 'dff_sync_programs' );
		return rest_ensure_response( [
			'status'  => 'success',
			'message' => __( 'Successfully synchronised programs.', 'dff' ),
		] );
	}

	public function permission_callback() {
		return current_user_can( 'manage_options' );
	}
}

new Rest();
