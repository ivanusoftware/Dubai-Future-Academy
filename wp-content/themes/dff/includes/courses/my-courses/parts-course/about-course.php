<section class="about-course">
    <?php
    // $future_user_id = '627cf5d504b88900290d26da';
    if ($_COOKIE['user'] && $_COOKIE['fid-is-loggedin']) {
        $dff_get_future_user_data = dff_get_future_user_data();
        $future_user_id = $dff_get_future_user_data->id;
    }
    
    $course_slug = get_query_var('course_slug') ? get_query_var('course_slug') : $_POST['course_slug'];
    $post_obj    = get_page_by_slug($course_slug, OBJECT, 'courses');
    $course_id   = $post_obj ? $post_obj->ID : $_POST['course_id'];
    // $course_id = get_query_var('course_id') ? get_query_var('course_id') : $_POST['course_id'];
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
            <section class="course-header-wrapper" style="background-image:url(<?php echo $src; ?>)">
                <div class="course-header-content">
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
            </section>
            <section class="cource-content">
                <div class="container">
                    <div class="columns">
                        <main class="main-content">
                            <div class="content">
                                <h2><?php echo _e('Description', 'dff'); ?></h2>
                                <div class="desc">
                                    <?php the_content(); ?>
                                    <a class="show-more"><?php echo _e('Show more', 'dff'); ?><span class="chevron bottom"></span></a>
                                    <a class="show-less" style="display: none;"><?php echo _e('Show less', 'dff'); ?><span class="chevron"></span></a>
                                </div>
                                <div class="course-requirments">
                                    <h2><?php echo _e('Requirments', 'dff'); ?></h2>
                                    <ul>
                                        <?php
                                        // Check rows exists.
                                        if (have_rows('course_requirements_repeater')) :
                                            // Loop through rows.
                                            while (have_rows('course_requirements_repeater')) : the_row();
                                                echo '<li>' . get_sub_field('course_requirements_list') . '</li>';
                                            endwhile;

                                        // No value.
                                        else :
                                        // Do something...
                                        endif;
                                        ?>
                                    </ul>
                                </div>
                                <?php
                                $exam_key = 'course_' . $course_id . '_exam_result';
                                $exem_result = get_exam_result($future_user_id, $exam_key);

                                if ($exem_result < 80) {
                                ?>
                                    <div class="course-leaving leave-course-popup">
                                        <div class="course-leaving__title"><?php echo _e('If you want to leave the course, you can do it here.', 'dff'); ?></div>
                                        <a href="#" class="dff-btn modal-toggle"><?php echo _e('Leave course', 'dff'); ?></a>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </main>
                        <aside class="course-sidebar">
                            <!-- <div class="single-sidebar"> -->
                            <h2><?php _e('Course Details', 'dff'); ?></h2>
                            <div class="sidebar-info">
                                <h3><?php _e('Learning Style', 'dff'); ?></h3>
                                <p><?php echo $learning_style['choices'][$learning_style['value']]; ?></p>
                            </div>
                            <div class="sidebar-info">
                                <h3><?php _e('Course format', 'dff'); ?></h3>
                                <p><?php echo $courses_format['choices'][$courses_format['value']]; ?></p>
                            </div>
                            <div class="sidebar-info">
                                <h3><?php _e('Complexities', 'dff'); ?></h3>
                                <p><?php echo $course_complexities['choices'][$course_complexities['value']]; ?></p>
                            </div>
                            <!-- </div> -->
                        </aside>
                    </div>
                </div>
            </section>

    <?php
        } // end while
    } // end if
    wp_reset_postdata();
    include(get_template_directory() . '/includes/courses/popup_leave_course.php');
    ?>
</section>