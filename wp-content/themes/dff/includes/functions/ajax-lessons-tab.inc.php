<?php

/**
 * Undocumented function
 *
 * @return void
 */
function upload_lesson_ajax_callback()
{
    $course_id    = $_POST['course_id'];
    $module_index = $_POST['module_index'];
    $lesson_index = $_POST['lesson_index'];
    include(get_template_directory() . '/includes/courses/my-courses/loader.php');

    if (have_rows('course_module_repeater', $course_id)) :  while (have_rows('course_module_repeater', $course_id)) : the_row();
            $model_i = get_row_index();
            if (have_rows('course_lesson_repeater')) : while (have_rows('course_lesson_repeater')) : the_row();
                    $lesson_or_test = get_sub_field('lesson_or_test');
                    $lesson_i = get_row_index();
                    if ($model_i == $module_index && $lesson_i == $lesson_index && $lesson_or_test == 'lesson') {
                        get_template_part('includes/courses/my-courses/parts-course/lesson-inner', 'content');
                    } elseif ($model_i == $module_index && $lesson_i == $lesson_index && $lesson_or_test == 'lesson_test') {
                        get_template_part('includes/courses/my-courses/parts-course/quiz', 'content');
                    }
                endwhile;
            // wp_reset_postdata();
            endif;
        endwhile;
    endif; ?>
<?php
    // wp_reset_postdata();
    wp_die();
}
add_action('wp_ajax_upload_lesson_ajax', 'upload_lesson_ajax_callback');
add_action('wp_ajax_nopriv_upload_lesson_ajax', 'upload_lesson_ajax_callback');


function upload_exam_ajax_callback()
{
    $course_id    = $_POST['course_id'];
    // $module_index = $_POST['module_index'];
    // $lesson_index = $_POST['lesson_index'];
    include(get_template_directory() . '/includes/courses/my-courses/loader.php');    
    $module_type  = $_POST['module_type'];
?>
   
    <?php if (have_rows('course_module_repeater',  $course_id )) :
        // $row_count = count(get_field('course_module_repeater'));
        while (have_rows('course_module_repeater',  $course_id )) : the_row();
            $module_or_exam = get_sub_field('module_or_exam');
            // $model_i = get_row_index();
            if($module_or_exam == $module_type){
                get_template_part('includes/courses/my-courses/parts-course/exam', 'content');
            }

        endwhile;
    endif; ?>
<?php

    
    // wp_reset_postdata();
    wp_die();
}
add_action('wp_ajax_upload_exam_ajax', 'upload_exam_ajax_callback');
add_action('wp_ajax_nopriv_upload_exam_ajax', 'upload_exam_ajax_callback');


// Ajax callback for tabs_lesson_ajax
/**
 * Undocumented function
 *
 * @return void
 */
function tabs_lesson_ajax_callback()
{
    $tab_id    = $_POST['main_tab_id'];
    if ($tab_id == 'tab-1') {
        include(get_template_directory() . '/includes/courses/my-courses/parts-course/about-course.php');
    } else if ($tab_id == 'tab-2') {
        include(get_template_directory() . '/includes/courses/my-courses/parts-course/modules-course.php');
    } else if ($tab_id == 'tab-3') {
        include(get_template_directory() . '/includes/courses/my-courses/parts-course/my-progress-course.php');
    }

    // wp_reset_postdata();
    wp_die();
}
add_action('wp_ajax_tabs_lesson_ajax', 'tabs_lesson_ajax_callback');
add_action('wp_ajax_nopriv_tabs_lesson_ajax', 'tabs_lesson_ajax_callback');


/**
 * Ajax callback for add course to the user account
 * @return void
 */
function add_lesson_to_user_ajax_callback()
{
    $course_id       = $_POST['course_id'];
    $current_user_id =  get_current_user_id();
    dff_user_courses($current_user_id, $course_id);
    dff_user_course_module_result($current_user_id,  $course_id);
    wp_send_json_success();
    wp_die();
}
add_action('wp_ajax_add_lesson_to_user_ajax', 'add_lesson_to_user_ajax_callback');
add_action('wp_ajax_nopriv_add_lesson_to_user_ajax', 'add_lesson_to_user_ajax_callback');
