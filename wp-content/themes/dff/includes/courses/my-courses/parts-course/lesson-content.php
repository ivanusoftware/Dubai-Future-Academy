    <div class="lesson-header">
        <span class="back"><?php _e('Back', 'dff'); ?></span>
        <span class="next"><?php _e('Next', 'dff'); ?></span>
    </div>
    <?php if (have_rows('course_module_repeater')) :  while (have_rows('course_module_repeater')) : the_row();
            $model_i = get_row_index();
            if (have_rows('course_lesson_repeater')) : while (have_rows('course_lesson_repeater')) : the_row();
                    $lesson_name = get_sub_field('lesson_name');
                    $lesson_i = get_row_index();
                    if ($model_i == 1 && $lesson_i == 1) {
                        get_template_part('includes/courses/my-courses/parts-course/lesson-inner', 'content');
                    }
                endwhile;
            // wp_reset_postdata();
            endif;
        endwhile;
    endif; ?>