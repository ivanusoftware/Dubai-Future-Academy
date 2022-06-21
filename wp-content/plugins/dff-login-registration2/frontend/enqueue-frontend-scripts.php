<?php
function dff_frontend_register_css() {    
    wp_register_style('dff-frontend-css', plugin_dir_url(__FILE__) . 'css/dff-frontend.css');
	wp_enqueue_style( 'dff-frontend-css' );
}
// Register dff - frontend. css scripts
add_action( 'wp_enqueue_scripts', 'dff_frontend_register_css' );

function dff_frontend_register_js() {    
    wp_register_script('dff-frontend-js', plugin_dir_url(__FILE__) . 'js/dff-frontend.js');
	wp_enqueue_script( 'dff-frontend-js' );
}
// Register dff - frontend. css scripts
add_action( 'wp_enqueue_scripts', 'dff_frontend_register_js' );