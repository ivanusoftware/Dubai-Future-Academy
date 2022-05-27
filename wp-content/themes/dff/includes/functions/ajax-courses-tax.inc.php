<?php

/**
 * Archive Courses Ajax category filter
 *
 * @return void
 */
function courses_tax_ajax_callback()
{
    $tax_courses_name = "courses-categories";

    $tax_id = (int)$_POST['tax_id'];
    $args = array(
        'posts_per_page' => -1,
        'post_type'      => 'courses',
        'post_status'    => array('publish'),
        'order'          => 'DESC',
        'orderby'        => 'date',
        'tax_query'      => array(
            array(
                'taxonomy' => $tax_courses_name,
                'field'    => 'id',
                'terms'    => $tax_id ? $tax_id : get_ids_courses_category($tax_courses_name),
            )
        )
    );
    // The Query
    $courses = new WP_Query($args);
?>
    <?php
    // The Loop
    if ($courses->have_posts()) {
        while ($courses->have_posts()) {
            $courses->the_post();
    ?>
            <div class="course-item">
                <a href="<?php echo get_the_permalink($courses->ID); ?>" class="course-item-content">
                    <?php
                    include(get_template_directory() . '/includes/courses/parts/courses-content.php');
                    include(get_template_directory() . '/includes/courses/parts/course-duration.php');
                    ?>
                </a>
            </div>
<?php
        }
    } else {
        // no posts found
    }
    wp_reset_postdata();
    wp_die();
}
add_action('wp_ajax_courses_tax_ajax', 'courses_tax_ajax_callback');
add_action('wp_ajax_nopriv_courses_tax_ajax', 'courses_tax_ajax_callback');
