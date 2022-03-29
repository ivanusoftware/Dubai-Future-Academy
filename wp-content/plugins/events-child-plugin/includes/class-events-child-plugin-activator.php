<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Events_Child_Plugin
 * @subpackage Events_Child_Plugin/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Events_Child_Plugin
 * @subpackage Events_Child_Plugin/includes
 * @author     multidots <r@test.com>
 */
class Events_Child_Plugin_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		$db_table_name = $wpdb->prefix . 'dff_history';


		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $db_table_name (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  request_url longtext NOT NULL,
		  total_event int(9) NOT NULL,
		  status varchar(20) NOT NULL,
		  sync_type varchar(20) NOT NULL,
		  start_time datetime DEFAULT '0000-00-00 00:00' NULL,
		  end_time datetime DEFAULT '0000-00-00 00:00' NULL,
		  response_data longtext DEFAULT '' NULL,
		  PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

	}

}
