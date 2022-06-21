<div class="leave-course-popup modal-popup">
	<div class="modal">
		<div class="modal-overlay modal-toggle"></div>
		<div class="modal-wrapper modal-transition">
			<div class="modal-header">
				<div class="modal-close modal-toggle"></div>
			</div>
			<div class="modal-body">
				<div class="modal-content">
					<?php
					//if (is_user_logged_in()) {
					?>
						<h2 class="modal-heading">
                            <?php echo get_field('leave_course_modal_title', 'option'); ?></h2>
						<p><?php echo get_field('leave_course_modal_content', 'option'); ?></p>
					<?php
					//}
					?>
					<div class="buttons">
						<?php
						if (is_user_logged_in()) {
							$post_id_lang =  dff_get_id_parrent_lang($course_id);
						?>
							<a href="<?php echo site_url('my-courses') ?>" class="btn-course-primary leave-course" course-id="<?php echo $course_id; ?>" course_id_lang="<?php echo $post_id_lang; ?>"><?php _e('Leave course', 'dff'); ?></a>
							<button class="btn-course-primary modal-toggle"><?php _e('Cancel', 'dff'); ?></button>
						<?php
						} 
                        ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>