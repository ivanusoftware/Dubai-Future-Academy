<?php
// user registration login form
function dff_register_form()
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
            $output = dff_registration_form_fields();
        } else {
            $output = __('User registration is not enabled');
        }
        return $output;
    }
}
add_shortcode('dff_register_form', 'dff_register_form');

// user login form
function dff_login_form()
{

    if (!is_user_logged_in()) {

        global $custom_load_css;

        // set this to true so the CSS is loaded
        $custom_load_css = true;

        $output = dff_login_form_fields();
        
    } else {
        // could show some logged in user info here
        // $output = 'user info here';
    }
    return $output;
}
add_shortcode('dff_login_form', 'dff_login_form');

// user login form
function dff_react_login_form()
{

    // if (!is_user_logged_in()) {

    //     global $custom_load_css;

    //     // set this to true so the CSS is loaded
    //     $custom_load_css = true;

    //     $output = dff_react_login_form_fields();

    // } else {
    //     // could show some logged in user info here
    //     // $output = 'user info here';
    // }
    $output = dff_react_login_form_fields();
    return $output;
}
add_shortcode('dff_react_login_form', 'dff_react_login_form');
