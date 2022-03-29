<?php
namespace DFF\Options;

class FutureId_Settings {
	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;

	/**
	 * Start up
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Register and add settings
	 */
	public function page_init() {
		add_settings_section(
			'future-id-settings', // ID
			'Future ID Settings', // Title
			array( $this, 'print_api_section_info' ), // Callback
			'global-options-admin' // Page
		);

		add_settings_field(
			'client_id', // ID
			'Client ID', // Title
			array( $this, 'client_id_callback' ), // Callback
			'global-options-admin', // Page
			'future-id-settings' // Section
		);

		add_settings_field(
			'client_secret', // ID
			'Client Secret', // Title
			array( $this, 'client_secret_callback' ), // Callback
			'global-options-admin', // Page
			'future-id-settings' // Section
		);

		add_settings_field(
			'auth_url', // ID
			'Auth Url', // Title
			array( $this, 'auth_url_callback' ), // Callback
			'global-options-admin', // Page
			'future-id-settings' // Section
		);

		add_settings_field(
			'identity_url', // ID
			'Identity Url', // Title
			array( $this, 'identity_url_callback' ), // Callback
			'global-options-admin', // Page
			'future-id-settings' // Section
		);

		add_settings_field(
			'dashboard_url', // ID
			'Dashboard Url', // Title
			array( $this, 'dashboard_url_callback' ), // Callback
			'global-options-admin', // Page
			'future-id-settings' // Section
		);

		add_settings_field(
			'programs_url', // ID
			'Programs Url', // Title
			array( $this, 'programs_url_callback' ), // Callback
			'global-options-admin', // Page
			'future-id-settings' // Section
		);

		add_settings_field(
			'redirect_uri', // ID
			'Redirect Url', // Title
			array( $this, 'redirect_uri_callback' ), // Callback
			'global-options-admin', // Page
			'future-id-settings' // Section
		);
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize( $input ) {
		$new_input = array();

		if ( isset( $input['client_id'] ) ) {
			$new_input['client_id'] = sanitize_text_field( $input['client_id'] );
		}

		if ( isset( $input['client_secret'] ) ) {
			$new_input['client_secret'] = sanitize_text_field( $input['client_secret'] );
		}

		if ( isset( $input['auth_url'] ) ) {
			$new_input['auth_url'] = sanitize_text_field( $input['auth_url'] );
		}

		if ( isset( $input['identity_url'] ) ) {
			$new_input['identity_url'] = sanitize_text_field( $input['identity_url'] );
		}

		if ( isset( $input['dashboard_url'] ) ) {
			$new_input['dashboard_url'] = sanitize_text_field( $input['dashboard_url'] );
		}

		if ( isset( $input['programs_url'] ) ) {
			$new_input['programs_url'] = sanitize_text_field( $input['programs_url'] );
		}

		if ( isset( $input['redirect_uri'] ) ) {
			$new_input['redirect_uri'] = sanitize_text_field( $input['redirect_uri'] );
		}

		return $new_input;
	}

	/**
	 * Print the api Section text
	 */
	public function print_api_section_info() {
		print 'Enter your api settings below:';
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function client_id_callback() {
		$this->options = get_option( 'global_options' );
		printf(
			'<input type="text" id="client_id" name="global_options[client_id]" value="%s" />',
			isset( $this->options['client_id'] ) ? esc_attr( $this->options['client_id'] ) : ''
		);
	}
	/**
	 * Get the settings option array and print one of its values
	 */
	public function client_secret_callback() {
		$this->options = get_option( 'global_options' );
		printf(
			'<input type="text" id="client_secret" name="global_options[client_secret]" value="%s" />',
			isset( $this->options['client_secret'] ) ? esc_attr( $this->options['client_secret'] ) : ''
		);
	}
	/**
	 * Get the settings option array and print one of its values
	 */
	public function auth_url_callback() {
		$this->options = get_option( 'global_options' );
		printf(
			'<input type="text" id="auth_url" name="global_options[auth_url]" value="%s" />',
			isset( $this->options['auth_url'] ) ? esc_attr( $this->options['auth_url'] ) : ''
		);
	}
	/**
	 * Get the settings option array and print one of its values
	 */
	public function identity_url_callback() {
		$this->options = get_option( 'global_options' );
		printf(
			'<input type="text" id="identity_url" name="global_options[identity_url]" value="%s" />',
			isset( $this->options['identity_url'] ) ? esc_attr( $this->options['identity_url'] ) : ''
		);
	}
	/**
	 * Get the settings option array and print one of its values
	 */
	public function dashboard_url_callback() {
		$this->options = get_option( 'global_options' );
		printf(
			'<input type="text" id="dashboard_url" name="global_options[dashboard_url]" value="%s" />',
			isset( $this->options['dashboard_url'] ) ? esc_attr( $this->options['dashboard_url'] ) : ''
		);
	}

	public function programs_url_callback() {
		$this->options = get_option( 'global_options' );
		printf(
			'<input type="text" id="programs_url" name="global_options[programs_url]" value="%s" />',
			isset( $this->options['programs_url'] ) ? esc_attr( $this->options['programs_url'] ) : ''
		);
	}
	/**
	 * Get the settings option array and print one of its values
	 */
	public function redirect_uri_callback() {
		$this->options = get_option( 'global_options' );
		printf(
			'<input type="text" id="redirect_uri" name="global_options[redirect_uri]" value="%s" />',
			isset( $this->options['redirect_uri'] ) ? esc_attr( $this->options['redirect_uri'] ) : ''
		);
	}
}
