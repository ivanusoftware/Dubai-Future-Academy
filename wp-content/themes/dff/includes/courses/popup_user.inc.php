<div class="single-course-modal">
	<div class="modal">
		<div class="modal-overlay modal-toggle"></div>
		<div class="modal-wrapper modal-transition">
			<div class="modal-header">
				<div class="modal-close modal-toggle"></div>
			</div>
			<div class="modal-body">
				<div class="modal-content">
					<h2 class="modal-heading">Thank you!</h2>
					<p>The course “ Lorem Ipsum dolor sit amet” was added to you courses list.</p>
					<div class="buttons">
						<?php
						if (is_user_logged_in()) {
						?>
							<a href="<?php echo site_url('my-courses') ?>" class="btn-course-primary go-to-courses" course_id="<?php echo get_the_ID(); ?>"><?php _e('Go to my courses', 'dff'); ?></a>
							<button class="modal-toggle btn-course-primary"><?php _e('Close', 'dff'); ?></button>
						<?php
						} else {
						?>
							<a href="<?php echo site_url('my-courses') ?>" class="btn-course-primary register"><?php _e('Register', 'dff'); ?></a>
							<a class="login btn-course-primary"><?php _e('Log in', 'dff'); ?></a>
						<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>