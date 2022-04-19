<?php

get_header();

$course_id = get_query_var( 'course_id' );
$current_user_id =  get_current_user_id();
$courses_array =  unserialize(get_user_meta($current_user_id, 'course_id_to_user', true));

if (in_array($course_id, $courses_array)) {
  
?>
<section class="my-courses-tabs" course-id="<?php echo $course_id; ?>">
    <div class="container">

        <header class="tabs-nav tabs-nav-my-courses">
            <ul>
                <li class="active"><a href="#tab1" tab-id="tab-1" course-id="<?php echo $course_id; ?>"><?php _e('About', 'dff'); ?></a></li>
                <li><a href="#tab2" tab-id="tab-2" course-id="<?php echo $course_id; ?>"><?php _e('Modules', 'dff'); ?></a></li>
                <li><a href="#tab3" tab-id="tab-3" course-id="<?php echo $course_id; ?>"><?php _e('My Progress', 'dff'); ?></a></li>
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


}else{
    wp_redirect(site_url('my-courses'));
}


get_footer();