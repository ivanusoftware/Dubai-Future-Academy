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
			if ($img = get_image_by_id(get_the_ID())) $src = $img[0];
			else $src = '';
			$home_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="17.333" height="16" viewBox="0 0 17.333 16">
			<path fill="currentColor" id="Icon_ionic-md-home" data-name="Icon ionic-md-home" d="M10.042,20.5V15.167h4V20.5h4.067v-8h2.6l-8.667-8-8.667,8h2.6v8Z" transform="translate(-3.375 -4.5)"></path>
		  </svg>';
			// $current_user_id =  get_current_user_id();
	?>
			<section class="course-header-wrapper" style="background-image:url(<?php echo $src; ?>)">
				<div class="breadcrumbs-container">
					<ul class="single-breadcrumbs" data-translate="0">
						<li>
							<a href="<?php echo get_bloginfo('url'); ?>">
								<?php echo $home_icon; ?>
							</a>
						</li>
						<li><a href="<?php echo site_url('courses') ?>"><?php _e('Courses', 'dff'); ?></a></li>

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
					<?php
					$slug = get_post(get_the_ID());
					if ($_COOKIE['user'] && $_COOKIE['fid-is-loggedin']) {
						
						$dff_get_future_user_data =  json_decode(stripslashes($_COOKIE['user']));
						$future_user_id = $dff_get_future_user_data->id;
					}
					$future_courses_ids = future_user_courses_ids($future_user_id);

					if (!empty($future_courses_ids) && in_array(get_the_ID(), $future_courses_ids)) {

					?>
						<a href="<?php echo site_url('my-courses/') . $slug->post_name; ?>" class="btn-course-primary apply-now"><?php _e('Go to my courses', 'dff'); ?></a>
					<?php
					} else {
						$post_id_lang =  dff_get_id_parrent_lang(get_the_ID());
					?>

						<a href="#" class="btn-course-primary apply-now <?php echo $future_user_id ? 'go-to-courses' : 'modal-toggle'; ?> " course_id="<?php echo get_the_ID(); ?>" slug="<?php echo $slug->post_name; ?>" course_id_lang="<?php echo $post_id_lang; ?>"><?php echo _e('Join the course', 'dff'); ?></a>
					<?php
					}

					?>



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
								<div class="single-modules">
									<h2><?php echo _e('Modules', 'dff'); ?></h2>
									<!-- Accordion -->
									<div class="accordion">
										<?php
										if (have_rows('course_module_repeater')) :
											while (have_rows('course_module_repeater')) : the_row();
												$module_or_exam = get_sub_field('module_or_exam');
												if ($module_or_exam == 'module') {
										?>
													<div class="accordion-item">
														<div class="accordion-head">
															<h6><?php echo _e('Module', 'dff') . ' ' . get_row_index(); ?></h6>
														</div>
														<div class="accordion-content">
															<?php
															if (have_rows('course_lesson_repeater')) :
															?>
																<ul>
																	<?php
																	while (have_rows('course_lesson_repeater')) : the_row();
																		$lesson_name = get_sub_field('lesson_name');
																		$lesson_or_test = get_sub_field('lesson_or_test');
																	?>
																		<?php echo $lesson_or_test == 'lesson' ? '<li>' . $lesson_name . '</li>' : ''; ?>
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
												}
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
							<h2><?php echo _e('Course Details', 'dff'); ?></h2>
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
<?php include(get_template_directory() . '/includes/courses/popup_user.inc.php'); ?>
<?php include(get_template_directory() . '/includes/courses/other-course.inc.php'); ?>
<?php get_footer(); ?>