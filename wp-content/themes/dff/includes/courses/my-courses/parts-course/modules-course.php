<section class="modules-course">
    <?php
    // WP_Query arguments
    $args = array(
        'post_type'      => array('courses'),
        'post_status'    => array('publish'),
        'page_id'        =>  $course_id,
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
                                        while (have_rows('course_module_repeater')) : the_row();
                                            $course_module_name = get_sub_field('course_module_name');
                                            $model_i = get_row_index();
                                            $choose_date_open_module = get_sub_field('choose_date_open_module');
                                            $date_open_module = date("d-m-Y", strtotime(get_sub_field('date_open_module')));
                                            $type_course = dff_open_module_by_date($date_open_module);
                                    ?>

                                            <div class="accordion-item <?php echo dff_open_module_by_date($date_open_module); ?>">
                                                <div class="accordion-head" time-progressive="<?php echo dff_open_module_by_date($date_open_module); ?>">
                                                    <h6><?php _e('Module', 'dff'); ?> <?php echo $model_i . dff_show_date($date_open_module); ?></h6>
                                                </div>
                                                <?php if ($type_course == 'open-module') { ?>
                                                    <div class="accordion-content">
                                                        <?php

                                                        if (have_rows('course_lesson_repeater')) :
                                                        ?>
                                                            <ul>
                                                                <?php
                                                                $i = 0;
                                                                while (have_rows('course_lesson_repeater')) : the_row();
                                                                    $lesson_name = get_sub_field('lesson_name');
                                                                    $lesson_i = get_row_index();
                                                                ?>
                                                                    <li class="tab-item <?php echo $lesson_i == 1 && $model_i == 1 ? 'active' : ''; ?>" module-index="<?php echo $model_i; ?>" lesson-index="<?php echo $lesson_i; ?>">
                                                                        <?php _e('Lesson', 'dff'); ?> 
                                                                        <?php echo $lesson_i; ?>

                                                                    </li>
                                                                <?php
                                                                    $i++;
                                                                // }
                                                                endwhile;
                                                                ?>
                                                            </ul>
                                                        <?php
                                                        else :
                                                        // Do something...
                                                        endif;

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
                        </aside>
                        <main class="main-content">
                            <div class="content">
                                <div class="lesson-container">
                                    <?php get_template_part('includes/courses/my-courses/parts-course/lesson', 'content');
                                    ?>
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