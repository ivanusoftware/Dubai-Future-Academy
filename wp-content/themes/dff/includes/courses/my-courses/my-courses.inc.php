<?php

get_header();
$course_slug = get_query_var('course_slug');
$post_obj    = get_page_by_slug($course_slug, OBJECT, 'courses');
$course_id   = $post_obj->ID;
// $current_user_id = get_current_user_id();

// $courses_array   = unserialize(get_user_meta($current_user_id, 'course_id_to_user', true));
// echo 'test courses';

if ($_COOKIE['user'] && $_COOKIE['fid-is-loggedin']) {
    $dff_get_future_user_data = dff_get_future_user_data();
    $future_user_id = $dff_get_future_user_data->id;
}

$future_courses_ids = future_user_courses_ids($future_user_id);

if (in_array($course_id, $future_courses_ids)) {
    $courses_format = get_field_object('courses_format', $course_id);
?>
    <section class="my-courses-tabs" course-id="<?php echo $course_id; ?>">
        <div class="container">
            <header class="tabs-nav tabs-nav-my-courses">
                <ul>
                    <li class="active"><button class="tab-module" tab-id="tab-1" course-id="<?php echo $course_id; ?>"><?php _e('About', 'dff'); ?></button></li>
                    <li><button class="tab-module" tab-id="tab-2" course-id="<?php echo $course_id; ?>" <?php echo dff_format_time_bound($courses_format, $course_id); ?>><?php _e('Modules', 'dff'); ?></button></li>
                    <li><button class="tab-module" tab-id="tab-3" course-id="<?php echo $course_id; ?>" <?php echo dff_format_time_bound($courses_format, $course_id); ?>><?php _e('My Progress', 'dff'); ?></button></li>
                </ul>
            </header>
        </div>
        <div class="tabs-content my-courses-tabs-content">
            <div class="tab-wrapper">
                <?php get_template_part('includes/courses/my-courses/parts-course/about', 'course'); ?>
            </div>
        </div>
    </section>
<?php
    //   echo 'Already exists course with ID: ' . $course_id;
    //   echo '<p>'.$course_id.'</p>';


} else {
    wp_redirect(site_url('my-courses'));
}

get_footer();
