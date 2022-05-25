<?php
/*
Plugin Name: Dff Login & Registration 2
Plugin URI: 
Description: Plugin that create custom login and registration forms that can be implemented using a shortcode.
Version: 1.0
Author: Vano
Author URI: 
*/
session_start();
add_action('admin_menu', 'dff_register_login_plugin_setup_menu');
function dff_register_login_plugin_setup_menu()
{
    // add_menu_page( 'Register Plugin Page', 'Register Plugin', 'manage_options', 'dff_plugin','dff_init', 'dff_custom_registration_form' );
    add_menu_page('Register&Login Plugin Page', 'Register&Login', 'manage_options', 'dff_register_login_plugin', 'dff_register_logininit');
}
function dff_register_logininit()
{
?>

    <div class="wrap">
        <h2>There are no settings here yet</h2>
        <div>
        <?php
    }

    // user registration login form
    function dff_custom_registration_form()
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
                $output = dff_custom_registration_form_fields();
            } else {
                $output = __('User registration is not enabled');
            }
            return $output;
        }
    }
    add_shortcode('dff_register_form', 'dff_custom_registration_form');

    // user login form
    function dff_custom_login_form()
    {

        if (!is_user_logged_in()) {

            global $custom_load_css;

            // set this to true so the CSS is loaded
            $custom_load_css = true;

            $output = dff_custom_login_form_fields();
        } else {
            // could show some logged in user info here
            // $output = 'user info here';
        }
        return $output;
    }
    add_shortcode('dff_login_form', 'dff_custom_login_form');

    // registration form fields
    function dff_custom_registration_form_fields()
    {

        ob_start(); ?>
            <?php
            // show any error messages after form submission
            dff_custom_show_error_messages();
            // $reg_options = get_option('reg_options');
            //echo "------------<pre>";
            //print_r($reg_options);
            ?>

            <form id="registerform" name="registerform" class="dff-register-login-form" action="" method="POST">
                <fieldset>
                    <p>
                        <label for="custom_user_Login"><?php _e('Username'); ?><span class="dff_required">*</span></label>
                        <input name="custom_user_login" id="custom_user_login" class="required" type="text" value="<?php echo $_POST['custom_user_login']; ?>" />
                    </p>
                    <p>
                        <label for="custom_user_email"><?php _e('Email'); ?><span class="dff_required">*</span></label>
                        <input name="custom_user_email" id="custom_user_email" class="required" type="email" value="<?php echo $_POST['custom_user_email']; ?>" />
                    </p>
                    <p>
                        <label for="custom_user_first"><?php _e('First Name'); ?></label>
                        <input name="custom_user_first" id="custom_user_first" type="text" value="<?php echo $_POST['custom_user_first']; ?>" />
                    </p>
                    <p>
                        <label for="custom_user_last"><?php _e('Last Name'); ?></label>
                        <input name="custom_user_last" id="custom_user_last" type="text" value="<?php echo $_POST['custom_user_last']; ?>" />
                    </p>
                    <p>
                        <label for="password"><?php _e('Password'); ?><span class="dff_required">*</span></label>
                        <input name="dff_user_pwd" id="password" class="required" type="password" />
                    </p>
                    <p>
                        <label for="password_again"><?php _e('Password Again'); ?></label>
                        <input name="dff_user_pwd_confirm" id="password_again" class="required" type="password" />
                    </p>


                    <p>
                        <input type="hidden" name="custom_register_nonce" value="<?php echo wp_create_nonce('custom-register-nonce'); ?>" />
                        <input type="submit" value="<?php _e('Register', 'dff'); ?>" />
                    </p>
                </fieldset>
            </form>
        <?php
        return ob_get_clean();
    }

    // login form fields
    function dff_custom_login_form_fields()
    {

        ob_start(); ?>

            <?php
            // show any error messages after form submission
            dff_custom_show_error_messages();
            ?>
            <form id="dff_custom_login_form" class="dff-register-login-form" action="" method="post">
                <fieldset>
                    <!-- <div class="form-group">                        
                        <input name="custom_user_login" id="custom_user_login" placeholder="<?php _e('Email', 'dff_register_login') ?>" class="required" type="text" />
                    </div> -->
                    <div class="form-group">
                        <!-- <label for="custom_user_Login">Username <span class="dff_required">*</span></label> -->
                        <input name="dff_email_log" id="dff_email_log" placeholder="<?php _e('Email', 'dff_register_login') ?>" class="required" type="email" />
                    </div>
                    <div class="form-group">
                        <!-- <label for="dff_user_pwd">Password <span class="dff_required">*</span></label> -->
                        <input name="dff_user_pwd" id="dff_user_pwd" placeholder="<?php _e('Password', 'dff_register_login') ?>" class="required" type="password" />
                    </div>
                    <div class="form-group dff-login">
                        <input type="hidden" name="dff_log_nonce" value="<?php echo wp_create_nonce('dff-log-nonce'); ?>" />
                        <input id="custom_login_submit" type="submit" value="Login" />
                    </div>
                </fieldset>
            </form>
        <?php
        return ob_get_clean();
    }

    // logs a member in after submitting a form
    function dff_custom_login_member()
    {



        if (isset($_POST['dff_email_log']) && wp_verify_nonce($_POST['dff_log_nonce'], 'dff-log-nonce')) {




            // $body   = array(
            //     // 'data_send' => $data_send,
            //     "email" => "ivanko.vano2010@gmail.com",
            //     "password" => "sdfyxevfr5",
            //     // "token" => "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjYyN2NmNjVmMDRiODg5MDAyOTBkMjZkYiJ9.bqu2Jt1dBC-bRBhhJWt6lTbT0huqweAm0AgNQZ6cMdQ"
            // );
            // $result = wp_remote_post('https://dev-auth.id.dubaifuture.ae/api/v1/auth/login', array(
            //     'method'      => 'POST',
            //     'redirection' => 1,
            //     'httpversion' => '1.0',
            //     'blocking'    => true,
            //     'headers'     => array(),
            //     'body'        => $body,
            //     'cookies'     => array(),
            // ));
            // if (is_wp_error($result)) {
            //     // вернуть ошибку
            // }

            // $body     = $result['body'];
            // $body_array     = json_decode($body);
            // $success = $body_array->success;
            // if (!$success) {
            //     // вернуть ошибку
            // }
            // $data = $body_array->data;
            // print_r($data);

            $url  = 'https://dev-auth.id.dubaifuture.ae/api/v1/auth/login';
            $body = array(
                'email'    => $_POST['dff_email_log'],
                'password' => $_POST['dff_user_pwd']
            );

            $args = array(
                'method'      => 'POST',
                'timeout'     => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking'    => true,
                'sslverify'   => false,
                // 'headers'     => array(
                //     'Authorization' => 'Bearer {token goes here}',
                //     'Content-Type'  => 'application/json',
                // ),
                'body'    => json_encode($body),
                'cookies' => array()
            );

            $request = wp_remote_post($url, $args);

            if (is_wp_error($request) || wp_remote_retrieve_response_code($request) != 200) {
                error_log(print_r($request, true));
            }

            $response = json_decode(wp_remote_retrieve_body($request), true);
            $user = get_user_by('email', $_POST['dff_email_log']);

            $data_user = $response['user'];
            // $data_user_email = $response['email'];
            $data_user_firstName = $response['firstName'];
            $data_user_lastName = $response['lastName'];
            $data_user_token = $response['token'];
            // echo $data_user_email = $data_user->email;
            // print_r($data_user);
            // echo '</pre>';

            //                    $current_user = wp_get_current_user();
            // echo $current_user->user_email;




            $user = get_user_by('email', $_POST['dff_email_log']);

            $errors = dff_custom_errors()->get_error_messages();

            // only log the user in if there are no errors
            if (empty($errors) && $user) {

                // wp_setcookie($_POST['custom_user_login'], $_POST['dff_user_pwd'], true);
                // wp_set_auth_cookie($user->ID, $_POST['custom_user_login'], $_POST['dff_user_pwd'], true);

                wp_set_current_user($user->ID, $_POST['dff_email_log']);
                nocache_headers();
                wp_clear_auth_cookie();
                wp_set_auth_cookie($user->ID, true);
                do_action('wp_login', $_POST['dff_email_log']);

                wp_redirect(home_url());
                // wp_redirect(site_url('my-courses'));
                exit;
            } else {
                // echo 'test';
                // $user_login       = $_POST["custom_user_login"];
                $user_login  = dff_email_sanitize_username($_POST['dff_email_log']);
                $new_user_id = wp_insert_user(
                    array(
                        'user_login'        => $user_login,
                        'user_pass'         => $_POST['dff_user_pwd'],
                        'user_email'        => $_POST['dff_email_log'],
                        // 'first_name'        => $user_first,
                        // 'last_name'         => $user_last,
                        'user_registered'   => date('Y-m-d H:i:s'),
                        'role'              => 'subscriber'
                    )
                );
                if ($new_user_id) {
                    // send an email to the admin alerting them of the registration
                    wp_new_user_notification($new_user_id);

                    // log the new user in
                    // wp_setcookie($user_login, $user_pass, true);
                    wp_set_current_user($new_user_id, $user_login);
                    update_user_meta($new_user_id, 'first_name', $data_user_firstName);
                    update_user_meta($new_user_id, 'last_name', $data_user_lastName);
                    nocache_headers();
                    wp_clear_auth_cookie();
                    wp_set_auth_cookie($user->ID, true);
                    do_action('wp_login', $user_login);

                    // send the newly created user to the home page after logging them in
                    wp_redirect(site_url('/'));
                    exit;
                }
            }



            // echo 'Response:<pre>';
            // print_r($response);
            // echo '</pre>';
            // $url = 'https://dev-auth.id.dubaifuture.ae/api/v1/auth/login';
            // $response = wp_remote_post(
            //     $url,
            //     array(
            //         'method' => 'POST',
            //         'timeout' => 45,
            //         'redirection' => 5,
            //         'httpversion' => '1.0',
            //         'blocking' => true,
            //         // 'headers'     => [
            //         //     'Content-Type'  => 'application/json',
            //         //     'Authorization' => $token
            //         // ],
            //         // 'headers' => array(
            //         //     'Accept' => 'application/json',
            //         //     'Authorization' => 'token 3f4f654ab31c2f15e839c74c952e5de2f562f1ee'
            //         // ),
            //         // 'body' => array( 'email' => 'ivanko.vano2010@gmail.com', 'password' => 'sdfyxevfr' ),
            //         'body' => array('email' => $_POST['dff_email_log'], 'password' => $_POST['dff_user_pwd']),
            //         'cookies' => array()
            //     )
            // );


            // $data_user = $response['user'];
            // // $data_user_email = $response['email'];
            // $data_user_firstName = $response['firstName'];
            // $data_user_lastName = $response['lastName'];
            // $data_user_token = $response['token'];
            // echo $data_user_email = $data_user->email;
            // print_r($data_user);
            // echo '</pre>';

            //                    $current_user = wp_get_current_user();
            // echo $current_user->user_email;








            // if (is_wp_error($response)) {
            // $error_message = $response->get_error_message();

            // echo "Something went wrong: $error_message";
            // } else {
            // echo 'Response:<pre>';
            // $body_array = json_decode($response['body']);
            // print_r($body_array);

            //    print_r( json_decode($response['body']));
            // $data = $body_array->user->email;






            // if (!$user) {
            //     // if the user name doesn't exist
            //     dff_custom_errors()->add('empty_useremail', __('Invalid username'));
            // }

            // if (!isset($_POST['dff_user_pwd']) || $_POST['dff_user_pwd'] == '') {
            //     // if no password was entered
            //     dff_custom_errors()->add('empty_password', __('Please enter a password'));
            // }

            // // check the user's login with their password
            // if (!wp_check_password($_POST['dff_user_pwd'], $user->user_pass, $user->ID)) {
            //     // if the password is incorrect for the specified user
            //     dff_custom_errors()->add('empty_password', __('Incorrect password'));
            // }

            // retrieve all error messages
            // $errors = dff_custom_errors()->get_error_messages();

            // // only log the user in if there are no errors
            // if (empty($errors) && $user) {

            //     // wp_setcookie($_POST['custom_user_login'], $_POST['dff_user_pwd'], true);
            //     // wp_set_auth_cookie($user->ID, $_POST['custom_user_login'], $_POST['dff_user_pwd'], true);

            //     wp_set_current_user($user->ID, $_POST['dff_email_log']);
            //     nocache_headers();
            //     wp_clear_auth_cookie();
            //     wp_set_auth_cookie($user->ID, true);
            //     do_action('wp_login', $_POST['dff_email_log']);

            //     // wp_redirect(home_url());
            //     // wp_redirect(site_url('my-courses'));
            //     exit;
            // } else {
            //     // echo 'test';
            //     // $user_login       = $_POST["custom_user_login"];
            //     $user_login  = dff_email_sanitize_username($_POST['dff_email_log']);
            //     $new_user_id = wp_insert_user(
            //         array(
            //             'user_login'        => $user_login,
            //             'user_pass'         => $_POST['dff_user_pwd'],
            //             'user_email'        => $_POST['dff_email_log'],
            //             // 'first_name'        => $user_first,
            //             // 'last_name'         => $user_last,
            //             'user_registered'   => date('Y-m-d H:i:s'),
            //             'role'              => 'subscriber'
            //         )
            //     );
            //     if ($new_user_id) {
            //         // send an email to the admin alerting them of the registration
            //         wp_new_user_notification($new_user_id);

            //         // log the new user in
            //         // wp_setcookie($user_login, $user_pass, true);
            //         wp_set_current_user($new_user_id, $user_login);
            //         update_user_meta($new_user_id, 'first_name', $data_user_firstName);
            //         update_user_meta($new_user_id, 'last_name', $data_user_lastName);
            //         nocache_headers();
            //         wp_clear_auth_cookie();
            //         wp_set_auth_cookie($user->ID, true);
            //         do_action('wp_login', $user_login);

            //         // send the newly created user to the home page after logging them in
            //         // wp_redirect(site_url('/'));
            //         exit;
            //     }
            // }
            // }

            // $data_send = array(
            //     'email' => $_POST['dff_email_log'],
            //     'password' => $_POST['dff_user_pwd']
            // );

            // $body   = array(
            //     'data_send' => $data_send,
            // );
            // $result = wp_remote_post('https://dev-auth.id.dubaifuture.ae/api/v1/auth/login', array(
            //     'method'      => 'POST',
            //     'redirection' => 1,
            //     'httpversion' => '1.0',
            //     'blocking'    => true,
            //     'headers'     => array(),
            //     'body'        =>  $body,
            //     'cookies'     => array(),
            // ));
            // if (is_wp_error($result)) {
            //     // вернуть ошибку
            // }

            // $body     = $result['body'];
            // $body_array     = json_decode($body);
            // $success = $body_array->success;
            // if (!$success) {
            //     // вернуть ошибку
            // }
            // $data = $body_array->data;


            // if (isset($_POST['data_send'])) {
            //     $data = $_POST['data_send'];
            //     // Обработка данных,
            //     // установка флага $result
            //     // создание (если нужно) массива возвращаемых данных $data
            //     if ($result) {
            //         wp_send_json_success($data);
            //     } else {
            //         wp_send_json_error();
            //     }
            // }

            // this returns the user ID and other info from the user name
            // $user = get_userdatabylogin($_POST['custom_user_login']);
            // $user = get_user_by('email', $_POST['dff_email_log']);

            // if (!$user) {
            //     // if the user name doesn't exist
            //     dff_custom_errors()->add('empty_useremail', __('Invalid username'));
            // }

            // if (!isset($_POST['dff_user_pwd']) || $_POST['dff_user_pwd'] == '') {
            //     // if no password was entered
            //     dff_custom_errors()->add('empty_password', __('Please enter a password'));
            // }

            // // check the user's login with their password
            // if (!wp_check_password($_POST['dff_user_pwd'], $user->user_pass, $user->ID)) {
            //     // if the password is incorrect for the specified user
            //     dff_custom_errors()->add('empty_password', __('Incorrect password'));
            // }

            // // retrieve all error messages
            // $errors = dff_custom_errors()->get_error_messages();

            // // only log the user in if there are no errors
            // if (empty($errors)) {

            //     // wp_setcookie($_POST['custom_user_login'], $_POST['dff_user_pwd'], true);
            //     // wp_set_auth_cookie($user->ID, $_POST['custom_user_login'], $_POST['dff_user_pwd'], true);
            //     wp_set_current_user($user->ID, $_POST['dff_email_log']);
            //     nocache_headers();
            //     wp_clear_auth_cookie();
            //     wp_set_auth_cookie($user->ID, true);
            //     do_action('wp_login', $_POST['dff_email_log']);

            //     // wp_redirect(home_url());
            //     wp_redirect(site_url('my-courses'));
            //     exit;
            // }
        }

        // if (isset($_POST['custom_user_login']) && wp_verify_nonce($_POST['dff_log_nonce'], 'dff-log-nonce')) {

        //     // this returns the user ID and other info from the user name
        //     // $user = get_userdatabylogin($_POST['custom_user_login']);
        //     $user = get_user_by('login', $_POST['custom_user_login']);

        //     if (!$user) {
        //         // if the user name doesn't exist
        //         dff_custom_errors()->add('empty_username', __('Invalid username'));
        //     }

        //     if (!isset($_POST['dff_user_pwd']) || $_POST['dff_user_pwd'] == '') {
        //         // if no password was entered
        //         dff_custom_errors()->add('empty_password', __('Please enter a password'));
        //     }

        //     // check the user's login with their password
        //     if (!wp_check_password($_POST['dff_user_pwd'], $user->user_pass, $user->ID)) {
        //         // if the password is incorrect for the specified user
        //         dff_custom_errors()->add('empty_password', __('Incorrect password'));
        //     }

        //     // retrieve all error messages
        //     $errors = dff_custom_errors()->get_error_messages();

        //     // only log the user in if there are no errors
        //     if (empty($errors)) {

        //         // wp_setcookie($_POST['custom_user_login'], $_POST['dff_user_pwd'], true);
        //         // wp_set_auth_cookie($user->ID, $_POST['custom_user_login'], $_POST['dff_user_pwd'], true);
        //         wp_set_current_user($user->ID, $_POST['custom_user_login']);
        //         nocache_headers();
        //         wp_clear_auth_cookie();
        //         wp_set_auth_cookie($user->ID, true);
        //         do_action('wp_login', $_POST['custom_user_login']);

        //         // wp_redirect(home_url());
        //         wp_redirect(site_url('my-courses'));
        //         exit;
        //     }
        // }
    }
    add_action('init', 'dff_custom_login_member');

    // register a new user
    function dff_custom_add_new_member()
    {
        if (isset($_POST["custom_user_login"]) && wp_verify_nonce($_POST['custom_register_nonce'], 'custom-register-nonce')) {
            $user_login       = $_POST["custom_user_login"];
            $user_email       = $_POST["custom_user_email"];
            $user_first       = $_POST["custom_user_first"];
            $user_last        = $_POST["custom_user_last"];
            $user_pass        = $_POST["dff_user_pwd"];
            $pass_confirm     = $_POST["dff_user_pwd_confirm"];

            // this is required for username checks
            require_once(ABSPATH . WPINC . '/registration.php');

            if (username_exists($user_login)) {
                // Username already registered
                dff_custom_errors()->add('username_unavailable', __('Username already taken'));
            }
            if (!validate_username($user_login)) {
                // invalid username
                dff_custom_errors()->add('username_invalid', __('Invalid username'));
            }
            if ($user_login == '') {
                // empty username
                dff_custom_errors()->add('username_empty', __('Please enter a username'));
            }
            if (!is_email($user_email)) {
                //invalid email
                dff_custom_errors()->add('email_invalid', __('Invalid email'));
            }
            if (email_exists($user_email)) {
                //Email address already registered
                dff_custom_errors()->add('email_used', __('Email already registered'));
            }
            if ($user_pass == '') {
                // passwords do not match
                dff_custom_errors()->add('password_empty', __('Please enter a password'));
            }
            if ($user_pass != $pass_confirm) {
                // passwords do not match
                dff_custom_errors()->add('password_mismatch', __('Passwords do not match'));
            }



            //echo $msg;

            $errors = dff_custom_errors()->get_error_messages();

            // only create the user in if there are no errors
            if (empty($errors)) {

                $new_user_id = wp_insert_user(
                    array(
                        'user_login'        => $user_login,
                        'user_pass'         => $user_pass,
                        'user_email'        => $user_email,
                        'first_name'        => $user_first,
                        'last_name'         => $user_last,
                        'user_registered'   => date('Y-m-d H:i:s'),
                        'role'              => 'subscriber'
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
                    wp_redirect(home_url());
                    exit;
                }
            }
        }
    }
    add_action('init', 'dff_custom_add_new_member');

    // used for tracking error messages
    function dff_custom_errors()
    {
        static $wp_error; // Will hold global variable safely
        return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
    }

    function dff_email_sanitize_username($username)
    {
        $parts = explode("@", $username);
        if (count($parts) == 2) {
            $username = $parts[0];
        }
        return $username;
    }

    // displays error messages from form submissions
    function dff_custom_show_error_messages()
    {
        if ($codes = dff_custom_errors()->get_error_codes()) {
            echo '<div class="dff_custom_errors">';
            // Loop error codes and display errors
            foreach ($codes as $code) {
                $message = dff_custom_errors()->get_error_message($code);
                echo '<span class="error"><strong>' . __('Error') . '</strong>: ' . $message . '</span><br/>';
            }
            echo '</div>';
        }
    }

    // register our form css
    function dff_custom_register_css()
    {
        wp_register_style('custom-form-css', plugin_dir_url(__FILE__) . '/css/forms.css');
    }
    add_action('init', 'dff_custom_register_css');

    // load our form css
    function dff_custom_print_css()
    {
        global $custom_load_css;

        // this variable is set to TRUE if the short code is used on a page/post
        if (!$custom_load_css)
            return; // this means that neither short code is present, so we get out of here

        wp_print_styles('custom-form-css');
    }
    add_action('wp_footer', 'dff_custom_print_css');


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


    /**
     * redirect to home page after successful login
     */

// function dff_login_redirect($redirect, $request, $user)
// {
// 	//is there a user to check?
// 	global $user;

// 	if (isset($user->roles) && is_array($user->roles)) {
// 		//check for admins
// 		if (in_array('administrator', $user->roles)) {
// 			// redirect them to the default place
// 			return home_url() . '/wp-admin/index.php';
// 		} else {
// 			return home_url() . '/my-courses';
// 		}
// 	} else {
// 		return $redirect;
// 	}
// }

// add_filter('dff_login_redirect', 'dff_login_redirect', 10, 3);