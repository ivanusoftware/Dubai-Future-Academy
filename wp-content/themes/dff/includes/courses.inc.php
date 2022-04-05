<?php

/**
 * Template Name: Courses
 */
get_header();
$image_main_courses = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'single-post-thumbnail');
?>
<main>
    <section class="hero hero--sbu-archive is-dark" style="background-image:url(<?php echo $image_main_courses[0]; ?>)">
        <?php echo do_shortcode('[breadcrumbs]'); ?>
        <div class="container">
            <div class="hero-main">
                <h1 class="hero-title hero-title--small is-aligned-right"><span><?php echo get_the_title(get_the_ID()); ?></span></h1>
            </div>
            <div class="hero-side">
                <?php echo get_the_content(get_the_ID()); ?>
                <a href="#archive-courses" target="" class="hero-scrollTo" rel="noopener noreferrer">
                    <div class="hero-scrollToIcon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="26" viewBox="0 0 10 26">
                            <path id="Union_1" data-name="Union 1" d="M-6020,20h4V0h2V20h4l-5,6Z" transform="translate(6020)" fill="currentColor"></path>
                        </svg>
                    </div>
                    <!-- <span class="hero-scrollToLabel">DISCOVER OUR INITIATIVES </span> -->
                </a>
            </div>
        </div>
    </section>
    <section class="archive-courses" id="archive-courses">
        <div class="container">
            <div class="courses-categories">
                <ul id="tabs-nav">
                    <?php $tax_courses_name = "courses-categories"; ?>
                    <li class="courses-cat active">
                        <a class="tabs-link" tax_id=""><?php _e('All Courses', 'dff'); ?></a>
                    </li>
                    <?php
                    $courses_terms = get_terms($tax_courses_name, array(
                        'parent'     => 0,
                        'hide_empty' => true,
                    ));
                    foreach ($courses_terms as $courses_term) {
                    ?>
                        <li class="courses-cat">
                            <a class="tabs-link" tax_id="<?php echo $courses_term->term_id; ?>"><?php echo $courses_term->name; ?></a>
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
                    'posts_per_page' => -1,
                    'post_type'      => array('courses'),
                    'post_status'    => array('publish'),
                    'order'          => 'DESC',
                    'orderby'        => 'date',
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
    <?php include(get_template_directory() . '/includes/courses/popular-courses.inc.php'); ?>
</main>
<?php
get_footer();
