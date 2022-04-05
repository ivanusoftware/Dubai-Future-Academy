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
            $course_complexities = get_field_object('course_complexities');
            if ($img = get_image_by_id($modules_course->ID)) $src = $img[0];
            else $src = '';

    ?>

            <section class="cource-content">
                <div class="container">
                    <div class="columns">
                        <aside class="course-sidebar">
                            <div class="single-modules">
                                <!-- Accordion -->
                                <div class="accordion">
                                    <?php
                                    if (have_rows('course_module_repeater')) :
                                        while (have_rows('course_module_repeater')) : the_row();

                                            // Load sub field value.
                                            $course_module_name = get_sub_field('course_module_name');
                                            $model_i = get_row_index();
                                    ?>
                                            <div class="accordion-item">
                                                <div class="accordion-head">
                                                    <h6><?php _e('Module', 'dff'); ?> <?php echo $model_i; ?></h6>
                                                </div>
                                                <div class="accordion-content">
                                                    <?php
                                                    if (have_rows('course_lesson_repeater')) :
                                                    ?>
                                                        <ul>
                                                            <?php
                                                            while (have_rows('course_lesson_repeater')) : the_row();
                                                                $lesson_name = get_sub_field('lesson_name');
                                                                $lesson_i = get_row_index();
                                                            ?>
                                                                <li id="tab<?php echo $model_i . '-' . $lesson_i; ?>" class="<?php echo $lesson_i == 1 && $model_i == 1 ? 'active' : ''; ?>" role="tab" aria-controls="panel<?php echo $model_i . '-' . $lesson_i; ?>" aria-selected="<?php echo $lesson_i == 1 ? 'true' : 'false'; ?>"><?php _e('Lesson', 'dff'); ?> <?php echo $lesson_i; ?></li>

                                                            <?php
                                                            endwhile;
                                                            ?>
                                                        </ul>
                                                    <?php
                                                    else :
                                                    // Do something...
                                                    endif;
                                                    ?>
                                                </div>
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
                                    <div class="lesson-header">
                                        <span class="back"><?php _e('Back', 'dff'); ?></span>
                                        <span class="next"><?php _e('Next', 'dff'); ?></span>
                                    </div>

                                    <?php if (have_rows('course_module_repeater')) :  while (have_rows('course_module_repeater')) : the_row();
                                            $model_i = get_row_index();
                                            if (have_rows('course_lesson_repeater')) : while (have_rows('course_lesson_repeater')) : the_row();
                                                    $lesson_name = get_sub_field('lesson_name');
                                                    $lesson_i = get_row_index();
                                    ?>
                                                    <div class="lesson-content box tab<?php echo $model_i . '-' . $lesson_i; ?>-box <?php echo $lesson_i == 1 && $model_i == 1 ? 'selected' : ''; ?>" id="panel<?php echo $model_i . '-' . $lesson_i; ?>" role="tabpanel" aria-labelledby="tab<?php echo $model_i . '-' . $lesson_i; ?>">
                                                        <article class="course-box">
                                                            <div class="lesson-inner-container">
                                                                <h2><?php echo $lesson_name; ?></h2>
                                                            </div>
                                                            <?php

                                                            // Check value exists.
                                                            if (have_rows('course_lesson_content')) :

                                                                // Loop through rows.
                                                                while (have_rows('course_lesson_content')) : the_row();

                                                                    // Case: Paragraph layout.
                                                                    if (get_row_layout() == 'lesson_video_content') :
                                                                        include get_template_directory() . '/includes/courses/layouts/video-layout.inc.php';
                                                                    // $text = get_sub_field('text');
                                                                    elseif (get_row_layout() == 'lesson_text_content') :
                                                                        include get_template_directory() . '/includes/courses/layouts/content-layout.inc.php';
                                                                        
                                                                    // Do something...

                                                                    // Case: Download layout.
                                                                    elseif (get_row_layout() == 'lesson_file_content') :
                                                                        include get_template_directory() . '/includes/courses/layouts/upload-file-layout.inc.php';
                                                                    // Case: Download layout.
                                                                    elseif (get_row_layout() == 'lesson_audio_file_content') :
                                                                        include get_template_directory() . '/includes/courses/layouts/audio-layout.inc.php';
                                                                    
                                                                    // Case: Download layout.
                                                                    elseif (get_row_layout() == 'download') :
                                                                        $file = get_sub_field('file');
                                                                    // Do something...

                                                                    endif;

                                                                // End loop.
                                                                endwhile;

                                                            // No value.
                                                            else :
                                                            // Do something...
                                                            endif;
                                                            ?>
                                                        </article>
                                                    </div>
                                    <?php endwhile;
                                            endif;
                                        endwhile;
                                    endif; ?>



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