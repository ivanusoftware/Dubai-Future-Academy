<?php

/**
 * Provide a public view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.multidots.com
 * @since      1.0.0
 *
 * @package    Impact_Map
 * @subpackage Impact_Map/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div id="map-wrapper-front-block-map" class="map-wrapper-front-block-map">
	
	<?php
		
		$is_share_page = filter_input(INPUT_GET, 'map-project', FILTER_VALIDATE_INT );
		if ( 1 !== $exhibitionMode && !isset( $is_share_page ) && empty( $is_share_page ) ) { ?>
			<div class="front-block-map-overlay"></div>
		<?php } 
	
	?>

	 <div class="gmap-sidebar-wrapper gmap_sidebar_left">
		<div id="sidebar" class="sidebar sidebar-left collapsed">
			<div class="sidebar-content-wrap">
				<!-- Nav tabs -->
				<div class="sidebar-tabs" style="display:none;">
					<ul role="tablist">
						<li role="tab"><a id="leftbararrow" href="#left-bar" class="arrow-icon"></a></li>
					</ul>
				</div>
				<!-- Tab panes -->
				<div class="sidebar-content">
					
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" id="map-zoom-control" value="<?php echo esc_attr( $show_zoom ); ?>">
	<input type="hidden" id="map-filter" value="<?php echo esc_attr( $show_filter ); ?>">
	<input type="hidden" id="map-PrimaryTechnology" value="<?php echo esc_attr( $PrimaryTechnology ); ?>">
	<input type="hidden" id="map-exhibition_mode" value="<?php echo esc_attr( $exhibitionMode ); ?>">
	<input type="hidden" id="map-exhibition_time" value="<?php echo esc_attr( $exhibitionTime ); ?>">

	<!-- Popup Settings -->
	<input type="hidden" id="map-description" value="<?php echo esc_attr( $description ); ?>">
	<input type="hidden" id="map-SharingOptions" value="<?php echo esc_attr( $SharingOptions ); ?>">
	<input type="hidden" id="map-pButton" value="<?php echo esc_attr( $pButton ); ?>">

	<!-- Project Detail Settings -->
	<input type="hidden" id="map-pdImageSlider" value="<?php echo esc_attr( $pdImageSlider ); ?>">
	<input type="hidden" id="map-pdChangeImageafter" value="<?php echo esc_attr( $pdChangeImageafter ); ?>">
	<input type="hidden" id="map-pdProjectTechnologies" value="<?php echo esc_attr( $pdProjectTechnologies ); ?>">
	<input type="hidden" id="map-pdProjectPartners" value="<?php echo esc_attr( $pdProjectPartners ); ?>">
	<input type="hidden" id="map-pdProjectStatus" value="<?php echo esc_attr( $pdProjectStatus ); ?>">
	<input type="hidden" id="map-pdProjectDescriptions" value="<?php echo esc_attr( $pdProjectDescriptions ); ?>">
	<input type="hidden" id="map-pdProjectAddress" value="<?php echo esc_attr( $pdProjectAddress ); ?>">
	<input type="hidden" id="map-pdSharingOptions" value="<?php echo esc_attr( $pdSharingOptions ); ?>">
	<input type="hidden" id="map-pdShowVIdeo" value="<?php echo esc_attr( $pdShowVIdeo ); ?>">
	<input type="hidden" id="map-defaultLatitude" value="<?php echo esc_attr( $MapLatitude ); ?>">
	<input type="hidden" id="map-defaultLongitude" value="<?php echo esc_attr( $MapLongitude ); ?>">
	<input type="hidden" id="map-defaultZoom" value="<?php echo esc_attr( $MapZoom ); ?>">

	<input type="hidden" id="map-terms" value="<?php echo esc_attr( wp_json_encode( $terms_array ) ); ?>">

	<div id="front-block-map" class="front-block-map"></div>	
	<?php 

	$arabic_language_checkbox = get_option( 'arabic_language_checkbox' );
	if( isset( $arabic_language_checkbox ) && !empty( $arabic_language_checkbox ) ) {
		$reset_text = 'إعادة ضبط';
		$filter_text = 'منقي';

		$completed_project = 'مشروع مكتمل';
		$technologies = 'التقنيات';
		$partners = 'شركاء';
		$clear = 'واضح';

	} else {
		$reset_text = 'Reset';
		$filter_text = 'Filter';

		$completed_project = 'Completed Project';
		$technologies = 'Technologies';
		$partners = 'Partners';
		$clear = 'Clear';
	}

	if( isset( $_GET['map-project'] ) && !empty( $_GET['map-project'] ) ) {
		?>
		 <div class="gmap-sidebar-wrapper right">
			<div id="right-sidebar" class="sidebar sidebar-right collapsed">
				<div class="sidebar-content-wrap">

				<div class="sidebar-tabs">
					<ul role="tablist">
                    	<li> <span id="reset-share-map" class="reset-map-button"><?php echo esc_html( $reset_text ); ?></span> </li>
					</ul>
				</div>

				</div>
			</div>
		</div>
		<?php
	}

	if ( 1 !== $exhibitionMode && !isset( $is_share_page ) && empty( $is_share_page ) ) { ?>

	 <div class="gmap-sidebar-wrapper right">
		<div id="right-sidebar" class="sidebar sidebar-right collapsed">
			<div class="sidebar-content-wrap">
				<!-- Nav tabs -->

				<div class="sidebar-tabs">
					<ul role="tablist">
                    <li> <span id="reset-map" class="reset-map-button"><?php echo esc_html( $reset_text ); ?></span> </li>
					<?php if ( 'false' !== $show_filter ) { ?>
						<li role="tab"><a href="javascript:void(0)" class="filter-icon"><svg xmlns="http://www.w3.org/2000/svg" width="26" height="15.756" viewBox="0 0 26 15.756"><g transform="translate(0 0)"><path d="M66.364,60.3a3.4,3.4,0,0,1,3.245,2.443h3.474A.914.914,0,0,1,74,63.7a.945.945,0,0,1-.954.954H69.571A3.367,3.367,0,0,1,66.326,67.1a3.413,3.413,0,0,1-3.283-2.443H48.954a.954.954,0,1,1,0-1.909H63.043A3.5,3.5,0,0,1,66.364,60.3Zm-.038,1.909A1.489,1.489,0,1,0,67.815,63.7,1.491,1.491,0,0,0,66.326,62.209Z" transform="translate(-48 -60.3)" fill="#fff"/></g><g transform="translate(0 8.96)"><path d="M54.872,120.3a3.4,3.4,0,0,1,3.245,2.443H73.046A.922.922,0,0,1,74,123.7a.945.945,0,0,1-.954.954H58.117a3.367,3.367,0,0,1-3.245,2.443,3.413,3.413,0,0,1-3.283-2.443H48.954a.954.954,0,1,1,0-1.909h2.634A3.413,3.413,0,0,1,54.872,120.3Zm-.038,1.909a1.489,1.489,0,1,0,1.489,1.489A1.467,1.467,0,0,0,54.834,122.209Z" transform="translate(-48 -120.3)" fill="#fff"/></g></svg><?php echo esc_html( $filter_text ); ?></a></li>                    
					<?php } ?>
					</ul>
				</div>
				<?php } 
				
				if( 1 !== $exhibitionMode ) {
				
					?>
					<div class="map_full_screen_controller">
						<button id="map_full_screen_btn" draggable="false" title="Toggle fullscreen view" aria-label="Toggle fullscreen view" type="button" class="gm-control-active gm-fullscreen-control" style="background: none rgb(255, 255, 255); border: 0px; margin: 10px; padding: 0px; text-transform: none; appearance: none; position: absolute; cursor: pointer; user-select: none; border-radius: 2px; height: 40px; width: 40px; box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px -1px; overflow: hidden; top: 0px; right: 0px;"><img src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2218%22%20height%3D%2218%22%20viewBox%3D%220%200%2018%2018%22%3E%0A%20%20%3Cpath%20fill%3D%22%23666%22%20d%3D%22M0%2C0v2v4h2V2h4V0H2H0z%20M16%2C0h-4v2h4v4h2V2V0H16z%20M16%2C16h-4v2h4h2v-2v-4h-2V16z%20M2%2C12H0v4v2h2h4v-2H2V12z%22%2F%3E%0A%3C%2Fsvg%3E%0A" alt="" style="height: 18px; width: 18px;"></button>
					</div>
					<?php

				}
				
				if ( 1 !== $exhibitionMode && !isset( $is_share_page ) && empty( $is_share_page ) ) { ?>
				<!-- Tab panes -->
				<div class="sidebar-content">
					<div class="sidebar-pane" id="right-bar">
						<div class="sidebar-header">
							<ul role="tablist">
								<li role="tab"><a href="javascript:void(0)" class="filter-icon"><svg xmlns="http://www.w3.org/2000/svg" width="26" height="15.756" viewBox="0 0 26 15.756"><g transform="translate(0 0)"><path d="M66.364,60.3a3.4,3.4,0,0,1,3.245,2.443h3.474A.914.914,0,0,1,74,63.7a.945.945,0,0,1-.954.954H69.571A3.367,3.367,0,0,1,66.326,67.1a3.413,3.413,0,0,1-3.283-2.443H48.954a.954.954,0,1,1,0-1.909H63.043A3.5,3.5,0,0,1,66.364,60.3Zm-.038,1.909A1.489,1.489,0,1,0,67.815,63.7,1.491,1.491,0,0,0,66.326,62.209Z" transform="translate(-48 -60.3)" fill="#fff"/></g><g transform="translate(0 8.96)"><path d="M54.872,120.3a3.4,3.4,0,0,1,3.245,2.443H73.046A.922.922,0,0,1,74,123.7a.945.945,0,0,1-.954.954H58.117a3.367,3.367,0,0,1-3.245,2.443,3.413,3.413,0,0,1-3.283-2.443H48.954a.954.954,0,1,1,0-1.909h2.634A3.413,3.413,0,0,1,54.872,120.3Zm-.038,1.909a1.489,1.489,0,1,0,1.489,1.489A1.467,1.467,0,0,0,54.834,122.209Z" transform="translate(-48 -120.3)" fill="#fff"/></g></svg><?php echo esc_html( $filter_text ); ?></a></li>
								<li role="tab"><span class="sidebar-close"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"><rect width="23.335" height="2.121" transform="translate(1.5) rotate(45)" fill="#fff"/><rect width="23.335" height="2.121" transform="translate(0 16.5) rotate(-45)" fill="#fff"/></svg></span></li>
							</ul>
							<?php
							if ( isset( $CompletedRadioButton ) && 1 === $CompletedRadioButton ) {
								?>
								<ul class="project-completed">
									<li><?php echo esc_html( $completed_project ); ?></li>
									<li><label class="switch"><input type="checkbox" class="map_filter_complete_project"><span class="slider round"></span><span class="impactmap-screen-reader-text">Switch</span></label></li>
								</ul>
								<?php
							}
							?>
						</div>
						<div class="tags-outer">
							<?php
							/**
							 * Get project technology details.
							 */
							if( isset( $project_technologies ) && !empty( $project_technologies ) ) {

								if ( isset( $ProjectTechnologies ) && 1 === $ProjectTechnologies && ! empty( $project_technologies ) ) {
	
									?>
										<div class="tag-group">
											<div class="tag-heading">
												<p class="tag-title"><?php echo esc_html( $technologies ); ?></p>
												<a href="javascript:void(0)" class="project_technologies_clear clear-tags"><?php echo esc_html( $clear ); ?></a>
											</div>
											<ul class="project_technologies_filter tags style-2">
												<?php
												foreach ( $project_technologies as $project_technologies_data ) {
													$term = get_term_by('slug', $project_technologies_data, 'project_technologies');
													?>
														<li><a href="javascript:void(0)" class="" data-slug="<?php echo esc_attr( $project_technologies_data ); ?>"><?php echo esc_html( $term->name ); ?></a></li>
														<?php
												}
												?>
											</ul>
										</div>
								
								<?php
								}

							} else {

								$ProjectTechnologies_terms = get_terms(
									'project_technologies', array(
										'hide_empty' => true,
									)
								);
								if ( isset( $ProjectTechnologies ) && 1 === $ProjectTechnologies && ! empty( $ProjectTechnologies_terms ) ) {
	
									?>
										<div class="tag-group">
											<div class="tag-heading">
												<p class="tag-title"><?php echo esc_html( $technologies ); ?></p>
												<a href="javascript:void(0)" class="project_technologies_clear clear-tags"><?php echo esc_html( $clear ); ?></a>
											</div>
											<ul class="project_technologies_filter tags style-2">
												<?php
												foreach ( $ProjectTechnologies_terms as $ProjectTechnologies_terms_data ) {
													?>
														<li><a href="javascript:void(0)" class="" data-slug="<?php echo esc_attr( $ProjectTechnologies_terms_data->slug ); ?>"><?php echo esc_html( $ProjectTechnologies_terms_data->name ); ?></a></li>
														<?php
												}
												?>
											</ul>
										</div>
								
								<?php
								}

							}
							

							/**
							 * Get project partners details.
							 */
							if( isset( $project_partners ) && !empty( $project_partners ) ) {
								if ( isset( $ProjectPartners ) && 1 === $ProjectPartners && ! empty( $project_partners ) ) {
									?>
										<div class="tag-group">
											<div class="tag-heading">
											<p class="tag-title"><?php echo esc_html( $partners ); ?></p>
											<a href="javascript:void(0)" class="project_partners_clear clear-tags"><?php echo esc_html( $clear ); ?></a>
											</div>
											<ul class="project_partners_filter tags style-2">
												<?php
												foreach ( $project_partners as $project_partners_data ) {
													$term = get_term_by('slug', $project_partners_data, 'project_partners');
													?>
																	<li><a href="javascript:void(0)" class="" data-slug="<?php echo esc_attr( $project_partners_data ); ?>"><?php echo esc_html( $term->name ); ?></a></li>
																	<?php
												}
												?>
											</ul>
										</div>
									<?php }
							} else {
								
								$project_partners_terms = get_terms(
									'project_partners', array(
										'hide_empty' => true,
										'orderby'    => 'meta_value_num',
										'meta_key'   => 'project_partner_order',
										'meta_type'  => 'NUMERIC',
									)
								);

								if ( isset( $ProjectPartners ) && 1 === $ProjectPartners && ! empty( $project_partners_terms ) ) {
									?>
										<div class="tag-group">
											<div class="tag-heading">
											<p class="tag-title"><?php echo esc_html( $partners ); ?></p>
											<a href="javascript:void(0)" class="project_partners_clear clear-tags"><?php echo esc_html( $clear ); ?></a>
											</div>
											<ul class="project_partners_filter tags style-2">
												<?php
												foreach ( $project_partners_terms as $project_partners_terms_data ) {
													?>
																	<li><a href="javascript:void(0)" class="" data-slug="<?php echo esc_attr( $project_partners_terms_data->slug ); ?>"><?php echo esc_html( $project_partners_terms_data->name ); ?></a></li>
																	<?php
												}
												?>
											</ul>
										</div>
									<?php } 

							}

							?>
							
						</div>                        
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php if( isset( $show_zoom ) && ( 1 === $show_zoom || true === $show_zoom ) ) : ?>
    <div class="impactmap-gmnoprint-control">
        <button id="map-custom-zoomin" class="zoom-in" value="zoomin">
        <svg id="Group_343" data-name="Group 343" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14">
            <line id="Line_4" data-name="Line 4" y2="14" transform="translate(7)" fill="none" stroke="#1f3144"
                stroke-width="3" />
            <line id="Line_5" data-name="Line 5" y2="14" transform="translate(14 7) rotate(90)" fill="none" stroke="#1f3144"
                stroke-width="3" />
		</svg>
		<span class="impactmap-screen-reader-text">zoomin</span>
        </button>
        <button id="map-custom-zoomout" class="zoom-out" value="zoomout"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="3" viewBox="0 0 14 3">
            <line id="Line_6" data-name="Line 6" y2="14" transform="translate(14 1.5) rotate(90)" fill="none" stroke="#1f3144"
                stroke-width="3" />
		</svg>
		<span class="impactmap-screen-reader-text">zoomout</span>
        </button>
        <!--<button>
            <svg xmlns="http://www.w3.org/2000/svg" width="13.61" height="15.546" viewBox="0 0 13.61 15.546">
                <path id="Path_960" data-name="Path 960"
                    d="M1634.782,219.431s1.617-2.217,2.568,0,5.622,12.6,5.622,12.6.259,2.5-1.359,1.851-4.83-4.424-4.83-4.424h-1.248s-3.791,4.474-5.054,4.424-1.181-.721-1-1.851S1634.782,219.431,1634.782,219.431Z"
                    transform="translate(-1629.377 -218.446)" fill="#1f3144" />
            </svg>
        </button>-->
	</div>
	<?php endif;  
	}
	
	/**
	 * Get backend options.
	 */
	$project_popup_title = get_option( 'project_popup_title' );
	$project_popup_description = get_option( 'project_popup_description' );
	$project_popup_button_title = get_option( 'project_popup_button_title' );
	$popup_logo_image = get_option( 'popup_logo_image' );
	$popup_background_image = get_option( 'popup_background_image' );
	$popup_mobile_background_image = get_option( 'popup_mobile_background_image' );

	
	if ( 1 !== $exhibitionMode && !isset( $is_share_page ) && empty( $is_share_page ) ) {
    ?>

	 <div id="impactmap_popup_onload">
		<div class="impactmap_popup-wrapper"> 
	<div class="logo">
		<img src="<?php echo esc_url( $popup_logo_image ); ?>" title="" alt="Dubai Future Foundation" />
	</div> 
	<h2><?php echo esc_html( $project_popup_title ); ?></h2>
	<p><?php echo esc_html( $project_popup_description ); ?></p>
<span class="explore-more-btn" id="impactmap-explore-more-btn"><?php echo esc_html( $project_popup_button_title ).' '; ?><svg xmlns="http://www.w3.org/2000/svg" width="7.977" height="14" viewBox="0 0 7.977 14">
  <g id="Group_1031" data-name="Group 1031" transform="translate(4.128)">
	<g id="Group_1068" data-name="Group 1068" transform="translate(-4.128)">
	  <rect id="Rectangle_323" data-name="Rectangle 323" width="9.862" height="1.42" transform="translate(1.004) rotate(45)" fill="#0e5d9e"/>
	  <rect id="Rectangle_324" data-name="Rectangle 324" width="9.862" height="1.42" transform="translate(0 12.996) rotate(-45)" fill="#0e5d9e"/>
	</g>
  </g>
</svg>

</span>
<button class="impactmap_popup_onload-close-btn" id="impactmap_popup_onload-close-btn">
	<img src="/wp-content/plugins/impact-map/public/images/close-svg.svg" alt="close">	
	<span class="impactmap-screen-reader-text">Close Button</span>
</button>
</div>  
	<div class="popup-map-bg">
     <img class="popup-map-desktop" src="<?php echo esc_url( $popup_background_image ); ?>" alt="Map">
	 <img class="popup-map-mobile" src="<?php echo esc_url( $popup_mobile_background_image ); ?>" alt="Map">
	<div>
<div>

<?php }
