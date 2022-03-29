<?php
global $wpdb;

$validate_token_true = get_option( 'validate_token_true' );
$edomain             = filter_input( INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_STRING );
$db_table_name       = $wpdb->prefix . 'dff_history';
$blog_url            = get_site_url();

?>
<div class="event_setting_page_loader">
	<div class="image_status" style="display: none;">
		<div class="loader-wrapper">
			<span class="loader-inner"><img class="loader" alt="loading-svg" src=<?php echo esc_url( EVENT_PLUGIN_URL . 'admin/images/spinner-2x.svg' ); ?>></span>
		</div>
	</div>
</div>

<div class="wrap news_master_settings_section">
	<?php
	$update = filter_input( INPUT_GET, 'update', FILTER_SANITIZE_STRING );
	$update = isset( $update ) ? $update : '';
	if ( isset( $update ) && 'yes' === $update ) {
		?>
		<div class="notice notice-success is-dismissible">
			<p><strong><?php echo esc_html__( 'Event categories & tags updated successfully!', 'events-child-plugin' ); ?></strong></p>
		</div>
		<?php
	}


	$request_url    = EVENT_SOURCE_URL . '/wp-json/events/token';
	$response_check = wp_remote_get( $request_url );

	if ( 'false' === $response_check['body'] ) {
		$flag_api = true;
	} else {
		?>
		<div class="notice notice-error">
			<p><strong>An error has occurred and the events API cannot be reached. Please reach out to the Dubai Future Foundation IT team for support</strong></p>
		</div>
		<?php
		$flag_api = false;
	}

	?>
	<h1><?php echo esc_html__( 'Events Settings', 'events-child-plugin' ); ?></h1>
	<div class="event_section">
		<?php
		if ( isset( $update ) && 'yes' === $update ) {
			?>
			<div class="tab">
				<span class="tablinks" onclick="openSettingsTab(event, 'dashboard')"><?php echo esc_html__( 'Dashboard', 'events-child-plugin' ); ?></span>
				<span class="tablinks" onclick="openSettingsTab(event, 'history')"><?php echo esc_html__( 'History', 'events-child-plugin' ); ?></span>
				<span class="tablinks active" onclick="openSettingsTab(event, 'config')"><?php echo esc_html__( 'Configuration', 'events-child-plugin' ); ?></span>
			</div>
			<?php
		} else {
			?>
			<div class="tab">
				<span class="tablinks active" onclick="openSettingsTab(event, 'dashboard')"><?php echo esc_html__( 'Dashboard', 'events-child-plugin' ); ?></span>
				<span class="tablinks" onclick="openSettingsTab(event, 'history')"><?php echo esc_html__( 'History', 'events-child-plugin' ); ?></span>
				<?php
				if ( false !== $flag_api ) {
					?>
					<span class="tablinks" onclick="openSettingsTab(event, 'config')"><?php echo esc_html__( 'Configuration', 'events-child-plugin' ); ?></span>
					<?php
				}
				?>
			</div>
			<?php
		}
		?>

		<div class="tabcontent" id="dashboard" 
		<?php
		if ( empty( $update ) && 'yes' !== $update ) {
			echo 'style="display: block;"'; }
?>
>
			<div class="page_section oauth_token_dashboard">
				<div class="two_col_layout">
					<div class="section_col">
						<h3><?php echo esc_html__( 'At a Glance', 'events-child-plugin' ); ?></h3>
						<?php
						$history_table_last_three_result = $wpdb->get_results( $wpdb->prepare( 'SELECT `id`, `total_event`, `status`, `end_time` FROM %1sdff_history ORDER BY id DESC LIMIT 0,3', $wpdb->prefix ), ARRAY_A );
						/**
						 * Get total count of events.
						 */
						$count_events = wp_count_posts( 'dff-events' );
						$total_events = $count_events->publish;

						/**
						 * Get total count of events category.
						 */
						$event_category_count = 0;
						$terms_events_cats    = get_terms(
							array(
								'taxonomy'   => 'events_categories',
								'hide_empty' => false,
							)
						);
						if ( isset( $terms_events_cats ) && ! empty( $terms_events_cats ) ) {
							foreach ( $terms_events_cats as $terms_events_cats_value ) {
								$event_category_count ++;
							}
						}

						/**
						 * Get total count of events category.
						 */
						$event_tags_count  = 0;
						$terms_events_tags = get_terms(
							array(
								'taxonomy'   => 'events_tags',
								'hide_empty' => false,
							)
						);
						if ( isset( $terms_events_tags ) && ! empty( $terms_events_tags ) ) {
							foreach ( $terms_events_tags as $terms_events_tags_value ) {
								$event_tags_count ++;
							}
						}

						?>
						<ul>
							<li><a href="/wp-admin/edit.php?post_type=dff-events"><span><?php echo esc_html( $total_events ); ?></span> Events</a></li>
							<li><a href="/wp-admin/edit-tags.php?taxonomy=events_categories&post_type=dff-events"><span><?php echo esc_html( $event_category_count ); ?></span> Categories</a></li>
							<li><a href="/wp-admin/edit-tags.php?taxonomy=events_tags&post_type=dff-events"><span><?php echo esc_html( $event_tags_count ); ?></span> Tags</a></li>
						</ul>
					</div>
					<div class="section_col">
						<h3><?php echo esc_html__( 'History Summary', 'events-child-plugin' ); ?></h3>
						<ul>
							<?php
							if ( isset( $history_table_last_three_result ) && ! empty( $history_table_last_three_result ) ) {
								foreach ( $history_table_last_three_result as $history_table_last_three_result_data ) {
									$event_time = gmdate( 'd-M-Y | h:i:s A', strtotime( $history_table_last_three_result_data['end_time'] ) );
									?>
									<li><?php echo esc_html( $history_table_last_three_result_data['total_event'] ); ?> Events (Sync <?php echo esc_html__( $history_table_last_three_result_data['status'], 'events-child-plugin' ); ?>) - <?php echo esc_html( $event_time ); ?></li>
									<?php
								}
							}
							?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="tabcontent" id="history">
			<div class="event_history">
				<table id="event_history">
					<thead>
					<tr>
						<th>#</th>
						<th>Date & Time</th>
						<th>Events Synced</th>
						<th>Status</th>
						<th>Sync Type</th>
					</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
			</div>
		</div>
		<?php
		if ( false !== $flag_api ) {
			?>
			<div class="tabcontent" id="config" 
			<?php
			if ( isset( $update ) && 'yes' === $update ) {
				echo ' style="display: block;"'; }
?>
>
				<input type="hidden" name="site_url" class="site_url" value="<?php echo esc_attr( $blog_url ); ?>">
				<div class="page_section oauth_token_config">
					<h3><?php echo esc_html__( 'OAuth token', 'events-child-plugin' ); ?></h3>
					<div class="oauth_token_field">
						<input type="text" class="enter_oauth_token" placeholder="Update OAuth token" value="<?php echo esc_attr( $validate_token_true ); ?>" <?php empty( $validate_token_true ) ? '' : 'readonly'; ?>>
						<input type="button" name="submit" id="submit" class="enter_oauth_token_button button-primary" value="<?php echo esc_attr( __( 'Validate Token', 'events-child-plugin' ) ); ?>">
					</div>
					<div class="oauth_token_messages">
						<i class="fa fa-times-circle fa-2x" id="incorrect_token" style="display: none"></i>
						<span class="error_message" style="display: none;"><?php echo esc_html__( 'Invalid OAuth token. Please check your entry and try again.', 'events-child-plugin' ); ?></span>
						<?php
						if ( isset( $validate_token_true ) && ! empty( $validate_token_true ) ) {
							?>
							<i class="fa fa-check-circle fa-2x" id="correct_token" aria-hidden="true"></i>
							<?php
						} else {
							?>
							<i class="fa fa-check-circle fa-2x" id="correct_token" aria-hidden="true" style="display: none;"></i>
							<?php
						}
						?>
						<?php
						if ( isset( $validate_token_true ) && ! empty( $validate_token_true ) ) {
							?>
							<span class="success_message"><?php echo esc_html__( 'OAuth token has been validated successfully.', 'events-child-plugin' ); ?></span>
							<?php
						} else {
							?>
							<span class="success_message" style="display: none;"><?php echo esc_html__( 'OAuth token has been validated successfully.', 'events-child-plugin' ); ?></span>
							<?php
						}
						?>
						<input type="hidden" name="current_site_url" class="current_site_url" value="<?php echo esc_attr( $edomain ); ?>">
					</div>
				</div>

				<div class="sync_now_button page_section manual_sync_section">
					<h3><?php echo esc_html__( 'Manual Sync', 'events-child-plugin' ); ?></h3>
					<input type="submit" name="sync_now_button" id="sync_now_button" class="sync_now_button button-primary" value="<?php echo esc_attr( __( 'Sync Now', 'events-child-plugin' ) ); ?>">
					<p class="sync_now_dynamic_value" style="display: none"></p>
					<label class="settings-tooltip"><i><?php echo esc_html__( 'Sync events manually which are associated with below selected categories and tags.', 'events-child-plugin' ); ?></i></label>
				</div>

				<div class="page_section cats_tags_section">
					<h3><?php echo esc_html__( 'Update Categories & Tags', 'events-child-plugin' ); ?></h3>
					<?php
					if ( isset( $validate_token_true ) && ! empty( $validate_token_true ) ) {
						$language = get_option( 'dff_language' );
						$dff_cat  = str_replace( '&cats=', '', get_option( 'dff_cat' ) );
						$dff_tags = str_replace( '&tags=', '', get_option( 'dff_tags' ) );

						$dff_cat  = explode( ',', $dff_cat );
						$dff_tags = explode( ',', $dff_tags );

						?>
						<div class="import_settings">
							<input type="hidden" name="language" value="<?php echo esc_attr( $language ); ?>">

							<div class="cat_tags_outer">
								<div class="category_section category_tags_section">
									<div class="category_block">
										<h4><?php echo esc_html__( 'Events Categories', 'events-child-plugin' ); ?></h4>
										<?php
										$url        = EVENT_SOURCE_URL . '/wp-json/events/cats';
										$categories = $this->dff_events_api_call( $url );

										$categories_data_array = (array) $categories->data;
										$parent_array          = array();
										$child_array           = array();
										$final_array           = array();

										foreach ( $categories_data_array as $key => $categories_data_array_value ) {

											if ( 0 === $categories_data_array_value->parent ) {
												$parent_array[ $key ]          = (array) $categories_data_array_value;
												$parent_array[ $key ]['child'] = array();
											} else {
												$child_array[ $key ] = (array) $categories_data_array_value;
											}
										}

										foreach ( $child_array as $child_key => $child_array_value ) {
											$parent_array[ $child_array_value['parent'] ]['child'][ $child_key ] = $child_array_value;
										}

										if ( isset( $parent_array ) && ! empty( $parent_array ) ) {
											?>
											<ul id="events_categorieschecklist" class="categorychecklist form-no-clear">
												<?php
												foreach ( $parent_array as $key => $parent_array_value ) {
													?>
													<li><label class="post_type_lable" for="<?php echo esc_attr( $parent_array_value['name'] ); ?>"><input class="parent_category" name="emp_english_category[]" 
																										<?php
																										if ( in_array( (string) $key, $dff_cat, true ) ) {
																											echo esc_html( 'checked' ); }
?>
 type="checkbox" id="<?php echo esc_attr( $parent_array_value['name'] ); ?>" value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $parent_array_value['name'] ); ?></label></li>
													<?php
													if ( isset( $parent_array_value['child'] ) && ! empty( $parent_array_value['child'] ) ) {
														?>
														<ul class="event_child_category">
															<?php
															foreach ( $parent_array_value['child'] as $key => $event_child_value ) {
																?>
																<li><label class="post_type_lable" for="<?php echo esc_attr( $event_child_value['name'] ); ?>"><input class="child_category" name="emp_english_category[]" 
																													<?php
																													if ( in_array( (string) $key, $dff_cat, true ) ) {
																														echo esc_html( 'checked' ); }
?>
 type="checkbox" id="<?php echo esc_attr( $event_child_value['name'] ); ?>" value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $event_child_value['name'] ); ?></label></li>
																<?php
															}
															?>
														</ul>
														<?php
													}
												}
												?>
											</ul>
											<?php
										}

										?>
									</div>
									<div class="tags_block">
										<h4><?php echo esc_html__( 'Events Tags', 'events-child-plugin' ); ?></h4>
										<?php
										$url  = EVENT_SOURCE_URL . '/wp-json/events/tags';
										$tags = $this->dff_events_api_call( $url );

										$categories_data_array = (array) $tags->data;
										$parent_array          = array();
										$child_array           = array();
										$final_array           = array();

										foreach ( $categories_data_array as $key => $categories_data_array_value ) {

											if ( 0 === $categories_data_array_value->parent ) {
												$parent_array[ $key ]          = (array) $categories_data_array_value;
												$parent_array[ $key ]['child'] = array();
											} else {
												$child_array[ $key ] = (array) $categories_data_array_value;
											}
										}

										foreach ( $child_array as $child_key => $child_array_value ) {
											$parent_array[ $child_array_value['parent'] ]['child'][ $child_key ] = $child_array_value;
										}

										if ( isset( $parent_array ) && ! empty( $parent_array ) ) {
											?>
											<ul id="events_tagschecklist" class="tagschecklist form-no-clear">
												<?php
												foreach ( $parent_array as $key => $parent_array_value ) {
													?>
													<li><label class="post_type_lable" for="<?php echo esc_attr( $parent_array_value['name'] ); ?>"><input class="parent_category" name="emp_english_tags[]" 
																										<?php
																										if ( in_array( (string) $key, $dff_tags, true ) ) {
																											echo esc_html( 'checked' ); }
?>
 type="checkbox" id="<?php echo esc_attr( $parent_array_value['name'] ); ?>" value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $parent_array_value['name'] ); ?></label></li>
													<?php
													if ( isset( $parent_array_value['child'] ) && ! empty( $parent_array_value['child'] ) ) {
														?>
														<ul class="event_child_category">
															<?php
															foreach ( $parent_array_value['child'] as $key => $event_child_value ) {
																?>
																<li><label class="post_type_lable" for="<?php echo esc_attr( $event_child_value['name'] ); ?>"><input class="child_category" name="emp_english_tags[]" 
																													<?php
																													if ( in_array( (string) $key, $dff_tags, true ) ) {
																														echo esc_attr( 'checked' ); }
?>
 type="checkbox" id="<?php echo esc_attr( $event_child_value['name'] ); ?>" value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $event_child_value['name'] ); ?></label></li>
																<?php
															}
															?>
														</ul>
														<?php
													}
												}
												?>
											</ul>
											<?php
										}
										?>
									</div>
								</div>
							</div>
						</div>
						<?php
					}
					?>
					<input type="submit" name="update_cat_tags_settings" id="update_cat_tags_settings" class="update_cat_tags_settings button-primary" value="<?php echo esc_attr( __( 'Update', 'events-child-plugin' ) ); ?>"><br>
					<label class="settings-tooltip"><i><?php echo esc_html__( 'Update categories and tags in the next automatic sync.', 'events-child-plugin' ); ?></i></label>
				</div>

				<div class="page_section danger_zone_section">
					<h3>Danger Zone</h3>
					<input type="button" name="plugin_reset_button" id="reset_plugin_button" class="reset_plugin_button button-primary" value="<?php echo esc_attr( __( 'Reset Plugin', 'events-child-plugin' ) ); ?>"><br>
					<label class="settings-tooltip"><i><?php echo esc_html__( "Reset the plugin's settings and run the setup wizard again.", 'events-child-plugin' ); ?></i></label>
				</div>
			</div>
		<?php } ?>
	</div>

	<!-- The Modal -->
	<div id="dff_reset_model" class="dff_reset_model" style="display: none;">
		<!-- Modal content -->
		<div class="dff-modal-content">
			<span class="dff-modal-close">&times;</span>
			<h3><?php echo esc_html__( 'Are you absolutely sure?', 'events-child-plugin' ); ?></h3>
			<p><?php echo esc_html__( 'This action cannot be undone. This will permanently delete all historical data and the API credentials, and will display the setup wizard for this plugin.', 'events-child-plugin' ); ?></p>
			<p><?php echo esc_html__( 'This will also remove all the event details, categories, and tags from the website.', 'events-child-plugin' ); ?></p>
			<p><?php echo esc_html__( "Please type in 'reset' to confirm.", 'events-child-plugin' ); ?></p>
			<input type="text" class="form-control reset_plugin input-block" name="reset_plugin">
			<button type="submit" id="reset_plugin_confirmed" class="btn btn-block btn-danger"><?php echo esc_html__( 'I understand the consequences. Reset this plugin.', 'events-child-plugin' ); ?></button>
		</div>

	</div>

</div>
