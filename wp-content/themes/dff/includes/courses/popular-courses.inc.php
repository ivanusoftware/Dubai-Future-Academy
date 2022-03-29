<section class="popular-courses">
    <div class="container">

        <div class="courses-categories">
            <ul id="tabs-nav">
                <?php
                $tax_courses_name = "courses-categories";
                $courses_terms = get_terms($tax_courses_name, array(
                    'parent'     => 0,
                    'hide_empty' => true,
                ));
                foreach ($courses_terms as $courses_term) {
                ?>
                    <li class="courses-cat">
                        <?php //echo  wp_get_cat_postcount($portfolio_term->term_id); 
                        ?>
                        <a class="tabs-link tax-parent" tax_id="<?php echo $courses_term->term_id; ?>"><?php echo $courses_term->name; ?></a>
                    </li>
                <?php
                }
                ?>
            </ul> <!-- END tabs-nav -->
        </div>
        <article class="archive-courses-list">
            <?php
            // WP_Query arguments
            $args = array(
                'post_type'   => array('courses'),
                'post_status' => array('publish'),
                'order'       => 'DESC',
                'orderby'     => 'date',
            );

            // The Query        
            $courses = new WP_Query($args);
            ?>
            <?php
            // The Loop
            if ($courses->have_posts()) {
                while ($courses->have_posts()) {
                    $courses->the_post();
                    if ($img = get_image_by_id($courses->ID)) $src = $img[0];
                    else $src = '';
                    // $courses_format = get_field('courses_format');
                    $courses_format = get_field_object('courses_format');
                    $courses_format_value = $courses_format['value'];
                    $courses_format_label = $courses_format['choices'][$courses_format_value];

                    $courses_tax_obj_list = get_the_terms($courses->ID, $tax_courses_name);
                    print_r($term_obj_list);

            ?>
                    <div class="course-item">
                        <a href="<?php echo get_the_permalink($courses->ID); ?>" class="course-item-content">

                            <div class="course-item-img">
                                <img src="<?php echo $src; ?>" alt="">
                                <span class="courses-item-category">
                                    <?php
                                    foreach ($courses_tax_obj_list as $courses_tax) {
                                        echo '<h4>' . $courses_tax->name .  '</h4>';
                                    }
                                    ?>
                                </span>
                            </div>
                            <div class="course-item-desc">
                                <h2><?php the_title(); ?></h2>
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
                            </div>
                        </a>
                    </div>
            <?php
                }
            } else {
                // no posts found
            }

            // Restore original Post Data
            wp_reset_postdata();
            ?>
        </article>
        <?php echo 'courses';  ?>
    </div>
</section>