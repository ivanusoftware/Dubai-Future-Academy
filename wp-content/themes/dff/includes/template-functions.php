<?php

/**********************************************************************************
 * Include js and css files
 **********************************************************************************/
// Include all enqueue scripts and lessons.
include(get_template_directory() . '/includes/enqueue-script-style.php');
include(get_template_directory() . '/includes/functions/user-functions.inc.php');
include(get_template_directory() . '/includes/functions/ajax-courses-tax.inc.php');
include(get_template_directory() . '/includes/functions/ajax-lessons-tab.inc.php');
include(get_template_directory() . '/includes/functions/ajax-quiz.inc.php');
include(get_template_directory() . '/includes/functions/ajax-leave-course.php');
include(get_template_directory() . '/includes/functions/pdf-function.inc.php');
include(get_template_directory() . '/includes/functions/future_id_users.php');



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
        case 'quizzes_answers':
            return false;
            break;
        case 'exams':
            return false;
            break;
        case 'exams_answers':
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
function get_display_name($user_id)
{

    $first_name = get_user_meta($user_id, 'first_name', true);
    $last_name = get_user_meta($user_id, 'last_name', true);
    if ($first_name || $last_name) {
        $user_name = $first_name . ' ' . $last_name;
    } else {
        $user = get_userdata($user_id);
        $user_name = $user->data->display_name;
    }
    return $user_name;
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

function pdf_return_courses_taxonomy($user_courses_id)
{
    $taxonomy_names = wp_get_object_terms($user_courses_id, 'courses-categories',  array("fields" => "names"));
    if (!empty($taxonomy_names)) :
        foreach ($taxonomy_names as $tax_name) :
            $taxonomy_name = $tax_name;
        endforeach;
    endif;
    return $taxonomy_name;
}


// Adds the rewrite rules for the user and course.
add_action('init', function () {
    // add_rewrite_rule( 'user-profile/([a-z]+)[/]?$', 'index.php?my_course=$matches[1]', 'top' );
    // add_rewrite_rule('user-profile/([0-9]+)/?$', 'index.php&course_id=$matches[1]', 'top');

    add_rewrite_rule('my-courses/([a-z0-9-]+)[/]?$', 'index.php?course_slug=$matches[1]', 'top');
    add_rewrite_rule('ar/my-courses/([a-z0-9-]+)[/]?$', 'index.php?course_slug=$matches[1]', 'top');


    // add_rewrite_rule('my-courses/([0-9]+)[/]?$', 'index.php?course_id=$matches[1]', 'top');
    // add_rewrite_rule('ar/my-courses/([0-9]+)[/]?$', 'index.php?course_id=$matches[1]', 'top');
});

// Adds the filter to the course_id.
add_filter('query_vars', function ($query_vars) {
    $query_vars[] = 'course_slug';
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

    if (get_query_var('course_slug') == false || get_query_var('course_slug') == '') {
        return $template;
    }
    return get_template_directory() . '/includes/courses/my-courses/my-courses.inc.php';
});

add_filter('flush_rewrite_rules_hard', '__return_false');

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

function dff_general_progress_mod_result($future_user_id, $course_id)
{
    $mod_result_arr = array();
    $exam_key    = 'course_' . $course_id . '_exam_result';
    $exam_result = get_exam_result($future_user_id, $exam_key);
    if ($exam_result == null) {
        $exam_result = 0;
    }
    if (have_rows('course_module_repeater')) :
        while (have_rows('course_module_repeater')) : the_row();
            $module_or_exam = get_sub_field('module_or_exam');
            $module_i       = get_row_index();
            $result_module_key = dff_module_course_user_key($course_id, $module_i);
            $result_module     = get_exam_result($future_user_id, $result_module_key);
            if ($result_module == null) {
                $result_module = 0;
            }
            if ($module_or_exam == 'module') {
                $mod_result_arr[] = $result_module;
            } elseif ($module_or_exam == 'exam') {
                $mod_result_arr[] = $exam_result;
            }
        endwhile;
    endif;
    return  implode(",", $mod_result_arr);
}

function dff_progress_bar($future_user_id, $course_id)
{
    $cource_result = dff_general_progress_mod_result($future_user_id, $course_id);
    $array_result = explode(",", $cource_result);
    $progress_bar = 0;
    $count_arr = count($array_result);
    foreach ($array_result as $item) {
        if ($item >= 80) {
            $progress_bar++;
        }
    }
    $progress_bar_result = $progress_bar / $count_arr * 100;
    return  ceil($progress_bar_result);
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
        if (have_rows('course_time_group', $course_id)) :
            while (have_rows('course_time_group', $course_id)) : the_row();
                $currentDateTime       = date('d-m-Y');
                $current_timestamp     = strtotime($currentDateTime);
                $start_date_timestamp  = strtotime(get_sub_field('course_start'));
                $finish_date_timestamp = strtotime(get_sub_field('course_finish'));
                if ($current_timestamp >= $start_date_timestamp && $current_timestamp <= $finish_date_timestamp) {
                    $disabled = '';
                } elseif ($current_timestamp > $start_date_timestamp && $current_timestamp > $finish_date_timestamp) {
                    $disabled = 'disabled';
                } else {
                    $disabled = 'disabled';
                }
            endwhile;
        endif;
    }
    return $disabled;
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
    $result_module     = get_user_meta(get_current_user_id(), $result_module_key, true);
    if ($result_module >= 80 || $module_i == 1) {
        $module = 'open-module';
    } else {
        $module = 'close-module';
    }
    return $module;
}


/**
 *  Count the number of course modules repeaters.
 *
 * @param [type] $coutce_id
 * @return void
 */
function dff_count_course_modules($coutce_id)
{
    if (have_rows('course_module_repeater', $coutce_id)) :
        while (have_rows('course_module_repeater', $coutce_id)) : the_row();
            $count_course_modules = count(get_field('course_module_repeater'));
        endwhile;
    else :
    endif;
    return $count_course_modules;
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
    $lang               = get_bloginfo("language");
    $currentDateTime    = date('d-m-Y');
    $current_timestamp  = strtotime($currentDateTime);
    $date_timestamp     = strtotime($date_open_module);
    if ($current_timestamp >= $date_timestamp) {
        $show_date = '';
    } else {
        $show_date = '<span>' . date($lang == 'ar' ? "Y.m.d" : "d.m.Y", strtotime($date_open_module)) . '</span>';
    }
    return $show_date;
}


// function dff_get_translation_ids($course_id)
// {
//     $translations = \Inpsyde\MultilingualPress\translationIds($course_id, 'post', 1);
//     if($translations) {
//         // print_r($translations);

//         foreach($translations as $siteId => $postId) {
//         //    echo 'Site ID: ' . $siteId . ' Post ID: ' . $postId . '<br>';
//         //    echo get_the_title($postId);
//            $course_id_translation = $postId;
//         }
//      }
//     return $course_id_translation;
// }
// echo dff_get_translation_ids(10447);
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


/**
 * Define the action and give functionality to the action.
 */
// function tutsplus_action()
// {
//     do_action('tutsplus_action');
// }

// 
//  Register the action with WordPress.
//  
// add_action('tutsplus_action', 'tutsplus_action_example');
// function tutsplus_action_example()
// {
//     // dff_user_courses_certificate($user_id, $dff_user_courses);
//     echo 'This is a custom action hook.';
// }

// add_action( 'init', 'tutsplus_register_post_type' );
// function tutsplus_register_post_type($user_id, $dff_user_courses) {

//     dff_user_courses_certificate($user_id, $dff_user_courses);

// }
// add_filter('the_content', function ($content) {
//     $args =
//         \Inpsyde\MultilingualPress\Framework\Api\TranslationSearchArgs::forContext(
//             new \Inpsyde\MultilingualPress\Framework\WordpressContext()
//         )->forSiteId(get_current_blog_id())->includeBase();

//     $translations = \Inpsyde\MultilingualPress\resolve(
//         \Inpsyde\MultilingualPress\Framework\Api\Translations::class
//     )->searchTranslations($args);

//     foreach ($translations as $translation) {
//         $postId = $translation->remoteContentId();
//         $title = $translation->remoteTitle();
//         $url = $translation->remoteUrl();

//         $language = $translation->language();
//         $bcp47tag = $language->bcp47tag();
//         $englishName = $language->englishName();
//         $isoCode = $language->isoCode();
//         $locale = $language->locale();
//         $name = $language->name();
//     }

//     return $content;
// });

// $args =
//         \Inpsyde\MultilingualPress\Framework\Api\TranslationSearchArgs::forContext(
//             new \Inpsyde\MultilingualPress\Framework\WordpressContext()
//         )->forSiteId(get_current_blog_id())->includeBase();

//     $translations = \Inpsyde\MultilingualPress\resolve(
//         \Inpsyde\MultilingualPress\Framework\Api\Translations::class
//     )->searchTranslations($args);
//     // print_r($translations);
//     foreach ($translations as $translation) {
//        echo  $postId = $translation->remoteContentId();
//        echo $title = $translation->remoteTitle();
//         $url = $translation->remoteUrl();

//         // $language = $translation->language();
//         // $bcp47tag = $language->bcp47tag();
//         // $englishName = $language->englishName();
//         // $isoCode = $language->isoCode();
//         // $locale = $language->locale();
//         // $name = $language->name();
//     }


// $translations = \Inpsyde\MultilingualPress\translationIds(10410, 'post', 1);
// print_r($translations);
// if($translations) {

//     foreach($translations as $siteId => $postId) {
//        echo 'Site ID: ' . $siteId . ' Post ID: ' . $postId . '<br>';
//     }
//  }

//  echo $lang = get_bloginfo("language");


function dff_get_id_parrent_lang($current_id_post)
{
    $lang = get_bloginfo('language');
    if ($lang == 'ar') {
        $site_number = 3;
    } else {
        $site_number = 1;
    }
    $translations = \Inpsyde\MultilingualPress\translationIds($current_id_post, 'post', $site_number);

    if ($translations) {
        if ($lang == 'ar') {
            $post_id_lang = $translations[1];
        } else {
            $post_id_lang = $translations[3];
        }
        return $post_id_lang;
    }
}


function get_page_by_slug($page_slug, $output = OBJECT, $post_type = 'page')
{
    global $wpdb;

    if (is_array($post_type)) {
        $post_type = esc_sql($post_type);
        $post_type_in_string = "'" . implode("','", $post_type) . "'";
        $sql = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type IN ($post_type_in_string)", $page_slug);
    } else {
        $sql = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type = %s", $page_slug, $post_type);
    }

    $page = $wpdb->get_var($sql);

    if ($page)
        return get_post($page, $output);

    return null;
}

function dff_get_url_pdf_certificate($course_id, $user_certificates)
{

    foreach ($user_certificates as $user_certificate) {
        if ($user_certificate['course_id'] === $course_id) {
            $pdf_certificate_url = $user_certificate['pdf_certificate_url'];
        }
    }
    return $pdf_certificate_url;
}

/**
 * Updates the rewrite => slug argument when registering a post type.
 *
 * Will use the option from the "Post Type Slugs" tab the 
 * Network Settings of a website, in case it’s not empty.
 *
 * @see register_post_type()
 *
 * @param array $args An array of arguments that will be passed to register_post_type().
 * @param string $postType The name/slug of the post type.
 *
 * @return array Updated arguments.
 */
add_filter('register_post_type_args', function ($args, $postType) {

    if ((isset($args['_builtin']) && $args['_builtin'])
        || (isset($args['public']) && !$args['public'])
    ) {
        return $args;
    }

    $slugSettings = get_network_option(
        0,
        \Inpsyde\MultilingualPress\Core\Admin\PostTypeSlugsSettingsRepository::OPTION,
        []
    );

    $siteId = get_current_blog_id();

    if (empty($slugSettings[$siteId][$postType])) {
        return $args;
    }

    $args = array_merge($args, [
        'rewrite' => [
            'slug' => $slugSettings[$siteId][$postType]
        ],
    ]);

    return $args;
}, 10, 2);


// Parse a dff url and return the host.
function dff_url_lang($url)
{
    $result = parse_url($url);
    return $result['scheme'] . "://" . $result['host'];
}

function dff_courses_switcher_lang($course_slug)
{
    $url = site_url();
    $lang = get_bloginfo('language');
    if ($lang == 'ar') {
        $lang_name = '<a class="button button button--ghost is-icon is-language" href="' . dff_url_lang($url) . '/my-courses/' . $course_slug . '">EN</a>';
    } else {
        $lang_name =  '<a class="button button button--ghost is-icon is-language" href="' . dff_url_lang($url) . '/ar/my-courses/' . $course_slug . '">ع</a>';
    }
    return  $lang_name;
}
function dff_create_array($response)
{
    $count_modules = array();
    for ($i = 1; $i <= $response; $i++) {
        $count_modules[] = $i;
    }
    return  $count_modules;
}


// function dff_get_meta_value_for_ar_lang($meta_key, $post_id)
// {
//     global $wpdb;
//     $row = $wpdb->get_row("SELECT meta_value FROM wp_postmeta WHERE meta_key = '$meta_key' AND post_id = $post_id",  ARRAY_A);
//     return $row['meta_value'];
// }
// function dff_get_meta_key_for_ar_lang($meta_value, $post_id)
// {
//     global $wpdb;
//     $row = $wpdb->get_row("SELECT meta_key FROM wp_postmeta WHERE meta_value = '$meta_value' AND post_id = $post_id",  ARRAY_A);
//     return $row['meta_key'];
// }


// $translations = \Inpsyde\MultilingualPress\translationIds(10410, 'post', 1);
// print_r($translations);
// if($translations) {

//     foreach($translations as $siteId => $postId) {
//        echo 'Site ID: ' . $siteId . ' Post ID: ' . $postId . '<br>';
//     }
//  }

// $api = \Inpsyde\MultilingualPress\resolve(
//     \Inpsyde\MultilingualPress\Framework\Api\ContentRelations::class
// );

// $contentIds = [
//     1 => 10410,    
//     3 => 8421,
// ];

// echo $api->createRelationship($contentIds, 'post');

// if (!function_exists('add_courses_future_user')) {
//     function add_courses_future_user($future_user_id, $course_en_id, $course_ar_id)
//     {
//         // echo 'test';
//         // global $wpdb;
//         // $table_name = $wpdb->prefix . 'dff_future_users';
//         // $data = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE future_user_id = '$future_user_id'"));
//         // // echo $data->future_user_id;
//         // // if (!$data ) { 
//         //     //if post id not already added
//         //     $wpdb->update(
//         //         $table_name,
//         //         array(
//         //             'course_en_id' => serialize($course_en_id),         
//         //             'course_ar_id' => serialize($course_ar_id),         
//         //             'user_date' => current_time('Y-m-d H:i:s'),        
//         //             'user_date_gmt' => current_time('Y-m-d H:i:s')        
//         //         )
//         //     );     
//         //  }
//         //  $wpdb->query( "INSERT INTO $table_name (rated_post_id)
//         //             SELECT future_user_id
//         //             FROM table_name as user
//         //             LEFT JOIN $table_name as dup_check
//         //             ON dup_check.future_user_id = posts.ID
//         //             WHERE dup_check.rated_post_id IS NULL" ); 
//     }
// }
