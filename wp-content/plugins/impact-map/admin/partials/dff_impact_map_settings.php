<?php
function dff_impact_map_settings_function() {

	$submit_general_settings = filter_input( INPUT_POST, 'submit_general_settings', FILTER_SANITIZE_STRING );
	$submit_general_settings = isset( $submit_general_settings ) ? esc_html( $submit_general_settings ) : '';

	if ( !empty( $submit_general_settings ) ) {

		$project_google_map_api = filter_input( INPUT_POST, 'project_google_map_api', FILTER_SANITIZE_STRING );
		$project_google_map_api = isset( $project_google_map_api ) ? esc_html( $project_google_map_api ) : '';
		update_option( 'project_google_map_api_key', $project_google_map_api, false );

		$arabic_language_checkbox = filter_input( INPUT_POST, 'arabic_language_checkbox', FILTER_SANITIZE_STRING );
		$arabic_language_checkbox = isset( $arabic_language_checkbox ) ? esc_html( $arabic_language_checkbox ) : '';
		update_option( 'arabic_language_checkbox', $arabic_language_checkbox, false );

		$map_center_lat = filter_input( INPUT_POST, 'map_center_latitude', FILTER_SANITIZE_STRING );
		$map_center_long = filter_input( INPUT_POST, 'map_center_longitude', FILTER_SANITIZE_STRING );
		$impact_map_center = array( 'lat' => $map_center_lat, 'long' => $map_center_long );
		update_option( 'impact_map_center_lat_long', $impact_map_center, false );

		$map_default_zoom = filter_input( INPUT_POST, 'map_default_zoom', FILTER_VALIDATE_INT );
		update_option( 'impact_map_default_zoom', $map_default_zoom, false );
	}

	$submit_popup_settings = filter_input( INPUT_POST, 'submit_popup_settings', FILTER_SANITIZE_STRING );
	$submit_popup_settings = isset( $submit_popup_settings ) ? esc_html( $submit_popup_settings ) : '';

	if ( !empty( $submit_popup_settings ) ) {

		$project_popup_title = filter_input( INPUT_POST, 'project_popup_title', FILTER_SANITIZE_STRING );
		$project_popup_title = isset( $project_popup_title ) ? esc_html( $project_popup_title ) : '';
		update_option( 'project_popup_title', $project_popup_title, false );

		$project_popup_description = filter_input( INPUT_POST, 'project_popup_description', FILTER_SANITIZE_STRING );
		$project_popup_description = isset( $project_popup_description ) ? esc_html( $project_popup_description ) : '';
		update_option( 'project_popup_description', $project_popup_description, false );

		$project_popup_button_title = filter_input( INPUT_POST, 'project_popup_button_title', FILTER_SANITIZE_STRING );
		$project_popup_button_title = isset( $project_popup_button_title ) ? esc_html( $project_popup_button_title ) : '';
		update_option( 'project_popup_button_title', $project_popup_button_title, false );

		$popup_background_image = filter_input( INPUT_POST, 'popup_background_image', FILTER_SANITIZE_STRING );
		$popup_background_image = isset( $popup_background_image ) ? esc_html( $popup_background_image ) : '';
		update_option( 'popup_background_image', $popup_background_image, false );

		$popup_mobile_background_image = filter_input( INPUT_POST, 'popup_mobile_background_image', FILTER_SANITIZE_STRING );
		$popup_mobile_background_image = isset( $popup_mobile_background_image ) ? esc_html( $popup_mobile_background_image ) : '';
		update_option( 'popup_mobile_background_image', $popup_mobile_background_image, false );

		$popup_logo_image = filter_input( INPUT_POST, 'popup_logo_image', FILTER_SANITIZE_STRING );
		$popup_logo_image = isset( $popup_logo_image ) ? esc_html( $popup_logo_image ) : '';
		update_option( 'popup_logo_image', $popup_logo_image, false );

	}

	$project_google_map_api = get_option( 'project_google_map_api_key' );
	
	$project_popup_title = get_option( 'project_popup_title' );
	$project_popup_description = get_option( 'project_popup_description' );
	$project_popup_button_title = get_option( 'project_popup_button_title' );

	$popup_background_image = get_option( 'popup_background_image' );
	$popup_mobile_background_image = get_option( 'popup_mobile_background_image' );
	$popup_logo_image = get_option( 'popup_logo_image' );
	
	$arabic_language_checkbox = get_option( 'arabic_language_checkbox' );
	
	?>
   <div class="wrap news_master_settings_section">
		
	<?php
		if( !empty( $submit_general_settings ) || !empty( $submit_popup_settings ) ) {
			?>
				<div class="notice notice-success is-dismissible">
					<p><strong><?php echo esc_html__( "Settings saved.", "news-child-plugin" ); ?></strong></p>
				</div>
			<?php
		}
	?>
	
		<h1>Impact Map</h1>
			<div class="event_section">

			<!-- Tab links -->
			<div class="settings_container">
				<div class="tab">
					<?php 
					$opup_settings = filter_input( INPUT_GET, 'update', FILTER_SANITIZE_STRING );
					$opup_settings = isset( $opup_settings ) ? esc_html( $opup_settings ) : '';

					if( !empty( $opup_settings ) && 'true' === $opup_settings ) {
						?>
						<span class="tablinks" onclick="openSettingsTab(event, 'general_setting')">General Setting</span>
						<span class="tablinks active" onclick="openSettingsTab(event, 'introduction-popup')">Introduction Popup</span>
						<?php
					} else {
						?>
						<span class="tablinks active" onclick="openSettingsTab(event, 'general_setting')">General Setting</span>
						<span class="tablinks" onclick="openSettingsTab(event, 'introduction-popup')">Introduction Popup</span>
						<?php
					}
					?>
					
				</div>

				<!-- Tab content -->
				<div id="general_setting" class="tabcontent" <?php if( empty( $opup_settings ) && 'true' !== $opup_settings ) {echo 'style="display: block;"'; } ?>>
					<form method="post" action="<?php echo site_url(); ?>/wp-admin/admin.php?page=impact-map">
					<div class="page_section send_grid_credentials">
						<h3>Google Map API Key</h3>
					   <div class="add_sites_group">
							<input type="text" class="project_google_map_api" name="project_google_map_api" value="<?php echo esc_attr( $project_google_map_api ); ?>">
							<span class="tool-tip" data-tip="Please enter Google Map API Key here."><i>!</i></span>
						</div>
						
					</div>
					
					<div class="page_section send_grid_credentials arabic_language_checkbox_wrapper">
						<h3>Switch to Arabic Language</h3>

						<label>If this plugin install in Arabic website then please tick mark this check box: </label>
						<input type="checkbox" id="switch" name="arabic_language_checkbox" class="arabic_language_checkbox" value="true" <?php if( isset( $arabic_language_checkbox ) && !empty
						( $arabic_language_checkbox ) ) { echo "checked"; } ?> />
					
					</div>

					<input type="submit" name="submit_general_settings" class="button button-primary" value="Save Changes">
					</form>
				</div>

				<div id="introduction-popup" class="tabcontent"  <?php if( !empty( $opup_settings ) && 'true' === $opup_settings ) {echo 'style="display: block;"'; } ?>>
					<form method="post" action="<?php echo site_url(); ?>/wp-admin/admin.php?page=impact-map&update=true">
						
						<div class="page_section send_grid_credentials">
							<h3>Set Background Image</h3>
							<div class="button-image-wrapper">
								<div class="background_image_view">
									<?php 
										if( isset( $popup_background_image ) && !empty( $popup_background_image ) ) {
											?>
												<img class="term-image" src="<?php echo esc_url( $popup_background_image ); ?>" alt="popup_background_image" />
											<?php
										}
									?>
								</div>
								<a href="#" class="popup_background_image button button-secondary"><?php if( isset( $popup_background_image ) && !empty( $popup_background_image ) ) { esc_html_e('Edit Image'); } else { esc_html_e('Upload Image'); } ?></a><span class="tool-tip" data-tip="Please select background image to display in introduction popup."><i>!</i></span>
							</div>
							<input type="hidden" name="popup_background_image" id="popup_background_image" value="<?php echo esc_attr( $popup_background_image ); ?>" style="width:500px;" />
						
						</div>

						<div class="page_section send_grid_credentials">
							<h3>Set Mobile Background Image</h3>
							<div class="button-image-wrapper">
								<div class="background_mobile_image_view">
									<?php 
										if( isset( $popup_mobile_background_image ) && !empty( $popup_mobile_background_image ) ) {
											?>
												<img class="term-image" src="<?php echo esc_url( $popup_mobile_background_image ); ?>" alt="popup_mobile_background_image" />
											<?php
										}
									?>
								</div>
								<a href="#" class="popup_mobile_background_image button button-secondary"><?php if( isset( $popup_mobile_background_image ) && !empty( $popup_mobile_background_image ) ) { esc_html_e('Edit Image'); } else { esc_html_e('Upload Image'); } ?></a><span class="tool-tip" data-tip="Please select background image to display in introduction popup."><i>!</i></span>
							</div>
							<input type="hidden" name="popup_mobile_background_image" id="popup_mobile_background_image" value="<?php echo esc_attr( $popup_mobile_background_image ); ?>" style="width:500px;" />
						
						</div>

						<div class="page_section send_grid_credentials">
							<h3>Set Logo Image</h3>
							<div class="button-image-wrapper">
								<div class="logo_image_view">
									<?php 
										if( isset( $popup_logo_image ) && !empty( $popup_logo_image ) ) {
											?>
												<img class="term-image" src="<?php echo esc_url( $popup_logo_image ); ?>" alt="popup_logo_image" />
											<?php
										}
									?>
								</div>
								<a href="#" class="popup_logo_image button button-secondary"><?php if( isset( $popup_logo_image ) && !empty( $popup_logo_image ) ) { esc_html_e('Edit Image'); } else { esc_html_e('Upload Image'); } ?></a><span class="tool-tip" data-tip="Please select logo image to display in introduction popup."><i>!</i>
							</div>
							<input type="hidden" name="popup_logo_image" id="popup_logo_image" value="<?php echo esc_attr( $popup_logo_image ); ?>" style="width:500px;" />
							
						</div>

						<div class="page_section send_grid_credentials">
							<h3>Set Popup Content</h3>
								<label for="popup_title"><span>Title</span><input type="text" id="popup_title" name="project_popup_title" class="popup_title" value="<?php echo esc_attr( $project_popup_title ); ?>"><span class="tool-tip" data-tip="Please enter introduction popup title."><i>!</i></label>

								<label for="popup_description"><span>Description</span><textarea id="popup_description" name="project_popup_description" class="popup_description"><?php echo esc_html( $project_popup_description ); ?></textarea><span class="tool-tip" data-tip="Please enter introduction popup description."><i>!</i></label>

								<label for="popup_button_title"><span>Button Title</span><input type="text" id="popup_button_title" name="project_popup_button_title" class="popup_button_title" value="<?php echo esc_attr( $project_popup_button_title ); ?>"><span class="tool-tip" data-tip="Please enter introduction popup button title."><i>!</i></label>
						</div>
						<input type="submit" name="submit_popup_settings" class="button button-primary" value="Save Changes">
					</form>
				</div>
				
			</div>
		</div>
	</div>
	<?php

}
