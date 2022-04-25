<section class="my-progress-content">
    <div class="container">
        <?php
        // WP_Query arguments
        $course_id = $_POST['course_id'];
        $args = array(
            'post_type'      => array('courses'),
            'post_status'    => array('publish'),
            'page_id'        => $_POST['course_id'],
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
                $index = '';
                $row_сount = count(get_field('course_module_repeater'));
        ?>

                <div class="columns">
                    <aside class="course-sidebar" course-id="<?php echo $course_id; ?>">

                        <div class="my-progres-modules">
                            <ul>
                                <li class="active">
                                    <a href="#tab1" tab-id="tab-1">
                                        <?php _e('General progress', 'dff'); ?>
                                    </a>
                                </li>
                                <?php

                                if (have_rows('course_module_repeater')) :
                                    while (have_rows('course_module_repeater')) : the_row();
                                        $module_or_exam = get_sub_field('module_or_exam');
                                        $module_i = get_row_index();
                                ?>
                                        <li>
                                            <a href="#tab<?php echo $module_i + 1; ?>" tab-id="tab-<?php echo $module_i + 1; ?>">
                                                <?php
                                                if ($module_or_exam == 'module') {
                                                    _e('Module', 'dff');
                                                    echo ' ' . $module_i;
                                                } elseif ($module_or_exam == 'exam') {
                                                    _e('Exam', 'dff');
                                                }
                                                ?>
                                            </a>
                                        </li>
                                <?php
                                    endwhile;
                                else :
                                endif;
                                ?>

                            </ul>
                        </div>
                    </aside>
                    <main class="main-content">
                        <div class="content">
                            <div class="progress-container">
                                <?php
                                // get_template_part('includes/courses/my-courses/parts-course/lesson', 'content');
                                ?>

                                <div class="tabs-content">
                                    <div class="progress-wrapper general-progress" id="tab1">
                                        <div class="module-header">
                                            <h2><?php _e('General progress', 'dff'); ?></h2>
                                        </div>
                                        <div class="progress-content">
                                            <h5>Functionality in development</h5>
                                            <!-- <p>Your result is higher than the passing score,
                                                you can go over the next module.</p>

                                            <p class="module-result">Result: <span>90%</span></p> -->

                                        </div>
                                    </div>
                                    <?php
                                    // $row_count         = count(get_field('course_module_repeater'));
                                    // $second_last = $row_count - 1;
                                    $exam_key   = 'course_' . $course_id . '_exam_result';
                                    $exam_result = get_user_meta(get_current_user_id(), $exam_key, true);
                                    if (have_rows('course_module_repeater')) :
                                        while (have_rows('course_module_repeater')) : the_row();
                                            $module_i = get_row_index();

                                            $module_or_exam    = get_sub_field('module_or_exam');
                                            $result_module_key = dff_module_course_user_key($course_id, $module_i);
                                            $result_module     = get_user_meta(get_current_user_id(), $result_module_key, true);
                                            $module_name       = get_sub_field('module_name');
                                    ?>
                                            <div class="progress-wrapper" id="tab<?php echo $module_i + 1; ?>">

                                                <div class="module-header">
                                                    <h2><?php echo $module_or_exam == 'module' ? $module_name : _e('Final exam', 'dff'); ?></h2>
                                                </div>

                                                <?php
                                                if ($module_or_exam == 'module') {
                                                ?>
                                                    <div class="progress-content">
                                                        <?php
                                                        if ($result_module >= 80) { ?>
                                                            <?php if (have_rows('сongratulation_group', 'option')) : ?>
                                                                <?php while (have_rows('сongratulation_group', 'option')) : the_row();  ?>
                                                                    <h5><?php the_sub_field('сongratulation_group_title'); ?></h5>
                                                                    <p><?php the_sub_field('сongratulation_content'); ?></p>
                                                                <?php endwhile; ?>
                                                            <?php endif; ?>
                                                            <p class="module-result"><?php _e('Result:', 'dff'); ?><span> <?php echo $result_module; ?>%</span></p>
                                                        <?php
                                                        } elseif ($result_module < 80 && $result_module != 1) {
                                                        ?>
                                                            <?php if (have_rows('unfortunately_group', 'option')) : ?>
                                                                <?php while (have_rows('unfortunately_group', 'option')) : the_row();  ?>
                                                                    <h5><?php the_sub_field('unfortunately_title'); ?></h5>
                                                                    <p><?php the_sub_field('unfortunately_content'); ?></p>
                                                                <?php endwhile; ?>
                                                            <?php endif; ?>
                                                            <p class="module-result"><?php _e('Result:', 'dff'); ?> <span><?php echo $result_module ? $result_module . '%' : ''; ?></span></p>
                                                            <div class="exam-footer">
                                                                <a href="#" class="btn-course-primary"><?php _e('Try again', 'dff'); ?></a>
                                                            </div>
                                                        <?php
                                                        } elseif ($result_module == 1 && $result_module < 80) {
                                                        ?>
                                                            <?php if (have_rows('before_test_group', 'option')) : ?>
                                                                <?php while (have_rows('before_test_group', 'option')) : the_row();  ?>
                                                                    <p><?php the_sub_field('before_test_content'); ?></p>
                                                                <?php endwhile; ?>
                                                            <?php endif; ?>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                <?php
                                                } else {
                                                ?>
                                                    <div class="progress-content">
                                                        <?php
                                                        if ($exam_result >= 80) {
                                                        ?>
                                                            <?php if (have_rows('сongratulation_group_exam', 'option')) : ?>
                                                                <?php while (have_rows('сongratulation_group_exam', 'option')) : the_row();  ?>
                                                                    <h5><?php the_sub_field('сongratulation_group_title_exam'); ?></h5>
                                                                    <p><?php the_sub_field('сongratulation_content_exam'); ?></p>
                                                                    <p class="module-result"><?php _e('Result:', 'dff'); ?> <span><?php echo $exam_result; ?>%</span></p>
                                                                    <div class="exam-footer">
                                                                        <span><?php the_sub_field('my_certificates_text'); ?></span>
                                                                        <a href="<?php echo site_url('my-courses'); ?>" class="btn-course-primary"><?php _e('My courses', 'dff'); ?></a>
                                                                    </div>
                                                                <?php endwhile; ?>
                                                            <?php endif; ?>
                                                        <?php
                                                        } elseif ($exam_result < 80 && $exam_result != 1) {
                                                        ?>
                                                            <?php if (have_rows('unfortunately_group_exam', 'option')) : ?>
                                                                <?php while (have_rows('unfortunately_group_exam', 'option')) : the_row();  ?>
                                                                    <h5><?php the_sub_field('unfortunately_title_exam'); ?></h5>
                                                                    <p><?php the_sub_field('unfortunately_content_exam'); ?></p>
                                                                <?php endwhile; ?>
                                                            <?php endif; ?>
                                                            <p class="module-result"><?php _e('Result:', 'dff'); ?> <span><?php echo $exam_result; ?>%</span></p>
                                                            <div class="exam-footer">
                                                                <a href="#" class="btn-course-primary"><?php _e('Try again', 'dff'); ?></a>
                                                            </div>
                                                        <?php
                                                        } elseif ($exam_result == 1) {
                                                        ?>
                                                            <?php if (have_rows('before_test_group_exam', 'option')) : ?>
                                                                <?php while (have_rows('before_test_group_exam', 'option')) : the_row();  ?>
                                                                    <p><?php the_sub_field('before_test_content_exam'); ?></p>
                                                                <?php endwhile; ?>
                                                            <?php endif; ?>
                                                        <?php
                                                        }
                                                        ?>
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
                        </div>
                    </main>
                </div>
        <?php
            } // end while
        } // end if
        wp_reset_postdata();
        ?>
    </div>
</section>