<?php

/**********************************************************************************
 * Include js and css files
 **********************************************************************************/
// Include all enqueue scripts and lessons.
include(get_template_directory() . '/includes/enqueue-script-style.php');
include(get_template_directory() . '/includes/functions/user-functions.inc.php');
include(get_template_directory() . '/includes/functions/ajax-courses-tax.inc.php');
include(get_template_directory() . '/includes/functions/ajax-lessons-tab.inc.php');
/**
 * Add disable Gutenberg filter for a given post type.
 * @param [type] $gutenberg_filter
 * @param [type] $post_type
 * @return void
 */
// Add Gutenberg filter for a given post type
add_filter('use_block_editor_for_post_type', 'dff_disable_gutenberg', 10, 2);
function dff_disable_gutenberg($gutenberg_filter, $post_type)
{
    switch ($post_type) {
        case 'courses':
            return false;
            break;
        case 'quizzes':
            return false;
            break;
    }

    return $gutenberg_filter;
}


// Adds the mejs container class to the script if it is done.
function dff_mejs_add_container_class()
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
add_action('wp_print_footer_scripts', 'dff_mejs_add_container_class');

// Enqueue a wp - media element
function dff_enqueue_mediaelement()
{
    wp_enqueue_style('wp-mediaelement');
    wp_enqueue_script('wp-mediaelement');
}
add_action('wp_enqueue_scripts', 'dff_enqueue_mediaelement');


/**
 * The function show default image.
 * Get image by id
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
 * Returns an array of ids for all categories of courses
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


// Adds the rewrite rules for the user and course.
add_action('init', function () {
    // add_rewrite_rule( 'user-profile/([a-z]+)[/]?$', 'index.php?my_course=$matches[1]', 'top' );
    // add_rewrite_rule('user-profile/([0-9]+)/?$', 'index.php&course_id=$matches[1]', 'top');
    add_rewrite_rule('my-courses/([0-9]+)[/]?$', 'index.php?course_id=$matches[1]', 'top');
});

// Adds the filter to the course_id.
add_filter('query_vars', function ($query_vars) {
    $query_vars[] = 'course_id';
    // $query_vars[] = 'id';
    return $query_vars;
});

// Add an action to include a course template.
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
 * Formats a size unit in a human readable format
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

/**
 * Remove a Read from a wpse 93843 cap.
 *
 * @return void
 */
function remove_read_wpse_93843()
{
    $role = get_role('subscriber');
    $role->remove_cap('read');
}
add_action('admin_init', 'remove_read_wpse_93843');

/**
 * Hide the admin wpse. 93843 bar
 * @return void
 */
function hide_admin_wpse_93843()
{
    if (current_user_can('subscriber')) {
        add_filter('show_admin_bar', '__return_false');
    }
}
add_action('wp_head', 'hide_admin_wpse_93843');

/**
 * Open module by date.
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
        $module = 'open-module';
    } else {
        $module = 'close-module';
    }
    return $module;
}
/**
 * Open and block Exam by date
 *
 * @param [type] $module_i
 * @param [type] $row_count
 * @param [type] $date_open_module
 * @return void
 */
// function dff_open_exam_by_date($module_i, $row_count, $date_open_module)
// {
//     $currentDateTime = date('d-m-Y');
//     $current_timestamp = strtotime($currentDateTime);
//     if ($module_i == $row_count - 1 && $module_i < $row_count) {
//         echo 'test';
//         $date_timestamp = strtotime($date_open_module);
//     }
//     if ($current_timestamp >= $date_timestamp && $module_i == $row_count - 1) {
//         echo $date_timestamp;
//         $module = 'open-module';
//     } else {
//         echo $date_timestamp;
//         $module = 'close-module';
//     }
//     return $module;
// }

/**
 * Opening the module after passing the test.
 *
 * @param [type] $course_id
 * @param [type] $module_i
 * @return void
 */
function dff_open_module_by_rusult_test($course_id, $module_i)
{
    $result_module_key = dff_module_course_user_key($course_id, $module_i - 1);
    $result_module = get_user_meta(get_current_user_id(), $result_module_key, true);
    if ($result_module >= 80 || $module_i == 1) {
        $module = 'open-module';
    } else {
        $module = 'close-module';
    }
    return $module;
}

/**
 * Create module course user key
 *
 * @param [type] $course_id
 * @param [type] $model_index
 * @return void
 */
function dff_module_course_user_key($course_id, $module_i)
{
    $result_module_key = 'course_' . $course_id . '_module_' . $module_i . '_result';
    return $result_module_key;
}



/** 
 * Show the date on the tabs on the left of my course page
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
