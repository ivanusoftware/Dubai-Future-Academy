<?php

/** 
 * Plugin Name: DFF Login & Registration
 * Plugin URI: dff-login-and-register
 * Description: Plugin that create custom login and registration forms that can be implemented using a shortcode for Future ID.
 * Version:           1.0.0
 * Author:            Ivan Chumak
 * Author URI:        ivan-chumak
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dff-login-and-register
 * Domain Path:       /languages
 */
session_start();

register_activation_hook(__FILE__, 'dff_login_register_activate_plugin');
function dff_login_register_activate_plugin()
{
    // if (!class_exists('WPCF7_ContactForm')) {

    //     wp_die('Please activate <a href="https://wordpress.org/plugins/contact-form-7/" target="_blank">contact form 7</a> plugin.');
    // }
    //create custom DB tables
    require('inc/create_db.php');
    flush_rewrite_rules();
}

// register_deactivation_hook(__FILE__, 'dff_login_register_deactivation_plugin');



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
        'login' => 'Login',
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



require_once plugin_dir_path(__FILE__) . 'admin/dff-option-admin.php';
require_once plugin_dir_path(__FILE__) . 'admin/enqueue-admin-scripts.php';
// require_once plugin_dir_path(__FILE__) . 'frontend/partials/dff-shortcodes.php';
// require_once plugin_dir_path(__FILE__) . 'frontend/partials/dff-login.php';
// require_once plugin_dir_path(__FILE__) . 'frontend/partials/dff-register.php';
// require_once plugin_dir_path(__FILE__) . 'frontend/partials/dff-errors.php';
require_once plugin_dir_path(__FILE__) . 'frontend/enqueue-frontend-scripts.php';
require_once plugin_dir_path(__FILE__) . 'frontend/dff-functions.php';


add_action('plugins_loaded', 'true_plugin_language');

function true_plugin_language()
{
    load_plugin_textdomain(
        'dff-login-and-registration',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages/'
    );
}
