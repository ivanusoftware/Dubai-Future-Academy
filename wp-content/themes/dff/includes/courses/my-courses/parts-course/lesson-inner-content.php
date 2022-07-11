<?php $lesson_name = get_sub_field('lesson_name'); ?>

<div class="lesson-content">
    <article class="course-box">
        <div class="lesson-inner-container">
            <h2><?php echo $lesson_name ? $lesson_name : ''; ?></h2>
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
                elseif (get_row_layout() == 'lesson_gallery_content') :
                    include get_template_directory() . '/includes/courses/layouts/gallery-layout.inc.php';
                endif;

            // End loop.
            endwhile;
        // wp_reset_postdata();
        // No value.

        endif;
        $course_id        = $_POST['course_id'];
        $module_index     = $_POST['module_index'];
        $lesson_index     = $_POST['lesson_index'];
        $count_lesson_row = $_POST['count_lesson_row'];
        $lesson_test_id   = $_POST['lesson_test_id'];
        ?>
        <div class="lesson-complete lesson-inner-container">
            <input type="checkbox" id="lesson-complete" name="lesson-complete" module-index="<?php echo $module_index ? $module_index : 1; ?>" lesson-index="<?php echo $lesson_index ? $lesson_index : 1; ?>" course-id="<?php echo $course_id; ?>">
            <label for="lesson-complete"><?php _e('Mark as complete', 'dff'); ?></label>
        </div>
    </article>
</div>