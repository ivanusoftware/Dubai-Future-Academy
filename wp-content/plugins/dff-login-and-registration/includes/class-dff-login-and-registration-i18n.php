<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       ivan-chumak
 * @since      1.0.0
 *
 * @package    Dff_Login_And_Registration
 * @subpackage Dff_Login_And_Registration/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Dff_Login_And_Registration
 * @subpackage Dff_Login_And_Registration/includes
 * @author     Ivan Chumak <ivankochumak@gmail.com>
 */
class Dff_Login_And_Registration_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'dff-login-and-registration',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
