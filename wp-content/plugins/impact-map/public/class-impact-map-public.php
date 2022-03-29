<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.multidots.com
 * @since      1.0.0
 *
 * @package    Impact_Map
 * @subpackage Impact_Map/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Impact_Map
 * @subpackage Impact_Map/public
 * @author     Multidots <contact@multidots.com>
 */
class Impact_Map_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Impact_Map_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Impact_Map_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/impact-map-public.css', array(), $this->version, 'all' );

		$arabic_language_checkbox = get_option( 'arabic_language_checkbox' );
		if( isset( $arabic_language_checkbox ) && !empty( $arabic_language_checkbox ) ) {
			wp_enqueue_style( $this->plugin_name.'arabic', plugin_dir_url( __FILE__ ) . 'css/impact-map-public-arabic.css', array(), $this->version, 'all' );
		}

		wp_enqueue_style( $this->plugin_name.'-bxslider', plugin_dir_url( __FILE__ ) . 'css/jquery.bxslider.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-magnific-popup', plugin_dir_url( __FILE__ ) . 'css/magnific-popup.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Impact_Map_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Impact_Map_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$map_api_key = get_option('project_google_map_api_key');
		$localize_args = array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' ), 
			'mapPage' => get_page_link(),
			'shareProject' => filter_input(INPUT_GET, 'map-project', FILTER_SANITIZE_STRING ),
		);
		wp_enqueue_script( 'google-api-front', "https://maps.googleapis.com/maps/api/js?key=".$map_api_key."&region=AE", array(), $this->version, true );
		wp_enqueue_script( 'google-marker-cluster', plugin_dir_url( __FILE__ ) . 'js/marker-cluster.js', array(), $this->version, false );
		wp_enqueue_script( 'gmap-sidebar', plugin_dir_url( __FILE__ ) . 'js/jquery-sidebar.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'gmap-bxslider', plugin_dir_url( __FILE__ ) . 'js/jquery.bxslider.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'gmap-magnific-popup.min', plugin_dir_url( __FILE__ ) . 'js/jquery.magnific-popup.min.js', array( 'jquery' ), $this->version, false );
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/impact-map-public.js', array( 'jquery', 'google-api-front', 'gmap-sidebar', 'google-marker-cluster' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'impactMap', $localize_args );
		wp_enqueue_script( $this->plugin_name );


	}

	public function impactmap_get_project_markers_public_callback() {
		$tax_query = array();
		$partners_query_args = array();
		$technology_query_args = array();
		$all_projects = array();
		$is_share = false;

		$terms = stripslashes( $_POST['project_terms'] );
		$map_terms = stripslashes( $_POST['map_terms'] );

		$exhibition_mode = filter_input( INPUT_POST, 'exhibition_mode', FILTER_SANITIZE_STRING );
		$exhibition_mode = isset( $exhibition_mode ) ? esc_html( $exhibition_mode ) : '';
		$exhibition_mode = stripslashes( $exhibition_mode );

		$PrimaryTechnology = filter_input( INPUT_POST, 'PrimaryTechnology', FILTER_SANITIZE_STRING );
		$PrimaryTechnology = isset( $PrimaryTechnology ) ? esc_html( $PrimaryTechnology ) : '';
		$PrimaryTechnology = stripslashes( $PrimaryTechnology );

		$SharingOptions = filter_input( INPUT_POST, 'SharingOptions', FILTER_SANITIZE_STRING );
		$SharingOptions = isset( $SharingOptions ) ? esc_html( $SharingOptions ) : '';
		$SharingOptions = stripslashes( $SharingOptions );

		$pButton = filter_input( INPUT_POST, 'pButton', FILTER_SANITIZE_STRING );
		$pButton = isset( $pButton ) ? esc_html( $pButton ) : '';
		$pButton = stripslashes( $pButton );

		$description = filter_input( INPUT_POST, 'description', FILTER_SANITIZE_STRING );
		$description = isset( $description ) ? esc_html( $description ) : '';
		$description = stripslashes( $description );

		$project_terms = json_decode( $terms, true );
		$project_map_terms = json_decode( $map_terms, true );

		$share_project = base64_decode( filter_input(INPUT_POST, 'share_project', FILTER_SANITIZE_STRING ) );
		$project_technologies = isset( $project_terms[0]['project_technologies'] ) && !empty( $project_terms[0]['project_technologies'] ) ? $project_terms[0]['project_technologies'] : array();
		$project_partners = isset( $project_terms[1]['project_partners'] ) && !empty( $project_terms[1]['project_partners'] ) ? $project_terms[1]['project_partners'] : array();

		if( empty( $project_technologies ) && empty( $project_partners ) ) {

			if( empty( $project_map_terms['project_technologies'] ) ) {
				$project_technologies = impact_map_get_project_term_slug( 'project_technologies' );
			} else {
				$project_technologies = $project_map_terms['project_technologies'];
			}

			if( empty( $project_map_terms['project_partners'] ) ) {
				$project_partners = impact_map_get_project_term_slug( 'project_partners' );
			} else {
				$project_partners = $project_map_terms['project_partners'];
			}

		}

		$map_page = filter_input(INPUT_POST, 'page_url', FILTER_SANITIZE_URL );

		$completeProject = filter_input( INPUT_POST, 'complete_project', FILTER_SANITIZE_STRING );
		$completeProject = isset( $completeProject ) ? esc_html( $completeProject ) : '';
	
		if( 1 === (int)$completeProject ) {
			$complete_array = array(
				'key' => 'project_status',
				'value' => 'completed',
			);

		}
		

		if( !empty( $project_partners ) && is_array( $project_partners ) ) {
			foreach( $project_partners as $term ) {
				$tax_arg['taxonomy'] = 'project_partners';
				$tax_arg['terms'] = $term;
				$tax_arg['field'] = 'slug';
				$tax_arg['include_children'] = true;
				$tax_arg['operator'] = 'IN';
				$partners_query_args[] = $tax_arg;
			}
		}

		if( !empty( $project_technologies ) && is_array( $project_technologies ) ) {
			foreach( $project_technologies as $term ) {
				$tax_arg['taxonomy'] = 'project_technologies';
				$tax_arg['terms'] = $term;
				$tax_arg['field'] = 'slug';
				$tax_arg['include_children'] = true;
				$tax_arg['operator'] = 'IN';
				$technology_query_args[] = $tax_arg;
			}
		}
		$tax_query = array_merge( $partners_query_args, $technology_query_args );
		
		$tax_query['relation'] = 'OR';

		$project_args = array(
			'post_type'      => 'dff-project-map',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields' => 'ids',
			'meta_query' => array(
				array(
					'key' => 'project_hide_show_switch',
					'value' => 'show',
					'compare' => '=='
				),
				$complete_array
			)
		);
		if( !empty( $tax_query ) ) {
			$project_args['tax_query'] = $tax_query;
		}
		if( !empty( $share_project ) ) {
			$projects = get_posts( array('numberposts' => 1,'post_type' => 'dff-project-map', 'include' => $share_project, 'fields' => 'ids' ) );
			if( empty( $projects ) ) {
				$projects = get_posts( $project_args );	
			} else {
				$is_share = true;
			}
		} else {
			$projects = get_posts( $project_args );
		}
		
		if( !empty( $projects ) ) {
			foreach( $projects as $project_id ) {
				$project_details['project_id'] = $project_id;
				$project_details['latitude'] = get_post_meta( $project_id, 'project_latitude', true );
				$project_details['longitude'] = get_post_meta( $project_id, 'project_longitude', true );
				$share_arg = array(
					'map-project' => base64_encode( $project_id )
				);
				$share_link = add_query_arg( $share_arg, $map_page );
				$project_details['content'] = $this->impactmap_get_project_modal_content( $project_id, $exhibition_mode, $SharingOptions, $pButton, $description, $share_link, $PrimaryTechnology );
				$all_projects[] = $project_details;
			}
		}

		$return = array(
			'project_markers' => $all_projects,
			'count'      	   => count( $all_projects ),
			'is_share' => $is_share
		);

		wp_send_json_success( $return );

	}

	public function impactmap_explore_more_button() {
		
		$data_project_id = filter_input( INPUT_POST, 'data_project_id', FILTER_SANITIZE_STRING );
		$data_project_id = isset( $data_project_id ) ? esc_html( $data_project_id ) : '';

		$pdImageSlider = filter_input( INPUT_POST, 'pdImageSlider', FILTER_SANITIZE_STRING );
		$pdImageSlider = isset( $pdImageSlider ) ? esc_html( $pdImageSlider ) : '';

		$pdChangeImageafter = filter_input( INPUT_POST, 'pdChangeImageafter', FILTER_SANITIZE_STRING );
		$pdChangeImageafter = isset( $pdChangeImageafter ) ? esc_html( $pdChangeImageafter ) : '';

		$pdProjectTechnologies = filter_input( INPUT_POST, 'pdProjectTechnologies', FILTER_SANITIZE_STRING );
		$pdProjectTechnologies = isset( $pdProjectTechnologies ) ? esc_html( $pdProjectTechnologies ) : '';

		$pdProjectPartners = filter_input( INPUT_POST, 'pdProjectPartners', FILTER_SANITIZE_STRING );
		$pdProjectPartners = isset( $pdProjectPartners ) ? esc_html( $pdProjectPartners ) : '';

		$pdProjectStatus = filter_input( INPUT_POST, 'pdProjectStatus', FILTER_SANITIZE_STRING );
		$pdProjectStatus = isset( $pdProjectStatus ) ? esc_html( $pdProjectStatus ) : '';

		$pdProjectDescriptions = filter_input( INPUT_POST, 'pdProjectDescriptions', FILTER_SANITIZE_STRING );
		$pdProjectDescriptions = isset( $pdProjectDescriptions ) ? esc_html( $pdProjectDescriptions ) : '';

		$pdProjectAddress = filter_input( INPUT_POST, 'pdProjectAddress', FILTER_SANITIZE_STRING );
		$pdProjectAddress = isset( $pdProjectAddress ) ? esc_html( $pdProjectAddress ) : '';

		$pdSharingOptions = filter_input( INPUT_POST, 'pdSharingOptions', FILTER_SANITIZE_STRING );
		$pdSharingOptions = isset( $pdSharingOptions ) ? esc_html( $pdSharingOptions ) : '';

		$pdShowVIdeo = filter_input( INPUT_POST, 'pdShowVIdeo', FILTER_SANITIZE_STRING );
		$pdShowVIdeo = isset( $pdShowVIdeo ) ? esc_html( $pdShowVIdeo ) : '';

		$dff_project_sub_title = get_post_meta( $data_project_id, 'dff_project_sub_title', true );
		$dff_project_description = get_post_meta( $data_project_id, 'dff_project_description', true );
		$project_video_url = get_post_meta( $data_project_id, 'project_video_url', true );
		$project_status = get_post_meta( $data_project_id, 'project_status', true );
		
		$project_pilot_date = get_post_meta( $data_project_id, 'project_pilot_date', true );
		$project_completion_date = get_post_meta( $data_project_id, 'project_completion_date', true );

		$pilot_month_picker = get_post_meta( $data_project_id, 'pilot_month_picker', true );
		$completion_month_picker = get_post_meta( $data_project_id, 'completion_month_picker', true );


		$project_map_address = get_post_meta( $data_project_id, 'project_map_address', true );

		$project_slider_images = get_post_meta( $data_project_id, 'project_slider_images', true );
		$map_page = filter_input(INPUT_POST, 'page_url', FILTER_SANITIZE_URL );
		$project_title = get_the_title( $data_project_id );

		$share_arg = array(
			'map-project' => base64_encode( $data_project_id )
		);
		$share_link = add_query_arg( $share_arg, $map_page );

		?>
		 <div class="sidebar-pane" id="left-bar">
			<div class="sidebar-content-wrapper">
			<?php 
					if( isset( $project_slider_images ) && !empty( $project_slider_images ) && 0 !== (int)$pdImageSlider ) { 

						ob_start();

						echo do_shortcode( '[gallery link="none" size="full" ids="'.$project_slider_images.'"]' );

						$dsf_map_gallery = ob_get_clean();
						
						$output_map_gallery = preg_replace('/<br style=(.*)>/mi', '', $dsf_map_gallery);

						//print_r($output_map_gallery);

					?>
						<div class="media-wrapper left_sidebar_gallery">
							<?php echo $output_map_gallery; ?>
						</div>
					<?php } ?>
				
				<div class="brif-wrapper">
					<h2 class="title"><?php echo esc_html( get_the_title( $data_project_id ) ); ?></h2>

					<?php 
					if( 0 !== (int)$pdSharingOptions ) {
						?>
						<div class="map-share project-share">
						<a class="export-link" href="<?php echo esc_url( $share_link ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="20" viewBox="0 0 16 20"><g transform="translate(7.552 0.634)"><rect width="0.896" height="12.113" fill="#0e5d9e"/></g><g transform="translate(3.787)"><path d="M1095.305,173.976l-3.58-3.58-3.58,3.58-.634-.634,4.214-4.214,4.214,4.214Z" transform="translate(-1087.511 -169.128)" fill="#0e5d9e"/></g><g transform="translate(0 5.909)"><path d="M1100,186.515h-12.732a1.6,1.6,0,0,1-1.634-1.547v-11a1.6,1.6,0,0,1,1.634-1.547h3.278v.9h-3.278a.671.671,0,0,0-.688.651v11a.671.671,0,0,0,.688.651H1100a.671.671,0,0,0,.688-.651v-11a.671.671,0,0,0-.688-.651h-3.276v-.9H1100a1.594,1.594,0,0,1,1.634,1.547v11A1.594,1.594,0,0,1,1100,186.515Z" transform="translate(-1085.631 -172.424)" fill="#0e5d9e"/></g></svg>
							<span class="impactmap-screen-reader-text">Export Link</span>
						</a>
						<ul class="social-share enable" style="display: none;">
										<li class="facebook">
											<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url($share_link); ?>">
												<svg id="Bold" enable-background="new 0 0 24 24" height="512" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m15.997 3.985h2.191v-3.816c-.378-.052-1.678-.169-3.192-.169-3.159 0-5.323 1.987-5.323 5.639v3.361h-3.486v4.266h3.486v10.734h4.274v-10.733h3.345l.531-4.266h-3.877v-2.939c.001-1.233.333-2.077 2.051-2.077z"/></svg>
												<span class="impactmap-screen-reader-text">facebook</span>
											</a>
										</li>
										<li class="twitter">
										<a target="_blank" href="https://twitter.com/intent/tweet?url=<?=urlencode( esc_url($share_link) )?>&text=<?php echo esc_attr( $project_title ); ?>">
<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
<g>
	<g>
		<path d="M512,97.248c-19.04,8.352-39.328,13.888-60.48,16.576c21.76-12.992,38.368-33.408,46.176-58.016
			c-20.288,12.096-42.688,20.64-66.56,25.408C411.872,60.704,384.416,48,354.464,48c-58.112,0-104.896,47.168-104.896,104.992
			c0,8.32,0.704,16.32,2.432,23.936c-87.264-4.256-164.48-46.08-216.352-109.792c-9.056,15.712-14.368,33.696-14.368,53.056
			c0,36.352,18.72,68.576,46.624,87.232c-16.864-0.32-33.408-5.216-47.424-12.928c0,0.32,0,0.736,0,1.152
			c0,51.008,36.384,93.376,84.096,103.136c-8.544,2.336-17.856,3.456-27.52,3.456c-6.72,0-13.504-0.384-19.872-1.792
			c13.6,41.568,52.192,72.128,98.08,73.12c-35.712,27.936-81.056,44.768-130.144,44.768c-8.608,0-16.864-0.384-25.12-1.44
			C46.496,446.88,101.6,464,161.024,464c193.152,0,298.752-160,298.752-298.688c0-4.64-0.16-9.12-0.384-13.568
			C480.224,136.96,497.728,118.496,512,97.248z"/>
	</g>
</g>
</svg>
<span class="impactmap-screen-reader-text">twitter</span>	

										</a>
										</li>
										<?php if( wp_is_mobile() ) {
											$whatsapp_share_url = "whatsapp://send?text=" . $project_title . " " . $share_link;
											$share_action = 'data-action="share/whatsapp/share"';
										} else {
											$whatsapp_share_url = "https://web.whatsapp.com/send?text=" . $project_title . " " . $share_link;
											$share_action = '';
										} ?>
										<li class="whatsapp">
											<a target="_blank" href="<?php echo esc_url( $whatsapp_share_url ); ?>" <?php esc_attr_e( $share_action ); ?>>
											<svg height="682pt" viewBox="-23 -21 682 682.66669" width="682pt" xmlns="http://www.w3.org/2000/svg"><path d="m544.386719 93.007812c-59.875-59.945312-139.503907-92.9726558-224.335938-93.007812-174.804687 0-317.070312 142.261719-317.140625 317.113281-.023437 55.894531 14.578125 110.457031 42.332032 158.550781l-44.992188 164.335938 168.121094-44.101562c46.324218 25.269531 98.476562 38.585937 151.550781 38.601562h.132813c174.785156 0 317.066406-142.273438 317.132812-317.132812.035156-84.742188-32.921875-164.417969-92.800781-224.359376zm-224.335938 487.933594h-.109375c-47.296875-.019531-93.683594-12.730468-134.160156-36.742187l-9.621094-5.714844-99.765625 26.171875 26.628907-97.269531-6.269532-9.972657c-26.386718-41.96875-40.320312-90.476562-40.296875-140.28125.054688-145.332031 118.304688-263.570312 263.699219-263.570312 70.40625.023438 136.589844 27.476562 186.355469 77.300781s77.15625 116.050781 77.132812 186.484375c-.0625 145.34375-118.304687 263.59375-263.59375 263.59375zm144.585938-197.417968c-7.921875-3.96875-46.882813-23.132813-54.148438-25.78125-7.257812-2.644532-12.546875-3.960938-17.824219 3.96875-5.285156 7.929687-20.46875 25.78125-25.09375 31.066406-4.625 5.289062-9.242187 5.953125-17.167968 1.984375-7.925782-3.964844-33.457032-12.335938-63.726563-39.332031-23.554687-21.011719-39.457031-46.960938-44.082031-54.890626-4.617188-7.9375-.039062-11.8125 3.476562-16.171874 8.578126-10.652344 17.167969-21.820313 19.808594-27.105469 2.644532-5.289063 1.320313-9.917969-.664062-13.882813-1.976563-3.964844-17.824219-42.96875-24.425782-58.839844-6.4375-15.445312-12.964843-13.359374-17.832031-13.601562-4.617187-.230469-9.902343-.277344-15.1875-.277344-5.28125 0-13.867187 1.980469-21.132812 9.917969-7.261719 7.933594-27.730469 27.101563-27.730469 66.105469s28.394531 76.683594 32.355469 81.972656c3.960937 5.289062 55.878906 85.328125 135.367187 119.648438 18.90625 8.171874 33.664063 13.042968 45.175782 16.695312 18.984374 6.03125 36.253906 5.179688 49.910156 3.140625 15.226562-2.277344 46.878906-19.171875 53.488281-37.679687 6.601563-18.511719 6.601563-34.375 4.617187-37.683594-1.976562-3.304688-7.261718-5.285156-15.183593-9.253906zm0 0" fill-rule="evenodd"/></svg>
											</a>
										</li>
									</ul>
						</div>
						<?php
					}
					
					if( isset( $dff_project_sub_title ) && !empty( $dff_project_sub_title ) ) {
						?>
							<p class="sub-title"><?php echo esc_html( $dff_project_sub_title ); ?></p>
						<?php
					}
					?>
					
					<?php 
					
					$ProjectTechnologies_terms = wp_get_post_terms( $data_project_id, 'project_technologies' );

					if( isset( $ProjectTechnologies_terms ) && !empty( $ProjectTechnologies_terms ) && 0 !== (int)$pdProjectTechnologies ) {
						?>
						<ul class="tags">
							<?php 
								foreach( $ProjectTechnologies_terms as $ProjectTechnologies_terms_data ) {
									?><li><?php echo esc_html( $ProjectTechnologies_terms_data->name ); ?></li><?php
								}
							?>
						</ul>
						<?php
					}

					/**
					 * Project description.
					 */
					if( 0 !== (int)$pdProjectDescriptions ) {
						echo wp_kses_post( wpautop( $dff_project_description ) );
					}

					if( isset( $project_map_address ) && !empty( $project_map_address ) && 0 !== (int)$pdProjectAddress ) {
						?>
							<div class="location">
								<svg xmlns="http://www.w3.org/2000/svg" width="15.526" height="20.5" viewBox="0 0 15.526 20.5"><g transform="translate(-53.15 -15.65)"><g transform="translate(53.4 15.9)"><path d="M59.942,35.386a1.21,1.21,0,0,0,.958.514,1.12,1.12,0,0,0,.958-.514c1.5-2.173,4.3-6.285,5.28-7.734a7.538,7.538,0,0,0-5.678-11.729,4.136,4.136,0,0,0-.537-.023A7.518,7.518,0,0,0,53.4,23.423a7.4,7.4,0,0,0,1.285,4.206C55.643,29.1,58.447,33.19,59.942,35.386Zm.981-19.019a3.785,3.785,0,0,1,.514.023,7.04,7.04,0,0,1,6.519,6.449,6.9,6.9,0,0,1-1.192,4.533c-.958,1.425-3.715,5.444-5.28,7.734a.714.714,0,0,1-1.168,0c-1.542-2.266-4.3-6.285-5.257-7.71a6.971,6.971,0,0,1-1.215-3.949A7.09,7.09,0,0,1,60.923,16.367Z" transform="translate(-53.4 -15.9)" fill="#0e5d9e" stroke="#0e5d9e" stroke-width="0.5"/></g><g transform="translate(56.8 19.213)"><path d="M98.624,65.248A4.124,4.124,0,1,0,94.5,61.124,4.129,4.129,0,0,0,98.624,65.248Zm0-7.8a3.678,3.678,0,1,1-3.678,3.678A3.682,3.682,0,0,1,98.624,57.446Z" transform="translate(-94.5 -57)" fill="#0e5d9e" stroke="#0e5d9e" stroke-width="0.5"/></g></g></svg> 

								<p><?php echo esc_html( $project_map_address ); ?></p>
							</div>
						<?php
					}
					?>
					
				</div>

				<?php 

				$arabic_language_checkbox = get_option( 'arabic_language_checkbox' );
					if( isset( $arabic_language_checkbox ) && !empty( $arabic_language_checkbox ) ) {
						
						$project_status_text = 'حالة المشروع';
						$completion_date_text = 'موعد الإكمال';
						$pilot_date_text = 'التاريخ التجريبي';
						$project_partners_text = 'شركاء المشروع';
						$Video_text = 'فيديو';
						$completed_text = 'منجز';
						$ongoing_text = 'جاري التنفيذ';

					} else {
						
						$project_status_text = 'Project Status';
						$completion_date_text = 'Completion Date';
						$pilot_date_text = 'Pilot Date';
						$project_partners_text = 'Project Partners';
						$Video_text = 'Video';
						$completed_text = 'completed';
						$ongoing_text = 'ongoing';
					}

					if( isset( $project_status ) && !empty( $project_status ) && 0 !== (int)$pdProjectStatus ) {
						if( 'completed' === $project_status ) {
							$project_status_class = 'completed';
							$project_status = $completed_text;
						} else {
							$project_status_class = 'ongoing';
							$project_status = $ongoing_text;
						}
						?>
							<div class="project-status">
								<p class="label"><?php echo esc_html( $project_status_text ); ?></p>
								<p class="status <?php echo esc_html( $project_status_class ); ?>"><?php echo esc_html( $project_status ); ?></p>
							</div>

							<div class="date-wrapper">
								<?php 
									if( isset( $pilot_month_picker ) && !empty( $pilot_month_picker ) ) {
										$pilot_date = esc_html( date( "M",strtotime( $pilot_month_picker ) ) . " ". $project_pilot_date );
									} else {
										$pilot_date = $project_pilot_date;
									}
									if( isset( $project_pilot_date ) && !empty( $project_pilot_date ) ) {
										?>
											<div class="pilot-date">
												<p class="label"><?php echo esc_html( $pilot_date_text ); ?></p>
												<p class="date"><?php echo esc_html( $pilot_date ); ?></p>
											</div>
										<?php
									}

									if( isset( $completion_month_picker ) && !empty( $completion_month_picker ) ) {
										$completion_date = esc_html( date( "M",strtotime( $completion_month_picker ) ) . " ". $project_completion_date  );
									} else {
										$completion_date = $project_completion_date;
									}

									if( isset( $project_completion_date ) && !empty( $project_completion_date ) && 'ongoing' !== $project_status_class ) {
										?>
											<div class="completion-date">
												<p class="label"><?php echo esc_html( $completion_date_text ); ?></p>
												<p class="date"><?php echo esc_html( $completion_date ); ?></p>
											</div>
										<?php
									}

								?>
							</div>

						<?php
					}
				?>

				<?php 
				$project_partners_terms = wp_get_post_terms( $data_project_id, 'project_partners', array(
					'hide_empty' => true,
					'orderby' => 'meta_value_num',
					'meta_key'  => 'project_partner_order',
					'meta_type' => 'NUMERIC',
				) );
				
				if( isset( $project_partners_terms ) && !empty( $project_partners_terms ) && 0 !== (int)$pdProjectPartners ) {
					?>
					<div class="partners-wrapper">
						<p class="label"><?php echo esc_html( $project_partners_text ); ?></p>
						<ul>
							<?php
							foreach( $project_partners_terms as $project_partners_terms_data ) {
								$txt_upload_image = get_term_meta($project_partners_terms_data->term_id, 'term_image', true);
								if( isset( $txt_upload_image ) && !empty( $txt_upload_image ) ) {
									?><li><img src="<?php echo esc_url( $txt_upload_image ); ?>" alt="Logo"></li><?php
								}
							}
							?>
						</ul>
					</div>

					<?php
				}

				if( isset( $project_video_url ) && !empty( $project_video_url ) && 0 !== (int)$pdShowVIdeo ) {

					preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $project_video_url, $match);
					$youtube_id = $match[1];
					
					?>
					<div class="video-wrapper"> 
						<p class="label"><?php echo esc_html( $Video_text ); ?></p>
						
						<?php 
						if( wp_is_mobile() ){
							?><iframe height="192" width="340" src="<?php echo esc_url( $project_video_url ); ?>" frameborder="0" allowfullscreen></iframe><?php
						} else {
							?>
							<a class="map-youtube-video" href="<?php echo esc_url( $project_video_url ); ?>">
								<figure class="sidebar-video-poster">
									<img src="https://img.youtube.com/vi/<?php echo esc_html( $youtube_id ); ?>/hqdefault.jpg" alt="Video Poster">
									<span class="video-play-icon">
										<svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 38 38">
											<g id="Group_221" data-name="Group 221" transform="translate(-0.039 -0.32)">
											<g id="Rectangle_106" data-name="Rectangle 106" transform="translate(0.039 0.32)" fill="none" stroke="#fff" stroke-width="1">
											<rect width="38" height="38" rx="19" stroke="none"/>
											<rect x="0.5" y="0.5" width="37" height="37" rx="18.5" fill="none"/>
											</g>
											<path id="Polygon_18" data-name="Polygon 18" d="M5.5,0,11,8H0Z" transform="translate(24.039 13.32) rotate(90)" fill="#fff"/>
											</g>
										</svg>
									</span>
								</figure>
							</a>
							<?php
						}

						?>
						
					</div>
					<?php
				}

				?>
			</div>                                                    
		</div>
		<?php

		die();
	}

	public function impactmap_get_project_modal_content( $project_id, $exhibition_mode, $SharingOptions, $pButton, $description, $share_url, $PrimaryTechnology ) {
		
		$project_title = get_the_title( $project_id );

		ob_start(); ?>
			<div class="map-popup-outer">
				<div class="map-popup-header">
					<h2 class="title"><?php esc_attr_e( get_the_title( $project_id ) ); ?></h2>
					<ul>
						<?php 
						if( 0 !== (int)$SharingOptions && 1 !== (int)$exhibition_mode ) {
							?>
								<li class="map-share project-share">
									<a class="export-link" href="<?php echo esc_url( $share_url ); ?>">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="20" viewBox="0 0 16 20"><g transform="translate(-3.152)"><g transform="translate(3.152)"><g transform="translate(7.552 0.634)"><rect width="0.896" height="12.113" fill="#fff"/></g><g transform="translate(3.787)"><path d="M1095.305,173.976l-3.58-3.58-3.58,3.58-.634-.634,4.214-4.214,4.214,4.214Z" transform="translate(-1087.511 -169.128)" fill="#fff"/></g><g transform="translate(0 5.909)"><path d="M1100,186.515h-12.732a1.6,1.6,0,0,1-1.634-1.547v-11a1.6,1.6,0,0,1,1.634-1.547h3.278v.9h-3.278a.671.671,0,0,0-.688.651v11a.671.671,0,0,0,.688.651H1100a.671.671,0,0,0,.688-.651v-11a.671.671,0,0,0-.688-.651h-3.276v-.9H1100a1.594,1.594,0,0,1,1.634,1.547v11A1.594,1.594,0,0,1,1100,186.515Z" transform="translate(-1085.631 -172.424)" fill="#fff"/></g></g></g></svg>
									<span class="impactmap-screen-reader-text">Export Link</span>
									</a>
									<ul class="social-share enable" style="display: none;">
										<li class="facebook">
											<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url($share_url); ?>">
												<svg version="1.1" id="Bold" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
<style type="text/css">
	.st0{fill:#0E5D9E;}
</style>
<path class="st0" d="M341.3,85H388V3.6C379.9,2.5,352.2,0,319.9,0c-67.4,0-113.6,42.4-113.6,120.3V192H132v91h74.4v229h91.2V283
	h71.4l11.3-91h-82.7v-62.7C297.5,103,304.6,85,341.3,85L341.3,85z"/>
</svg>
<span class="impactmap-screen-reader-text">facebook</span>
											</a>
										</li>
										<li class="twitter">
										<a target="_blank" href="https://twitter.com/intent/tweet?url=<?=urlencode( esc_url($share_url) )?>&text=<?php echo esc_attr( $project_title ); ?>">
<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
<style type="text/css">
	.st0{fill:#0E5D9E;}
</style>
<g>
	<g>
		<path class="st0" d="M512,97.2c-19,8.4-39.3,13.9-60.5,16.6c21.8-13,38.4-33.4,46.2-58c-20.3,12.1-42.7,20.6-66.6,25.4
			C411.9,60.7,384.4,48,354.5,48c-58.1,0-104.9,47.2-104.9,105c0,8.3,0.7,16.3,2.4,23.9c-87.3-4.3-164.5-46.1-216.4-109.8
			c-9.1,15.7-14.4,33.7-14.4,53.1c0,36.4,18.7,68.6,46.6,87.2c-16.9-0.3-33.4-5.2-47.4-12.9c0,0.3,0,0.7,0,1.2
			c0,51,36.4,93.4,84.1,103.1c-8.5,2.3-17.9,3.5-27.5,3.5c-6.7,0-13.5-0.4-19.9-1.8c13.6,41.6,52.2,72.1,98.1,73.1
			c-35.7,27.9-81.1,44.8-130.1,44.8c-8.6,0-16.9-0.4-25.1-1.4c46.5,30,101.6,47.1,161,47.1c193.2,0,298.8-160,298.8-298.7
			c0-4.6-0.2-9.1-0.4-13.6C480.2,137,497.7,118.5,512,97.2z"/>
	</g>
</g>
</svg>
<span class="impactmap-screen-reader-text">twitter</span>		
</a>
										</li>
										<?php if( wp_is_mobile() ) {
											$whatsapp_share_url = "whatsapp://send?text=" . $project_title . " " . $share_url;
											$share_action = 'data-action="share/whatsapp/share"';
										} else {
											$whatsapp_share_url = "https://web.whatsapp.com/send?text=" . $project_title . " " . $share_url;
											$share_action = '';
										} ?>
										<li class="whatsapp">
											<a target="_blank" href="<?php echo esc_url( $whatsapp_share_url ); ?>" <?php esc_attr_e( $share_action ); ?>>
											<svg height="682pt" viewBox="-23 -21 682 682.66669" width="682pt" xmlns="http://www.w3.org/2000/svg"><path d="m544.386719 93.007812c-59.875-59.945312-139.503907-92.9726558-224.335938-93.007812-174.804687 0-317.070312 142.261719-317.140625 317.113281-.023437 55.894531 14.578125 110.457031 42.332032 158.550781l-44.992188 164.335938 168.121094-44.101562c46.324218 25.269531 98.476562 38.585937 151.550781 38.601562h.132813c174.785156 0 317.066406-142.273438 317.132812-317.132812.035156-84.742188-32.921875-164.417969-92.800781-224.359376zm-224.335938 487.933594h-.109375c-47.296875-.019531-93.683594-12.730468-134.160156-36.742187l-9.621094-5.714844-99.765625 26.171875 26.628907-97.269531-6.269532-9.972657c-26.386718-41.96875-40.320312-90.476562-40.296875-140.28125.054688-145.332031 118.304688-263.570312 263.699219-263.570312 70.40625.023438 136.589844 27.476562 186.355469 77.300781s77.15625 116.050781 77.132812 186.484375c-.0625 145.34375-118.304687 263.59375-263.59375 263.59375zm144.585938-197.417968c-7.921875-3.96875-46.882813-23.132813-54.148438-25.78125-7.257812-2.644532-12.546875-3.960938-17.824219 3.96875-5.285156 7.929687-20.46875 25.78125-25.09375 31.066406-4.625 5.289062-9.242187 5.953125-17.167968 1.984375-7.925782-3.964844-33.457032-12.335938-63.726563-39.332031-23.554687-21.011719-39.457031-46.960938-44.082031-54.890626-4.617188-7.9375-.039062-11.8125 3.476562-16.171874 8.578126-10.652344 17.167969-21.820313 19.808594-27.105469 2.644532-5.289063 1.320313-9.917969-.664062-13.882813-1.976563-3.964844-17.824219-42.96875-24.425782-58.839844-6.4375-15.445312-12.964843-13.359374-17.832031-13.601562-4.617187-.230469-9.902343-.277344-15.1875-.277344-5.28125 0-13.867187 1.980469-21.132812 9.917969-7.261719 7.933594-27.730469 27.101563-27.730469 66.105469s28.394531 76.683594 32.355469 81.972656c3.960937 5.289062 55.878906 85.328125 135.367187 119.648438 18.90625 8.171874 33.664063 13.042968 45.175782 16.695312 18.984374 6.03125 36.253906 5.179688 49.910156 3.140625 15.226562-2.277344 46.878906-19.171875 53.488281-37.679687 6.601563-18.511719 6.601563-34.375 4.617187-37.683594-1.976562-3.304688-7.261718-5.285156-15.183593-9.253906zm0 0" fill-rule="evenodd"/></svg>
											</a>
										</li>
									</ul>
							</li>
							<?php
						}
						?>
						<li>
							<span class="close-map-popup">
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 20 20">
  <g id="Group_1041" data-name="Group 1041" transform="translate(-20.492 -29.515)">
    <path id="Path_1239" data-name="Path 1239" d="M0,0,27.091.03V1.191L0,1.161Z" transform="translate(21.334 29.515) rotate(45)" fill="#fff"/>
    <path id="Path_1240" data-name="Path 1240" d="M0,.03,27.093,0V1.161L0,1.191Z" transform="translate(20.493 48.672) rotate(-45)" fill="#fff"/>
  </g>
</svg>
							</span>
						</li>
					</ul>
					<p class="sub-title"><?php esc_attr_e( get_post_meta($project_id, 'dff_project_sub_title', true ) ); ?></p>
			</div>
			<div class="map-popup-content">
				<?php

				$ProjectTechnologies_terms = get_post_meta( $project_id, 'project_primary_technologies', true );

				$term = get_term_by('slug', $ProjectTechnologies_terms, 'project_technologies');

				if( isset( $ProjectTechnologies_terms ) && !empty( $ProjectTechnologies_terms ) && 0 !== (int)$PrimaryTechnology ) {
					?>
					<ul class="tags">
						<li><?php echo esc_html( $term->name ); ?></li>
					</ul>
					<?php
				}

				if( 0 !== (int)$description ) {
					$dff_short_description = get_post_meta( $project_id, 'dff_short_description', true );
					$dff_short_description_length = strlen( $dff_short_description );
					if( (int)$dff_short_description_length > 170 ) {
						$dff_short_description = substr($dff_short_description, 0, 170);
						$dff_short_description = $dff_short_description. '...';
					}
					?>
						<p><?php esc_html_e( $dff_short_description ); ?></p>
					<?php
				}
				

				$arabic_language_checkbox = get_option( 'arabic_language_checkbox' );
				
				if( isset( $arabic_language_checkbox ) && !empty( $arabic_language_checkbox ) ) {
					$explore_more = 'استكشاف المزيد';
				} else {
					$explore_more = 'Explore More';
				}
				
				if( 1 !== (int)$exhibition_mode && 0 !== (int)$pButton ) {
					?>
						<a href="javascript:void(0)" class="more-button" data-project_id="<?php esc_attr_e( $project_id ); ?>"><?php echo esc_html( $explore_more ); ?> 
						<svg xmlns="http://www.w3.org/2000/svg" width="8.2" height="13.674" viewBox="0 0 8.2 13.674"><g transform="translate(50 -18)">
						<g transform="translate(-50 18)"><path d="M0,.08,9.6,0l0,1.92L0,2Z" transform="translate(1.414 0) rotate(45)" fill="#fff"/>
						<path d="M0,0,9.592.08,9.6,2,0,1.92Z" transform="translate(0 12.26) rotate(-45)" fill="#fff"/></g></g></svg>
						
					</a>				
					<?php
				}
				?>

			</div>
		<?php
		$content = ob_get_clean();
		return $content;
	}

	// remove wp version number from scripts and styles

	public function impactmap_remove_css_js_version( $src ) {
		if( strpos( $src, '?ver=' ) )
			$src = remove_query_arg( 'ver', $src );
		return $src;
	}

}

function impact_map_get_project_term_slug( $term_name ) {
	$terms = get_terms( $term_name, array(
		'hide_empty' => false,
	) );

	$terms_name = array();
	foreach( $terms as $terms_array ) {
		$terms_name[] = $terms_array->slug;
	}

	return $terms_name;

}