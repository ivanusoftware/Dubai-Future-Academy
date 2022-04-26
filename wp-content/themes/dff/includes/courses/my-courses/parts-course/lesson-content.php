 <?php if (have_rows('course_module_repeater')) :  while (have_rows('course_module_repeater')) : the_row();
            $model_i = get_row_index();
            if (have_rows('course_lesson_repeater')) : while (have_rows('course_lesson_repeater')) : the_row();
                    $lesson_name = get_sub_field('lesson_name');
                    $lesson_i = get_row_index();
                    if ($lesson_i == 1 && $model_i == 1) {
    ?>
                     <div class="lesson-header">
                         <button class="back" <?php echo $lesson_i == 1 ? 'disabled' : ''; ?>><?php _e('Back', 'dff'); ?></button>
                         <button class="next" module-index="<?php echo $model_i; ?>" lesson-index="<?php echo $lesson_i + 1; ?>">
                             <?php _e('Next', 'dff'); ?>
                         </button>
                     </div>
 <?php
                    }
                    if ($model_i == 1 && $lesson_i == 1) {
                        get_template_part('includes/courses/my-courses/parts-course/lesson-inner', 'content');
                    }
                endwhile;
                wp_reset_postdata();
            endif;
        endwhile;
    endif; ?>