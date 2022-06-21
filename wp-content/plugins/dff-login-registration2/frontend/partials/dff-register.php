<?php
// registration form fields
function dff_registration_form_fields()
{

    ob_start(); ?>
    <?php
    // show any error messages after form submission
    dff_show_error_messages();
    // $reg_options = get_option('reg_options');
    //echo "------------<pre>";
    //print_r($reg_options);
    ?>

    <form id="registerform" name="registerform" class="dff-register-login-form" action="" method="POST">
        <fieldset>
            <!-- <p>
                <label for="custom_user_Login"><?php _e('Username'); ?><span class="dff_required">*</span></label>
                <input name="custom_user_login" id="custom_user_login" class="required" type="text" value="<?php echo $_POST['custom_user_login']; ?>" />
            </p> -->
            <div class="form-group">
                <!-- <label for="dff_register_user_email"><?php _e('Email'); ?><span class="dff_required">*</span></label> -->
                <input name="dff_register_user_email" id="dff_register_user_email" class="required" type="email" placeholder="<?php _e('Email', 'dff-login-and-registration'); ?>" value="<?php echo $_POST['dff_register_user_email']; ?>" />
            </div>
            <!-- <p>
                <label for="custom_user_first"><?php _e('First Name'); ?></label>
                <input name="custom_user_first" id="custom_user_first" type="text" value="<?php echo $_POST['custom_user_first']; ?>" />
            </p>
            <p>
                <label for="custom_user_last"><?php _e('Last Name'); ?></label>
                <input name="custom_user_last" id="custom_user_last" type="text" value="<?php echo $_POST['custom_user_last']; ?>" />
            </p> -->
            <div class="form-group">
                <!-- <label for="password"><?php _e('Password'); ?><span class="dff_required">*</span></label> -->
                <input name="dff_register_pwd" id="password" class="required" type="password" placeholder="<?php _e('Password', 'dff-login-and-registration'); ?>" />
            </div>
            <!-- <p>
                <label for="password_again"><?php _e('Password Again'); ?></label>
                <input name="dff_register_pwd_confirm" id="password_again" class="required" type="password" />
            </p> -->


            <div class="form-group dff-register">
                <input type="hidden" name="dff_register_nonce" value="<?php echo wp_create_nonce('dff-register-nonce'); ?>" />
                <input type="submit" value="<?php _e('Register', 'dff'); ?>" />
            </div>
        </fieldset>
    </form>
<?php
    return ob_get_clean();
}



// register a new user
function dff_add_new_member()
{
    if (isset($_POST["dff_register_user_email"]) && wp_verify_nonce($_POST['dff_register_nonce'], 'dff-register-nonce')) {
        // $user_login       = $_POST["custom_user_login"];
        $user_login  = dff_email_sanitize_username($_POST['dff_register_user_email']);
        $user_email       = $_POST["dff_register_user_email"];
        // $user_first       = $_POST["custom_user_first"];
        // $user_last        = $_POST["custom_user_last"];
        $user_pass        = $_POST["dff_register_pwd"];
        // $pass_confirm     = $_POST["dff_register_pwd_confirm"];

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
        // if ($user_login == '') {
        //     // empty username
        //     dff_custom_errors()->add('username_empty', __('Please enter a username'));
        // }
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
        // if ($user_pass != $pass_confirm) {
        //     // passwords do not match
        //     dff_custom_errors()->add('password_mismatch', __('Passwords do not match'));
        // }




        $array_options = get_option('dff_reg_options');
        $url  = $array_options['dff_api_url'] . 'api/v1/auth/register';
        $body = array(
            'email'    => $_POST['dff_register_user_email'],
            'password' => $_POST['dff_register_pwd']
        );

        $args = array(
            'method'      => 'POST',
            'timeout'     => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            // 'blocking'    => true,
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


        // https://dev-auth.id.dubaifuture.ae/api/v1/auth/verifyCode

        if (is_wp_error($request)) {
            // error_log(print_r($request, true));
            $error_message = $request->get_error_message();
            echo "Something went wrong: $error_message";
        } else {

            $errors = dff_custom_errors()->get_error_messages();

            // only create the user in if there are no errors
            if (empty($errors)) {

                $new_user_id = wp_insert_user(
                    array(
                        'user_login'        => $user_login,
                        'user_pass'         => $user_pass,
                        'user_email'        => $user_email,
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
                    wp_setcookie($user_login, $user_pass, true);
                    wp_set_current_user($new_user_id, $user_login);
                    do_action('wp_login', $user_login);

                    // send the newly created user to the home page after logging them in
                    wp_redirect(home_url('login'));
                    exit;
                }
            }
        }
    }
}
add_action('init', 'dff_add_new_member');
