<section class="my-progress-content">
    <div class="container">
        <?php
        // WP_Query arguments
        $args = array(
            'post_type'      => array('courses'),
            'post_status'    => array('publish'),
            'page_id'        =>  $course_id,
        );
        // The Query
        $modules_course = new WP_Query($args);
        // The Loop
        if ($modules_course->have_posts()) {
            while ($modules_course->have_posts()) {
                $modules_course->the_post();
                $learning_style = get_field_object('learning_style');
                $courses_format = get_field_object('courses_format');
                $courses_format_value = $courses_format['value'];
                $courses_format_label = $courses_format['choices'][$courses_format_value];
                $course_complexities  = get_field_object('course_complexities');
                if ($img = get_image_by_id($modules_course->ID)) $src = $img[0];
                else $src = '';
                $index = '';
        ?>

                <div class="columns">
                    <aside class="course-sidebar" course-id="<?php echo $course_id; ?>">
                        <div class="my-progres-modules">
                            <ul>
                                <li class="active">
                                    <a href="#tab1" tab-id="tab-1">
                                        <?php _e('General progress', 'dff'); ?>
                                    </a>
                                </li>
                                <?php
                                if (have_rows('course_module_repeater')) :
                                    while (have_rows('course_module_repeater')) : the_row();
                                        $course_module_name = get_sub_field('course_module_name');
                                        $model_i = get_row_index();
                                        $choose_date_open_module = get_sub_field('choose_date_open_module');
                                        $date_open_module = date("d-m-Y", strtotime(get_sub_field('date_open_module')));
                                        $type_course = dff_open_module_by_date($date_open_module);
                                ?>
                                        <li>
                                            <a href="#tab<?php echo $model_i + 1; ?>" tab-id="tab-<?php echo $model_i + 1; ?>">
                                                <?php _e('Module', 'dff'); ?> <?php echo $model_i; ?>
                                            </a>
                                        </li>
                                <?php
                                    endwhile;
                                else :
                                endif;
                                ?>
                                <li>
                                    <a href="#tab5" tab-id="tab-5">
                                        <?php _e('Exam', 'dff'); ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </aside>
                    <main class="main-content">
                        <div class="content">
                            <div class="progress-container">
                                <?php
                                // get_template_part('includes/courses/my-courses/parts-course/lesson', 'content');
                                ?>

                                <div class="tabs-content">
                                    <div class="progress-wrapper" id="tab1">
                                        <div class="module-header">
                                            <h2>Name of Module</h2>
                                        </div>
                                        <p>We don't have anything but happy trees here. See. We take the corner of the brush and let it play back-and-forth. You can work and carry-on and put lots of little happy things in here. Without washing the brush, I'm gonna go right into some Van Dyke Brown, some Burnt Umber, and a little bit of Sap Green. This is a fantastic little painting. The first step to doing anything is to believe you can do it. See it finished in your mind before you ever start.</p>
                                    </div>
                                    <div class="progress-wrapper" id="tab2">
                                        <h3>Second Tab 2</h3>
                                        <p>We don't have anything but happy trees here. See. We take the corner of the brush and let it play back-and-forth. You can work and carry-on and put lots of little happy things in here. Without washing the brush, I'm gonna go right into some Van Dyke Brown, some Burnt Umber, and a little bit of Sap Green. This is a fantastic little painting. The first step to doing anything is to believe you can do it. See it finished in your mind before you ever start.</p>
                                    </div>
                                    <div class="progress-wrapper" id="tab3">
                                        <h3>Second Tab 3</h3>
                                        <p>We don't have anything but happy trees here. See. We take the corner of the brush and let it play back-and-forth. You can work and carry-on and put lots of little happy things in here. Without washing the brush, I'm gonna go right into some Van Dyke Brown, some Burnt Umber, and a little bit of Sap Green. This is a fantastic little painting. The first step to doing anything is to believe you can do it. See it finished in your mind before you ever start.</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </main>

                </div>



        <?php
            } // end while
        } // end if
        wp_reset_postdata();
        ?>
    </div>
</section>