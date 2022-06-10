<?php
// Sanitizes a username.
function dff_email_sanitize_username($username)
{
    $parts = explode("@", $username);
    if (count($parts) == 2) {
        $username = $parts[0];
    }
    return $username;
}

// login form fields
function dff_login_form_fields()
{

    ob_start(); ?>

    <?php
    // show any error messages after form submission
    dff_show_error_messages();

    ?>
    <form id="dff_login_form" class="dff-register-login-form" action="" method="post">
        <fieldset>
            <div class="form-group">
                <!-- <label for="custom_user_Login">Username <span class="dff_required">*</span></label> -->
                <input name="dff_email_log" id="dff_email_log" placeholder="<?php _e('Email', 'dff-login-and-registration'); ?>" class="required" type="email" />
            </div>
            <div class="form-group">
                <!-- <label for="dff_user_pwd">Password <span class="dff_required">*</span></label> -->
                <input name="dff_user_pwd" id="dff_user_pwd" placeholder="<?php _e('Password', 'dff-login-and-registration'); ?>" class="required" type="password" />
            </div>
            <div class="form-group dff-login">
                <input type="hidden" name="dff_log_nonce" value="<?php echo wp_create_nonce('dff-log-nonce'); ?>" />
                <input id="login_submit" type="submit" value="Login" />
            </div>
        </fieldset>
    </form>
<?php
    return ob_get_clean();
}

// logs a member in after submitting a form
function dff_login_member()
{

    if (isset($_POST['dff_email_log']) && wp_verify_nonce($_POST['dff_log_nonce'], 'dff-log-nonce')) {
        $array_options = get_option('dff_reg_options');
        $url  = $array_options['dff_api_url'] . 'api/v1/auth/login';
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
            'headers'     => array(),
            'cookies'     => array(),
            // 'headers'     => array(
            //     'Authorization' => 'Bearer {token goes here}',
            //     'Content-Type'  => 'application/json',
            // ),
            // 'body'    => json_encode($body),
            'body'    => $body
        );

        $request = wp_remote_post($url, $args);

        // if (is_wp_error($request) || wp_remote_retrieve_response_code($request) != 200) {
        if (is_wp_error($request)) {
            // error_log(print_r($request, true));
            $error_message = $request->get_error_message();
            echo "Something went wrong: $error_message";
        } else {




            //Retrieve only the body from the raw response.
            $response = json_decode(wp_remote_retrieve_body($request), true);
            $logins = wp_remote_retrieve_cookie_value($request, 'logins');

            // echo 'response_cookie: ' .$response_cookie = wp_remote_retrieve_cookie($request, true);
            // $sess_cookie = wp_remote_retrieve_cookie($request, 'PHPSESSID');
            $token =  $response['token'];

            $token2 = wp_remote_retrieve_cookie_value($request, 'token');



            // echo $retrieve_cookies = wp_remote_retrieve_cookies( $request );
            $retrieve_cookies = wp_remote_retrieve_cookie($request, 'logins');

            $retrieve_cookies = wp_remote_retrieve_cookie_value($request, 'logins');
            $logins2 = substr(strstr($retrieve_cookies, ':'), 1, strlen($retrieve_cookies));
            // $response_header = wp_remote_retrieve_headers($request);

            // $set_cookie_logins = wp_remote_retrieve_header( $request, 'set-cookie' );
            $url_redirect = $array_options['dff_api_url'] . 'api/v1/auth/set-auth-cookies';
            $data = array(
                'token'     => $token,
                'logins'    => $logins2,
                'url_next'  => wp_get_current_url()
                // 'token'     => '123',
                // 'logins'    => '321',
                // 'url_next'  => 'http://dubaifuture.loc/initiatives'
            );
            $query_url = $url_redirect . '?' . http_build_query($data);

            $user = get_user_by('email', $_POST['dff_email_log']);
            $errors = dff_custom_errors()->get_error_messages();
            $data_user = $response['user'];
            // $data_user_email = $response['email'];
            $data_user_firstName = $data_user['firstName'];
            $data_user_lastName = $data_user['lastName'];

            
            if (empty($errors) && $user) {

                // wp_setcookie($_POST['custom_user_login'], $_POST['dff_user_pwd'], true);
                // wp_set_auth_cookie($user->ID, $_POST['custom_user_login'], $_POST['dff_user_pwd'], true);

                wp_set_current_user($user->ID, $_POST['dff_email_log']);
                nocache_headers();
                wp_clear_auth_cookie();
                wp_set_auth_cookie($user->ID, true);
                do_action('wp_login', $_POST['dff_email_log']);

                // wp_redirect(home_url());
                wp_redirect($query_url);
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
                    // wp_redirect(site_url('/'));
                    wp_redirect($query_url);
                    exit;
                }
            }
        }
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
add_action('init', 'dff_login_member');
