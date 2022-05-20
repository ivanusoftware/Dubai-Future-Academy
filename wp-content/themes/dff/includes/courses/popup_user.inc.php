<div class="single-course-modal modal-popup">
	<div class="modal">
		<div class="modal-overlay modal-toggle"></div>
		<div class="modal-wrapper modal-transition">
			<div class="modal-header">
				<div class="modal-close modal-toggle"></div>
			</div>
			<div class="modal-body">
				<div class="modal-content">
					<h2 class="modal-heading"><?php echo get_field('popup_modal_title', 'option'); ?></h2>
					<p><?php echo get_field('popup_modal_content', 'option'); ?></p>
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