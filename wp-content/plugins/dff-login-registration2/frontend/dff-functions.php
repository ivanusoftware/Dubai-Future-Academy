<?php


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


function dff_logout_action()
{
    if (isset($_COOKIE['token'])) {
        unset($_COOKIE['token']);
        setcookie("token", '', time() + 3600, "/", $_SERVER['HTTP_HOST']);
    }
    if (isset($_COOKIE['user'])) {
        unset($_COOKIE['user']);
        setcookie("user", '', time() + 3600, "/", $_SERVER['HTTP_HOST']);
    }
    if (isset($_COOKIE['fid-is-loggedin'])) {
        unset($_COOKIE['fid-is-loggedin']);
        setcookie("fid-is-loggedin", '', time() + 3600, "/", $_SERVER['HTTP_HOST']);
    }
}
add_action('wp_logout', 'dff_logout_action');


if (!function_exists('dff_get_future_user_name')) {
    function dff_get_future_user_name($user_id)
    {
        if ($_COOKIE['auth_Token']) {
            $auth_Token = $_COOKIE['auth_Token'];
        }
        $array_options = get_option('dff_reg_options');
        $remote_url = $array_options['dff_api_url_future_user'] . 'api/v1/users/' . $user_id;
        $args = array(
            'headers'     => array(
                'Authorization' =>  $auth_Token,
            ),
        );
        $result = wp_remote_get($remote_url, $args);
        $response = json_decode(wp_remote_retrieve_body($result), true);
        return $response['displayName'];
    }
}

if (!function_exists('dff_get_future_user_data')) {
    function dff_get_future_user_data()
    {
        if ($_COOKIE['user']) {
            return json_decode(stripslashes($_COOKIE['user']));
        }
    }
}

// function create_future_user()
// {

//     global $wpdb;
//     if ($_COOKIE['user'] && $_COOKIE['fid-is-loggedin']) {
//         $dff_get_future_user_data = dff_get_future_user_data();
//         $future_user_id = $dff_get_future_user_data->id;
//         $table_name = $wpdb->base_prefix . 'dff_future_users';

//         // $user_id = $wpdb->get_results("SELECT ID FROM $table_name WHERE future_user_id = $future_user_id");
//         // $user_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");


//         // $res = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_name} WHERE `future_user_id` = %s",$future_user_id));
//         $res = $wpdb->get_row($wpdb->prepare("SELECT future_user_id FROM $table_name WHERE future_user_id = '$future_user_id'"));
//         // print_r($user_id);
//         if (!$res) {
//             //if post id not already added
//             $wpdb->insert(
//                 $table_name,
//                 array(
//                     'future_user_id' => $future_user_id,
//                     'user_date' => current_time('Y-m-d H:i:s'),
//                     'user_date_gmt' => current_time('Y-m-d H:i:s')
//                 )
//             );
//         }
//     }
// }


// echo dirname( __FILE__ );
//Load template from specific page
add_filter('page_template', 'wpa3396_page_template');
function wpa3396_page_template($page_template)
{

    if (get_page_template_slug() == 'template-login.php') {
        $page_template = dirname(__FILE__) . '/template-login.php';
    }
    return $page_template;
}

/**
 * Add "Custom" template to page attirbute template section.
 */
add_filter('theme_page_templates', 'wpse_288589_add_template_to_select', 10, 4);
function wpse_288589_add_template_to_select($post_templates, $wp_theme, $post, $post_type)
{

    // Add custom template named template-custom.php to select dropdown 
    $post_templates['template-login.php'] = __('Login');

    return $post_templates;
}
