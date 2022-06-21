<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              ivan-chumak
 * @since             1.0.0
 * @package           Dff_Login_And_Registration
 *
 * @wordpress-plugin
 * Plugin Name:       DFF Login and Registration
 * Plugin URI:        dff-login-and-registration
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Ivan Chumak
 * Author URI:        ivan-chumak
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dff-login-and-registration
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'DFF_LOGIN_AND_REGISTRATION_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dff-login-and-registration-activator.php
 */
function activate_dff_login_and_registration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dff-login-and-registration-activator.php';
	Dff_Login_And_Registration_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dff-login-and-registration-deactivator.php
 */
function deactivate_dff_login_and_registration() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dff-login-and-registration-deactivator.php';
	Dff_Login_And_Registration_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dff_login_and_registration' );
register_deactivation_hook( __FILE__, 'deactivate_dff_login_and_registration' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-dff-login-and-registration.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_dff_login_and_registration() {

	$plugin = new Dff_Login_And_Registration();
	$plugin->run();

}
run_dff_login_and_registration();
