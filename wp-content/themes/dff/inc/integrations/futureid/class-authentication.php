<?php

namespace DFF\Integrations\FutureID;

use DFF\Integrations\Request;
use Exception;
use InvalidArgumentException;
use WP_Error;
use WP_REST_Request;

class Authentication {
	public function __construct() {
		$options = get_option( 'global_options', '' );

		$this->auth_url      = $options['auth_url'] ?? '';
		$this->client_secret = $options['client_secret'] ?? '';
		$this->client_id     = $options['client_id'] ?? '';
		$this->id_url        = $options['identity_url'] ?? '';

		$this->provider = new \League\OAuth2\Client\Provider\GenericProvider( [
			'clientId'                => $options['client_id'] ?? '',
			'clientSecret'            => $options['client_secret'] ?? '',
			'redirectUri'             => ( $options['redirect_uri'] ?? '' ) ?: home_url( '/' ),
			'urlAuthorize'            => $this->auth_url . '/authorize',
			'urlAccessToken'          => $this->auth_url . '/token',
			'urlResourceOwnerDetails' => ( $options['identity_url'] ?? '' ) . '/user',
			'scopes'                  => [ 'user.email' ],
		] );

		$this->provider->setOptionProvider( new HttpHeaderAuthOptionProvider() );

		add_action( 'init', [ $this, 'refresh_token' ] );
		add_filter( 'query_vars', [ $this, 'register_query_vars' ] );
		add_action( 'init', [ $this, 'register_login_route' ] );
		add_action( 'rest_api_init', [ $this, 'register_rest_routes' ] );
		add_action( 'parse_request', [ $this, 'authorize_token' ], 10 );
		add_action( 'parse_request', [ $this, 'handle_login' ], 10 );
		add_action( 'parse_request', [ $this, 'handle_logout' ], 10 );
	}

	public function register_login_route(): void {
		add_rewrite_rule( '^oauth/login$', 'index.php?dff-action=login', 'top' );
		add_rewrite_rule( '^oauth/authorize$', 'index.php?dff-action=authorize', 'top' );
		add_rewrite_rule( '^oauth/logout$', 'index.php?dff-action=logout', 'top' );
	}

	public function register_query_vars( array $query_vars ): array {
		$query_vars[] = 'dff-action';
		return $query_vars;
	}

	public function do_remove( $id ): void {
		setcookie( '_fid_dff_uid', '0', time() - 60 * 60, '/', 'dubaifuture.ae' ); // phpcs:ignore
		setcookie( 'fid-is-loggedin', '0', time() - 60 * 60, '/', 'dubaifuture.ae' ); // phpcs:ignore
		setcookie( 'oauth2state', '', time() - 90, '/', 'dubaifuture.ae' ); // phpcs:ignore
		setcookie( '_dff_return_to', '', time() - 90, '/', 'dubaifuture.ae' ); // phpcs:ignore

		delete_transient( '_dff_user_' . $id );
	}

	public function handle_login( \WP $wp ): void {

		if (
			! array_key_exists( 'dff-action', $wp->query_vars )
			|| 'login' !== $wp->query_vars['dff-action']
		) {
			return;
		}

		header( 'Cache-Control: max-age=0' );

		if ( ! empty( $_SERVER['HTTP_REFERER'] ) ) {
			setcookie( '_dff_return_to', $_SERVER['HTTP_REFERER'], time() + YEAR_IN_SECONDS, '/', 'dubaifuture.ae' ); // phpcs:ignore
		}

		if ( isset( $_COOKIE['_fid_dff_uid'], $_COOKIE['fid-is-loggedin'] ) ) { // phpcs:ignore
			header( 'Location: ' . home_url(), true, 302 );
			exit;
		}

		// Fetch the authorization URL from the provider; this returns the
		// urlAuthorize option and generates and applies any necessary parameters
		// (e.g. state).
		$authorization_url = $this->provider->getAuthorizationUrl();

		// Get the state generated for you and store it to the session.
		setcookie( 'oauth2state', $this->provider->getState(), 0, '/', 'dubaifuture.ae' ); // phpcs:ignore

		// Redirect the user to the authorization URL.
		header( 'Location: ' . $authorization_url );
		exit;
	}

	public function authorize_token( \WP $wp ): void {
		if (
			! array_key_exists( 'dff-action', $wp->query_vars )
			|| 'authorize' !== $wp->query_vars['dff-action']
		) {
			return;
		}

		header( 'Cache-Control: max-age=0' );

		if ( ! isset( $_GET['code'] ) ) { // phpcs:ignore
			header( 'Location: ' . home_url(), true, 302 );
			exit;
		}

		// If we don't have an authorization code then get one
		if ( empty( $_GET['state'] ) || ( isset( $_COOKIE['oauth2state'] ) && $_GET['state'] !== $_COOKIE['oauth2state'] ) ) { // phpcs:ignore
			if ( isset( $_COOKIE['oauth2state'] ) ) { // phpcs:ignore
				setcookie( 'oauth2state', '', time() - 90, '/', 'dubaifuture.ae' ); // phpcs:ignore
			}

			exit( 'Invalid state' );
		}

		try {
			// Try to get an access token using the authorization code grant.
			$access_token = $this->provider->getAccessToken('authorization_code', [
				'code' => $_GET['code'], // phpcs:ignore
			]);

			$id = uniqid();

			set_transient( '_dff_user_' . $id, [
				'access_token'  => $access_token->getToken(),
				'refresh_token' => $access_token->getRefreshToken(),
				'expires'       => time() + 60 * 90,
			] );

			setcookie( '_fid_dff_uid', $id, time() + YEAR_IN_SECONDS, '/', 'dubaifuture.ae' ); // phpcs:ignore

			if ( ! empty( $_COOKIE['_dff_return_to'] ) ) { // phpcs:ignore
				setcookie( '_dff_return_to', '', time() - 90, '/', 'dubaifuture.ae' ); // phpcs:ignore
				wp_safe_redirect( $_COOKIE['_dff_return_to'] ); // phpcs:ignore
				exit;
			}

			header( 'Location: ' . home_url( '/' ) );
			exit;

		} catch ( Exception $e ) {
			// Failed to get the access token or user details.
			exit( wp_kses_post( $e->getMessage() ) );
		}
	}

	public function refresh_token(): void {
		if ( ! isset( $_COOKIE['_fid_dff_uid'] ) ) { // phpcs:ignore
			return;
		}

		$id = $_COOKIE['_fid_dff_uid']; // phpcs:ignore

		$tokens = get_transient( '_dff_user_' . $id );

		if ( ! $tokens || $tokens['expires'] > time() ) {
			return;
		}

		try {
			$access_token = $this->provider->getAccessToken( 'refresh_token', [
				'refresh_token' => $tokens['refresh_token'],
			] );

			set_transient( '_dff_user_' . $id, [
				'access_token'  => $access_token->getToken(),
				'refresh_token' => $access_token->getRefreshToken(),
				'expires'       => time() + 60 * 90,
			] );
		} catch ( InvalidArgumentException $e ) {
			$this->do_remove( $id );
		}
	}

	public function handle_logout( \WP $wp ): void {
		if (
			! array_key_exists( 'dff-action', $wp->query_vars )
			|| 'logout' !== $wp->query_vars['dff-action']
		) {
			return;
		}

		header( 'Cache-Control: max-age=0' );

		if ( ! isset( $_COOKIE['_fid_dff_uid'] ) ) { // phpcs:ignore
			return;
		}

		$id = $_COOKIE['_fid_dff_uid']; // phpcs:ignore

		$tokens = get_transient( '_dff_user_' . $id );

		$url = sprintf(
			'%s/logout?client_id=%s&redirect_uri=%s&accessToken=%s',
			$this->auth_url,
			$this->client_id,
			home_url( '/' ),
			$tokens['access_token']
		);


		$this->do_remove( $id );
		header( 'Location: ' . $url );
		exit;
	}

	public function get_user() {
		$id = $_COOKIE['_fid_dff_uid']; // phpcs:ignore

		$tokens = get_transient( '_dff_user_' . $id );

		$user = Request::get( $this->id_url . '/user', [
			'headers' => [
				'Origin' => 'https://dubaifuture.ae',
				'token'  => $tokens['access_token'],
			],
		] );

		return $user;
	}

	public function register_rest_routes(): void {
		register_rest_route( 'dff/v1/oauth', 'logged_in', [
			'methods'             => [ 'POST' ],
			'callback'            => [ $this, 'check_session' ],
			'permission_callback' => '__return_true',
		] );
	}

	public function check_session() {
		$id = $_COOKIE['_fid_dff_uid']; // phpcs:ignore

		if ( ! $id ) {
			return rest_ensure_response( new WP_Error( 'invalid_session', 'Invalid user session. You are not logged in.', [ 'status' => 403 ] ) );
		}

		$user = $this->get_user();

		if ( is_wp_error( $user ) ) {
			$this->do_remove( $id );
			return rest_ensure_response( new WP_Error( 'invalid_session', 'Invalid user session. You are not logged in.', [ 'status' => 403 ] ) );
		}

		return rest_ensure_response( [
			'code'    => 'valid_session',
			'message' => 'User is logged in.',
		] );
	}
}

new Authentication();
