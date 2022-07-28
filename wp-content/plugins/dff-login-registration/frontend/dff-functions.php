<?php

/**
 * Adds a new user to the future.
 *
 * @return void
 */
function create_future_user()
{
    if (isset($_COOKIE['user']) && isset($_COOKIE['fid-is-loggedin'])) {
        $dff_get_future_user_data = dff_get_future_user_data();
        $future_user_id = $dff_get_future_user_data->id;
        global $wpdb;
        $table_name = $wpdb->base_prefix . 'dff_future_users';
        $res = $wpdb->get_row($wpdb->prepare("SELECT future_user_id FROM $table_name WHERE future_user_id = %s", $future_user_id));
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

    if (get_page_template_slug() == 'template-react.php') {
        $page_template = dirname(__FILE__) . '/template-react.php';
    } 
    // elseif (get_page_template_slug() == 'template-register.php') {
    //     $page_template = dirname(__FILE__) . '/template-register.php';
    // } elseif (get_page_template_slug() == 'template-dashboard.php') {
    //     $page_template = dirname(__FILE__) . '/template-dashboard.php';
    // }
    return $page_template;
}


if (!function_exists('dff_add_template_to_select')) {
    /**
     * Add "Custom" template to page attirbute template section.
     *
     * @param [type] $post_templates
     * @param [type] $wp_theme
     * @param [type] $post
     * @param [type] $post_type
     * @return void
     */
    function dff_add_template_to_select($post_templates, $wp_theme, $post, $post_type)
    {

        // Add custom template named template-custom.php to select dropdown 
        // $post_templates['template-login.php'] = __('Login Future ID', 'dff-plugin');
        // $post_templates['template-register.php'] = __('Register Future ID', 'dff-plugin');
        // $post_templates['template-dashboard.php'] = __('Dashboard Future ID', 'dff-plugin');
        $post_templates['template-react.php'] = __('Default Future ID', 'dff-plugin');

        return $post_templates;
    }
}
add_filter('theme_page_templates', 'dff_add_template_to_select', 10, 4);



// Logout a user ajax callback
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


if (!function_exists('dff_register_future_id_nav')) {
    /**
     * Register the nav menus
     *
     * @return void
     */
    function dff_register_future_id_nav()
    {
        register_nav_menu('future-logged-in-menu', __('Future logged in menu', 'dff-plugin'));
        register_nav_menu('future-logged-out-menu', __('Future logged out menu', 'dff-plugin'));
    }
}
add_action('init', 'dff_register_future_id_nav');

if (!function_exists('dff_add_params_redirect_after_login')) {
    /**
     * The function adds redirection options 
     * after login or registration
     *
     * @param [type] $items
     * @param [type] $menu
     * @param [type] $args
     * @return void
     */
    function dff_add_params_redirect_after_login($items, $menu, $args)
    {
        global $post;
        $post_slug = $post->post_name;
        if ($menu->slug == 'future-logged-out-menu') {
            foreach ($items as $item) {
                $item_class = $item->classes;
                // && $post_slug != 'homepage'
                // if ($item_class[0] == 'dff-login-register-item' && $post_slug != 'authorize') {
                if ($item_class[0] == 'dff-login-register-item' && $post_slug != 'authorize') {
                    $item->url .= '?api_id=' . $post_slug;
                }
            }
        }
        return $items;
    }
}
add_filter('wp_get_nav_menu_items', 'dff_add_params_redirect_after_login', 11, 3);




// function dff_add_build_pages()
// {
//     // Create post object
//     $my_post = array(
//         'post_title'    => wp_strip_all_tags('My Custom Page'),
//         'post_content'  => 'My custom page content',
//         'post_status'   => 'publish',
//         'post_author'   => 1,
//         'post_type'     => 'page',
//     );

//     // Insert the post into the database
//     wp_insert_post($my_post);
// }

// register_activation_hook(__FILE__, 'dff_add_build_pages');



// register_activation_hook( __FILE__, 'beardbot_plugin_activation' );
// function beardbot_plugin_activation() {

//   if ( ! current_user_can( 'activate_plugins' ) ) return;

//   global $wpdb;

//   if ( null === $wpdb->get_row( "SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'new-page-slug'", 'ARRAY_A' ) ) {

//     $current_user = wp_get_current_user();

//     // create post object
//     $page = array(
//       'post_title'  => __( 'TestPage' ),
//       'post_status' => 'publish',
//       'post_author' => $current_user->ID,
//       'post_type'   => 'page',
//     );

//     // insert the post into the database
//     wp_insert_post( $page );
//   }
// }


register_activation_hook(__FILE__, 'insert_page_on_activation_en');

function data_for_insert_pages(){
    $pages = array(
        'authorize' => 'Login',
        'register' => 'Register',
        'dashboard' => 'Dashboard',
        'dashboard' => 'Dashboard',
        'updatemail' => 'Updatemail',
        'updatepassword' => 'Updatepassword',
    );
    foreach ($pages as $page_url => $page_title) {
        // $id = get_page_by_title($page_title);
        $new_page = array(
            'post_type'   => 'page',
            'post_name'   => $page_url,
            'post_title'  => $page_title,
            'post_status' => 'publish',
            'post_author' => 1,
            'post_parent' => ''
        );
        // if (!isset($id)) wp_insert_post($page);
        if (!get_page_by_path($page_url, OBJECT, 'page')) { // Check If Page Not Exits
            $new_page_id = wp_insert_post($new_page);
        }

        if ($new_page_id && !is_wp_error($new_page_id)) {
            update_post_meta($new_page_id, '_wp_page_template', 'template-react.php');
        }
    };
}

function insert_page_on_activation_en()
{
    if (!current_user_can('activate_plugins')) return;
    switch_to_blog(3);
    data_for_insert_pages();
    restore_current_blog();
    data_for_insert_pages();
}

// register_activation_hook(__FILE__, 'insert_page_on_activation_ar');

// function insert_page_on_activation_ar()
// {

//     $pages = array(
//         'login' => 'Login',
//         'register' => 'Register',
//         'dashboard' => 'Dashboard',
//         'dashboard' => 'Dashboard',
//         'updatemail' => 'Updatemail',
//         'updatepassword' => 'Updatepassword',
//     );
//     foreach ($pages as $page_url => $page_title) {
//         // $id = get_page_by_title($page_title);
//         $new_page = array(
//             'post_type'   => 'page',
//             'post_name'   => $page_url,
//             'post_title'  => $page_title,
//             'post_status' => 'publish',
//             'post_author' => 1,
//             'post_parent' => ''
//         );
//         // if (!isset($id)) wp_insert_post($page);
//         if (!get_page_by_path($page_url, OBJECT, 'page')) { // Check If Page Not Exits
//             $new_page_id = wp_insert_post($new_page);
//         }

//         if ($new_page_id && !is_wp_error($new_page_id)) {
//             update_post_meta($new_page_id, '_wp_page_template', 'template-react.php');
//         }
//     };
// }





// check for plugin using plugin name

// function dff_unset_cookies()
// {
//     
//     include_once ABSPATH . 'wp-admin/includes/plugin.php';
//     if (!is_plugin_active('dff-login-registration/dff-login-registration.php')) {
//         echo "plugin is not activated";
//         // register_post_type( 'book', ['public' => true ] ); 
//         if (isset($_COOKIE['token'])) {
//             unset($_COOKIE['token']);
//             setcookie("token", '', time() + 3600, "/", $_SERVER['HTTP_HOST']);
//         }
//         if (isset($_COOKIE['user'])) {
//             unset($_COOKIE['user']);
//             setcookie("user", '', time() + 3600, "/", $_SERVER['HTTP_HOST']);
//         }
//         if (isset($_COOKIE['fid-is-loggedin'])) {
//             unset($_COOKIE['fid-is-loggedin']);
//             setcookie("fid-is-loggedin", '', time() + 3600, "/", $_SERVER['HTTP_HOST']);
//         }
//     }
// }
// add_action('init', 'dff_unset_cookies');