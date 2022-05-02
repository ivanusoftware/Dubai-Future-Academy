<?php

/**
 * The function include a css files
 */
add_action('wp_enqueue_scripts', 'include_css_files');
function include_css_files()
{
    wp_enqueue_style('style', get_stylesheet_directory_uri() . '/includes/css/main.css', array(), null, false);
}
// echo basename( get_page_template());
// echo is_page_template();
if (is_page_template('/includes/user-profile.inc.php')) { 
    echo 'test test';
}


add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style('admin-css', get_template_directory_uri() . '/includes/css/main.css');
    wp_enqueue_script('admin-main-js', get_stylesheet_directory_uri() . '/includes/js/main.js', array('jquery'), null, true);
}, 99);

/**
 * The function include a javascript files
 */
add_action('wp_enqueue_scripts', 'include_javascript_files');
function include_javascript_files()
{

    wp_enqueue_script('jquery');
    wp_enqueue_script('main', get_stylesheet_directory_uri() . '/includes/js/main.js', array('jquery'), null, true);
    // wp_enqueue_script('custom', get_stylesheet_directory_uri() . '/includes/js/custom.js', array('jquery'), null, true);
    wp_enqueue_script('highcharts', get_stylesheet_directory_uri() . '/includes/js/libs/highcharts.js', array('jquery'), null, true);
}

add_action('wp_enqueue_scripts', 'courses_ajax_data', 99);
function courses_ajax_data()
{
    global $wp_query;
    wp_localize_script(
        'main',
        'courses_ajax',
        array(
            'url' => admin_url('admin-ajax.php'),

        )
    );
}
