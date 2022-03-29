<?php
get_header();


echo $posttype = get_post_type();

?>
<article class="single-course">
	<?php
	echo 'test';
	if (have_posts()) {
		while (have_posts()) {
			the_post();
		$learning_style = get_field_object('learning_style');
		 $value_learning_style = $learning_style['value'];
		 echo $label =  $value_learning_style['choices'][ $value_learning_style ];
		// print_r($learning_style);
		$courses_format = get_field('courses_format');
		$course_complexities = get_field('course_complexities');
	?>
			<section class="single-cource-content">
				<div class="container">
					<div class="columns">
						<main class="main-content">
							<div class="content">
								<h2>Description</h2>
								<?php the_content(); ?>
							</div>

						</main>
						<aside class="single-sidebar">
							<!-- <div class="single-sidebar"> -->
								<h2>Course Details</h2>
								<div class="sidebar-info">
									<h3><?php _e('Learning Style', 'dff'); ?></h3>
									<p><?php echo $learning_style; ?></p>
								</div>
								<div class="sidebar-info">
									<h3><?php _e('Couse format', 'dff'); ?></h3>
									<p><?php echo $courses_format; ?></p>
								</div>
								<div class="sidebar-info">
									<h3><?php _e('Complexities', 'dff'); ?></h3>
									<p><?php echo $course_complexities; ?></p>
								</div>
							<!-- </div> -->
						</aside>
					</div>
				</div>
			</section>
	<?php
		} // end while
	} // end if
	?>
</article>
<?php get_footer(); ?>