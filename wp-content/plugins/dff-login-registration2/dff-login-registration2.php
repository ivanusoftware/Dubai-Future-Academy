<?php

/** 
 * Plugin Name: DFF Login & Registration 2
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

require_once plugin_dir_path(__FILE__) . 'admin/dff-option-admin.php';
require_once plugin_dir_path(__FILE__) . 'admin/enqueue-admin-scripts.php';
require_once plugin_dir_path(__FILE__) . 'frontend/partials/dff-shortcodes.php';
require_once plugin_dir_path(__FILE__) . 'frontend/partials/dff-login.php';
require_once plugin_dir_path(__FILE__) . 'frontend/partials/dff-register.php';
require_once plugin_dir_path(__FILE__) . 'frontend/partials/dff-errors.php';
require_once plugin_dir_path(__FILE__) . 'frontend/enqueue-frontend-scripts.php';
require_once plugin_dir_path(__FILE__) . 'frontend/dff-functions.php';


add_action( 'plugins_loaded', 'true_plugin_language' );
 
function true_plugin_language() {
	load_plugin_textdomain(
        'dff-login-and-registration',
        false, dirname(plugin_basename(__FILE__)) . '/languages/'
    );
}