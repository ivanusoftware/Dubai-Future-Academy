<?php
// Enqueue css scripts.
function dff_admin_register_css() {    
    wp_register_style('dff-admin-css', plugin_dir_url(__FILE__) . 'css/dff-admin.css');
	wp_enqueue_style( 'dff-admin-css' );
}
add_action( 'admin_enqueue_scripts', 'dff_admin_register_css' );

