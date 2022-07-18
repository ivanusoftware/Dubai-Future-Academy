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

register_deactivation_hook(__FILE__, 'dff_login_register_deactivation_plugin');



function dff_login_register_deactivation_plugin()
{
    // dff_unset_cookies();
?>
    <script>
        // if (localStorage.length > 0) {
        //     console.log(localStorage);
        //     localStorage.clear();
        // }
        // location.reload();
        // window.location.reload();
    </script>
<?php
    flush_rewrite_rules();
}



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
