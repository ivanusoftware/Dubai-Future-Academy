<?php

/**
 * Undocumented function
 *
 * @return void
 */
function upload_lesson_ajax_callback()
{
    $course_id        = $_POST['course_id'];
    $module_index     = $_POST['module_index'];
    $lesson_index     = $_POST['lesson_index'];
    $count_lesson_row = $_POST['count_lesson_row'];
    $lesson_test_id   = $_POST['lesson_test_id'];
    include(get_template_directory() . '/includes/courses/my-courses/loader.php');
    if (have_rows('course_module_repeater', $course_id)) :  while (have_rows('course_module_repeater', $course_id)) : the_row();
            $model_i = get_row_index();
            if (have_rows('course_lesson_repeater')) :
                while (have_rows('course_lesson_repeater')) : the_row();
                    $lesson_or_test = get_sub_field('lesson_or_test');
                    $lesson_i = get_row_index();
                    if ($model_i == $module_index && $lesson_i == $lesson_index && $lesson_or_test == 'lesson') {
?>
                        <div class="lesson-header">
                            <button class="back" module-index="<?php echo $module_index; ?>" lesson-index="<?php echo $lesson_index - 1; ?>" <?php echo $lesson_i == 1 ? 'disabled' : ''; ?>><?php _e('Back', 'dff'); ?></button>
                            <?php
                            if ($count_lesson_row - 1 >= $lesson_index) {
                            ?>
                                <button class="next module-lesson-test" module-index="<?php echo $module_index; ?>" lesson-index="<?php echo $lesson_index + 1; ?>" lesson-test-id="<?php echo $lesson_test_id; ?>">
                                    <?php _e('Next', 'dff'); ?>
                                </button>
                            <?php
                            } else {
                            ?>
                                <button class="next" module-index="<?php echo $module_index; ?>" lesson-index="<?php echo $lesson_index + 1; ?>">
                                    <?php _e('Next', 'dff'); ?>
                                </button>
                            <?php
                            }
                            ?>
                        </div>
    <?php
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


// Adds the upload_exam_ajax callback to the wp_die action.
/**
 * Undocumented function
 *
 * @return void
 */
function upload_exam_ajax_callback()
{
    $course_id    = $_POST['course_id'];
    // $module_index = $_POST['module_index'];
    // $lesson_index = $_POST['lesson_index'];
    include(get_template_directory() . '/includes/courses/my-courses/loader.php');
    $module_type  = $_POST['module_type'];
?>
    <?php if (have_rows('course_module_repeater',  $course_id)) :
        // $row_count = count(get_field('course_module_repeater'));
        while (have_rows('course_module_repeater',  $course_id)) : the_row();
            $module_or_exam = get_sub_field('module_or_exam');
            // $model_i = get_row_index();
            if ($module_or_exam == $module_type) {
                get_template_part('includes/courses/my-courses/parts-course/exam', 'content');
            }

        endwhile;
    endif; ?>
<?php
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
    // $course_id = $_POST['course_id'];
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
    $course_id_lang  = $_POST['course_id_lang'];
    $current_user_id =  get_current_user_id();

    // $future_user_id = '628b65ec50c67e00289e9b89';
    // $future_user_id = '627cf5d504b88900290d26da';
    if($_COOKIE['future_ID']){
        $future_user_id = $_COOKIE['future_ID'];
     }
    $lang = get_bloginfo('language');
    if ($lang == 'ar') {
        add_course_id_future_user_ar($future_user_id, $course_id);
        add_course_id_future_user_en($future_user_id, $course_id_lang);        
        dff_user_course_module_result($future_user_id, $course_id);
        dff_user_course_module_result_lang($future_user_id, $course_id_lang);
    } else {
        add_course_id_future_user_en($future_user_id, $course_id);
        add_course_id_future_user_ar($future_user_id, $course_id_lang);
        dff_user_course_module_result($future_user_id, $course_id);
        dff_user_course_module_result_lang($future_user_id, $course_id_lang);        
    }
    wp_send_json_success();
    wp_die();
}
add_action('wp_ajax_add_lesson_to_user_ajax', 'add_lesson_to_user_ajax_callback');
add_action('wp_ajax_nopriv_add_lesson_to_user_ajax', 'add_lesson_to_user_ajax_callback');


