<?php

// function dff_logout_action()
// {
//     if (isset($_COOKIE['token'])) {
//         unset($_COOKIE['token']);
//         setcookie("token", '', time() + 3600, "/", $_SERVER['HTTP_HOST']);
//     }
//     if (isset($_COOKIE['user'])) {
//         unset($_COOKIE['user']);
//         setcookie("user", '', time() + 3600, "/", $_SERVER['HTTP_HOST']);
//     }
//     if (isset($_COOKIE['fid-is-loggedin'])) {
//         unset($_COOKIE['fid-is-loggedin']);
//         setcookie("fid-is-loggedin", '', time() + 3600, "/", $_SERVER['HTTP_HOST']);
//     }

//     <script type="text/javascript">
//         jQuery(function($) {
//             if (localStorage.length > 0) {
//                 console.log(localStorage);
//                 localStorage.clear();
//             }
//         });
//         if (localStorage.length > 0 ) {
//             console.log(localStorage);
//             localStorage.clear();
//         }
//     </script>

// <?php// wp_redirect(site_url('/'));
// }
// add_action('wp_logout', 'dff_logout_action');


// if (!function_exists('dff_get_future_user_name')) {
//     function dff_get_future_user_name($user_id)
//     {
//         if ($_COOKIE['auth_Token']) {
//             $auth_Token = $_COOKIE['auth_Token'];
//         }
//         $array_options = get_option('dff_reg_options');
//         $remote_url = $array_options['dff_api_url_future_user'] . 'api/v1/users/' . $user_id;
//         $args = array(
//             'headers'     => array(
//                 'Authorization' =>  $auth_Token,
//             ),
//         );
//         $result = wp_remote_get($remote_url, $args);
//         $response = json_decode(wp_remote_retrieve_body($result), true);
//         return $response['displayName'];
//     }
// }

if (!function_exists('dff_get_future_user_data')) {
    function dff_get_future_user_data()
    {
        if ($_COOKIE['user']) {
            return json_decode(stripslashes($_COOKIE['user']));
        }
    }
}


function create_future_user()
{    
    if (isset($_COOKIE['user']) && isset($_COOKIE['fid-is-loggedin'])) {
        $dff_get_future_user_data = dff_get_future_user_data();
        $future_user_id = $dff_get_future_user_data->id;
        global $wpdb;
        $table_name = $wpdb->base_prefix . 'dff_future_users';
        $res = $wpdb->get_row($wpdb->prepare("SELECT future_user_id FROM $table_name WHERE future_user_id = %s", $future_user_id));
        // print_r($user_id);
        if (!$res) {
            //if post id not already added
            $wpdb->insert(
                $table_name,
                array(
                    'future_user_id' => $future_user_id,
                    'user_date' => current_time('Y-m-d H:i:s'),
                    'user_date_gmt' => current_time('Y-m-d H:i:s')
                )
            );
        }
    }
    // exit;
}
add_action('init', 'create_future_user');
// add_action( 'plugins_loaded', 'create_future_user' );


// echo dirname( __FILE__ );
//Load template from specific page
add_filter('page_template', 'dff_page_template');
function dff_page_template($page_template)
{

    if (get_page_template_slug() == 'template-login.php') {
        $page_template = dirname(__FILE__) . '/template-login.php';
    }elseif(get_page_template_slug() == 'template-register.php'){
        $page_template = dirname(__FILE__) . '/template-register.php';    
    }elseif(get_page_template_slug() == 'template-dashboard.php'){
        $page_template = dirname(__FILE__) . '/template-dashboard.php';
    }
    return $page_template;
}

/**
 * Add "Custom" template to page attirbute template section.
 */
add_filter('theme_page_templates', 'dff_add_template_to_select', 10, 4);
function dff_add_template_to_select($post_templates, $wp_theme, $post, $post_type)
{

    // Add custom template named template-custom.php to select dropdown 
    $post_templates['template-login.php'] = __('Login Future ID', 'dff-plugin');
    $post_templates['template-register.php'] = __('Register Future ID', 'dff-plugin');
    $post_templates['template-dashboard.php'] = __('Dashboard Future ID', 'dff-plugin');

    return $post_templates;
}



function dff_logout_user_ajax_callback()
{
    // echo 'test';
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
    wp_send_json_success();
    wp_die();
}
add_action('wp_ajax_dff_logout_user_ajax', 'dff_logout_user_ajax_callback');
add_action('wp_ajax_nopriv_dff_logout_user_ajax', 'dff_logout_user_ajax_callback');


// if (has_nav_menu('future-id')) {
//     wp_nav_menu(
//         array(
//             'theme_location' => 'future-id',
//             'menu_class'     => 'future-id',
//             // 'container'      => true,
//             // 'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
//             // 'walker'         => new Nav_MegaMenu_Walker(),
//         )
//     );
// }

if ( ! function_exists( 'register_future_id_nav' ) ) {
	function register_future_id_nav() {		
		register_nav_menu( 'future-logged-in-menu', __( 'Future logged in menu', 'dff-plugin' ) );
		register_nav_menu( 'future-logged-out-menu', __( 'Future logged out menu', 'dff-plugin' ) );
	}
}
add_action( 'init', 'register_future_id_nav' );