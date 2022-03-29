<?php

namespace DFF\Options;

class Global_Options {
	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;

	/**
	 * Start up
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page() {
		// This page will be under "Settings"
		add_theme_page(
			'Settings Admin',
			'Theme Settings',
			'manage_options',
			'global-options-admin',
			array( $this, 'create_admin_page' )
		);

		register_setting(
			'global_options_group', // Option group
			'global_options', // Option name
			array( $this, 'sanitize' ) // Sanitize
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page() {
		// Set class property
		$this->options = get_option( 'global_options' );
		?>
		<div class="wrap">
			<h1>Global Settings</h1>
			<form method="post" action="options.php">
			<?php
				// This prints out all hidden setting fields
				settings_fields( 'global_options_group' );
				do_settings_sections( 'global-options-admin' );
				submit_button();
			?>
			</form>
		</div>
		<?php
	}
}
