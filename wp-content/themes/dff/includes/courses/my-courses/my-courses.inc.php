<?php

get_header();
if ($_COOKIE['user'] && $_COOKIE['fid-is-loggedin']) {
    $dff_get_future_user_data = dff_get_future_user_data();
    $future_user_id = $dff_get_future_user_data->id;
}

$course_slug = get_query_var('course_slug') ? get_query_var('course_slug') : $_POST['course_slug'];
$post_obj    = get_page_by_slug($course_slug, OBJECT, 'courses');
$course_id   = $_POST['course_id'] ? $_POST['course_id'] : $post_obj->ID;
// WP_Query arguments
$args = array(
    'post_type'      => array('courses'),
    'post_status'    => array('publish'),
    'page_id'        =>  $course_id,
);
// The Query
$about_course = new WP_Query($args);
// The Loop
if ($about_course->have_posts()) {
    while ($about_course->have_posts()) {
        $about_course->the_post();
        $learning_style = get_field_object('learning_style');
        $courses_format = get_field_object('courses_format');
        $courses_format_value = $courses_format['value'];
        $courses_format_label = $courses_format['choices'][$courses_format_value];
        $course_complexities = get_field_object('course_complexities');
        if ($img = get_image_by_id($about_course->ID)) $src = $img[0];
        else $src = '';
?>
        <section class="course-header-wrapper course-header-my-tabs" style="background-image:url(<?php echo $src; ?>)">
            <div class="course-header-content">
                <div class="course-title-wrap">

                    <div class="course-format">
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
                                    echo  '<h5 class="course-duration">' .  $date_course_start . ' - ' .  $date_course_finish . '</h5>';
                                endwhile; ?>
                            <?php endif; ?>
                        <?php
                        }
                        ?>
                    </div>
                    <h1><?php the_title(); ?></h1>
                </div>


                <div class="hero-side-course">
                    <a href="#tabs-content" target="" class="hero-scrollTo" rel="noopener noreferrer">
                        <div class="hero-scrollToIcon"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="26" viewBox="0 0 10 26">
                                <path id="Union_1" data-name="Union 1" d="M-6020,20h4V0h2V20h4l-5,6Z" transform="translate(6020)" fill="#000000"></path>
                            </svg></div><span class="u-hiddenVisually">Scroll to content</span>
                    </a>
                </div>

            </div>
        </section>

    <?php

    } // end while
} // end if
wp_reset_postdata();
// $current_user_id = get_current_user_id();

// $courses_array   = unserialize(get_user_meta($current_user_id, 'course_id_to_user', true));
// echo 'test courses';

if ($_COOKIE['user'] && $_COOKIE['fid-is-loggedin']) {
    $dff_get_future_user_data = dff_get_future_user_data();
    $future_user_id = $dff_get_future_user_data->id;


    $future_courses_ids = future_user_courses_ids($future_user_id);

    if (in_array($course_id, $future_courses_ids)) {
        $courses_format = get_field_object('courses_format', $course_id);
    ?>
        <section class="my-courses-tabs" course-id="<?php echo $course_id; ?>" id="tabs-content">
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


    }
} else {
    wp_redirect(site_url('my-courses'));
}

get_footer();
