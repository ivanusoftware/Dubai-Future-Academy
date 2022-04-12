<?php
get_header();
$posttype = get_post_type();

?>
<article class="single-course">
	<?php
	if (have_posts()) {
		while (have_posts()) {
			the_post();
			$learning_style = get_field_object('learning_style');
			$courses_format = get_field_object('courses_format');
			$courses_format_value = $courses_format['value'];
			$courses_format_label = $courses_format['choices'][$courses_format_value];
			$course_complexities = get_field_object('course_complexities');
			if ($img = get_image_by_id($courses->ID)) $src = $img[0];
			else $src = '';
			$home_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="17.333" height="16" viewBox="0 0 17.333 16">
			<path fill="currentColor" id="Icon_ionic-md-home" data-name="Icon ionic-md-home" d="M10.042,20.5V15.167h4V20.5h4.067v-8h2.6l-8.667-8-8.667,8h2.6v8Z" transform="translate(-3.375 -4.5)"></path>
		  </svg>';
	?>
			<section class="course-header-wrapper" style="background-image:url(<?php echo $src; ?>)">
				<div class="breadcrumbs-container">
					<ul class="single-breadcrumbs" data-translate="0">
						<li>
							<a href="<?php echo get_bloginfo('url'); ?>">
								<?php echo $home_icon; ?>
							</a>
						</li>
						<li><a href="/courses"><?php echo _e('Courses', 'dff'); ?></a></li>
						<li><?php echo _e(get_the_title(), 'dff'); ?></li>
					</ul>
					<a class="breadcrumbs-right breadcrumb-arrow"></a>
				</div>
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
					<button class="btn-course-primary apply-now"><?php echo _e('Apply Now', 'dff'); ?></button>
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
								</div>
								<div class="single-modules">
									<h2><?php echo _e('Modules', 'dff'); ?></h2>
									<!-- Accordion -->
									<div class="accordion">
										<?php
										if (have_rows('course_module_repeater')) :
											while (have_rows('course_module_repeater')) : the_row();

												
										?>
												<div class="accordion-item">
													<div class="accordion-head">
														<h6><?php echo _e('Module', 'dff') .' '. get_row_index(); ?></h6>
													</div>
													<div class="accordion-content">
														<?php
														if (have_rows('course_lesson_repeater')) :
														?>
															<ul>
																<?php
																while (have_rows('course_lesson_repeater')) : the_row();
																$lesson_name = get_sub_field('lesson_name');
																?>
																	<li><?php echo $lesson_name; ?></li>
																<?php
																endwhile;
																?>
															</ul>
														<?php
														else :
														// Do something...
														endif;
														?>
													</div>
												</div>
										<?php
											endwhile;
										else :
										endif;
										?>

									</div>
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
							</div>
						</main>
						<aside class="course-sidebar">
							<!-- <div class="single-sidebar"> -->
							<h2>Course Details</h2>
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
	?>
</article>
<?php include(get_template_directory() . '/includes/courses/other-course.inc.php'); ?>
<?php get_footer(); ?>