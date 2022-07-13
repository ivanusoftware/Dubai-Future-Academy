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
        if ($_COOKIE['user'] && $_COOKIE['fid-is-loggedin']) {
            $dff_get_future_user_data = dff_get_future_user_data();
            $future_user_id = $dff_get_future_user_data->id;
        }
        if ($module_index && $lesson_index) {
            $state_key = 'course_' . $course_id . '_status_module_' . $module_index . '_lesson_' . $lesson_index;
        } else {
            $state_key = 'course_' . $course_id . '_status_module_1_lesson_1';
        }
        $status = get_state_lesson_user($future_user_id, $state_key);

        ?>
        <div class="lesson-complete lesson-inner-container">
            <label for="lesson-complete" class="control control-checkbox">
                <?php _e('Mark as complete', 'dff'); ?>
                <input type="checkbox" id="lesson-complete" name="lesson-complete" module-index="<?php echo $module_index ? $module_index : 1; ?>" lesson-index="<?php echo $lesson_index ? $lesson_index : 1; ?>" course-id="<?php echo $course_id; ?>" course-id-lang="<?php echo dff_get_id_parrent_lang($course_id); ?>" <?php echo $status->dff_meta_value == 1 ? 'checked' : ''; ?>>
                <div class="control_indicator"></div>
            </label>
        </div>
    </article>
</div>