<?php

/**
 * Template Name: My courses
 *
 * Allow users to update their profiles from Frontend.
 *
 **/
get_header(); // Loads the header.php template.
// Get user info
// global $wp_roles;
$current_user     = wp_get_current_user();
$current_user_id  = get_current_user_id();

$dff_user_courses = unserialize(get_user_meta($current_user_id, 'course_id_to_user', true));

if (!is_user_logged_in()) {
    wp_redirect(site_url('courses'));
};

?>
<section class="my-courses-tabs my-course-tab" id="post-<?php the_ID(); ?>">
    <div class="container">

        <header class="tabs-nav">
            <ul>
                <li class="active"><a href="#tab1"><?php _e('My courses', 'dff'); ?></a></li>
                <li><a href="#tab2"><?php _e('My certification', 'dff'); ?></a></li>
            </ul>
        </header>

        <div class="tabs-content">
            <div class="tab-wrapper" id="tab1">

                <article class="archive-courses-list">
                    <?php                    
                    // WP_Query arguments
                    if (!empty($dff_user_courses)) {
                        $args = array(
                            'posts_per_page' => -1,
                            'post_type'      => array('courses'),
                            'post_status'    => array('publish'),
                            'order'          => 'DESC',
                            'orderby'        => 'post__in',
                            'post__in'       => $dff_user_courses,
                            'ignore_sticky_posts' => 0
                        );
                    }
                    // The Query
                    $courses = new WP_Query($args);
                    // The Loop
                    if ($courses->have_posts()) {
                        while ($courses->have_posts()) {
                            $courses->the_post();
                    ?>
                            <div class="course-item">
                                <a href="<?php echo site_url('my-courses') . '/' . get_the_ID(); ?>" class="course-item-content">
                                    <?php
                                    // dff_user_course_module_result($current_user_id,  get_the_ID());
                                    include(get_template_directory() . '/includes/courses/parts/courses-content.php'); ?>

                                </a>
                            </div>
                        <?php
                        }
                    } else {
                        ?>
                        <p><?php _e('You have no courses. Please, check our <a href="' . esc_url(site_url('courses')) . '">courses</a>', 'dff');
                            ?></p>

                    <?php
                    }
                    wp_reset_postdata();
                    ?>
                </article>

            </div>
            <div id="tab2" class="tab-wrapper">
                <h3><?php _e('My certification', 'dff'); ?></h3>
                <p><?php _e('You have no courses. Please, check our <a href="' . esc_url(site_url('courses')) . '">courses</a>', 'dff');
                    ?></p>
            </div>
        </div>

        <span>User: <b><?php echo $current_user->user_login ?></b></span>
        <?php if (is_user_logged_in()) : ?>
            <a href="<?php echo wp_logout_url(home_url()); ?>">Logout</a>
        <?php endif; ?>
    </div> <!-- END tabs -->
</section>




<?php get_footer(); 
// Loads the footer.php template. 
