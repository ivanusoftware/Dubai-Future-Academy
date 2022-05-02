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


/**
 * Options page
 */
if (function_exists('acf_add_options_page')) {


    acf_add_options_sub_page(array(
        'page_title'  => 'Course Settings',
        'menu_title'  => 'Course Settings',
        'parent_slug' => 'edit.php?post_type=courses'
    ));
    // acf_add_options_sub_page(array(
    //     'page_title'  => 'Products Settings',
    //     'menu_title'  => 'Products Settings',
    //     'parent_slug' => 'edit.php?post_type=products'
    // ));

    // acf_add_options_sub_page(array(
    //     'page_title'  => 'Vacancies Settings',
    //     'menu_title'  => 'Vacancies Settings',
    //     'parent_slug' => 'edit.php?post_type=vacancies'
    // ));
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
    add_rewrite_rule('ar/my-courses/([0-9]+)[/]?$', 'index.php?course_id=$matches[1]', 'top');
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
    $currentDateTime   = date('d-m-Y');
    $current_timestamp = strtotime($currentDateTime);
    $date_timestamp    = strtotime($date_open_module);
    if ($current_timestamp >= $date_timestamp) {
        $module = 'open-module';
    } else {
        $module = 'close-module';
    }
    return $module;
}

/**
 * Returns a json encoded representation of a progress module.
 *
 * @return void
 */
function dff_general_progress_mod()
{
    $mod_arr = array();
    if (have_rows('course_module_repeater')) :
        while (have_rows('course_module_repeater')) : the_row();
            $module_or_exam = get_sub_field('module_or_exam');
            $module_i = get_row_index();
            if ($module_or_exam == 'module') {
                $mod_arr[] = __('Mod', 'dff') . ' ' . $module_i;
            } elseif ($module_or_exam == 'exam') {
                $mod_arr[] = __('Exam', 'dff');
            }
        endwhile;
    else :
    endif;
    return  json_encode($mod_arr);
}

// dff_general_progress_mod_result
function dff_general_progress_mod_result($course_id)
{
    $mod_result_arr = array();
    $exam_key    = 'course_' . $course_id . '_exam_result';
    $exam_result = get_user_meta(get_current_user_id(), $exam_key, true);
    if($exam_result == 1){
        $exam_result = 0;
    }
    if (have_rows('course_module_repeater')) :
        while (have_rows('course_module_repeater')) : the_row();
            $module_or_exam = get_sub_field('module_or_exam');
            $module_i = get_row_index();
            $result_module_key = dff_module_course_user_key($course_id, $module_i);
            $result_module     = get_user_meta(get_current_user_id(), $result_module_key, true);
            if($result_module == 1){
                $result_module = 0;
            }
            if ($module_or_exam == 'module') {
                $mod_result_arr[] = $result_module;
            } elseif ($module_or_exam == 'exam') {
                $mod_result_arr[] = $exam_result;
            }
        endwhile;
    else :
    endif;
    return  implode(",", $mod_result_arr);
}

//
/**
 *  Determines if a time group is disabled or not.
 *
 * @param [type] $courses_format
 * @param [type] $course_id
 * @return void
 */
function dff_format_time_bound($courses_format, $course_id)
{
    $courses_format_value = $courses_format['value'];
    if ($courses_format_value == 'time_bound_course') {
        $course_time_group = get_field('course_time_group', $course_id);
        if ($course_time_group) :
            $currentDateTime       = date('d-m-Y');
            $current_timestamp     = strtotime($currentDateTime);
            $start_date_timestamp  = strtotime($course_time_group['course_start']);
            $finish_date_timestamp = strtotime($course_time_group['course_finish']);
            if ($current_timestamp >= $start_date_timestamp && $current_timestamp <= $finish_date_timestamp) {
                $disabled = '';
            } elseif ($current_timestamp > $start_date_timestamp && $current_timestamp > $finish_date_timestamp) {
                $disabled = 'disabled';
            } else {
                $disabled = 'disabled';
            }
            return $disabled;
        endif;
    }
}

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
 * Creates a button to try again for test.
 *
 * @param [type] $module_i
 * @return void
 */
function dff_button_try_again_test($module_i)
{
    if (have_rows('course_lesson_repeater')) :
        while (have_rows('course_lesson_repeater')) : the_row();
            $lesson_i = get_row_index();
            $lesson_or_test = get_sub_field('lesson_or_test');
            $lesson_test_id = get_sub_field('lesson_test_id');
            if ($lesson_or_test == 'lesson_test') {
                $try_again = '<button class="btn-course-primary test-try-again" tab-id="tab-2" module-index="' . $module_i . '" lesson-index="' . $lesson_i . '" lesson-test-id="' . $lesson_test_id . '">' . __('Try again', 'dff') . '</button>';
            }
        endwhile;
    else :
    endif;
    return $try_again;
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

/**
 * php to js
 *
 * @return void
 */
function dff_action_function_php_to_js()
{
    // $straight = get_field('straight', 'option');
    // $anim = get_field('anim', 'option');
    wp_localize_script('main', 'php_params', array(
        'site_url'  => get_site_url(),
    ));
}
add_action('wp_enqueue_scripts', 'dff_action_function_php_to_js', 999);

/**
 * Returns the permalink for the current user courses
 *
 * @param [type] $redirect_to
 * @param [type] $request
 * @param [type] $user
 * @return void
 */
function dff_redirect_to_my_courses($redirect_to, $request, $user)
{
    if (isset($user->roles) && is_array($user->roles)) {
        if (in_array('subscriber', $user->roles)) {
            return get_permalink(10447);
        }
    }
    return $redirect_to;
}
add_filter('login_redirect', 'dff_redirect_to_my_courses', 10, 3);

/**
 * Returns the navigation classes for the courses menu item.
 *
 * @param [type] $classes
 * @param [type] $item
 * @return void
 */
function dff_courses_nav_class($classes, $item)
{
    if (is_single() && 'courses' == get_post_type() && $item->title == "Courses") {
        $classes[] = "current-menu-item";
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'dff_courses_nav_class', 10, 2);
