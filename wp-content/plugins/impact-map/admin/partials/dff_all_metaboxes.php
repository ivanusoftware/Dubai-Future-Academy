<?php

/**
 * Function for project sub title function.
 */

function project_sub_title_function() {

	global $post;
	$dff_project_sub_title = get_post_meta( $post->ID, 'dff_project_sub_title', true );
	$arabic_language_checkbox = get_option( 'arabic_language_checkbox' );

	?>
	<div class="project_sub_title_div">
		<input type="text" <?php if( isset( $arabic_language_checkbox ) && !empty ( $arabic_language_checkbox ) ) { echo ' dir="rtl" '; } ?> name="dff_project_sub_title" size="30" id="project_sub_title_div" spellcheck="true" autocomplete="off"
					   class="input_full_width" value="<?php echo esc_attr( $dff_project_sub_title ); ?>"
					   placeholder="Enter Project Sub Title Title Here">
		</div>
	<?php
}

/**
 * Function for project description function.
 */

function project_description_function() {

	global $post;
	$dff_project_description = get_post_meta( $post->ID, 'dff_project_description', true );
	$arabic_language_checkbox = get_option( 'arabic_language_checkbox' );
	?>
		<div class="project_sub_title_div">
			<textarea <?php if( isset( $arabic_language_checkbox ) && !empty ( $arabic_language_checkbox ) ) { echo ' dir="rtl" '; } ?> class="input_full_width" id="dff_project_description" rows="10" name="dff_project_description"><?php echo esc_attr( $dff_project_description ); ?></textarea>
		</div>
	<?php
}

/**
 * Function for short description function.
 */

function short_description_function() {

	global $post;
	$dff_short_description = get_post_meta( $post->ID, 'dff_short_description', true );
	$arabic_language_checkbox = get_option( 'arabic_language_checkbox' );
	?>
		<div class="project_sub_title_div">
			<textarea <?php if( isset( $arabic_language_checkbox ) && !empty ( $arabic_language_checkbox ) ) { echo ' dir="rtl" '; } ?> class="input_full_width" id="dff_short_description" rows="3" name="dff_short_description"><?php echo esc_attr( $dff_short_description ); ?></textarea>
		</div>
	<?php
}

/**
 * Function for project video URL.
 */
function project_video_url_function() {

	global $post;
	$project_video_url = get_post_meta( $post->ID, 'project_video_url', true );
	?>
	<label for="project_video_url">
		<span class="screen-reader-text">project_video_url</span>
		<input type="text" id="project_video_url" class="project_video_url input_full_width" name="project_video_url"
		   value="<?php echo esc_attr( $project_video_url ); ?>" placeholder=""/>
		   <span><i>YouTube embedded link</i></span>
	</label>
	<?php
}

/**
 * Function for project status.
 */
function project_status_function() {

	global $post;
	$project_status = get_post_meta( $post->ID, 'project_status', true );
	?>
	<label for="project_status">
	<span class="screen-reader-text">project_status</span>
		<select id="u1832_input" name="project_status" class="project_status input_full_width" id="project_status">
			<option class="u1832_input_option" value="">Select Project Status</option>
			<option class="u1832_input_option" value="completed" <?php selected( $project_status, 'completed' ); ?>>Completed</option>
			<option class="u1832_input_option" value="ongoing" <?php selected( $project_status, 'ongoing' ); ?>>Ongoing</option>
		</select>
	</label>
	<?php
}

/**
 * Function for project pilot date.
 */
function project_pilot_date_function() {

	global $post;
	$project_pilot_date = get_post_meta( $post->ID, 'project_pilot_date', true );
	$pilot_month_picker = get_post_meta( $post->ID, 'pilot_month_picker', true );
	?>
	<label for="project_pilot_date">
		<span class="screen-reader-text">project_pilot_date</span>
			<!-- <input type="text" id="project_pilot_date" class="project_pilot_date input_full_width" name="project_pilot_date" value="<?php //echo esc_attr( $project_pilot_date ); ?>" placeholder=""/> -->

			<span class="input_full_width">
				Month
				<select id='pilot_month_picker' name="pilot_month_picker" style="width: 195px;">>
					<option value=''>Select Month</option>
					<option value='January' <?php if( 'January' === $pilot_month_picker ){echo 'selected';} ?>>January</option>
					<option value='February' <?php if( 'February' === $pilot_month_picker ){echo 'selected';} ?>>February</option>
					<option value='March' <?php if( 'March' === $pilot_month_picker ){echo 'selected';} ?>>March</option>
					<option value='April' <?php if( 'April' === $pilot_month_picker ){echo 'selected';} ?>>April</option>
					<option value='May' <?php if( 'May' === $pilot_month_picker ){echo 'selected';} ?>>May</option>
					<option value='June' <?php if( 'June' === $pilot_month_picker ){echo 'selected';} ?>>June</option>
					<option value='July' <?php if( 'July' === $pilot_month_picker ){echo 'selected';} ?>>July</option>
					<option value='August' <?php if( 'August' === $pilot_month_picker ){echo 'selected';} ?>>August</option>
					<option value='September' <?php if( 'September' === $pilot_month_picker ){echo 'selected';} ?>>September</option>
					<option value='October' <?php if( 'October' === $pilot_month_picker ){echo 'selected';} ?>>October</option>
					<option value='November' <?php if( 'November' === $pilot_month_picker ){echo 'selected';} ?>>November</option>
					<option value='December' <?php if( 'December' === $pilot_month_picker ){echo 'selected';} ?>>December</option>
				</select> 
				
			</span>
			
			<p></p>

			<span class="input_full_width">
			Year
			<input type="text" id="pilot_year_picker" name="project_pilot_date" value="<?php echo esc_attr( $project_pilot_date ); ?>" class="project_pilot_date" style="margin-left: 12px;"></span>



	</label>
	<?php
}

/**
 * Function for project Completion Date.
 */
function project_completion_date_function() {

	global $post;
	$project_completion_date = get_post_meta( $post->ID, 'project_completion_date', true );
	$completion_month_picker = get_post_meta( $post->ID, 'completion_month_picker', true );
	?>
	<label for="project_completion_date">
		<span class="screen-reader-text">project_completion_date</span>
			<!-- <input type="date" id="project_pilot_date" class="project_completion_date input_full_width" name="project_completion_date" value="<?php //echo esc_attr( $project_completion_date ); ?>" placeholder=""/> -->

			<span class="input_full_width">
				Month
				<select id='completion_month_picker' name="completion_month_picker" style="width: 195px;">>
					<option value=''>Select Month</option>
					<option value='January' <?php if( 'January' === $completion_month_picker ){echo 'selected';} ?>>January</option>
					<option value='February' <?php if( 'February' === $completion_month_picker ){echo 'selected';} ?>>February</option>
					<option value='March' <?php if( 'March' === $completion_month_picker ){echo 'selected';} ?>>March</option>
					<option value='April' <?php if( 'April' === $completion_month_picker ){echo 'selected';} ?>>April</option>
					<option value='May' <?php if( 'May' === $completion_month_picker ){echo 'selected';} ?>>May</option>
					<option value='June' <?php if( 'June' === $completion_month_picker ){echo 'selected';} ?>>June</option>
					<option value='July' <?php if( 'July' === $completion_month_picker ){echo 'selected';} ?>>July</option>
					<option value='August' <?php if( 'August' === $completion_month_picker ){echo 'selected';} ?>>August</option>
					<option value='September' <?php if( 'September' === $completion_month_picker ){echo 'selected';} ?>>September</option>
					<option value='October' <?php if( 'October' === $completion_month_picker ){echo 'selected';} ?>>October</option>
					<option value='November' <?php if( 'November' === $completion_month_picker ){echo 'selected';} ?>>November</option>
					<option value='December' <?php if( 'December' === $completion_month_picker ){echo 'selected';} ?>>December</option>
				</select> 
			</span>

			<p></p>

			<span class="input_full_width">
			Year
			<input type="text" id="completion_year_picker" name="project_completion_date" value="<?php echo esc_attr( $project_completion_date ); ?>" class="project_completion_date" style="margin-left: 12px;"></span>

			
			

	</label>
	<?php
}

/**
 * Function for project map address function.
 */

function project_hide_show_switch_function() {

	global $post;
	$project_hide_show_switch = get_post_meta( $post->ID, 'project_hide_show_switch', true );
	$project_hide_show_switch = ! empty( $project_hide_show_switch ) ? $project_hide_show_switch : 'show';
	?>
	<div class="project_hide_show_switch_div">
		<span>ON/OFF</span>

		<div class="onoffswitch">
			<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" tabindex="0" <?php if( 'show' !== $project_hide_show_switch ) { echo "checked"; } ?>>
			<label class="onoffswitch-label" for="myonoffswitch">
				<span class="onoffswitch-inner"></span>
				<span class="onoffswitch-switch"></span>
			</label>
		</div>

		<input type="hidden" name="project_hide_show_switch" class="project_hide_show" value="<?php echo esc_attr( $project_hide_show_switch ); ?>">
	</div>
	<?php
}

/**
 * Function for Do you want to hide this project from the MAP?.
 */

function project_map_address_function() {

	global $post;
	$project_map_address = get_post_meta( $post->ID, 'project_map_address', true );
	$arabic_language_checkbox = get_option( 'arabic_language_checkbox' );

	?>
	<div class="project_map_address_div">
		<input type="text" <?php if( isset( $arabic_language_checkbox ) && !empty ( $arabic_language_checkbox ) ) { echo ' dir="rtl" '; } ?> name="project_map_address" size="30" id="project_map_address" spellcheck="true" autocomplete="off"
					   class="input_full_width" value="<?php echo esc_attr( $project_map_address ); ?>">
		</div>
	<?php
}

function project_image_uploader_field( $name, $value = '' ) {
     
    $image = 'Upload Image';
    $button = 'button';
    $image_size = 'full'; // it would be better to use thumbnail size here (150x150 or so)
    $display = 'none'; // display state of the "Remove image" button
     
    ?>
     
    <label>
        <div class="gallery-screenshot clearfix">
            <?php
            {
                $ids = explode(',', $value);
                foreach ($ids as $attachment_id) {
                    $img = wp_get_attachment_image_src($attachment_id, 'thumbnail');
                    echo '<div class="screen-thumb"><img src="' . esc_url($img[0]) . '" /></div>';
                }
            }
            ?>
        </div>
         
        <input id="edit-gallery" class="button upload_gallery_button" type="button"
               value="<?php esc_html_e('Add/Edit Project Images', 'mytheme') ?>"/>
        <input id="clear-gallery" class="button upload_gallery_button" type="button"
			   value="<?php esc_html_e('Clear', 'mytheme') ?>"/>
		<span class="tool-tip" data-tip="The Project Images are shown as a slider in the project details sidebar. For better Display, please ensure that Image size is 420 px by 220 px."><i>!</i></span>
        <input type="hidden" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($name); ?>" class="gallery_values" value="<?php echo esc_attr($value); ?>">
    </label>
<?php 

}
 
/*
 * Meta Box HTML
 */
function project_slider_images_function( $post ) {
     
    wp_nonce_field( 'save_feat_gallery', 'mytheme_feat_gallery_nonce' );
     
    $meta_key = 'project_slider_images';
    echo project_image_uploader_field( $meta_key, get_post_meta($post->ID, $meta_key, true) );
}

/**
 * Function for Project Location & Area.
 */

function project_location_area_function() {

	global $post;

	$project_search_location = get_post_meta( $post->ID, 'project_search_location', true );
	$project_latitude = get_post_meta( $post->ID, 'project_latitude', true );
	$project_longitude = get_post_meta( $post->ID, 'project_longitude', true );
	$arabic_language_checkbox = get_option( 'arabic_language_checkbox' );

	?>
	
	<div class="location_search_section">
		<input id="pac-input" <?php if( isset( $arabic_language_checkbox ) && !empty ( $arabic_language_checkbox ) ) { echo ' dir="rtl" '; } ?> class="controls" type="text" name="project_search_location" value="<?php echo esc_attr( $project_search_location ); ?>" placeholder="Search Location" />
		<div id="map"></div>
	</div>
	<hr>
	
	<div class="map_bottom_section">

		<label for="project_completion_date">
			<span class="">Latitude</span>
				<input type="text" id="project_latitude" class="project_latitude input_half_width" name="project_latitude" value="<?php echo esc_attr( $project_latitude ); ?>" placeholder=""/>
		</label>

		<label for="project_completion_date">
			<span class="">Longitude</span>
				<input type="text" id="project_longitude" class="project_longitude input_half_width" name="project_longitude" value="<?php echo esc_attr( $project_longitude ); ?>" placeholder=""/>
		</label>

	</div>
	<?php
	
}

function project_primary_technologies_function() {

	global $post;
	$project_primary_technologies = get_post_meta( $post->ID, 'project_primary_technologies', true );
	
	$ProjectTechnologies_terms = get_terms(
		'project_technologies', array(
			'hide_empty' => false,
		)
	);

	if( isset( $ProjectTechnologies_terms ) && !empty( $ProjectTechnologies_terms ) ) {
		?>
		<select name="project_primary_technologies" class="project_primary_technologies input_full_width">
			<option class="u1832_input_option" value="">Select Primary Technology</option>
			
			<?php 
				foreach( $ProjectTechnologies_terms as $ProjectTechnologies_terms_value ) {
					?>
						<option class="u1832_input_option" value="<?php echo esc_attr( $ProjectTechnologies_terms_value->slug ); ?>" <?php selected( $project_primary_technologies, $ProjectTechnologies_terms_value->slug ); ?>><?php echo esc_html( $ProjectTechnologies_terms_value->name ); ?></option>			
					<?php
				}
			?>
		</select>
		<?php
	}
	

}