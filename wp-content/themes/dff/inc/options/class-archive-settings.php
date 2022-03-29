<?php
namespace DFF\Options;

class Archive_Settings {
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
			'archive-settings', // ID
			'Archive Settings', // Title
			array( $this, 'print_archive_settings_info' ), // Callback
			'global-options-admin' // Page
		);

		add_settings_field(
			'events_archive_url', // ID
			'Events Archive URL', // Title
			array( $this, 'events_archive_url_callback' ), // Callback
			'global-options-admin', // Page
			'archive-settings' // Section
		);

		add_settings_field(
			'programs_archive_url', // ID
			'Programs Archive URL', // Title
			array( $this, 'programs_archive_url_callback' ), // Callback
			'global-options-admin', // Page
			'archive-settings' // Section
		);

		add_settings_field(
			'exclude_from_search', // ID
			'Exclude from search', // Title
			array( $this, 'exclude_from_search_callback' ), // Callback
			'global-options-admin', // Page
			'archive-settings' // Section
		);
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize( $input ) {
		$new_input = array();

		if ( isset( $input['events_archive_url'] ) ) {
			$new_input['events_archive_url'] = sanitize_text_field( $input['events_archive_url'] );
		}

		if ( isset( $input['programs_archive_url'] ) ) {
			$new_input['programs_archive_url'] = sanitize_text_field( $input['programs_archive_url'] );
		}

		if ( isset( $input['exclude_from_search'] ) ) {
			$new_input['exclude_from_search'] = sanitize_text_field( $input['exclude_from_search'] );
		}

		return $new_input;
	}

	/**
	 * Print the api Section text
	 */
	public function print_archive_settings_info() {
		print 'Enter your archive settings below:';
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function programs_archive_url_callback() {
		$this->options = get_option( 'global_options' );
		printf(
			'<input type="text" id="programs_archive_url" name="global_options[programs_archive_url]" value="%s" />',
			isset( $this->options['programs_archive_url'] ) ? esc_attr( $this->options['programs_archive_url'] ) : ''
		);
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function exclude_from_search_callback() {
		$this->options = get_option( 'global_options' );
		printf(
			'<input type="text" id="exclude_from_search" name="global_options[exclude_from_search]" value="%s" />',
			isset( $this->options['exclude_from_search'] ) ? esc_attr( $this->options['exclude_from_search'] ) : ''
		);
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function events_archive_url_callback() {
		$this->options = get_option( 'global_options' );
		printf(
			'<input type="text" id="events_archive_url" name="global_options[events_archive_url]" value="%s" />',
			isset( $this->options['events_archive_url'] ) ? esc_attr( $this->options['events_archive_url'] ) : ''
		);
	}
}
