<section class="popular-courses">
    <div class="container">
        <h2><?php _e('Popular courses', 'dff'); ?></h2>
        <article class="archive-courses-list">
            <?php
            $popular_courses_relatiomships = get_field('popular_courses_relationship', 'option');
            // print_r($popular_courses_relatiomship);

            if ($popular_courses_relatiomships) :
                foreach ($popular_courses_relatiomships as $course) :
                    // Setup this post for WP functions (variable must be named $post).
                    setup_postdata($course); ?>
                    <div class="course-item">
                        <a href="<?php echo get_the_permalink($course->ID); ?>" class="course-item-content">
                            <?php                            
                            if ($img = get_image_by_id($course->ID)) $src = $img[0];
                            else $src = '';
                            $courses_format = get_field_object('courses_format', $course->ID);
                            $courses_format_value = $courses_format['value'];
                            $courses_format_label = $courses_format['choices'][$courses_format_value];
                            $courses_tax_obj_list = get_the_terms($course->ID, "courses-categories");
                            ?>

                            <div class="course-item-img" style="background-image: url(<?php echo $src; ?>);">
                                <!-- <img src="<?php echo $src; ?>" alt=""> -->
                                <span class="courses-item-category">
                                    <?php
                                    if (is_array($courses_tax_obj_list) || is_object($courses_tax_obj_list)) {
                                        foreach ($courses_tax_obj_list as $courses_tax) {
                                            // $term = get_queried_object();                    
                                            $course_category_icon = get_field('course_category_icon', $courses_tax->taxonomy . '_' . $courses_tax->term_id);
                                            echo '<h4><span><img src="' . $course_category_icon['url'] . '" /></span>' . $courses_tax->name .  '</h4>';
                                        }
                                    }
                                    ?>
                                </span>
                            </div>
                            <div class="course-item-desc">
                                <h2><?php echo get_the_title($course->ID); ?></h2>
                                <div class="course-duration">
                                    <?php
                                    if ($courses_format_value == 'open_course') {
                                        echo '<h5>' . $courses_format_label . '</h5>';
                                    } else {
                                    ?>
                                        <?php if (have_rows('course_time_group', $course->ID)) : ?>
                                            <?php while (have_rows('course_time_group', $course->ID)) : the_row();
                                                $course_start = get_sub_field('course_start');
                                                $course_finish = get_sub_field('course_finish');
                                                // Load field value and convert to numeric timestamp.                                                
                                                // $date_course_start  = date_i18n("j M Y", strtotime($course_start));
                                                // $date_course_finish = date_i18n("j M Y",  strtotime($course_finish));
                                                echo  '<h5>' .  $course_start . ' - ' .  $course_finish . '</h5>';
                                            endwhile; ?>
                                        <?php endif; ?>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>

            <?php
                // Reset the global post object so that the rest of the page works correctly.
                wp_reset_postdata();
            endif;
            ?>
        </article>
</section>