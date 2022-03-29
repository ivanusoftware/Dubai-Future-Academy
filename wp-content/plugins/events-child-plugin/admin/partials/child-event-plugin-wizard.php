<?php

$reset_key  = filter_input( INPUT_GET, 'reset', FILTER_SANITIZE_STRING );
$edomain    = filter_input( INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_STRING );

if ( 'yes' === $reset_key ) {
	update_option( 'dff_complete_wizard', false, false );
	?>
	<div class="notice notice-success is-dismissible reset_message">
		<p><strong><?php echo esc_html__( 'Plugin reset successfully!', 'events-child-plugin' ); ?></strong></p>
	</div>
	<?php
}

$dff_complete_wizard = get_option( 'dff_complete_wizard' );

if ( isset( $dff_complete_wizard ) && 'complete' === $dff_complete_wizard ) {
	wp_safe_redirect( '/wp-admin/admin.php?page=cep-settings' );
	exit;
}

$request_url    = EVENT_SOURCE_URL . '/wp-json/events/token';
$response_check = wp_remote_get( $request_url );

if ( 'false' !== $response_check['body'] ) {
	wp_safe_redirect( '/wp-admin/admin.php?page=cep-settings' );
	exit;
}

?>

<div class="image_status" style="display: none;">
	<div class="loader-wrapper">
		<span class="loader-inner"><img class="loader" alt="loading-svg" src=<?php echo esc_url( EVENT_PLUGIN_URL . 'admin/images/spinner-2x.svg' ); ?>></span>
	</div>
</div>
<div class="container" id="wizard-main">
	<div class="row">
		<div class="col-md-12">
			<div class="wizard-intro">
				<h1><?php echo esc_html__( 'Welcome to Events Setting Wizard!', 'events-child-plugin' ); ?></h1>
			</div>
		</div>
		<div class="col-md-12">
			<div class="wizard">
				<div class="wizard-inner">
					<ul class="nav nav-tabs" role="tablist">

						<li role="presentation" class="active">
							<a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Enter Authentication Token" aria-label="wizard-tab">
								<span class="round-tab"><?php echo esc_html__( '1', 'events-child-plugin' ); ?></span>
							</a>
						</li>

						<li role="presentation" class="disabled">
							<a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Language" aria-label="wizard-tab">
								<span class="round-tab"><?php echo esc_html__( '2', 'events-child-plugin' ); ?></span>
							</a>
						</li>
						<li role="presentation" class="disabled">
							<a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="Select Events Categories & Tags" aria-label="wizard-tab">
								<span class="round-tab"><?php echo esc_html__( '3', 'events-child-plugin' ); ?></span>
							</a>
						</li>

						<li role="presentation" class="disabled">
							<a href="#step4" data-toggle="tab" aria-controls="step4" role="tab" title="How Many Event Data Import?" aria-label="wizard-tab">
								<span class="round-tab"><?php echo esc_html__( '4', 'events-child-plugin' ); ?></span>
							</a>
						</li>
					</ul>
				</div>

				<form role="form">
					<div class="tab-content">
						<div class="tab-pane active" role="tabpanel" id="step1">
							<div class="bs-calltoaction bs-calltoaction-primary">
								<div class="row">
									<div class="col-md-12 cta-contents">
										<h2 class="cta-title"><?php echo esc_html__( 'Enter Authentication Token', 'events-child-plugin' ); ?></h2>
										<div class="oauth_token_section">
											<label><?php echo esc_html__( 'OAuth token', 'events-child-plugin' ); ?></label>
											<div class="button-wrap">
												<label for="enter_oauth_token"><span style="display: none">OAuth token</span><input type="text" id="enter_oauth_token" aria-label="enter_oauth_token" class="enter_oauth_token" placeholder="Enter OAuth token" value=""><span class="error_token_message" style="color: red; margin-left: 5px; display: none;"><?php echo esc_html__( 'Invalid Token!', 'events-child-plugin' ); ?></span></label>
												<input type="hidden" name="current_site_url" class="current_site_url" value="<?php echo esc_attr( $edomain ); ?>">
											</div>
										</div>
									</div>
								 </div>
							</div>
							<ul class="list-inline pull-right">
								<li><button type="button" id="wizard_validate_token" class="btn btn-primary next-step"><?php echo esc_html__( 'Save & Next >', 'events-child-plugin' ); ?></button></li>
							</ul>
						</div>
						<div class="tab-pane" role="tabpanel" id="step2">
							<div class="bs-calltoaction bs-calltoaction-info">
								<div class="row">
									<div class="col-md-12 cta-contents">
										<h2 class="cta-title"><?php echo esc_html__( 'Select a language', 'events-child-plugin' ); ?></h2>
										<div class="cta-desc">
											<input type="radio" id="arabic" name="language" value="arabic">
											<label for="arabic"><?php echo esc_html__( 'Arabic', 'events-child-plugin' ); ?></label>
											<input type="radio" id="english" name="language" value="english">
											<label for="english"><?php echo esc_html__( 'English', 'events-child-plugin' ); ?></label>
										</div>
									</div>
								 </div>
							</div>
							<ul class="list-inline pull-right">
								<li><button type="button" class="btn btn-default prev-step"><?php echo esc_html__( 'Back', 'events-child-plugin' ); ?></button></li>
								<li><button type="button" id="wizard_language_select" class="btn btn-primary next-step"><?php echo esc_html__( 'Save & Next >', 'events-child-plugin' ); ?></button></li>
							</ul>
						</div>
						<div class="tab-pane" role="tabpanel" id="step3">
							<div class="bs-calltoaction bs-calltoaction-success">
								<div class="row">
									<div class="col-md-12 cta-contents">
										<h2 class="cta-title"><?php echo esc_html__( 'Select Events Categories & Tags', 'events-child-plugin' ); ?></h2>
										<div class="select_event_categories_tags">

										</div>
									</div>
								 </div>
							</div>
							<ul class="list-inline pull-right">
								<li><button type="button" class="btn btn-default prev-step"><?php echo esc_html__( 'Back', 'events-child-plugin' ); ?></button></li>
								<li><button type="button" id="wizard_select_cat_tag" class="btn btn-primary btn-info-full next-step"><?php echo esc_html__( 'Save & Next >', 'events-child-plugin' ); ?></button></li>
							</ul>
						</div>
						<div class="tab-pane" role="tabpanel" id="step4">
							<div class="bs-calltoaction bs-calltoaction-info">
								<div class="row">
									<div class="col-md-12 cta-contents">
										<h2 class="cta-title"><?php echo esc_html__( 'How Many Events Data Import?', 'events-child-plugin' ); ?></h2>
										<div class="cta-desc">
											<div class="number_of_events">
												<span>Kindly enter total number of events to import <input type="number" name="event_number" class="event_number"> OR <input type="checkbox" name="all_events" class="all_events" > All </span>
											</div>
										</div>
									</div>

								</div>
							</div>
							<ul class="list-inline pull-right">
								<li><button type="button" class="btn btn-default prev-step"><?php echo esc_html__( 'Back', 'events-child-plugin' ); ?></button></li>
								<li><button type="button" id="wizard_sync_button" class="btn btn-primary next-step"><?php echo esc_html__( 'Sync Now', 'events-child-plugin' ); ?></button></li>
							</ul>
						</div>
						<div class="clearfix"></div>
					</div>
				</form>
			</div>
		</div>
   </div>
</div>

<div class="wizard-thank-you" style="display: none;">
	<div class="row">
		<div class="col-md-12">
			<div class="wizard-thank-you-inner">
				<div class="wizard-intro">
					<h1>Thank you for completing the wizard!</h1>
					<p class="wizard_dynamic_count"></p>
				</div>
				<div class="wizard-redirect-button-wrap">
					<a href="/wp-admin/edit.php?post_type=dff-events" class="btn btn-primary">Events</a>
					<a href="/wp-admin/edit.php?post_type=dff-events&page=cep-settings" class="btn btn-primary">Events Settings</a>
				</div>
			</div>
		</div>
	</div>
</div>
