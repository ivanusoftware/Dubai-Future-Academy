<?php

/**
 * Register all Rest Endpoints.
 *
 * @link       https://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Events_Child_Plugin
 * @subpackage Events_Child_Plugin/includes
 */

class Events_Rest_Endpoints {


	/**
	 * Constructor.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		//Initialize the Rest End Point
		add_action( 'rest_api_init', array( $this, 'dff_rest_endpoints' ) );

	}

	public function dff_rest_endpoints() {
		// Get events categories.
		register_rest_route( 'dff', '/get_terms', array(
				'methods'  => 'GET',
				'callback' => array( $this, 'dff_get_terms' )
			)
		);
	}

	public function dff_get_terms( WP_REST_Request $request ) {

		$parameters = $request->get_params();
		$taxonomy = isset( $parameters['tax'] ) ? $parameters['tax'] : 'categories';

		$terms = get_terms( [
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
		] );

		$data = array();

		foreach ( $terms as $t ) {

			$id   = $t->term_id;
			$data[$id]['name'] = $t->name;

		}

		return $data;
	}


}

new Events_Rest_Endpoints();
