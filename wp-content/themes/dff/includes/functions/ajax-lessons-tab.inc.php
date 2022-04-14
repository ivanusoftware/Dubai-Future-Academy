<?php

/**
 * Undocumented function
 *
 * @return void
 */
function upload_lesson_ajax_callback()
{
    $course_id = $_POST['course_id'];
    $module_index = $_POST['module_index'];
    $lesson_index = $_POST['lesson_index'];
    //    get_template_part('includes/courses/my-courses/parts-course/lesson', 'content'); 
?>
    <div id="loader"><svg version="1.1" id="L4" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
            <circle fill="#000" stroke="none" cx="6" cy="50" r="6">
                <animate attributeName="opacity" dur="1s" values="0;1;0" repeatCount="indefinite" begin="0.1" />
            </circle>
            <circle fill="#000" stroke="none" cx="26" cy="50" r="6">
                <animate attributeName="opacity" dur="1s" values="0;1;0" repeatCount="indefinite" begin="0.2" />
            </circle>
            <circle fill="#000" stroke="none" cx="46" cy="50" r="6">
                <animate attributeName="opacity" dur="1s" values="0;1;0" repeatCount="indefinite" begin="0.3" />
            </circle>
        </svg></div>

    <div class="lesson-header">
        <span class="back"><?php _e('Back', 'dff'); ?></span>
        <span class="next"><?php _e('Next', 'dff'); ?></span>
    </div>


    <?php if (have_rows('course_module_repeater', $course_id)) :  while (have_rows('course_module_repeater', $course_id)) : the_row();
            $model_i = get_row_index();
            if (have_rows('course_lesson_repeater')) : while (have_rows('course_lesson_repeater')) : the_row();

                    $lesson_i = get_row_index();
                    if ($model_i == $module_index && $lesson_i == $lesson_index) {
                        get_template_part('includes/courses/my-courses/parts-course/lesson-inner', 'content');
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


function tabs_lesson_ajax_callback()
{
    $tab_id    = $_POST['main_tab_id'];
    $course_id = $_POST['course_id'];

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

function left_module_tab_ajax_callback()
{
    // $modules_length    = $_POST['modules_length'];
    // $lesson_count     = $_POST['lesson_count'];
    $time_progressive = $_POST['time_progressive'];
    $module_index     = $_POST['module_index'];
    // $item_index       = $_POST['item_index'];
    // if ($lesson_count) {
    // include get_template_directory() . '/includes/courses/my-courses/parts-course/tabs-my-course.php';
    if (have_rows('course_module_repeater', 10410)) :
        while (have_rows('course_module_repeater', 10410)) : the_row();
            $course_module_name = get_sub_field('course_module_name');
            echo $model_i = get_row_index();
            // $choose_date_open_module = get_sub_field('choose_date_open_module');
            // $date_open_module = date("d-m-Y", strtotime(get_sub_field('date_open_module')));

    ?>

            <div class="lesson-blocks">


                <?php
                $lesson_i = get_row_index();
                if ($model_i == $module_index) {

                    // if ($model_i == 1) {
                    if (have_rows('course_lesson_repeater')) :
                ?>
                        <ul>
                            <?php
                            // $i = 0;
                            while (have_rows('course_lesson_repeater')) : the_row();
                                $lesson_name = get_sub_field('lesson_name');
                                $lesson_i = get_row_index();
                            ?>
                                <li class="tab-item <?php echo $lesson_i == 1 && $model_i == 1 ? 'active' : ''; ?>" module-index="<?php echo $model_i; ?>" lesson-index="<?php echo $lesson_i; ?>">
                                    <?php _e('Lesson', 'dff'); ?> <?php echo $lesson_i; ?>
                                </li>
                            <?php

                            endwhile;
                            ?>
                        </ul>
                <?php
                    else :
                    // Do something...
                    endif;
                }

                ?>
            </div>

    <?php

        endwhile;
    else :
    endif;
    ?>


<?php
    // }

    wp_die();
}
add_action('wp_ajax_left_module_tab_ajax', 'left_module_tab_ajax_callback');
add_action('wp_ajax_nopriv_left_module_tab_ajax', 'left_module_tab_ajax_callback');
