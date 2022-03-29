<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.multidots.com/
 * @since             1.0.0
 * @package           Events_Child_Plugin
 *
 * @wordpress-plugin
 * Plugin Name:       DFF Events Syndication Plugin
 * Plugin URI:        https://www.multidots.com/
 * Description:       This plugin allows authorized websites to syndicate and list events from across the Dubai Future Foundation ecosystem.
 * Version:           1.0.0
 * Author:            Multidots
 * Author URI:        https://www.multidots.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       events-child-plugin
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
define( 'EVENTS_CHILD_PLUGIN_VERSION', '1.0.0' );

/**
 * Define event source URL For API calls.
 */
define( 'EVENT_SOURCE_API_URL', 'https://events.dubaifuture.ae' );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-events-child-plugin-activator.php
 */
function activate_events_child_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-events-child-plugin-activator.php';
	Events_Child_Plugin_Activator::activate();

	add_option( 'nht_plugin_do_activation_redirect', true );

	if ( ! wp_next_scheduled( 'cron_event_fetch' ) ) {
		wp_schedule_event( time(), 'cron_every_thirty_min', 'cron_event_fetch' );
	}

	// Cron to update 'upcoming' meta to yes daily.
	if ( ! wp_next_scheduled( 'cron_upcoming_events' ) ) {
		wp_schedule_event( time(), 'daily', 'cron_upcoming_events' );
	}
}

/**
 * Cron to sync event on every 30 minutes.
 */
add_action( 'cron_event_fetch', 'cron_event_fetch_function' );
function cron_event_fetch_function() {

	require_once plugin_dir_path( __FILE__ ) . 'admin/class-events-child-plugin-admin.php';
	Events_Child_Plugin_Admin::sync_manually_event_ajax();

}

/**
 * Cron to update 'upcoming' meta to yes daily.
 */
add_action( 'cron_upcoming_events', 'cron_upcoming_events_function' );
function cron_upcoming_events_function() {

	require_once plugin_dir_path( __FILE__ ) . 'admin/class-events-child-plugin-admin.php';
	Events_Child_Plugin_Admin::update_upcoming_events();

}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-events-child-plugin-deactivator.php
 */
function deactivate_events_child_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-events-child-plugin-deactivator.php';
	Events_Child_Plugin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_events_child_plugin' );

/**
 * Register new interval time for 30 minutes.
 */
add_filter( 'cron_schedules', 'cep_wpcron_custom_timings' );
function cep_wpcron_custom_timings( $schedules ) {

	$schedules = $schedules;

	$schedules['cron_every_thirty_min'] = array(
		'interval' => 1800,
		'display'  => __( 'Every 30 min' ),
	);

	return $schedules;

}

/**
 * Redirect the plugin to wizard page after reset the plugin or activate the plugin.
 */
add_action( 'admin_init', 'cep_plugin_redirect' );
function cep_plugin_redirect() {
	if ( get_option( 'nht_plugin_do_activation_redirect', false ) ) {
		delete_option( 'nht_plugin_do_activation_redirect' );
		$activate_multi = filter_input( INPUT_GET, 'activate-multi', FILTER_SANITIZE_STRING );
		if ( ! isset( $activate_multi ) ) {
			wp_safe_redirect( '/wp-admin/admin.php?page=event_setup_wizard' );
			exit();
		}
	}
}

register_deactivation_hook( __FILE__, 'deactivate_events_child_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-events-child-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_events_child_plugin() {

	$plugin = new Events_Child_Plugin();
	$plugin->run();

}
run_events_child_plugin();
