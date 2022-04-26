<section class="modules-course">
    <?php
    // WP_Query arguments
    echo $course_id = $_POST['course_id'];
    $args = array(
        'post_type'   => array('courses'),
        'post_status' => array('publish'),
        'page_id'     => $course_id,
    );
    // The Query
    $modules_course = new WP_Query($args);
    // The Loop
    if ($modules_course->have_posts()) {
        while ($modules_course->have_posts()) {
            $modules_course->the_post();
            $learning_style = get_field_object('learning_style');
            $courses_format = get_field_object('courses_format');
            $courses_format_value = $courses_format['value'];
            $courses_format_label = $courses_format['choices'][$courses_format_value];
            $course_complexities  = get_field_object('course_complexities');
            if ($img = get_image_by_id($modules_course->ID)) $src = $img[0];
            else $src = '';            
    ?>
            <section class="cource-content">
                <div class="container">
                    <div class="columns">
                        <aside class="course-sidebar" course-id="<?php echo $course_id; ?>">
                            <div class="single-modules my-single-modules">
                                <!-- Accordion -->
                                <div class="accordion">
                                    <?php
                                    if (have_rows('course_module_repeater')) :
                                        $row_count = count(get_field('course_module_repeater'));
                                        while (have_rows('course_module_repeater')) : the_row();
                                            $module_or_exam = get_sub_field('module_or_exam');
                                            $module_name    = get_sub_field('module_name');
                                            $module_i       = get_row_index();

                                            $choose_date_open_module = get_sub_field('choose_date_open_module');
                                            $date_open_module = date("d-m-Y", strtotime(get_sub_field('date_open_module')));

                                            if ($learning_style['value'] == 'timed_progression') {
                                                $type_course   = dff_open_module_by_date($date_open_module);
                                                $dff_show_date = dff_show_date($date_open_module);
                                            } elseif ($learning_style['value'] == 'progressive') {
                                                $type_course = dff_open_module_by_rusult_test($course_id, $module_i);
                                            } else {
                                                $type_course = 'open-module';
                                            }
                                    ?>
                                            <div class="accordion-item <?php echo $type_course; ?> <?php echo ($module_or_exam == 'exam') ? 'accordion-item-exam ' : ''; ?>module_<?php echo $module_i; ?>">
                                                <?php
                                                $count_lessons = count(get_sub_field('course_lesson_repeater'));
                                                if ($module_or_exam == 'module') {
                                                ?>
                                                    <div class="accordion-head" count-lesson-row="<?php echo $count_lessons; ?>">
                                                        <h6><?php _e('Module', 'dff'); ?> <?php echo $module_i . $dff_show_date; ?></h6>
                                                    </div>
                                                    <?php if ($type_course == 'open-module') { ?>
                                                        <div class="accordion-content">
                                                            <?php
                                                            if (have_rows('course_lesson_repeater')) :
                                                            ?>
                                                                <ul>
                                                                    <?php
                                                                    while (have_rows('course_lesson_repeater')) : the_row();
                                                                        // $lesson_name = get_sub_field('lesson_name');
                                                                        $lesson_or_test = get_sub_field('lesson_or_test');
                                                                        $lesson_test_id = get_sub_field('lesson_test_id');
                                                                        $lesson_i = get_row_index();
                                                                        if ($lesson_or_test == 'lesson') {
                                                                    ?>
                                                                            <li class="tab-item <?php echo $lesson_i == 1 && $module_i == 1 ? 'active' : ''; ?>" module-index="<?php echo $module_i; ?>" lesson-index="<?php echo $lesson_i; ?>">
                                                                                <?php _e('Lesson', 'dff'); ?>
                                                                                <?php echo $lesson_i; ?>
                                                                            </li>
                                                                        <?php
                                                                        } elseif ($lesson_or_test == 'lesson_test') {
                                                                        ?>
                                                                            <li class="tab-item <?php echo $lesson_i == 1 && $module_i == 1 ? 'active' : ''; ?> module-lesson-test" module-index="<?php echo $module_i; ?>" lesson-index="<?php echo $lesson_i; ?>" lesson-test-id="<?php echo $lesson_test_id; ?>">
                                                                                <?php _e('Test', 'dff'); ?>
                                                                            </li>
                                                                    <?php
                                                                        }
                                                                    endwhile;
                                                                    ?>
                                                                </ul>
                                                            <?php
                                                            else :
                                                            endif;
                                                            ?>
                                                        </div>
                                                    <?php
                                                    }
                                                } elseif ($module_or_exam == 'exam') {
                                                    $exam_post_id = get_sub_field('exam_block');
                                                    ?>
                                                    <div class="accordion-head <?php echo $type_course == 'open-module' ? 'exam-tab-item' : ''; ?>" exam-post-id="<?php echo $exam_post_id; ?>" module-type="<?php echo $module_or_exam; ?>">
                                                        <h6><?php _e('Exam', 'dff'); ?> <?php echo $dff_show_date; ?></h6>
                                                    </div>
                                                <?php
                                                } else {
                                                ?>
                                                    <div class="accordion-head">
                                                        <h6><?php _e('Please add a module', 'dff'); ?> <?php echo $dff_show_date; ?></h6>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                    <?php
                                        endwhile;
                                    else :
                                    endif;
                                    ?>
                                </div>
                            </div>
                        </aside>
                        <main class="main-content">
                            <div class="content">
                                <div class="lesson-container">
                                    <?php get_template_part('includes/courses/my-courses/parts-course/lesson', 'content'); ?>
                                </div>
                            </div>
                        </main>

                    </div>
                </div>
            </section>


    <?php
        } // end while
    } // end if
    wp_reset_postdata();
    ?>
</section>