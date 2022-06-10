<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       ivan-chumak
 * @since      1.0.0
 *
 * @package    Dff_Login_And_Registration
 * @subpackage Dff_Login_And_Registration/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Dff_Login_And_Registration
 * @subpackage Dff_Login_And_Registration/public
 * @author     Ivan Chumak <ivankochumak@gmail.com>
 */
class Dff_Login_And_Registration_Public
{

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dff_Login_And_Registration_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dff_Login_And_Registration_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/dff-login-and-registration-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dff_Login_And_Registration_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dff_Login_And_Registration_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/dff-login-and-registration-public.js', array('jquery'), $this->version, false);
	}

	// dff_login_form_fields.
	public function dff_login_form()
	{

		if (!is_user_logged_in()) {

			// global $custom_load_css;

			// // set this to true so the CSS is loaded
			// $custom_load_css = true;

			ob_start();
			include_once plugin_dir_url(__FILE__) . 'partials/dff-login-form.php';
			
			$template = ob_get_contents();

			ob_end_clean();
			echo $template;
			// $output = dff_custom_login_form_fields();
		} else {
			// could show some logged in user info here
			$output = 'user info here';
		}
		// return $output;
		return $output;
	}


	// dff_register_form_fields.
	public function dff_register_form()
	{

		// only show the registration form to non-logged-in members
		if (!is_user_logged_in()) {

			global $custom_load_css;

			// set this to true so the CSS is loaded
			$custom_load_css = true;

			// check to make sure user registration is enabled
			$registration_enabled = get_option('users_can_register');

			// only show the registration form if allowed
			if ($registration_enabled) {
				// $output = dff_custom_registration_form_fields();
			} else {
				$output = __('User registration is not enabled');
			}
			return $output;
		}
	}
}
