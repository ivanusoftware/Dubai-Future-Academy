<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.multidots.com
 * @since             1.0.0
 * @package           Impact_Map
 *
 * @wordpress-plugin
 * Plugin Name:       Impact Map
 * Plugin URI:        https://www.multidots.com
 * Description:       The objective of the plugin is to create projects with all the information related to it and display it on a page through the “Project Map” Gutenberg block. So it will show on a specific page with Google MAP.
 * Version:           1.0.0
 * Author:            Multidots
 * Author URI:        https://www.multidots.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       impact-map
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
define( 'IMPACT_MAP_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-impact-map-activator.php
 */
function activate_impact_map() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-impact-map-activator.php';
	Impact_Map_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-impact-map-deactivator.php
 */
function deactivate_impact_map() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-impact-map-deactivator.php';
	Impact_Map_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_impact_map' );
register_deactivation_hook( __FILE__, 'deactivate_impact_map' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-impact-map.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_impact_map() {

	$plugin = new Impact_Map();
	$plugin->run();

}
run_impact_map();
