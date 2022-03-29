<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.multidots.com
 * @since      1.0.0
 *
 * @package    Impact_Map
 * @subpackage Impact_Map/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Impact_Map
 * @subpackage Impact_Map/admin
 * @author     Multidots <contact@multidots.com>
 */
class Impact_Map_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		include plugin_dir_path( __FILE__ ) . 'partials/dff_all_metaboxes.php';
		include plugin_dir_path( __FILE__ ) . 'partials/dff_impact_map_settings.php';

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action( 'init', array( $this, 'custom_post_type_project_map' ) );
		add_filter( 'manage_dff-project-map_posts_columns', array( $this, 'dff_project_map_list_columns' ) );
		add_filter( 'enter_title_here', array( $this, 'dff_event_title_place_holder' ), 20, 2 );
		add_action( 'add_meta_boxes', array( $this, 'dff_impactmap_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_dff_impactmap_meta_boxes' ) );

		add_action('project_partners_add_form_fields', array( $this, 'add_project_partners_term_image' ), 10, 2);
		add_action('created_project_partners', array( $this, 'save_project_partners_term_image' ), 10, 2);
		add_action('project_partners_edit_form', array( $this, 'edit_project_partners_term_image' ), 10, 2);
		add_action('edited_project_partners', array( $this, 'update_project_partners_term_image' ), 10, 2);

		add_filter('manage_project_partners_custom_column', array( $this, 'project_partner_taxonomy_column_update' ),10,3);
		add_action( 'admin_menu', array( $this, 'project_map_admin_settings_page' ) );
		add_action( 'init', array( $this, 'impact_map_register_dynamic_block' ) );
		
		add_filter('upload_mimes', array( $this, 'dff_mime_types' ) );

		add_filter( 'post_row_actions',array( $this, 'remove_project_map_row_actions' ), 10, 1 );

		add_filter( 'manage_edit-project_partners_columns', array( $this, 'add_project_partners_columns' ) );

	}

	public function remove_project_map_row_actions( $actions ) {
		if( 'dff-project-map' === get_post_type() )
        unset( $actions['view'] );
    	return $actions;
	}

	public function dff_mime_types($mimes) {
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}

	public function add_project_partners_columns( $columns ) {
	
		unset( $columns['slug'] );
		unset( $columns['posts'] );
	
		$columns['logo'] = 'Logo';
		$columns['order'] = 'Order';
		
		
		return $columns;
	}

	public function impact_map_register_dynamic_block() {
		register_block_type( 'imap/imap', array(
			'render_callback' => array($this,'impact_map_dynamic_render_callback'),
			'attributes'      => array(
				'title' => array(
					'type' => 'string',
					'default' => ''
				),
				'showProjectDetail' => array(
					'type' => 'boolean',
					'default' => false
				),
				'showZoom'  => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'showFilter'       => array(
					'type'    => 'boolean',
					'default' => true
				),

				'exhibitionMode'=> array(
					'type'=> 'boolean',
					'default'=> false
				),

				'changeProjectafter'=> array(
					'type'=> 'string',
					'default'=> '5'
				),

				'MapLatitude'=> array(
					'type'=> 'string',
					'default'=> '25.2048493'
				),

				'MapZoom'=> array(
					'type'=> 'string',
					'default'=> '12'
				),

				'MapLongitude'=> array(
					'type'=> 'string',
					'default'=> '55.2707828'
				),
			
				'PrimaryTechnology'=> array(
					'type'=> 'boolean',
					'default'=> true
				),
			
				'Description'=> array(
					'type'=> 'boolean',
					'default'=> true
				),
			
				'SharingOptions'=> array(
					'type'=> 'boolean',
					'default'=> true
				),
				'pButton'=> array(
					'type'=> 'boolean',
					'default'=> true
				),
			
				'CompletedRadioButton'=> array(
					'type'=> 'boolean',
					'default'=> true
				),
				'ProjectTechnologies'=> array(
					'type'=> 'boolean',
					'default'=> true
				),
				'ProjectPartners'=> array(
					'type'=> 'boolean',
					'default'=> true
				),
			
				'pdImageSlider'=> array(
					'type'=> 'boolean',
					'default'=> true
				),

				'pdChangeImageafter'=> array(
					'type'=> 'string',
					'default'=> '20'
				),
			
				'pdProjectTechnologies'=> array(
					'type'=> 'boolean',
					'default'=> true
				),
			
				'pdProjectPartners'=> array(
					'type'=> 'boolean',
					'default'=> true
				),
			
				'pdProjectStatus'=> array(
					'type'=> 'boolean',
					'default'=> true
				),
			
				'pdProjectDescriptions'=> array(
					'type'=> 'boolean',
					'default'=> true
				),
			
				'pdProjectAddress'=> array(
					'type'=> 'boolean',
					'default'=> true
				),
				
				'pdSharingOptions'=> array(
					'type'=> 'boolean',
					'default'=> true
				),

				'pdShowVIdeo'=> array(
					'type'=> 'boolean',
					'default'=> true
				),

				'terms'=> array(
					'type'=> 'string',
					'default'=> array()
				),

				'taxonomies'=> array(
					'type'=> 'array',
					'default'=> []
				)
			),
		));
	}

	public function impact_map_dynamic_render_callback( $attributes ) {
		
		$show_zoom = isset( $attributes['showZoom'] ) && true === $attributes['showZoom'] ? true : false;
		$show_filter = isset( $attributes['showFilter'] ) && true === $attributes['showFilter'] ? "true" : "false";

		$CompletedRadioButton = isset( $attributes['CompletedRadioButton'] ) && true === $attributes['CompletedRadioButton'] ? 1 : 0;
		$ProjectTechnologies = isset( $attributes['ProjectTechnologies'] ) && true === $attributes['ProjectTechnologies'] ? 1 : 0;
		$ProjectPartners = isset( $attributes['ProjectPartners'] ) && true === $attributes['ProjectPartners'] ? 1 : 0;
		
		$exhibitionMode = isset( $attributes['exhibitionMode'] ) && true === $attributes['exhibitionMode'] ? 1 : 0;
		$exhibitionTime = isset( $attributes['changeProjectafter'] ) ? $attributes['changeProjectafter'] : 10;
		
		$MapLatitude = isset( $attributes['MapLatitude'] ) ? $attributes['MapLatitude'] : '25.2048493';
		$MapLongitude = isset( $attributes['MapLongitude'] ) ? $attributes['MapLongitude'] : '55.2707828';
		$MapZoom = isset( $attributes['MapZoom'] ) ? $attributes['MapZoom'] : '12';

		/**
		 * Popup settings
		 */
		$description = isset( $attributes['Description'] ) && true === $attributes['Description'] ? 1 : 0;
		$SharingOptions = isset( $attributes['SharingOptions'] ) && true === $attributes['SharingOptions'] ? 1 : 0;
		$pButton = isset( $attributes['pButton'] ) && true === $attributes['pButton'] ? 1 : 0;
	

		/**
		 * Project detail settings.
		 */
		$pdImageSlider = isset( $attributes['pdImageSlider'] ) && true === $attributes['pdImageSlider'] ? 1 : 0;
		$pdChangeImageafter = isset( $attributes['pdChangeImageafter'] ) ? $attributes['pdChangeImageafter'] : 20;
		$pdProjectTechnologies = isset( $attributes['pdProjectTechnologies'] ) && true === $attributes['pdProjectTechnologies'] ? 1 : 0;
		$pdProjectPartners = isset( $attributes['pdProjectPartners'] ) && true === $attributes['pdProjectPartners'] ? 1 : 0;
		$pdProjectStatus = isset( $attributes['pdProjectStatus'] ) && true === $attributes['pdProjectStatus'] ? 1 : 0;
		$pdProjectDescriptions = isset( $attributes['pdProjectDescriptions'] ) && true === $attributes['pdProjectDescriptions'] ? 1 : 0;
		$pdProjectAddress = isset( $attributes['pdProjectAddress'] ) && true === $attributes['pdProjectAddress'] ? 1 : 0;
		$pdSharingOptions = isset( $attributes['pdSharingOptions'] ) && true === $attributes['pdSharingOptions'] ? 1 : 0;
		$pdShowVIdeo = isset( $attributes['pdShowVIdeo'] ) && true === $attributes['pdShowVIdeo'] ? 1 : 0;
		$PrimaryTechnology = isset( $attributes['PrimaryTechnology'] ) && true === $attributes['PrimaryTechnology'] ? 1 : 0;
		
		$terms = isset( $attributes['terms'] ) ? $attributes['terms'] : array();
		if( !empty( $terms ) ) {
			$terms_array = json_decode( $terms );
			$project_partners = $terms_array->project_partners;
			$project_technologies = $terms_array->project_technologies;
		}
		
		ob_start(); 
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			include_once 'partials/impact-map-admin-display.php';
		} else {
			require_once ( plugin_dir_path( __DIR__ ) . 'admin/partials/impact-map-front-display.php' );
		}
		
		return ob_get_clean();
	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/impact-map-admin.css', array(), $this->version, 'all' );

		$dff_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
		$dff_page = isset( $dff_page ) ? esc_html( $dff_page ) : '';

		if( 'impact-map' === $dff_page ) {
			wp_enqueue_style( $this->plugin_name.'admin-setting', plugin_dir_url( __FILE__ ) . 'css/impact-map-settings.css', array(), $this->version, 'all' );
		}

		$arabic_language_checkbox = get_option( 'arabic_language_checkbox' );
		if( isset( $arabic_language_checkbox ) && !empty( $arabic_language_checkbox ) ) {
			wp_enqueue_style( $this->plugin_name.'arabic', plugin_dir_url( __FILE__ ) . 'css/impact-map-admin-arabic.css', array(), $this->version, 'all' );
		}

		wp_enqueue_style('e2b-admin-ui-css','https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/themes/base/jquery-ui.css',false,"1.9.0",false);


	}

	/**
	 * Register the JavaScript for the admin area.
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
		wp_enqueue_media();
		
		wp_enqueue_script( 'imap-block', plugin_dir_url( __FILE__ ) . 'js/blocks/block.build.js', array( 'jquery', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-components', 'jquery-ui-datepicker' , 'wp-api-fetch','lodash'), $this->version, false );

		$map_api_key = get_option('project_google_map_api_key');
		wp_enqueue_script( 'google-api-js', "https://maps.googleapis.com/maps/api/js?key=".$map_api_key."&libraries=places&v=weekly&&region=AE", array(), $this->version, true );
		wp_enqueue_script( 'google-marker-cluster', plugin_dir_url( __FILE__ ) . 'js/marker-cluster.js', array(), '1.0.1', true );
		wp_enqueue_script( $this->plugin_name.'polyfill', 'https://polyfill.io/v3/polyfill.min.js?features=default', array( 'google-api-js' ), $this->version, true );
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/impact-map-admin.js', array( 'google-api-js','jquery', 'imap-block' ), $this->version, true );

		wp_enqueue_script( 'jquery-ui-datepicker' );


		wp_register_script( 'meta-image', plugin_dir_url( __FILE__ ) . 'js/media-uploader.js', array( 'jquery' ) );
		wp_localize_script( 'meta-image', 'meta_image',
            array(
                'title' => 'Upload an Image',
                'button' => 'Use this Image',
            )
        );
        wp_enqueue_script( 'meta-image' );


	}

	public function google_map_custom_js() {
		$localize_options = array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' )
		);
		wp_register_script('google-map-custom', plugin_dir_url( __FILE__ ) . 'js/google-map-custom.js', array('google-api-js', 'imap-block', 'google-marker-cluster' ), $this->version, true );
		wp_localize_script( 'google-map-custom', 'impactMap', $localize_options );
		wp_enqueue_script( 'google-map-custom' );
	}

	/**
	 * Register custom post types.
	 */
	public function custom_post_type_project_map() {

		/**
		 * Register post type "Project Map"
		 */		
		$labels = array(
			'name'               => _x( 'Project List', 'Post Type General Name', 'dff-impact-map' ),
			'singular_name'      => _x( 'Project Map', 'Post Type Singular Name', 'dff-impact-map' ),
			'menu_name'          => __( 'Project Map', 'dff-impact-map' ),
			'parent_item_colon'  => __( 'Parent Project Map', 'dff-impact-map' ),
			'all_items'          => __( 'Project List', 'dff-impact-map' ),
			'view_item'          => __( 'View Project Map', 'dff-impact-map' ),
			'add_new_item'       => __( 'Add Project Detail', 'dff-impact-map' ),
			'add_new'            => __( 'Add Project', 'dff-impact-map' ),
			'edit_item'          => __( 'Edit Project Detail', 'dff-impact-map' ),
			'update_item'        => __( 'Update Project Map', 'dff-impact-map' ),
			'search_items'       => __( 'Search Project Map', 'dff-impact-map' ),
			'not_found'          => __( 'Not Found', 'dff-impact-map' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'dff-impact-map' ),
			'featured_image'     => __( 'Featured Image', 'dff-impact-map' ),
		);
		 
		$args = array(
			'label'               => __( 'Project Map', 'dff-impact-map' ),
			'description'         => __( 'Project Map', 'dff-impact-map' ),
			'labels'              => $labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'menu_icon'           => 'dashicons-location-alt',
			'show_in_rest'        => true,
		);
		 
		// Registering your Custom Post Type
		register_post_type( 'dff-project-map', $args );

		/**
		 * Register taxonomy "Project Technologies"
		 */
		$labels = array(
			'name'              => _x( 'Project Technologies', 'dff-impact-map' ),
			'singular_name'     => _x( 'Project Technologies', 'dff-impact-map' ),
			'search_items'      => __( 'Search Project Technologies' ),
			'all_items'         => __( 'All Project Technologies' ),
			'parent_item'       => __( 'Parent Project Technologies' ),
			'parent_item_colon' => __( 'Parent Topic:' ),
			'edit_item'         => __( 'Edit Project Technologies' ),
			'update_item'       => __( 'Update Project Technologies' ),
			'add_new_item'      => __( 'Add New Project Technologies' ),
			'new_item_name'     => __( 'New Project Technologies' ),
			'menu_name'         => __( 'Project Technologies' ),
		);

		register_taxonomy(
			'project_technologies', array( 'dff-project-map' ), array(
				'hierarchical'      => true,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'topic' ),
			)
		);

		/**
		 * Register taxonomy "Project Partners"
		 */
		$labels = array(
			'name'              => _x( 'Project Partners', 'dff-impact-map' ),
			'singular_name'     => _x( 'Project Partners', 'dff-impact-map' ),
			'search_items'      => __( 'Search Project Partners' ),
			'all_items'         => __( 'All Project Partners' ),
			'parent_item'       => __( 'Parent Project Partners' ),
			'parent_item_colon' => __( 'Parent Topic:' ),
			'edit_item'         => __( 'Edit Project Partners' ),
			'update_item'       => __( 'Update Project Partners' ),
			'add_new_item'      => __( 'Add New Project Partners' ),
			'new_item_name'     => __( 'New Project Partners' ),
			'menu_name'         => __( 'Project Partners' ),
		);

		register_taxonomy(
			'project_partners', array( 'dff-project-map' ), array(
				'hierarchical'      => true,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'topic' ),
			)
		);

	}

	/**
	 * Set Custom Column in Dff post listing page
	 *
	 * @param $columns
	 *
	 * @return mixed
	 */
	public function dff_project_map_list_columns( $columns ) {
	
		$columns['title'] = 'Project Title';

		unset(
			$columns['author']
		);

		return $columns;

	}


	/**
		 * API endpoints register function will register new custom endpoints in WordPress rest API.
		 *
		 * @action rest_api_init
		 */
	function dsf_register_api_endpoints() {
			$version   = '1';
			$namespace = 'wp/v' . $version;
			$base      = 'all-terms';
			register_rest_route(
				$namespace,
				'/' . $base,
				array(
					'methods'  => 'GET',
					'callback' => array( $this, 'get_all_terms' ),
				)
			);
		}

/**
		 * Get all Terms function will return all the terms.
		 *
		 * @return WP_REST_Response
		 */
		public function get_all_terms() {
			$return = array();
			// get all terms.
			$terms = get_terms( array( 'hide_empty' => false ) );
			// arrange term according to taxonomy.
			foreach ( $terms as $term ) {
				$return[ $term->taxonomy ][] = array(
					'term_id' => $term->term_id,
					'name'    => html_entity_decode( $term->name ),
					'slug'    => $term->slug,
				);
			}
			// set response into the cache.
			set_transient( 'dfs_faq_get_all_terms_cache', $return, 60 * MINUTE_IN_SECONDS + wp_rand( 1, 60 ) );
			return new WP_REST_Response( $return, 200 );
		}

		/**
	 * Update Placeholder of DFF event post title
	 *
	 * @param $title
	 * @param $post
	 * @return string
	 */
	public function dff_event_title_place_holder( $title, $post ) {

		if ( 'dff-project-map' === $post->post_type ) {
			
			$my_title = 'Enter Project Title Here';
			return $my_title;

		}

		return $title;
	}

	/**
	 *  DFF custom post type meta box
	 */
	public function dff_impactmap_meta_boxes() {

		add_meta_box( 'project_sub_title', __( 'Project Sub Title', 'dff-impact-map' ), 'project_sub_title_function', 'dff-project-map', 'normal', 'high' );
		add_meta_box( 'project_description', __( 'Project Description', 'dff-impact-map' ), 'project_description_function', 'dff-project-map', 'normal', 'high' );

		add_meta_box( 'short_description', __( 'Short Description', 'dff-impact-map' ), 'short_description_function', 'dff-project-map', 'normal', 'high' );

		add_meta_box( 'project_map_address', __( 'Map Address', 'dff-impact-map' ), 'project_map_address_function', 'dff-project-map', 'normal', 'high' );

		add_meta_box( 'project_hide_show_switch', __( 'Do you want to hide this project from the MAP?', 'dff-impact-map' ), 'project_hide_show_switch_function', 'dff-project-map', 'normal', 'high' );

		add_meta_box( 'project_location_area', __( 'Project Location & Area', 'dff-impact-map' ), 'project_location_area_function', 'dff-project-map', 'normal', 'high' );

		add_meta_box( 'project_video_url', __( 'Video URL', 'dff-impact-map' ), 'project_video_url_function', 'dff-project-map', 'side', 'low' );
		add_meta_box( 'project_project_status', __( 'Project Status', 'dff-impact-map' ), 'project_status_function', 'dff-project-map', 'side', 'low' );
		add_meta_box( 'project_pilot_date', __( 'Pilot Date', 'dff-impact-map' ), 'project_pilot_date_function', 'dff-project-map', 'side', 'low' );
		add_meta_box( 'project_completion_date', __( 'Completion Date', 'dff-impact-map' ), 'project_completion_date_function', 'dff-project-map', 'side', 'low' );
		add_meta_box( 'project_slider_images', __( 'Project Slider Images', 'dff-impact-map' ), 'project_slider_images_function', 'dff-project-map', 'side', 'low' );

		add_meta_box( 'project_primary_technologies', __( 'Project Primary Technology (Popup)', 'dff-impact-map' ), 'project_primary_technologies_function', 'dff-project-map', 'side', 'low' );

	}


	/**
	 * Save metabox values
	 *
	 * @throws Exception
	 */
	public function save_dff_impactmap_meta_boxes() {
		$post_id = get_the_id();

		/**
		 * Update project sub title.
		 * 
		 */
		$dff_project_sub_title = filter_input( INPUT_POST, 'dff_project_sub_title', FILTER_SANITIZE_STRING );
		$dff_project_sub_title = isset( $dff_project_sub_title ) ? esc_html( $dff_project_sub_title ) : '';
		update_post_meta( $post_id, 'dff_project_sub_title', $dff_project_sub_title );

		/**
		 * Update project description.
		 * 
		 */
		$dff_project_description = filter_input( INPUT_POST, 'dff_project_description', FILTER_SANITIZE_STRING );
		$dff_project_description = isset( $dff_project_description ) ? esc_html( $dff_project_description ) : '';
		update_post_meta( $post_id, 'dff_project_description', $dff_project_description );

		/**
		 * Update short description.
		 * 
		 */
		$dff_short_description = filter_input( INPUT_POST, 'dff_short_description', FILTER_SANITIZE_STRING );
		$dff_short_description = isset( $dff_short_description ) ? esc_html( $dff_short_description ) : '';
		update_post_meta( $post_id, 'dff_short_description', $dff_short_description );

		/**
		 * Update project video URL.
		 * 
		 */
		$project_video_url = filter_input( INPUT_POST, 'project_video_url', FILTER_SANITIZE_STRING );
		$project_video_url = isset( $project_video_url ) ? esc_html( $project_video_url ) : '';
		update_post_meta( $post_id, 'project_video_url', $project_video_url );

		/**
		 * Update project Status.
		 * 
		 */
		$project_status = filter_input( INPUT_POST, 'project_status', FILTER_SANITIZE_STRING );
		$project_status = isset( $project_status ) ? esc_html( $project_status ) : '';
		update_post_meta( $post_id, 'project_status', $project_status );

		/**
		 * Update project pilot date.
		 * 
		 */
		$project_pilot_date = filter_input( INPUT_POST, 'project_pilot_date', FILTER_SANITIZE_STRING );
		$project_pilot_date = isset( $project_pilot_date ) ? esc_html( $project_pilot_date ) : '';
		update_post_meta( $post_id, 'project_pilot_date', $project_pilot_date );

		/**
		 * Update pilot_month_picker.
		 * 
		 */
		$pilot_month_picker = filter_input( INPUT_POST, 'pilot_month_picker', FILTER_SANITIZE_STRING );
		$pilot_month_picker = isset( $pilot_month_picker ) ? esc_html( $pilot_month_picker ) : '';
		update_post_meta( $post_id, 'pilot_month_picker', $pilot_month_picker );

		/**
		 * Update pilot_month_picker.
		 * 
		 */
		$completion_month_picker = filter_input( INPUT_POST, 'completion_month_picker', FILTER_SANITIZE_STRING );
		$completion_month_picker = isset( $completion_month_picker ) ? esc_html( $completion_month_picker ) : '';
		update_post_meta( $post_id, 'completion_month_picker', $completion_month_picker );

		/**
		 * Update project Completion Date.
		 * 
		 */
		$project_completion_date = filter_input( INPUT_POST, 'project_completion_date', FILTER_SANITIZE_STRING );
		$project_completion_date = isset( $project_completion_date ) ? esc_html( $project_completion_date ) : '';
		update_post_meta( $post_id, 'project_completion_date', $project_completion_date );

		/**
		 * Update project map URL.
		 * 
		 */
		$project_map_address = filter_input( INPUT_POST, 'project_map_address', FILTER_SANITIZE_STRING );
		$project_map_address = isset( $project_map_address ) ? esc_html( $project_map_address ) : '';
		update_post_meta( $post_id, 'project_map_address', $project_map_address );

		/**
		 * Update project status hide/show.
		 * 
		 */
		$project_hide_show_switch = filter_input( INPUT_POST, 'project_hide_show_switch', FILTER_SANITIZE_STRING );
		$project_hide_show_switch = isset( $project_hide_show_switch ) ? esc_html( $project_hide_show_switch ) : '';
		update_post_meta( $post_id, 'project_hide_show_switch', $project_hide_show_switch );

		$mytheme_feat_gallery_nonce = filter_input( INPUT_POST, 'mytheme_feat_gallery_nonce', FILTER_SANITIZE_STRING );
		$mytheme_feat_gallery_nonce = isset( $mytheme_feat_gallery_nonce ) ? esc_html( $mytheme_feat_gallery_nonce ) : '';

		/**
		 * Update Project Slider Images
		 */
		if ( !isset( $mytheme_feat_gallery_nonce ) ) {
			return $post_id;
		}
		 
		if ( !wp_verify_nonce( $mytheme_feat_gallery_nonce, 'save_feat_gallery') ) {
			return $post_id;
		} 
		
		$project_slider_images = filter_input( INPUT_POST, 'project_slider_images', FILTER_SANITIZE_STRING );
		$project_slider_images = isset( $project_slider_images ) ? esc_html( $project_slider_images ) : '';

		if ( isset( $_POST[ 'project_slider_images' ] ) ) {
			update_post_meta( $post_id, 'project_slider_images', esc_attr( $project_slider_images ) );
		} else {
			update_post_meta( $post_id, 'project_slider_images', '' );
		}

		/**
		 * Update map search section.
		 * 
		 */
		$project_search_location = filter_input( INPUT_POST, 'project_search_location', FILTER_SANITIZE_STRING );
		update_post_meta( $post_id, 'project_search_location', $project_search_location );

		$project_latitude = filter_input( INPUT_POST, 'project_latitude', FILTER_SANITIZE_STRING );
		$project_latitude = isset( $project_latitude ) ? esc_html( $project_latitude ) : '';
		update_post_meta( $post_id, 'project_latitude', $project_latitude );

		$project_longitude = filter_input( INPUT_POST, 'project_longitude', FILTER_SANITIZE_STRING );
		$project_longitude = isset( $project_longitude ) ? esc_html( $project_longitude ) : '';
		update_post_meta( $post_id, 'project_longitude', $project_longitude );

		/**
		 * Project Primary Technology.
		 * 
		 */
		$project_primary_technologies = filter_input( INPUT_POST, 'project_primary_technologies', FILTER_SANITIZE_STRING );
		$project_primary_technologies = isset( $project_primary_technologies ) ? esc_html( $project_primary_technologies ) : '';
		update_post_meta( $post_id, 'project_primary_technologies', $project_primary_technologies );

	}

	/**
	 * Image field for Project Partners
	 */
	public function add_project_partners_term_image($taxonomy){
		?>
		
		<div class="form-field term-group form-wrap ">
			<label for="">Set Order</label>
			<input type="number" min="1" name="project_partner_order" id="project_partner_order" value="" style="width: 560px">
			<p>The number that how Project Partners is appears on front site.</p>
		</div>

		<div class="form-field term-group form-wrap">
			<label for="">Partner Logo</label>
			<input type="text" name="txt_upload_image" id="txt_upload_image" value="" style="width: 77%">
			<input type="button" id="upload_image_btn" class="button" value="Upload an Image" />
			<p>Upload project Partner logo in transparent with X Y size.</p>
		</div>
		<?php
	}

	/**
	 * Image field for Project Partners
	 */
	public function save_project_partners_term_image($term_id, $tt_id) {

		$txt_upload_image = filter_input( INPUT_POST, 'txt_upload_image', FILTER_SANITIZE_STRING );
		$txt_upload_image = isset( $txt_upload_image ) ? esc_html( $txt_upload_image ) : '';

		if (isset( $txt_upload_image ) && '' !== $txt_upload_image ){
			$group = $txt_upload_image;
			add_term_meta($term_id, 'term_image', $group, true);
		}

		$project_partner_order = filter_input( INPUT_POST, 'project_partner_order', FILTER_SANITIZE_STRING );
		$project_partner_order = isset( $project_partner_order ) ? esc_html( $project_partner_order ) : '';

		if (isset( $project_partner_order ) && '' !== $project_partner_order ){
			$project_partner_order = $project_partner_order;
			add_term_meta($term_id, 'project_partner_order', $project_partner_order, true);
		}

	}
	
	/**
	 * Image field for Project Partners
	 */
	public function edit_project_partners_term_image($term, $taxonomy) {
		// get current group
		$txt_upload_image = get_term_meta($term->term_id, 'term_image', true);
		$project_partner_order = get_term_meta($term->term_id, 'project_partner_order', true);

		?>
		<table class="form-table" role="presentation">
			<tbody>
				<tr class="form-field term-name-wrap">
					<th scope="row"><label for="name">Set Order</label></th>
					<td>
						<input type="number" min="1" name="project_partner_order" id="project_partner_order" value="<?php echo esc_attr( $project_partner_order ); ?>" style="">
					</td>
				</tr>

				<tr class="form-field term-name-wrap">
					<th scope="row"><label for="name">Partner Logo</label></th>
					<td>
						<?php 
							if( isset( $txt_upload_image ) && !empty( $txt_upload_image ) ) {
								?>
									<img src="<?php echo esc_url( $txt_upload_image ); ?>" alt="term-image" class="term-image">
								<?php
							}
						?>
						<input type="text" name="txt_upload_image" id="txt_upload_image" value="<?php echo esc_attr( $txt_upload_image ); ?>" style="width: 77%">
						<input type="button" id="upload_image_btn" class="button" value="Upload an Image" />
					</td>
				</tr>
			</tbody>
		</table>
	<?php
	}

	/**
	 * Image field for Project Partners
	 */
	public function update_project_partners_term_image($term_id, $tt_id) {

		$txt_upload_image = filter_input( INPUT_POST, 'txt_upload_image', FILTER_SANITIZE_STRING );
		$txt_upload_image = isset( $txt_upload_image ) ? esc_html( $txt_upload_image ) : '';

		if (isset( $txt_upload_image ) && '' !== $txt_upload_image ){
			$group = $txt_upload_image;
			update_term_meta($term_id, 'term_image', $group);
		}

		$project_partner_order = filter_input( INPUT_POST, 'project_partner_order', FILTER_SANITIZE_STRING );
		$project_partner_order = isset( $project_partner_order ) ? esc_html( $project_partner_order ) : '';

		if (isset( $project_partner_order ) && '' !== $project_partner_order ){
			$project_partner_order = $project_partner_order;
			update_term_meta($term_id, 'project_partner_order', $project_partner_order);
		}

	}

	/**
	 * Function for add Impact Map setting page.
	 */
	public function project_map_admin_settings_page() {

		add_menu_page(
			__( 'Impact Map', 'dff-impact-map' ),
			'Impact Map',
			'manage_options',
			'impact-map',
			'dff_impact_map_settings_function',
			'dashicons-location',
			6
		);

	}

	/**
	 * Update project partner taxonomy column fields.
	 */
	public function project_partner_taxonomy_column_update( $content, $column_name, $term_id ) {

		switch ($column_name) {
			case 'logo':
				$term_image = get_term_meta( $term_id, 'term_image', true);
				if( !empty( $term_image ) ) {
					$content = '<span><img src="' . $term_image. '" class="wp-post-image" style="width:200px" /></span>';
				} else {
					$content = '-';
				}
				
				break;
	
			case 'order':
				$term_order = get_term_meta( $term_id, 'project_partner_order', true);
				$term_order = ! empty( $term_order ) ? $term_order : "-"; 
				$content = $term_order;
				break;
	
			default:
				break;
		}
		return $content;
		
	}

	public function impactmap_get_project_markers_admin_callback() {
		$tax_query = array();
		$partners_query_args = array();
		$technology_query_args = array();
		$all_projects = array();

		$terms = stripslashes( $_POST['project_terms'] );
		$project_terms = json_decode( $terms, true );
		$project_partners = isset( $project_terms['project_partners'] ) && !empty( $project_terms['project_partners'] ) ? $project_terms['project_partners'] : array();
		$project_technologies = isset( $project_terms['project_technologies'] ) && !empty( $project_terms['project_technologies'] ) ? $project_terms['project_technologies'] : array();

		if( empty( $project_partners ) ) {
			
			$terms = get_terms( 'project_partners', array(
				'hide_empty' => false,
			) );

			foreach( $terms as $terms_data ) {
				$project_partners[] = $terms_data->slug;
			}


		} else if( empty( $project_technologies ) ) {

			$terms = get_terms( 'project_technologies', array(
				'hide_empty' => false,
			) );

			foreach( $terms as $terms_data ) {
				$project_technologies[] = $terms_data->slug;
			}

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
				)
			)
		);
		if( !empty( $tax_query ) ) {
			$project_args['tax_query'] = $tax_query;
		}

		$projects = get_posts( $project_args );
		if( !empty( $projects ) ) {
			foreach( $projects as $project_id ) {
				$project_details['latitude'] = get_post_meta( $project_id, 'project_latitude', true );
				$project_details['longitude'] = get_post_meta( $project_id, 'project_longitude', true );
				$all_projects[] = $project_details;
			}
		}

		$return = array(
			'project_markers' => $all_projects,
			'count'      	   => count( $all_projects )
		);

		wp_send_json_success( $return );
	}

}
