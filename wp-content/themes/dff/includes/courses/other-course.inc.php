<section class="other-course">
    <div class="container">
        <h2><?php _e('Other courses', 'dff'); ?></h2>
        <article class="archive-courses-list">
            <?php
            // WP_Query arguments
            $args = array(
                'posts_per_page' => 4,
                'post_type'      => array('courses'),
                'post_status'    => array('publish'),
                'order'          => 'DESC',
                'orderby'        => 'date',
                'post__not_in'   => array(get_the_ID()),
            );
            // The Query
            $courses = new WP_Query($args);
            // The Loop
            if ($courses->have_posts()) {
                while ($courses->have_posts()) {
                    $courses->the_post();
            ?>
                    <div class="course-item">
                        <a href="<?php echo get_the_permalink($courses->ID); ?>" class="course-item-content">
                            <?php get_template_part('includes/courses/parts/courses', 'content'); ?>
                        </a>
                    </div>
            <?php
                }
            } else {
                // no posts found
            }
            wp_reset_postdata();
            ?>
        </article>
    </div>
</section>