<div class="course-duration">
    <?php

        if ($courses_format_value == 'open_course') {
            echo '<h5>' . $courses_format_label . '</h5>';
        } else {
        ?>
            <?php if (have_rows('course_time_group')) : ?>
                <?php while (have_rows('course_time_group')) : the_row();
                    $course_start = get_sub_field('course_start');
                    $course_finish = get_sub_field('course_finish');
                    // Load field value and convert to numeric timestamp.                                                
                    $date_course_start  = date_i18n("j M Y", strtotime($course_start));
                    $date_course_finish = date_i18n("j M Y",  strtotime($course_finish));
                    echo  '<h5>' .  $date_course_start . ' - ' .  $date_course_finish . '</h5>';
                endwhile; ?>
            <?php endif; ?>
        <?php
        }
    ?>
</div>