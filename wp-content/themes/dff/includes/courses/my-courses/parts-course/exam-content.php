<?php 
$exam_post_id = $_POST['exam_post_id']; 
$course_id = $_POST['course_id'];
$module_or_exam = get_sub_field('module_or_exam');
$module_i = get_row_index();
?>
<div class="phrases">
    <span class="phrase_result"><?php _e('Result', 'dff'); ?></span>
    <span class="phrase_done"><?php _e('Done', 'dff'); ?></span>
    <span class="phrase_back"><?php _e('Back', 'dff'); ?></span>
    <span class="phrase_next"><?php _e('Next', 'dff'); ?></span>
</div>
<div class="course-quiz">

    <div class="course-quiz__top">
        <div class="course-quiz__title"><?php _e('Exam', 'dff'); ?></div>
        <div class="course-quiz__steps"><?php _e('Question', 'dff'); ?> <span class="currentStepId">1</span>/<span class="lastStepId"></span></div>
    </div>
    <?php
    
    if ($exam_post_id) : $post = $exam_post_id;
        setup_postdata($post);
    ?>

        <?php if (have_rows('quiz_steps')) : ?>
            <form id="quiz" class="course-quiz__content" data-type="<?php echo $module_or_exam; ?>" data-quiz-id="<?php echo $exam_post_id; ?>" data-module-id="<?php echo $module_or_exam; ?>" data-course-id="<?php echo $course_id; ?>" data-user-id="<?php echo get_current_user_id(); ?>">
                <?php while (have_rows('quiz_steps')) : the_row();
                    $row_parent = get_row_index(); ?>
                    <div class="course-quiz__step-title"><?php echo get_row_index(); ?></div>
                    <div class="course-quiz__step" data-step="<?php echo get_row_index(); ?>">
                        <?php if (have_rows('quiz_step_inputs')) : ?>
                            <?php while (have_rows('quiz_step_inputs')) : the_row(); 
                                $layout_parent = get_row_index(); ?>

                                <?php if (get_row_layout() == 'radio') : ?>

                                    <?php the_sub_field('question'); ?>

                                    <?php if (have_rows('answers_radio')) : ?>
                                        <?php while (have_rows('answers_radio')) : the_row(); ?>

                                            <div class="course-quiz__radio">
                                                <input class="required" type="radio" name="radio_<?php echo $row_parent; ?>_<?php echo $layout_parent; ?>" value="<?php the_sub_field('answer'); ?>" id="radio_parent_<?php echo $row_parent; ?>_<?php echo $layout_parent; ?>_<?php echo get_row_index(); ?>">
                                                <label for="radio_parent_<?php echo $row_parent; ?>_<?php echo $layout_parent; ?>_<?php echo get_row_index(); ?>"><?php the_sub_field('answer'); ?></label>
                                            </div>

                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                <?php elseif (get_row_layout() == 'checkbox') : ?>
                                    <?php the_sub_field('question'); ?>

                                    <?php if (have_rows('answers_checkbox')) : ?>
                                        <?php while (have_rows('answers_checkbox')) : the_row(); ?>

                                            <div class="course-quiz__checkbox">
                                                <input class="required" type="checkbox" name="checkbox_<?php echo $row_parent; ?>_<?php echo $layout_parent; ?>[]" value="<?php the_sub_field('answer'); ?>" id="radio_parent_<?php echo $row_parent; ?>_<?php echo $layout_parent; ?>_<?php echo get_row_index(); ?>">
                                                <label for="radio_parent_<?php echo $row_parent; ?>_<?php echo $layout_parent; ?>_<?php echo get_row_index(); ?>"><?php the_sub_field('answer'); ?></label>
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

                                                    <select class="required" name="select_<?php echo $row_parent; ?>_<?php echo $layout_parent; ?>_<?php echo get_row_index(); ?>">
                                                        <option value="<?php _e('Choose the option', 'dff'); ?>" disabled="" selected=""><?php _e('Choose the option', 'dff'); ?></option>
                                                        <?php if (have_rows('select')) : ?>
                                                            <?php while (have_rows('select')) : the_row(); ?>
                                                                <option value="<?php the_sub_field('answer'); ?>"><?php the_sub_field('answer'); ?></option>
                                                            <?php endwhile; ?>
                                                        <?php endif; ?>
                                                    </select>

                                                <?php } ?>

                                                <?php if (get_sub_field('variant') == 'input') { ?>
                                                    <input class="required" type="text" name="fragment_text_<?php echo $row_parent; ?>_<?php echo $layout_parent; ?>_<?php echo get_row_index(); ?>">
                                                <?php } ?>


                                            <?php endwhile; ?>
                                        <?php endif; ?>

                                    </div>

                                <?php endif; ?>

                            <?php endwhile; ?>
                        <?php endif; ?>
                    </div>

                <?php endwhile; ?>

            </form>


            <div class="course-quiz__progress" data-success="thanks">
                <?php if (have_rows('thank_you_exam', 'option')) : ?>
                    <?php while (have_rows('thank_you_exam', 'option')) : the_row();  ?>
                        <div class="course-quiz__progress-title"><?php the_sub_field('title'); ?></div>
                        <div class="course-quiz__progress-subtitle"><?php the_sub_field('content'); ?></div>
                    <?php endwhile; ?>
                <?php endif; ?>
                
                 

                <div class="course-quiz__buttons">
                    <ul>
                        <li>
                            <a href="<?php echo site_url('my-courses'); ?>"><?php _e('Go to my courses', 'dff'); ?></a>
                        </li>
                        <!-- <li>

                            <a href="#" class="download-certificate" course-id="<?php echo $course_id; ?>"><?php _e('Download', 'dff'); ?></a>
                            
                        </li> -->
                    </ul>
                </div>
            </div>

            <div class="course-quiz__progress" data-success="fail">
                <?php if (have_rows('unfortunately_group_exam', 'option')) : ?>
                    <?php while (have_rows('unfortunately_group_exam', 'option')) : the_row();  ?>
                        <div class="course-quiz__progress-title"><?php the_sub_field('unfortunately_title_exam'); ?></div>
                        <div class="course-quiz__progress-subtitle"><?php the_sub_field('unfortunately_content_exam'); ?></div>
                        <div class="course-quiz__progress-result"><?php _e('Result:', 'dff'); ?> <span></span></div>
                    <?php endwhile; ?>
                <?php endif; ?>
                
                
                <div class="course-quiz__buttons">
                    <ul>
                        <li>
                            
                            <?php echo '<a href="#" class="btn-course-primary test-try-again-exam" tab-id="tab-2"
                                                                 module-type="' . $module_or_exam . '" module-index="' . $module_i . '" exam-post-id="' .  $exam_post_id . '" >' . __('Try again', 'dff') . '</a>'; ?>
                        </li>
                    </ul>
                </div>
            </div>


        <?php endif; ?>


    <?php endif;
    wp_reset_postdata();
    ?>
</div>