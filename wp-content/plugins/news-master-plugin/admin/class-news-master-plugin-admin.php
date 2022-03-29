<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.multidots.com/
 * @since      1.0.0
 *
 * @package    News_Master_Plugin
 * @subpackage News_Master_Plugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    News_Master_Plugin
 * @subpackage News_Master_Plugin/admin
 * @author     multidots <rishi.shah@multidots.com>
 */
class News_Master_Plugin_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		add_action( 'admin_menu', array( $this, 'nmp_admin_settings_page' ) );
		add_action( 'wp_ajax_add_sites_action', array( $this, 'add_sites_action' ) );
		add_action( 'wp_ajax_nopriv_add_sites_action', array( $this, 'add_sites_action' ) );
		add_action( 'wp_ajax_delete_sites_action', array( $this, 'delete_sites_action' ) );
		add_action( 'wp_ajax_nopriv_delete_sites_action', array( $this, 'delete_sites_action' ) );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in News_Master_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The News_Master_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/news-master-plugin-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in News_Master_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The News_Master_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/news-master-plugin-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script(
			$this->plugin_name, 'ajax_object', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			)
		);

	}

	/**
	 * Callback function for admin settings page.
	 */
	public function nmp_admin_settings_page() {
		add_menu_page(
			__( 'Publisher Master Settings', 'textdomain' ),
			'DFF Publisher',
			'manage_options',
			'nmp-settings',
			'nmp_settings_function',
			'',
			120
		);
	}

	/**
	 * Add sites ajax function.
	 */
	public function add_sites_action() {

		global $wpdb;

		$add_sites_field = filter_input( INPUT_POST, 'add_sites_field', FILTER_SANITIZE_STRING );
		$add_sites_field = isset( $add_sites_field ) ? $add_sites_field : '';
		$add_sites_field = preg_replace( '(^https?://)', '', rtrim( $add_sites_field, '/\\' ) );

		$add_sites_field = explode( '/', $add_sites_field );
		$add_sites_field = $add_sites_field[0];

		$token                        = bin2hex( random_bytes( 8 ) );
		$new_site_array               = array();
		$new_site_array[0]['siteurl'] = $add_sites_field;
		$new_site_array[0]['token']   = $token;

		$table_name     = $wpdb->base_prefix . 'options';
		$new_site_array = maybe_serialize( $new_site_array );
		$child_sites    = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM %1soptions WHERE option_name = 'npm_child_sites'", $wpdb->base_prefix ), ARRAY_A );

		if ( isset( $child_sites ) && ! empty( $child_sites ) ) {

			$new_data              = $child_sites[0]['option_value'];
			$new_site_array_update = $new_data . '/' . $new_site_array;
			$wpdb->update(
				$table_name,
				array(
					'option_value' => $new_site_array_update,
				),
				array(
					'option_id' => $child_sites[0]['option_id'],
				)
			);

		} else {
			$wpdb->insert($table_name, array(
				'option_name' => 'npm_child_sites',
				'option_value' => $new_site_array,
				'autoload' => false, 
			));

		}
		?>
		<tr>
			<td><?php echo esc_html( $add_sites_field ); ?></td>
			<td><?php echo esc_html( $token ); ?></td>
			<td class="action"><span class="dashicons dashicons-no-alt delete_site_button"></span></td>
		</tr>
		<?php
		die();
	}

	/**
	 * Delete sites ajax function.
	 */
	public function delete_sites_action() {

		global $wpdb;

		$delete_site_button = filter_input( INPUT_POST, 'delete_site_button', FILTER_SANITIZE_STRING );
		$delete_site_button = isset( $delete_site_button ) ? $delete_site_button : '';

		$table_name      = $wpdb->base_prefix . 'options';
		$npm_added_sites = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM %1soptions WHERE option_name = 'npm_child_sites'", $wpdb->base_prefix ), ARRAY_A );

		if ( isset( $npm_added_sites ) && ! empty( $npm_added_sites ) ) {

			$site_data       = $npm_added_sites[0]['option_value'];
			$site_data_array = explode( '/', $site_data );
			foreach ( $site_data_array as $k => $site_data_array_single ) {

				$site_data_array_single = maybe_unserialize( $site_data_array_single );
				if ( $site_data_array_single[0]['siteurl'] === $delete_site_button ) {
					unset( $site_data_array[ $k ] );
				}
			}

			$site_data_string = implode( '/', $site_data_array );
			$wpdb->update(
				$table_name,
				array(
					'option_value' => $site_data_string,
				),
				array(
					'option_id' => $npm_added_sites[0]['option_id'],
				)
			);

		}

		die();
	}

}

/**
 * Include settings page HTML.
 */
function nmp_settings_function() {
	include plugin_dir_path( __FILE__ ) . 'partials/news-master-plugin-admin-display.php';
}
