<?php

get_header();

$course_id = get_query_var( 'course_id' );
$current_user_id =  get_current_user_id();
$courses_array =  unserialize(get_user_meta($current_user_id, 'course_id_to_user', true));

if (in_array($course_id, $courses_array)) {
  
?>
<section class="my-courses-tabs" id="post-course-<?php echo $course_id; ?>">
    <div class="container">

        <header class="tabs-nav">
            <ul>
                <li class="active"><a href="#tab1"><?php _e('About', 'dff'); ?></a></li>
                <li><a href="#tab2"><?php _e('Modules', 'dff'); ?></a></li>
                <li><a href="#tab3"><?php _e('My Progress', 'dff'); ?></a></li>
            </ul>
        </header>
    </div>

        <div class="tabs-content my-courses-tabs-content">
            <div class="tab-wrapper" id="tab1">
            <?php get_template_part('includes/courses/my-courses/parts-course/about', 'course'); ?>
            </div>
            <div id="tab2" class="tab-wrapper">
          
            <?php get_template_part('includes/courses/my-courses/parts-course/modules', 'course'); ?>
            </div>
            <div id="tab3" class="tab-wrapper">
           
            <?php get_template_part('includes/courses/my-courses/parts-course/my-progress', 'course'); ?>
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