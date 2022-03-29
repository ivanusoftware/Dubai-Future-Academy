<?php
namespace DFF\Options;

class API_Settings {
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
			'api-settings', // ID
			'API Settings', // Title
			array( $this, 'print_api_section_info' ), // Callback
			'global-options-admin' // Page
		);

		add_settings_field(
			'google_maps_api_key', // ID
			'Google Maps API key', // Title
			array( $this, 'google_maps_api_key_callback' ), // Callback
			'global-options-admin', // Page
			'api-settings' // Section
		);
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize( $input ) {
		$new_input = array();

		if ( isset( $input['google_maps_api_key'] ) ) {
			$new_input['google_maps_api_key'] = sanitize_text_field( $input['google_maps_api_key'] );
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
	public function google_maps_api_key_callback() {
		$this->options = get_option( 'global_options' );
		printf(
			'<input type="text" id="google_maps_api_key" name="global_options[google_maps_api_key]" value="%s" />',
			isset( $this->options['google_maps_api_key'] ) ? esc_attr( $this->options['google_maps_api_key'] ) : ''
		);
	}
}
