<div class="register-login-module modal-popup">
	<div class="modal">
		<div class="modal-overlay modal-toggle"></div>
		<div class="modal-wrapper modal-transition">
			<div class="modal-header">
				<div class="modal-close modal-toggle"></div>
			</div>
			<div class="modal-body">
				<div class="modal-content register-login-tab">
					<header class="tabs-nav">
						<ul>
							<li class="active"><a href="#tab1"><?php _e('Login', 'dff'); ?></a></li>
							<li><a href="#tab2"><?php _e('Register', 'dff'); ?></a></li>
						</ul>
					</header>
					<h2 class="modal-heading"><?php _e('Future ID', 'dff'); ?></h2>
					<div class="tabs-content">
						<div class="tab-wrapper" id="tab1">
							<?php echo do_shortcode("[login_form]"); ?>
						</div>
						<div id="tab2" class="tab-wrapper">
							<?php echo do_shortcode("[register_form]");	?>
						</div>
					</div>
					<?php
					// echo do_shortcode("[jay-login-form]");
					// echo do_shortcode("[loginform]");
					// echo do_shortcode("[register_form]");
					// echo do_shortcode("[login_form]");
					?>
				</div>
			</div>
		</div>
	</div>
</div>