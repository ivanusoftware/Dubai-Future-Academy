<?php

class Redirect_Styles {
	protected $allowed_resources = [];

	public function __construct() {
		add_filter( 'query_vars', [ $this, 'register_query_vars' ] );
		add_action( 'init', [ $this, 'register_stylesheet_route' ] );
		add_action( 'parse_request', [ $this, 'handle_redirect_stylesheet' ], 10 );

		$this->allowed_resources['header'] = defined( 'DFF_HEADER_CSS' ) ? DFF_HEADER_CSS : false;
	}

	/**
	 * Register the required query vars for the capi image api.
	 *
	 * @param array $query_vars - current query vars.
	 * @return array
	 */
	public function register_query_vars( $query_vars = [] ) {
		$query_vars[] = 'stylesheet';
		$query_vars[] = 'dff-private';
		return $query_vars;
	}

	/**
	 * Register the wp route for redirecting to stylesheet.
	 */
	public function register_stylesheet_route() {
		add_rewrite_rule( '^dff/private/stylesheet/(?P<stylesheet>\w+)/?', 'index.php?dff-private=stylesheet&stylesheet=$matches[1]', 'top' );
	}

	/**
	 * Return the image from the private api.
	 *
	 * @param WP $wp - Current WordPress class.
	 * @return mixed
	 */
	public function handle_redirect_stylesheet( WP &$wp ) {
		if (
			! array_key_exists( 'dff-private', $wp->query_vars )
			|| ! array_key_exists( 'stylesheet', $wp->query_vars )
		) {
			return null;
		}

		$stylesheet = $wp->query_vars['stylesheet'] ?? false;

		if ( ! $stylesheet || ! $this->allowed_resources[ $stylesheet ] ) {
			$wp->handle_404();
			die;
		}

		$stylesheet_uri = get_template_directory_uri() . '/dist/styles/' . $this->allowed_resources[ $stylesheet ];

		header( 'Location: ' . $stylesheet_uri );
	}
}

new Redirect_Styles();
