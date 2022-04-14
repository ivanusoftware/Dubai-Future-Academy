<?php

/**********************************************************************************
 * Include js and css files
 **********************************************************************************/
include(get_template_directory() . '/includes/enqueue-script-style.php');
include(get_template_directory() . '/includes/functions/user-functions.inc.php');
include(get_template_directory() . '/includes/functions/ajax-courses-tax.inc.php');
include(get_template_directory() . '/includes/functions/ajax-lessons-tab.inc.php');
/**
 * Disable Gutenberg template
 *
 * @param [type] $gutenberg_filter
 * @param [type] $post_type
 * @return void
 */
add_filter('use_block_editor_for_post_type', 'dff_disable_gutenberg', 10, 2);
function dff_disable_gutenberg($gutenberg_filter, $post_type)
{
    switch ($post_type) {
        case 'courses':
            return false;
            break;
            // case 'services':
            //     return false;
            //     break;      
    }

    return $gutenberg_filter;
}

add_action('wp_print_footer_scripts', 'mytheme_mejs_add_container_class');

function mytheme_mejs_add_container_class()
{
    if (!wp_script_is('mediaelement', 'done')) {
        return;
    }
?>
    <script>
        (function() {
            var settings = window._wpmejsSettings || {};
            settings.features = settings.features || mejs.MepDefaults.features;
            settings.features.push('exampleclass');
            MediaElementPlayer.prototype.buildexampleclass = function(player) {
                player.container.addClass('lesson-audio-container');
            };
        })();
    </script>
<?php
}

function enqueue_mediaelement()
{
    wp_enqueue_style('wp-mediaelement');
    wp_enqueue_script('wp-mediaelement');
}
add_action('wp_enqueue_scripts', 'enqueue_mediaelement');


/**
 * The function show default image.
 * @param $pid
 * @param string $size
 * @return array|bool|false
 **/
function get_image_by_id($pid, $size = '')
{
    $img = false;
    $thumbnail_id = get_post_thumbnail_id($pid);
    if ($thumbnail_id)
        $img = wp_get_attachment_image_src($thumbnail_id, $size);
    return $img;
}

/**
 * Get Ids Courses Category
 *
 * @param [type] $tax_courses_name
 * @return void
 */
function get_ids_courses_category($tax_courses_name)
{
    $ids = [];
    $courses_terms = get_terms($tax_courses_name, array(
        'parent'     => 0,
        'hide_empty' => true,
    ));
    foreach ($courses_terms as $courses_term) {
        $ids[] = $courses_term->term_id;
    };
    return $ids;
}


add_action('init', function () {
    // add_rewrite_rule( 'user-profile/([a-z]+)[/]?$', 'index.php?my_course=$matches[1]', 'top' );
    // add_rewrite_rule('user-profile/([0-9]+)/?$', 'index.php&course_id=$matches[1]', 'top');
    add_rewrite_rule('my-courses/([0-9]+)[/]?$', 'index.php?course_id=$matches[1]', 'top');
});

add_filter('query_vars', function ($query_vars) {
    $query_vars[] = 'course_id';
    // $query_vars[] = 'id';
    return $query_vars;
});

add_action('template_include', function ($template) {
    // if (is_user_logged_in()) {
    //     // wp_redirect( home_url( '/wp-login.php' ), 302 );
    //     get_template_part(404);
    //     exit();
    // }
    if (get_query_var('course_id') == false || get_query_var('course_id') == '') {
        return $template;
    }
    return get_template_directory() . '/includes/courses/my-courses/my-courses.inc.php';
});

/**
 * Undocumented function
 *
 * @param [type] $bytes
 * @return void
 */
function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}

function remove_read_wpse_93843()
{
    $role = get_role('subscriber');
    $role->remove_cap('read');
}
add_action('admin_init', 'remove_read_wpse_93843');

function hide_admin_wpse_93843()
{
    if (current_user_can('subscriber')) {
        add_filter('show_admin_bar', '__return_false');
    }
}
add_action('wp_head', 'hide_admin_wpse_93843');

/**
 * Undocumented function
 *
 * @param [type] $date_open_module
 * @return void
 */
function dff_open_module_by_date($date_open_module)
{

    $currentDateTime = date('d-m-Y');
    $current_timestamp = strtotime($currentDateTime);
    $date_timestamp = strtotime($date_open_module);
    if ($current_timestamp >= $date_timestamp) {
        $time_progressive = 'open-module';
    } else {
        $time_progressive = 'close-module';
    }
    return $time_progressive;
}

/**
 * Undocumented function
 *
 * @param [type] $repeater_name
 * @return void
 */
function dff_show_date($date_open_module)
{

    $currentDateTime = date('d-m-Y');
    $current_timestamp = strtotime($currentDateTime);
    $date_timestamp = strtotime($date_open_module);
    if ($current_timestamp >= $date_timestamp) {
        $show_date = '';
    } else {
        $show_date = '<span>' . date("d.m.Y", strtotime($date_open_module)) . '</span>';
    }
    return $show_date;
}


// function redirect_sub_to_home_wpse_93843( $redirect_to, $request, $user ) {
//     if ( isset($user->roles) && is_array( $user->roles ) ) {
//       if ( in_array( 'subscriber', $user->roles ) ) {
//           return home_url( );
//       }   
//     }
//     return $redirect_to;
// }
// add_filter( 'login_redirect', 'redirect_sub_to_home_wpse_93843', 10, 3 );



/**
 * URL Rewrites
 */
// function myRewrite()
// {
//     /** @global WP_Rewrite $wp_rewrite */
//     global $wp_rewrite;

//     $newRules = array(
//         'pets/?$' => 'index.php?my_page=pet',
//         'pets/(\d+)/?$' => sprintf(
//             'index.php?my_page=pet&pet_id=%s',
//             $wp_rewrite->preg_index(1)
//         ),
//     );

//     $wp_rewrite->rules = $newRules + (array) $wp_rewrite->rules;
// }

// add_action('generate_rewrite_rules', 'myRewrite');
