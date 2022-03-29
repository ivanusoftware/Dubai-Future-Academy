<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.multidots.com/
 * @since      1.0.0
 *
 * @package    News_Master_Plugin
 * @subpackage News_Master_Plugin/admin/partials
 */
?>

<?php

$submit = filter_input( INPUT_POST, 'submit', FILTER_SANITIZE_STRING );
if ( $submit ) {

	$nmp_english_category = filter_input( INPUT_POST, 'nmp_english_category', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
	$nmp_english_tags     = filter_input( INPUT_POST, 'nmp_english_tags', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

	$nmp_english_category = isset( $nmp_english_category ) ? $nmp_english_category : '';
	$nmp_english_tags     = isset( $nmp_english_tags ) ? $nmp_english_tags : '';

	$nmp_english_category_string = implode( ',', $nmp_english_category );
	$nmp_english_tags_string     = implode( ',', $nmp_english_tags );

	update_option( 'nmp_english_category', $nmp_english_category_string, false );
	update_option( 'nmp_english_tags', $nmp_english_tags_string, false );

}

?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap news_master_settings_section">
	<h1>Publisher Settings</h1>
	<?php
		$update = filter_input( INPUT_GET, 'update', FILTER_SANITIZE_STRING );
	if ( isset( $update ) && 'yes' === $update ) {
		?>
		<div id="setting-error-settings_updated" class="notice notice-success settings-error is-dismissible">
			<p>
				<strong>Settings saved.</strong>
			</p>
			<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
		</div>
		<?php
	}
	?>
	<div class="event_section">

		<!-- Tab links -->
		<div class="settings_container">
			<div class="tab">
				<span class="tablinks active" onclick="openSetting(event, 'general_setting')">Configuration</span>
				<span class="tablinks" onclick="openSetting(event, 'add_sites')">Add sites</span>
			</div>

			<!-- Tab content -->
			<div id="general_setting" class="tabcontent" style="display: block;">
				<form action="<?php echo esc_attr( get_site_url() ); ?>/wp-admin/admin.php?page=nmp-settings&update=yes" method="post">
					<div class="cat_tags_outer">
						<div class="page_section english_cats_tags_section">
							<h3>Select Categories and Tags</h3>
							<?php
								$nmp_post_types_option = get_option( 'nmp_english_category' );
								$nmp_post_tags_option  = get_option( 'nmp_english_tags' );

								$nmp_post_types_array = explode( ',', $nmp_post_types_option );
								$nmp_post_tags_array  = explode( ',', $nmp_post_tags_option );
							?>
							<div class="category_tags_section">
								<div class="category_block post_type_block">
									<h4>Publisher Categories</h4>
									<?php
									$get_parent_cats  = array(
										'parent'     => '0',
										'hide_empty' => 0,
									);

									$all_categories = get_categories( $get_parent_cats );
									if ( isset( $all_categories ) && ! empty( $all_categories ) ) {
										?>
										<ul class="categorychecklist">
											<?php
											foreach ( $all_categories as $single_category ) {
												// for each category, get the ID
												$catID = $single_category->cat_ID;
												?>
													<li>
														<label class="post_type_lable parent_category" for="en_<?php echo esc_attr( $single_category->slug ); ?>"><input name="nmp_english_category[]" type="checkbox" id="en_<?php echo esc_html( $single_category->slug ); ?>" value="<?php echo esc_attr( $catID ); ?>" 
																															<?php
																															if ( in_array( (string) $catID, $nmp_post_types_array, true ) ) {
																																echo 'checked'; }
?>
><?php echo esc_html( $single_category->name ); ?></label>
													</li>
													<?php

													$get_children_cats = array(
														'parent' => $catID,
														'hide_empty' => 0,
													);
													$child_cats        = get_categories( $get_children_cats );

													if ( isset( $child_cats ) && ! empty( $child_cats ) ) {
														?>
														<li>
														<ul class="event_child_category">
															<?php
															foreach ( $child_cats as $child_cat ) {
																$childID = $child_cat->cat_ID;
																?>
																<li>
																	<label class="post_type_lable sub_category" for="en_<?php echo esc_attr( $child_cat->slug ); ?>"><input name="nmp_english_category[]" type="checkbox" id="en_<?php echo esc_html( $child_cat->slug ); ?>" value="<?php echo esc_attr( $childID ); ?>" 
																																	<?php
																																	if ( in_array( (string) $childID, $nmp_post_types_array, true ) ) {
																																		echo 'checked'; }
																		?>
																		><?php echo esc_html( $child_cat->name ); ?></label>
																	</li>
																	<?php

																	$get_super_children_cats = array(
																		'parent' => $childID,
																		'hide_empty' => 0,
																	);
																	$super_child             = get_categories( $get_super_children_cats );
																	if ( isset( $super_child ) && ! empty( $super_child ) ) {
																		?>
																		<ul class="event_sub_child_category">
																			<?php
																			foreach ( $super_child as $super_child_val ) {
																				$super_child_id = $super_child_val->cat_ID;
																				?>
																				<li>
																					<label class="post_type_lable super_sub_category" for="en_<?php echo esc_attr( $super_child_val->slug ); ?>"><input name="nmp_english_category[]" type="checkbox" id="en_<?php echo esc_attr( $super_child_val->slug ); ?>" value="<?php echo esc_attr( $super_child_id ); ?>" 
																																							<?php
																																							if ( in_array( (string) $super_child_id, $nmp_post_types_array, true ) ) {
																																								echo 'checked'; }
																						?>
																						><?php echo esc_html( $super_child_val->name ); ?></label>
																					</li>
																				<?php } ?>
																			</ul>
																			<?php
																	}
															}
																?>
															</ul>
															</li>
															<?php
													}
											}
												?>
											</ul>
											<?php
									}
									?>
								</div>
								<div class="tags_block post_type_block">
									<h4>Publisher tags</h4>
									<?php
										$posts_tags = get_tags(
											array(
												'hide_empty' => false,
											)
										);
										if ( isset( $posts_tags ) && ! empty( $posts_tags ) ) {
											?>
											<ul class="tagschecklist">
												<?php foreach ( $posts_tags as $posts_tags_value ) { ?>
													<li>
														<label class="post_type_lable" for="en_<?php echo esc_attr( $posts_tags_value->slug ); ?>"><input name="nmp_english_tags[]" type="checkbox" id="en_<?php echo esc_attr( $posts_tags_value->slug ); ?>" value="<?php echo esc_attr( $posts_tags_value->term_id ); ?>" 
																											<?php
																											if ( in_array( (string) $posts_tags_value->term_id, $nmp_post_tags_array, true ) ) {
																												echo 'checked'; }
															?>
															><?php echo esc_html( $posts_tags_value->name ); ?></label>
													</li>
												<?php } ?>
											</ul>
											<?php
										}
									?>
								</div>

							</div>
						</div>
						<input type="submit" name="submit" id="submit" class="nmp_save_button button-primary" value="Save Changes">
					</div>
				</form>
			</div>

			<div id="add_sites" class="tabcontent">
				<h3>Child Site List</h3>
				<div class="add_sites_group">
					<input type="text" class="add_sites_field" placeholder="Example: abc.com( without http/https )"><input type="button" name="submit" id="submit" class="add_sites_button button-primary" value="Generate Token">
				</div>
				<div class="add_site_table_group">
					<table class="add_site_table">
						<tr>
							<th>Site URL</th>
							<th>OAuth Token</th>
							<th>Action</th>
						</tr>
						<?php
						global $wpdb;

						$table_name      = $wpdb->base_prefix . 'options';
						$npm_added_sites = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM %1soptions WHERE option_name = 'npm_child_sites'", $wpdb->base_prefix ), ARRAY_A );
						$npm_added_sites = explode( '/', $npm_added_sites[0]['option_value'] );

						if ( isset( $npm_added_sites ) && ! empty( $npm_added_sites ) ) {
							foreach ( $npm_added_sites as $npm_added_sites_data ) {
								$npm_added_sites_data = maybe_unserialize( $npm_added_sites_data );
								if ( isset( $npm_added_sites_data[0]['siteurl'] ) && ! empty( $npm_added_sites_data[0]['siteurl'] ) ) {
									?>
									<tr>
										<td class="siteurl"><?php echo isset( $npm_added_sites_data[0]['siteurl'] ) ? esc_html( $npm_added_sites_data[0]['siteurl'] ) : ''; ?></td>
										<td class="token"><?php echo isset( $npm_added_sites_data[0]['token'] ) ? esc_html( $npm_added_sites_data[0]['token'] ) : ''; ?></td>
										<td class="action"><span class="dashicons dashicons-no-alt delete_site_button"></span></td>
									</tr>
									<?php
								}
							}
						}
						?>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
