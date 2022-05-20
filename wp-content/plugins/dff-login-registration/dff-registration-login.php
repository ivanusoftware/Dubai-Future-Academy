<?php
/*
Plugin Name: Dff Login & Registration
Plugin URI: 
Description: Plugin that creates custom login and registration forms that can be implemented using a shortcode.
Version: 1.0
Author: Ivan Chumak
Author URI: 
*/




/* ------------------------------------------------------------------------- */
// user registration login form
/* ------------------------------------------------------------------------- */
function dff_registration_form()
{

	// only show the registration form to non-logged-in members
	if (!is_user_logged_in()) {

		global $dff_load_css;

		// set this to true so the CSS is loaded
		$dff_load_css = true;

		// check to make sure user registration is enabled
		$registration_enabled = get_option('users_can_register');

		// only show the registration form if allowed
		if ($registration_enabled) {
			$output = dff_registration_form_fields();
		} else {
			$output = __('User registration is not enabled');
		}
		return $output;
	}
}
add_shortcode('register_form', 'dff_registration_form');

/* ------------------------------------------------------------------------- */
// user login form
/* ------------------------------------------------------------------------- */
function dff_login_form()
{

	if (!is_user_logged_in()) {

		global $dff_load_css;

		// set this to true so the CSS is loaded
		$dff_load_css = true;

		$output = dff_login_form_fields();
	} else {
		// could show some logged in user info here
		// $output = 'user info here';
		echo 'Already Logged-In <a id="dff_logout" href="' . wp_logout_url(get_permalink()) . '" title="Logout">Logout</a>';
	}
	return $output;
}
add_shortcode('login_form', 'dff_login_form');

/* ------------------------------------------------------------------------- */
// registration form fields
/* ------------------------------------------------------------------------- */
function dff_registration_form_fields()
{

	ob_start(); ?>
	<h3 class="dff_header"><?php _e('Register New Account'); ?></h3>

	<?php
	// show any error messages after form submission
	dff_show_error_messages(); ?>

	<form id="dff_registration_form" class="dff_form" action="" method="POST">
		<fieldset>
			<p>
				<label for="dff_user_Login"><?php _e('Username'); ?></label>
				<input name="dff_user_login" id="dff_user_login" class="required" type="text" />
			</p>
			<p>
				<label for="dff_user_email"><?php _e('Email'); ?></label>
				<input name="dff_user_email" id="dff_user_email" class="required" type="email" />
			</p>
			<p>
				<label for="dff_user_first"><?php _e('First Name'); ?></label>
				<input name="dff_user_first" id="dff_user_first" class="required" type="text" />
			</p>
			<p>
				<label for="dff_user_last"><?php _e('Last Name'); ?></label>
				<input name="dff_user_last" id="dff_user_last" class="required" type="text" />
			</p>
			<p>
				<label for="password"><?php _e('Password'); ?></label>
				<input name="dff_user_pass" id="password" class="required" type="password" />
			</p>
			<p>
				<label for="password_again"><?php _e('Password Again'); ?></label>
				<input name="dff_user_pass_confirm" id="password_again" class="required" type="password" />
			</p>
			<p>
				<input type="hidden" name="dff_register_nonce" value="<?php echo wp_create_nonce('dff-register-nonce'); ?>" />
				<input type="submit" value="<?php _e('Register Your Account'); ?>" />
			</p>
		</fieldset>
	</form>
<?php
	return ob_get_clean();
}

/* ------------------------------------------------------------------------- */
// login form fields
/* ------------------------------------------------------------------------- */
function dff_login_form_fields()
{

	ob_start(); ?>
	<!-- <h3 class="dff_header"><?php _e('Login'); ?></h3> -->

	<?php
	// show any error messages after form submission
	dff_show_error_messages(); ?>

	<form id="dff_login_form" class="dff_form" action="" method="post">
		<fieldset>
			<p>
				<label for="dff_user_Login">Username</label>
				<input name="dff_user_login" id="dff_user_login" class="required" type="text" />
			</p>
			<p>
				<label for="dff_user_pass">Password</label>
				<input name="dff_user_pass" id="dff_user_pass" class="required" type="password" />
			</p>
			<p>
				<input type="hidden" name="dff_login_nonce" value="<?php echo wp_create_nonce('dff-login-nonce'); ?>" />
				<input id="dff_login_submit" type="submit" value="Login" />
			</p>
		</fieldset>
	</form>
<?php
	return ob_get_clean();
}

/* ------------------------------------------------------------------------- */
// Logs a member in after submitting a form
/* ------------------------------------------------------------------------- */
function dff_login_member()
{

	if (isset($_POST['dff_user_login']) && wp_verify_nonce($_POST['dff_login_nonce'], 'dff-login-nonce')) {

		// this returns the user ID and other info from the user name
		$user = get_userdatabylogin($_POST['dff_user_login']);

		if (!$user) {
			// if the user name doesn't exist
			dff_errors()->add('empty_username', __('Invalid inputs'));
		}

		if (!isset($_POST['dff_user_pass']) || $_POST['dff_user_pass'] == '') {
			// if no password was entered
			dff_errors()->add('empty_password', __('Please enter a password'));
		}

		// check the user's login with their password
		if (!wp_check_password($_POST['dff_user_pass'], $user->user_pass, $user->ID)) {
			// if the password is incorrect for the specified user
			dff_errors()->add('empty_password', __('Incorrect inputs'));
		}

		// retrieve all error messages
		$errors = dff_errors()->get_error_messages();

		// only log the user in if there are no errors
		if (empty($errors)) {

			wp_setcookie($_POST['dff_user_login'], $_POST['dff_user_pass'], true);
			wp_set_current_user($user->ID, $_POST['dff_user_login']);
			do_action('wp_login', $_POST['dff_user_login']);

			wp_redirect(home_url("/"));
			exit;
		}
	}
}
add_action('init', 'dff_login_member');

/* ------------------------------------------------------------------------- */
// Register a new user
/* ------------------------------------------------------------------------- */
function dff_add_new_member()
{
	if (isset($_POST["dff_user_login"]) && wp_verify_nonce($_POST['dff_register_nonce'], 'dff-register-nonce')) {
		$user_login		= $_POST["dff_user_login"];
		$user_email		= $_POST["dff_user_email"];
		$user_first 	= $_POST["dff_user_first"];
		$user_last	 	= $_POST["dff_user_last"];
		$user_pass		= $_POST["dff_user_pass"];
		$pass_confirm 	= $_POST["dff_user_pass_confirm"];

		// this is required for username checks
		require_once(ABSPATH . WPINC . '/registration.php');

		if (username_exists($user_login)) {
			// Username already registered
			dff_errors()->add('username_unavailable', __('Username already taken'));
		}
		if (!validate_username($user_login)) {
			// invalid username
			dff_errors()->add('username_invalid', __('Invalid username'));
		}
		if ($user_login == '') {
			// empty username
			dff_errors()->add('username_empty', __('Please enter a username'));
		}



		if (!is_email($user_email)) {
			//invalid email
			dff_errors()->add('email_invalid', __('Invalid email'));
		}
		if (email_exists($user_email)) {
			//Email address already registered
			dff_errors()->add('email_used', __('Email already registered'));
		}
		if ($user_pass == '') {
			// passwords do not match
			dff_errors()->add('password_empty', __('Please enter a password'));
		}
		if ($user_pass != $pass_confirm) {
			// passwords do not match
			dff_errors()->add('password_mismatch', __('Passwords do not match'));
		}

		$errors = dff_errors()->get_error_messages();

		// only create the user in if there are no errors
		if (empty($errors)) {

			$new_user_id = wp_insert_user(
				array(
					'user_login'		=> $user_login,
					'user_pass'	 		=> $user_pass,
					'user_email'		=> $user_email,
					'first_name'		=> $user_first,
					'last_name'			=> $user_last,
					'user_registered'	=> date('Y-m-d H:i:s'),
					'role'				=> 'subscriber'
				)
			);
			if ($new_user_id) {
				// send an email to the admin alerting them of the registration
				wp_new_user_notification($new_user_id);

				// log the new user in
				wp_setcookie($user_login, $user_pass, true);
				wp_set_current_user($new_user_id, $user_login);
				do_action('wp_login', $user_login);

				// send the newly created user to the home page after logging them in
				wp_redirect(home_url("/"));
				exit;
			}
		}
	}
}
add_action('init', 'dff_add_new_member');

/* ------------------------------------------------------------------------- */
// used for tracking error messages
/* ------------------------------------------------------------------------- */
function dff_errors()
{
	static $wp_error; // Will hold global variable safely
	return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}

/* ------------------------------------------------------------------------- */
// Displays error messages from form submissions
/* ------------------------------------------------------------------------- */
function dff_show_error_messages()
{
	if ($codes = dff_errors()->get_error_codes()) {
		echo '<div class="dff_errors">';
		// Loop error codes and display errors
		foreach ($codes as $code) {
			$message = dff_errors()->get_error_message($code);
			echo '<span class="error"><strong>' . __('Error') . '</strong>: ' . $message . '</span><br/>';
		}
		echo '</div>';
	}
}

/* ------------------------------------------------------------------------- */
// register our form css
/* ------------------------------------------------------------------------- */
function dff_register_css()
{
	wp_register_style('dff-form-css', plugin_dir_url(__FILE__) . '/css/style.css');
}
add_action('init', 'dff_register_css');

/* ------------------------------------------------------------------------- */
// load our form css
/* ------------------------------------------------------------------------- */
function dff_print_css()
{
	global $pippin_load_css;

	// this variable is set to TRUE if the short code is used on a page/post
	// if (!$dff_load_css)
	if (!$pippin_load_css)
		return; // this means that neither short code is present, so we get out of here

	wp_print_styles('dff-form-css');
}
add_action('wp_footer', 'dff_print_css');

/* ------------------------------------------------------------------------- */
// Redirect to custom registration and login form
/* ------------------------------------------------------------------------- */
// Hook the appropriate WordPress action
// add_action('init', 'prevent_wp_login');

// function prevent_wp_login()
// {
// 	// WP tracks the current page - global the variable to access it
// 	global $pagenow;
// 	// Check if a $_GET['action'] is set, and if so, load it into $action variable
// 	$action = (isset($_GET['action'])) ? $_GET['action'] : '';
// 	// Check if we're on the login page, and ensure the action is not 'logout'
// 	if ($pagenow == 'wp-login.php' && (!$action || ($action && !in_array($action, array('logout', 'lostpassword', 'rp'))))) {
// 		// Load the home page url
// 		$page = site_url("/login/");
// 		// Redirect to the home page
// 		wp_redirect($page);
// 		// Stop execution to prevent the page loading for any reason
// 		exit();
// 	}
// }

/* ------------------------------------------------------------------------- */
// Disable Admin Bar for All Users Except for Administrators
/* ------------------------------------------------------------------------- */
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar()
{
	if (!current_user_can('administrator') && !is_admin()) {
		show_admin_bar(false);
	}
}
