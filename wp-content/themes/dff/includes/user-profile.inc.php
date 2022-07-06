<?php

/**
 * Template Name: My courses
 *
 * Allow users to update their profiles from Frontend.
 *
 **/
get_header(); // Loads the header.php template.
// Get user info


// if ($_COOKIE['user'] && $_COOKIE['fid-is-loggedin']) {
//     $dff_get_future_user_data = dff_get_future_user_data();
//     $future_user_id = $dff_get_future_user_data->id;
// }

// dff_user_courses_certificate($current_user_id);

if (!$_COOKIE['user'] && !$_COOKIE['fid-is-loggedin']) {
    wp_redirect(site_url('courses'));
}else{
    $dff_get_future_user_data = dff_get_future_user_data();
    $future_user_id = $dff_get_future_user_data->id;
};
$future_courses_ids = future_user_courses_ids($future_user_id);

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
                    $ratings = array();
                    // WP_Query arguments                 
                    $args = array(
                        'posts_per_page' => -1,
                        'post_type'      => array('courses'),
                        'post_status'    => array('publish'),
                        'order'          => 'DESC',
                        'orderby'        => 'post__in',
                        // 'post__in'       => $dff_user_courses,
                        'post__in'       => $future_courses_ids,
                        'ignore_sticky_posts' => 0
                    );
                    // The Query
                    $courses = new WP_Query(!empty($future_courses_ids) ? $args : '');
                    // The Loop
                    if ($courses->have_posts()) {
                        while ($courses->have_posts()) {
                            $courses->the_post();
                            // $exem_result = get_user_meta(get_current_user_id(), 'course_' . get_the_ID() . '_exam_result', true);
                            $slug = get_post_field('post_name', get_the_ID());

                            $exam_key = 'course_' . get_the_ID() . '_exam_result';
                            $exem_result = get_exam_result($future_user_id, $exam_key);
                    ?>
                            <div class="course-item">
                                <a href="<?php echo site_url('my-courses') . '/' . $slug; ?>" class="course-item-content">
                                    <?php
                                    include(get_template_directory() . '/includes/courses/parts/courses-content.php');
                                    // if ($exem_result >= 80 && $exem_result != 1) {
                                    if ($exem_result >= 80) {
                                    ?>
                                        <div class="course-duration__status">
                                            <?php echo _e('Completed Course', 'dff'); ?>
                                        </div>
                                    <?php
                                    } else {
                                        include(get_template_directory() . '/includes/courses/parts/course-duration.php');
                                    }
                                    include(get_template_directory() . '/includes/courses/parts/course-progress-bar.php');
                                    ?>
                                </a>
                            </div>
                        <?php
                        }
                    } else {
                        ?>
                        <p><?php printf(__('You have no courses. Please, check our <a href="%s">courses</a>', 'dff'), esc_url(site_url('courses'))); ?></p>
                    <?php
                    }

                    wp_reset_postdata();
                    ?>
                </article>

            </div>
            <div id="tab2" class="tab-wrapper">
                <?php include(get_template_directory() . '/includes/courses/my-courses/my-certification.inc.php'); ?>
            </div>
        </div>

        <!-- <span>User: <b><?php echo $current_user->user_login ?></b></span>
        <?php if (is_user_logged_in()) : ?>
            <a href="<?php echo wp_logout_url(home_url()); ?>">Logout</a>
        <?php endif; ?> -->
    </div> <!-- END tabs -->
</section>




<?php
get_footer(); 
// Loads the footer.php template. 
