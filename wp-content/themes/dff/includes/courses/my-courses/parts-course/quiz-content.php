<?php echo $lesson_test_id = $_POST['lesson_test_id'];  ?>
<div class="course-quiz">

    <div class="course-quiz__top">
        <div class="course-quiz__title">TEST</div>
        <div class="course-quiz__steps">Question <span class="currentStepId">1</span>/<span class="lastStepId"></span></div>
    </div>
    <?php if (have_rows('course_module_repeater')) :  while (have_rows('course_module_repeater')) : the_row();

            $quiz = get_sub_field('quiz');
            if ($quiz) : $post = $quiz;
                setup_postdata($post); ?>

                <?php if (have_rows('quiz_steps')) : ?>
                    <div id="quiz" class="course-quiz__content">
                        <?php while (have_rows('quiz_steps')) : the_row();
                            $row_parent = get_row_index(); ?>
                            <div class="course-quiz__step-title"><?php echo get_row_index(); ?></div>
                            <div class="course-quiz__step" data-step="<?php echo get_row_index(); ?>">
                                <?php if (have_rows('quiz_step_inputs')) : ?>
                                    <?php while (have_rows('quiz_step_inputs')) : the_row(); ?>

                                        <?php if (get_row_layout() == 'radio') : ?>

                                            <?php the_sub_field('question'); ?>

                                            <?php if (have_rows('answers_radio')) : ?>
                                                <?php while (have_rows('answers_radio')) : the_row(); ?>

                                                    <div class="course-quiz__radio">
                                                        <input type="radio" name="radio_<?php echo $row_parent; ?>" value="<?php the_sub_field('answer'); ?>" id="radio_parent_<?php echo $row_parent; ?>_<?php echo get_row_index(); ?>">
                                                        <label for="radio_parent_<?php echo $row_parent; ?>_<?php echo get_row_index(); ?>"><?php the_sub_field('answer'); ?></label>
                                                    </div>

                                                <?php endwhile; ?>
                                            <?php endif; ?>


                                        <?php elseif (get_row_layout() == 'checkbox') : ?>

                                            <?php the_sub_field('question'); ?>

                                            <?php if (have_rows('answers_checkbox')) : ?>
                                                <?php while (have_rows('answers_checkbox')) : the_row(); ?>

                                                    <div class="course-quiz__checkbox">
                                                        <input type="checkbox" name="checkbox_<?php echo $row_parent; ?>" value="<?php the_sub_field('answer'); ?>" id="radio_parent_<?php echo $row_parent; ?>_<?php echo get_row_index(); ?>">
                                                        <label for="radio_parent_<?php echo $row_parent; ?>_<?php echo get_row_index(); ?>"><?php the_sub_field('answer'); ?></label>
                                                    </div>

                                                <?php endwhile; ?>
                                            <?php endif; ?>

                                        <?php elseif (get_row_layout() == 'text_with_select') : ?>

                                            <?php the_sub_field('question'); ?>

                                            <div class="course-quiz__select">

                                                <?php if (have_rows('answer_fragments')) : ?>
                                                    <?php while (have_rows('answer_fragments')) : the_row(); ?>

                                                        <?php the_sub_field('fragment'); ?>

                                                        <?php if (get_sub_field('variant') == 'select') { ?>

                                                            <select name="select_<?php echo $row_parent; ?>_<?php echo get_row_index(); ?>">
                                                                <option value="" disabled="" selected="">Choose the option</option>
                                                                <?php if (have_rows('select')) : ?>
                                                                    <?php while (have_rows('select')) : the_row(); ?>
                                                                        <option value="<?php the_sub_field('answer'); ?>"><?php the_sub_field('answer'); ?></option>
                                                                    <?php endwhile; ?>
                                                                <?php endif; ?>
                                                            </select>

                                                        <?php } ?>

                                                        <?php if (get_sub_field('variant') == 'input') { ?>
                                                            <input type="text" name="fragment_text_<?php echo $row_parent; ?>_<?php echo get_row_index(); ?>">
                                                        <?php } ?>


                                                    <?php endwhile; ?>
                                                <?php endif; ?>

                                            </div>

                                        <?php endif; ?>

                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </div>

                        <?php endwhile; ?>

                    </div>
                <?php endif; ?>


    <?php endif;
            wp_reset_postdata();
        endwhile;
    endif; ?>
</div>