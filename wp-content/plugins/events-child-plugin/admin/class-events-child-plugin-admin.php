<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Events_Child_Plugin
 * @subpackage Events_Child_Plugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Events_Child_Plugin
 * @subpackage Events_Child_Plugin/admin
 * @author     multidots <r@test.com>
 */
class Events_Child_Plugin_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		include plugin_dir_path( __FILE__ ) . 'partials/events_all_metaboxes.php';

		add_action( 'admin_menu', array( $this, 'cep_admin_settings_page' ) );
		add_action( 'init', array( $this, 'custom_post_type_events' ) );
		add_action( 'init', array( $this, 'events_categories' ) );
		add_action( 'init', array( $this, 'events_tags' ) );
		add_action( 'add_meta_boxes', array( $this, 'event_editor_meta_boxes' ) );
		add_action( 'init', array( $this, 'add_cors_http_header' ) );

		add_action( 'wp_ajax_entered_token_validation', array( $this, 'entered_token_validation' ) );
		add_action( 'wp_ajax_nopriv_entered_token_validation', array( $this, 'entered_token_validation' ) );

		add_action( 'wp_ajax_events_render_callback', array( $this, 'events_render_callback' ) );
		add_action( 'wp_ajax_nopriv_events_render_callback', array( $this, 'events_render_callback' ) );

		add_action( 'wp_ajax_entered_language_ajax', array( $this, 'entered_language_ajax' ) );
		add_action( 'wp_ajax_nopriv_entered_language_ajax', array( $this, 'entered_language_ajax' ) );

		add_action( 'wp_ajax_wizard_sync_event_ajax', array( $this, 'wizard_sync_event_ajax' ) );
		add_action( 'wp_ajax_nopriv_wizard_sync_event_ajax', array( $this, 'wizard_sync_event_ajax' ) );

		add_action( 'wp_ajax_sync_manually_event_ajax', array( $this, 'sync_manually_event_ajax' ) );
		add_action( 'wp_ajax_nopriv_sync_manually_event_ajax', array( $this, 'sync_manually_event_ajax' ) );

		add_action( 'wp_ajax_dff_update_cat_tags', array( $this, 'dff_update_cat_tags' ) );
		add_action( 'wp_ajax_nopriv_dff_update_cat_tags', array( $this, 'dff_update_cat_tags' ) );

		add_action( 'wp_ajax_dff_reset_plugin', array( $this, 'dff_reset_plugin' ) );
		add_action( 'wp_ajax_nopriv_dff_reset_plugin', array( $this, 'dff_reset_plugin' ) );

		add_action( 'wp_ajax_event_history_table_pagination', array( $this, 'event_history_table_pagination' ) );
		add_action( 'wp_ajax_nopriv_event_history_table_pagination', array( $this, 'event_history_table_pagination' ) );

		add_filter( 'manage_dff-events_posts_columns', array( $this, 'dff_set_dff_events_list_columns' ) );

		add_action( 'init', array( $this, 'do_output_buffer' ) );

		add_action( 'admin_menu', array( $this, 'disable_new_event_posts' ) );

		$this->dff_events_constants();
	}

	/**
	 * Define global namespace of API site URL.
	 */
	public function dff_events_constants() {
		define( 'EVENTS_POST_TYPE', 'dff-events' );
		$ip  = $this->dff_events_get_ip();
		$url = '192.168.50.1' === $ip || '127.0.0.1' === $ip ? 'https://events.dubaifuture.ae' : 'https://events.dubaifuture.ae';
		define( 'EVENT_SOURCE_URL', $url );

		define( 'EVENT_PLUGIN_URL', plugin_dir_url( dirname( __FILE__ ) ) );
		define( 'EVENT_PLUGIN_PATH', plugin_dir_path( dirname( __FILE__ ) ) );
	}

	/**
	 * return IP address of a currunt site.
	 *
	 * @return array|false|string
	 */
	public function dff_events_get_ip() {
		$ipaddress = '';
		if ( getenv( 'HTTP_CLIENT_IP' ) ) {
			$ipaddress = getenv( 'HTTP_CLIENT_IP' );
		} elseif ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
			$ipaddress = getenv( 'HTTP_X_FORWARDED_FOR' );
		} elseif ( getenv( 'HTTP_X_FORWARDED' ) ) {
			$ipaddress = getenv( 'HTTP_X_FORWARDED' );
		} elseif ( getenv( 'HTTP_FORWARDED_FOR' ) ) {
			$ipaddress = getenv( 'HTTP_FORWARDED_FOR' );
		} elseif ( getenv( 'HTTP_FORWARDED' ) ) {
			$ipaddress = getenv( 'HTTP_FORWARDED' );
		} elseif ( getenv( 'REMOTE_ADDR' ) ) {
			$ipaddress = getenv( 'REMOTE_ADDR' );
		} else {
			$ipaddress = 'UNKNOWN';
		}

		return $ipaddress;
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
		 * defined in Events_Child_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Events_Child_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/events-child-plugin-admin.css', array(), $this->version, 'all' );
		$wizard_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
		$wizard_page = isset( $wizard_page ) ? $wizard_page : '';

		if ( 'event_setup_wizard' === $wizard_page ) {
			wp_enqueue_style( $this->plugin_name . 'wizard', plugin_dir_url( __FILE__ ) . 'css/events-child-plugin-wizard.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name . 'bootstrap.min', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );
		}
		wp_enqueue_style( $this->plugin_name . '-dataTables', plugin_dir_url( __FILE__ ) . 'css/dataTables.jqueryui.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . 'fontawesome', plugin_dir_url( __FILE__ ) . 'css/fontawesome.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . 'fa-all', plugin_dir_url( __FILE__ ) . 'css/all.min.css', array(), $this->version, 'all' );

		// Gutenberg Block CSS.
		wp_enqueue_style( $this->plugin_name . 'gutenberg-block', EVENT_PLUGIN_URL . 'assets/css/events-block.css', array(), $this->version, 'all' );
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
		 * defined in Events_Child_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Events_Child_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/events-child-plugin-admin.js', array( 'jquery' ), $this->version, false );
		$page_slug = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
		$page_slug = isset( $page_slug ) ? $page_slug : '';
		if ( 'event_setup_wizard' === $page_slug ) {
			wp_enqueue_script( $this->plugin_name . 'wizard', plugin_dir_url( __FILE__ ) . 'js/events-child-plugin-wizard.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name . 'bootstrap.min', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( 'jquery' ), $this->version, false );
		}
		wp_enqueue_script( $this->plugin_name . 'datatables', plugin_dir_url( __FILE__ ) . 'js/datatables.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . 'fontawesome', plugin_dir_url( __FILE__ ) . 'js/fontawesome.min.js', array( 'jquery' ), $this->version, false );
		wp_localize_script(
			$this->plugin_name, 'ajax_object', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			)
		);

		// Gutenberg Block JS.
		$screen = get_current_screen();
		if ( $screen->is_block_editor ) {
			wp_enqueue_script( 'events-block-js', plugin_dir_url( __FILE__ ) . 'block/events-block.build.js', '', '1.0.6', true );
		}
	}

	/**
	 * Register Dff event post type.
	 */
	public function custom_post_type_events() {
		$labels = array(
			'name'               => _x( 'Events', 'Post Type General Name', 'events-child-plugin' ),
			'singular_name'      => _x( 'Event', 'Post Type Singular Name', 'events-child-plugin' ),
			'menu_name'          => __( 'Events', 'events-child-plugin' ),
			'parent_item_colon'  => __( 'Parent Event', 'events-child-plugin' ),
			'all_items'          => __( 'All Events', 'events-child-plugin' ),
			'view_item'          => __( 'View Event', 'events-child-plugin' ),
			'add_new_item'       => __( 'Add Event Detail', 'events-child-plugin' ),
			'add_new'            => __( 'Add New Event', 'events-child-plugin' ),
			'edit_item'          => __( 'Edit Event Detail', 'events-child-plugin' ),
			'update_item'        => __( 'Update Event', 'events-child-plugin' ),
			'search_items'       => __( 'Search Event', 'events-child-plugin' ),
			'not_found'          => __( 'Not Found', 'events-child-plugin' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'events-child-plugin' ),
		);

		$args = array(
			'label'               => __( 'Event', 'events-child-plugin' ),
			'description'         => __( 'Event', 'events-child-plugin' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'author', 'thumbnail', 'revisions', 'editor', 'custom-fields' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'menu_icon'           => 'dashicons-calendar',
			'show_in_rest'        => true,
			'rewrite'             => [
				'slug'       => 'event',
				'with_front' => false,
			],
		);
		register_post_type( 'dff-events', $args );

	}

	/**
	 * Register Dff event categories taxonomy.
	 */
	public function events_categories() {

		$labels = array(
			'name'              => __( 'Event Categories', 'events-child-plugin' ),
			'singular_name'     => __( 'Event Category', 'events-child-plugin' ),
			'search_items'      => __( 'Search Event Category', 'events-child-plugin' ),
			'all_items'         => __( 'All Event Categories', 'events-child-plugin' ),
			'parent_item'       => __( 'Parent Event Category', 'events-child-plugin' ),
			'parent_item_colon' => __( 'Parent Topic:', 'events-child-plugin' ),
			'edit_item'         => __( 'Edit Event Category', 'events-child-plugin' ),
			'update_item'       => __( 'Update Event Category', 'events-child-plugin' ),
			'add_new_item'      => __( 'Add New Event Category', 'events-child-plugin' ),
			'new_item_name'     => __( 'New Event Category', 'events-child-plugin' ),
			'menu_name'         => __( 'Event Categories', 'events-child-plugin' ),
		);

		register_taxonomy(
			'events_categories', array( 'dff-events' ), array(
				'hierarchical'      => true,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'show_in_rest'      => true,
				'rewrite'           => array( 'slug' => 'topic' ),
			)
		);

	}

	/**
	 * Disable New Event Post Links.
	 */
	public function disable_new_event_posts() {
		// Hide sidebar link
		global $submenu;
		unset( $submenu['edit.php?post_type=dff-events'][10] ); // add new event
		unset( $submenu['edit.php?post_type=dff-events'][15] ); // categorise
		unset( $submenu['edit.php?post_type=dff-events'][16] ); // tags
	}

	/**
	 * Register Dff event tags taxonomy.
	 */
	public function events_tags() {

		$labels = array(
			'name'              => __( 'Event Tags', 'events-child-plugin' ),
			'singular_name'     => __( 'Event Tag', 'events-child-plugin' ),
			'search_items'      => __( 'Search Event Tag', 'events-child-plugin' ),
			'all_items'         => __( 'All Event Tags', 'events-child-plugin' ),
			'parent_item'       => __( 'Parent Event Tag', 'events-child-plugin' ),
			'parent_item_colon' => __( 'Parent Topic:', 'events-child-plugin' ),
			'edit_item'         => __( 'Edit Event Tag', 'events-child-plugin' ),
			'update_item'       => __( 'Update Event Tag', 'events-child-plugin' ),
			'add_new_item'      => __( 'Add New Event Tag', 'events-child-plugin' ),
			'new_item_name'     => __( 'New Event Tag', 'events-child-plugin' ),
			'menu_name'         => __( 'Event Tags', 'events-child-plugin' ),
		);

		register_taxonomy(
			'events_tags', array( 'dff-events' ), array(
				'hierarchical'      => true,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'show_in_rest'      => true,
				'rewrite'           => array( 'slug' => 'topic' ),
			)
		);

	}

	/**
	 * Register meta boxes for the event post type.
	 */
	public function event_editor_meta_boxes() {

	}


	/**
	 * Add admin menu for Settings and Wizard page.
	 */
	public function cep_admin_settings_page() {

		add_submenu_page(
			'edit.php?post_type=dff-events',
			__( 'Settings', 'events-child-plugin' ),
			__( 'Settings', 'events-child-plugin' ),
			'manage_options',
			'cep-settings',
			array( $this, 'cep_settings_function' )
		);

		// Register the hidden submenu.
		add_submenu_page(
			'', __( 'Event Wizard', 'textdomain' ), 'Event Wizard', 'manage_options', 'event_setup_wizard', 'event_setup_wizard_function'
		);

	}

	/**
	 * Function for enter token validation in wizard section.
	 */
	public function entered_token_validation() {

		$entered_token    = filter_input( INPUT_POST, 'entered_token', FILTER_SANITIZE_STRING );
		$current_site_url = filter_input( INPUT_POST, 'current_site_url', FILTER_SANITIZE_STRING );

		$entered_token    = isset( $entered_token ) ? esc_html( $entered_token ) : '';
		$current_site_url = isset( $current_site_url ) ? $current_site_url : '';

		$request_url = EVENT_SOURCE_URL . "/wp-json/events/token?key=$entered_token&domain=$current_site_url";
		$response    = wp_remote_get( $request_url );

		if ( 'true' === $response['body'] ) {
			update_option( 'validate_token_true', $entered_token );
		} else {
			update_option( 'validate_token_true', '' );
		}

		echo esc_html( $response['body'] );
		die();

	}

	/**
	 * Function for sync category and tags based on the selected wizard option.
	 */
	public function entered_language_ajax() {

		$entered_token    = filter_input( INPUT_POST, 'entered_token', FILTER_SANITIZE_STRING );
		$current_site_url = filter_input( INPUT_POST, 'current_site_url', FILTER_SANITIZE_STRING );
		$language         = filter_input( INPUT_POST, 'language', FILTER_SANITIZE_STRING );
		$entered_token    = isset( $entered_token ) ? esc_html( $entered_token ) : '';
		$current_site_url = isset( $current_site_url ) ? esc_html( $current_site_url ) : '';
		$language         = isset( $language ) ? esc_html( $language ) : 'english';

		if ( 'english' === $language ) {
			?>
			<div class="english_section">
				<div class="english_category_tags_section">
					<div class="english_category_block">
						<h4>Events Categories</h4>
						<?php

						$request_url = EVENT_SOURCE_URL . "/wp-json/events/cats?key=$entered_token&domain=$current_site_url";
						$response    = wp_remote_get( $request_url );
						$response    = json_decode( $response['body'] );

						$categories_data_array = (array) $response->data;
						$parent_array          = array();
						$child_array           = array();

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
							<ul id="events_english_categorieschecklist" class="categorychecklist form-no-clear">
								<li><label><input type="checkbox" name="select-all" id="select-all-eng-cat" value="All"/>All</label></li>
								<?php
								foreach ( $parent_array as $key => $parent_array_value ) {
									?>
									<li><label class="post_type_lable" for="<?php echo esc_attr( $parent_array_value['name'] ); ?>"><input name="emp_english_category[]" class="emp_english_category" type="checkbox" id="<?php echo esc_attr( $parent_array_value['name'] ); ?>" value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $parent_array_value['name'] ); ?></label></li>
									<?php
									if ( isset( $parent_array_value['child'] ) && ! empty( $parent_array_value['child'] ) ) {
										?>
										<ul class="event_child_category">
											<?php
											foreach ( $parent_array_value['child'] as $key => $event_child_value ) {
												?>
												<li><label class="post_type_lable" for="<?php echo esc_attr( $event_child_value['name'] ); ?>"><input name="emp_english_category[]" class="emp_english_category" type="checkbox" id="<?php echo esc_attr( $event_child_value['name'] ); ?>" value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $event_child_value['name'] ); ?></label></li>
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

					<div class="english_tags_block">
						<input type="hidden" class="post_id" value="152">
						<h4>Events Tags</h4>
						<?php
						$domain                = filter_input( INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_STRING );
						$request_url           = EVENT_SOURCE_URL . "/wp-json/events/tags?key=$entered_token&domain=$current_site_url";
						$response              = wp_remote_get( $request_url );
						$response              = json_decode( $response['body'] );
						$categories_data_array = (array) $response->data;
						$parent_array          = array();
						$child_array           = array();

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
							<ul id="events_english_tagschecklist" class="tagschecklist form-no-clear">
								<li><label><input type="checkbox" name="select-all" id="select-all-eng-tags" value="All"/>All</label></li>
								<?php
								foreach ( $parent_array as $key => $parent_array_value ) {
									?>
									<li><label class="post_type_lable" for="<?php echo esc_attr( $parent_array_value['name'] ); ?>"><input name="emp_english_tags[]" class="emp_english_tags" type="checkbox" id="<?php echo esc_attr( $parent_array_value['name'] ); ?>" value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $parent_array_value['name'] ); ?></label></li>
									<?php
									if ( isset( $parent_array_value['child'] ) && ! empty( $parent_array_value['child'] ) ) {
										?>
										<ul class="event_child_category">
											<?php
											foreach ( $parent_array_value['child'] as $key => $event_child_value ) {
												?>
												<li><label class="post_type_lable" for="<?php echo esc_attr( $event_child_value['name'] ); ?>"><input name="emp_english_tags[]" class="emp_english_tags" type="checkbox" id="<?php echo esc_attr( $event_child_value['name'] ); ?>" value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $event_child_value['name'] ); ?></label></li>
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
			<?php
		} else {
			?>
			<div class="arabic_section">
				<div class="arabic_category_tags_section">
					<div class="arabic_category_block">
						<h4>فئات الأحداث</h4>
						<?php
						$domain                = filter_input( INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_STRING );
						$request_url           = EVENT_SOURCE_URL . "/wp-json/events/cats?key=$entered_token&domain=$current_site_url&lang=ar";
						$response_ar           = wp_remote_get( $request_url );
						$response              = json_decode( $response_ar['body'] );
						$categories_data_array = (array) $response->data;
						$parent_array          = array();
						$child_array           = array();

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
							<ul id="events_arbic_categorieschecklist" class="categorychecklist form-no-clear">
								<li><label><input type="checkbox" name="select-all" id="select-all-ar-cats" value="All"/>الكل</label></li>
								<?php
								foreach ( $parent_array as $key => $parent_array_value ) {
									?>
									<li><label class="post_type_lable" for="<?php echo esc_attr( $parent_array_value['name'] ); ?>"><input class="emp_arabic_category" name="emp_arabic_category[]" type="checkbox" id="<?php echo esc_attr( $parent_array_value['name'] ); ?>" value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $parent_array_value['name'] ); ?></label></li>
									<?php
									if ( isset( $parent_array_value['child'] ) && ! empty( $parent_array_value['child'] ) ) {
										?>
										<ul class="event_child_category_ar">
											<?php
											foreach ( $parent_array_value['child'] as $key => $event_child_value ) {
												?>
												<li><label class="post_type_lable" for="<?php echo esc_attr( $event_child_value['name'] ); ?>"><input class="emp_arabic_category" name="emp_arabic_category[]" type="checkbox" id="<?php echo esc_attr( $event_child_value['name'] ); ?>" value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $event_child_value['name'] ); ?></label></li>
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

					<div class="arabic_tags_block">
						<input type="hidden" class="post_id" value="152">
						<h4>علامات الأحداث</h4>
						<?php
						$domain                = filter_input( INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_STRING );
						$request_url           = EVENT_SOURCE_URL . "/wp-json/events/tags?key=$entered_token&domain=$current_site_url&lang=ar";
						$response              = wp_remote_get( $request_url );
						$response              = json_decode( $response['body'] );
						$categories_data_array = (array) $response->data;
						$parent_array          = array();
						$child_array           = array();

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
							<ul id="events_arabic_tagschecklist" class="tagschecklist form-no-clear">
								<li><label><input type="checkbox" name="select-all" id="select-all-ar-tags" value="All"/>الكل</label></li>
								<?php
								foreach ( $parent_array as $key => $parent_array_value ) {
									?>
									<li><label class="post_type_lable" for="<?php echo esc_attr( $parent_array_value['name'] ); ?>"><input class="emp_arabic_tags" name="emp_arabic_tags[]" type="checkbox" id="<?php echo esc_attr( $parent_array_value['name'] ); ?>" value=<?php echo esc_attr( $key ); ?>><?php echo esc_html( $parent_array_value['name'] ); ?></label></li>
									<?php
									if ( isset( $parent_array_value['child'] ) && ! empty( $parent_array_value['child'] ) ) {
										?>
										<ul class="event_child_category_ar">
											<?php
											foreach ( $parent_array_value['child'] as $key => $event_child_value ) {
												?>
												<li><label class="post_type_lable" for="<?php echo esc_attr( $event_child_value['name'] ); ?>"><input class="emp_arabic_tags" name="emp_arabic_tags[]" type="checkbox" id="<?php echo esc_attr( $event_child_value['name'] ); ?>" value=<?php echo esc_attr( $key ); ?>><?php echo esc_html( $event_child_value['name'] ); ?></label></li>
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
			<?php
		}
		die();

	}

	/**
	 * Function for sync events from the wizard.
	 */
	public function wizard_sync_event_ajax() {

		global $wpdb;

		$entered_token    = filter_input( INPUT_POST, 'entered_token', FILTER_SANITIZE_STRING );
		$current_site_url = filter_input( INPUT_POST, 'current_site_url', FILTER_SANITIZE_STRING );
		$language         = filter_input( INPUT_POST, 'language', FILTER_SANITIZE_STRING );
		$number_of_events = filter_input( INPUT_POST, 'number_of_events', FILTER_SANITIZE_STRING );
		$cats_array_get   = filter_input( INPUT_POST, 'cat', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY );
		$tags_array_get   = filter_input( INPUT_POST, 'tags', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY );

		$entered_token    = isset( $entered_token ) ? esc_html( $entered_token ) : '';
		$current_site_url = isset( $current_site_url ) ? esc_html( $current_site_url ) : '';
		$language         = isset( $language ) ? esc_html( $language ) : 'english';
		$number_of_events = isset( $number_of_events ) ? $number_of_events : '';
		$cat              = isset( $cats_array_get ) ? '&cats=' . implode( ',', $cats_array_get ) : '';
		$tags             = isset( $tags_array_get ) ? '&tags=' . implode( ',', $tags_array_get ) : '';
		$lang             = '';
		if ( 'english' === $language ) {
			$language = 'en';
		} else {
			$language = 'ar';
			$lang     = '&lang=ar';
		}

		update_option( 'validate_token_true', $entered_token );
		update_option( 'dff_language', $language );
		update_option( 'dff_cat', $cat );
		update_option( 'dff_tags', $tags );

		$db_table_name        = $wpdb->prefix . 'dff_history';
		$timestamp_start_time = date( 'Y-m-d H:i:s' );

		/**
		 * Insert cron status first time.
		 */
		$wpdb->insert(
			$db_table_name, array(
				'request_url'   => '',
				'total_event'   => 0,
				'status'        => 'In Progress',
				'sync_type'     => 'Manual',
				'start_time'    => $timestamp_start_time,
				'end_time'      => '',
				'response_data' => '',
			)
		);

		$request_url = EVENT_SOURCE_URL . "/wp-json/events/pull?key=$entered_token&domain=$current_site_url&total=$number_of_events$cat$tags$lang";
		$response    = wp_remote_get( $request_url );
		$sync_data   = json_decode( $response['body'] );

		switch ( $sync_data->status ) {
			case 200:
			case 'No Events updated':
				$status = 'Successful';
				break;
			default:
				$status = 'Failed';
				break;
		}

		/**
		 * Update cron status.
		 */
		$last_history = $wpdb->insert_id;
		$request_url  = EVENT_SOURCE_URL . "/wp-json/events/pull?key=$entered_token&domain=$current_site_url&total=$number_of_events$cat$tags$lang";
		$wpdb->update(
			$db_table_name, array(
				'request_url'   => $request_url,
				'total_event'   => 0,
				'status'        => 'In Progress',
				'sync_type'     => 'Manual',
				'response_data' => wp_json_encode( (array) $sync_data ),
			), array( 'id' => $last_history )
		);

		if ( isset( $sync_data ) && ! empty( $sync_data ) ) {

			$current_date = current_time( 'd F Y' );
			$current_date = strtotime( $current_date );

			foreach ( $sync_data->data as $key => $sync_data_value ) {

				$html  = '';
				$html .= $sync_data_value->overview;
				$html .= '<h3>Event Agenda</h3>';
				$html .= $sync_data_value->agenda;

				/**
				 * Check if event is already available in database or not.
				 */
				$update_args = array(
					'post_status' => 'publish',
					'post_type'   => 'dff-events',
					'meta_query'  => array(
						array(
							'key'     => 'eid',
							'value'   => $sync_data_value->eid,
							'compare' => '=',
						),
					),
					'fields'      => 'ids',
				);
				
				$update_posts       = new WP_Query( $update_args );
				$update_found_posts = $update_posts->found_posts;

				/**
				 * Insert or Update the event according to the condition.
				 */
				if ( 0 < $update_found_posts ) {
					$my_post     = array(
						'ID'           => $update_posts->posts[0],
						'post_title'   => $sync_data_value->title,
						'post_status'  => 'publish',
						'post_type'    => 'dff-events',
						'post_content' => $html,
					);
					$the_post_id = wp_update_post( $my_post );
				} else {
					$my_post     = array(
						'post_title'   => $sync_data_value->title,
						'post_status'  => 'publish',
						'post_type'    => 'dff-events',
						'post_content' => $html,
					);
					$the_post_id = wp_insert_post( $my_post );
				}
				
				update_post_meta( $the_post_id, 'eid', $sync_data_value->eid );
				update_post_meta( $the_post_id, 'event_location', $sync_data_value->location );
				update_post_meta( $the_post_id, 'event_cost_name', $sync_data_value->cost );
				update_post_meta( $the_post_id, 'event_date_select', $sync_data_value->date );

				update_post_meta( $the_post_id, 'event_end_date_select', $sync_data_value->event_end_date_select );
				update_post_meta( $the_post_id, 'event_time_start_select', $sync_data_value->starttime );
				update_post_meta( $the_post_id, 'event_time_end_select', $sync_data_value->endtime );
				update_post_meta( $the_post_id, 'event_google_map_input', $sync_data_value->gmap );
				update_post_meta( $the_post_id, 'event_slug', $sync_data_value->slug );
				cep_insert_feature_image_from_url( $sync_data_value->featured_photo, 'feature_image', $the_post_id );
				cep_insert_feature_image_from_url( $sync_data_value->detail_photo, 'detail_photo', $the_post_id );

				// Update the 'upcoming' meta for future events.
				$e_date = $sync_data_value->date;
				$e_date = ! empty( $e_date ) ? date( 'd F Y', strtotime( $e_date ) ) : '';
				$e_date = ! empty( $e_date ) ? strtotime( $e_date ) : '';
				if ( empty( $e_date ) || $e_date >= $current_date ) {
					update_post_meta( $the_post_id, 'upcoming', 'yes' );
				}

				$new_cat_array  = (array) $sync_data_value->cats;
				$new_tags_array = (array) $sync_data_value->tags;

				$new_cats_ids = array();
				$new_tags_ids = array();

				$cats_wp_ids = array();
				$tags_wp_ids = array();

				if ( isset( $new_cat_array ) && ! empty( $new_cat_array ) ) {
					foreach ( $new_cat_array as $key => $new_cat_array_data ) {

						$cats_array = get_term_by( 'name', $new_cat_array_data->name, 'events_categories' );
						if ( empty( $cats_array ) ) {

							$parent_rest_id = (int) $new_cat_array_data->parent;

							$args  = array(
								'hide_empty' => false, // also retrieve terms which are not used yet
								'meta_query' => array(
									array(
										'key'     => 'eid',
										'value'   => $parent_rest_id,
										'compare' => '=',
									),
								),
								'taxonomy'   => 'events_categories',
							);
							$terms = get_terms( $args );

							// If parent not equals to 0 Find $parent_rest_id id new $new_cats_ids array.
							$parent_wp_id = isset( $terms[0]->term_id ) ? $terms[0]->term_id : 0;

							$currant_cats   = wp_insert_term( $new_cat_array_data->name, 'events_categories', array( 'parent' => $parent_wp_id ) );
							$new_cats_ids[] = $currant_cats['term_id'];
							add_term_meta( $currant_cats['term_id'], 'eid', $key );
						}

						$cats_wp_array = get_term_by( 'name', $new_cat_array_data->name, 'events_categories' );
						$cats_wp_ids[] = $cats_wp_array->term_id;

					}
				}

				if ( isset( $new_tags_array ) && ! empty( $new_tags_array ) ) {
					foreach ( $new_tags_array as $key => $new_tags_array_data ) {

						$tags_array = get_term_by( 'name', $new_tags_array_data->name, 'events_tags' );

						if ( empty( $tags_array ) ) {

							$parent_rest_id = (int) $new_tags_array_data->parent;

							$args  = array(
								'hide_empty' => false, // also retrieve terms which are not used yet
								'meta_query' => array(
									array(
										'key'     => 'eid',
										'value'   => $parent_rest_id,
										'compare' => '=',
									),
								),
								'taxonomy'   => 'events_tags',
							);
							$terms = get_terms( $args );

							// If parent not equals to 0 Find $parent_rest_id id new $new_cats_ids array.
							$parent_wp_id = isset( $terms[0]->term_id ) ? $terms[0]->term_id : 0;

							$currant_tags   = wp_insert_term( $new_tags_array_data->name, 'events_tags', array( 'parent' => $parent_wp_id ) );
							$new_tags_ids[] = $currant_tags['term_id'];
							add_term_meta( $currant_tags['term_id'], 'eid', $key );
						}

						$tags_wp_array = get_term_by( 'name', $new_tags_array_data->name, 'events_tags' );
						$tags_wp_ids[] = $tags_wp_array->term_id;

					}
				}

				wp_set_post_terms( $the_post_id, $cats_wp_ids, 'events_categories', false );
				wp_set_post_terms( $the_post_id, $tags_wp_ids, 'events_tags', false );

			}
		}

		$timestamp_end_time = date( 'Y-m-d H:i:s' );
		$wpdb->update(
			$db_table_name, array(
				'request_url'   => $request_url,
				'total_event'   => $sync_data->total_events,
				'status'        => $status,
				'sync_type'     => 'Manual',
				'start_time'    => $timestamp_start_time,
				'end_time'      => $timestamp_end_time,
				'response_data' => wp_json_encode( (array) $sync_data ),
			), array( 'id' => $last_history )
		);

		update_option( 'dff_complete_wizard', 'complete', false );
		echo esc_html( $sync_data->total_events . ' Events are synced successfully.' );
		die();
	}

	/**
	 * Function for return settings page code.
	 */
	public function cep_settings_function() {
		include plugin_dir_path( __FILE__ ) . 'partials/child-event-plugin-settings.php';
	}

	/**
	 * Function for return DFF event API.
	 */
	public function dff_events_api_call( $url ) {

		$domain              = filter_input( INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_STRING );
		$validate_token_true = get_option( 'validate_token_true' );
		$language            = get_option( 'dff_language' );

		$request_url = "$url?key=$validate_token_true&domain=$domain&lang=$language";
		$response    = wp_remote_get( $request_url );

		return json_decode( $response['body'] );

	}

	/**
	 * @param $columns
	 *
	 * @return mixed
	 */
	public function dff_set_dff_events_list_columns( $columns ) {

		$columns['author'] = __( 'Created By', 'events-child-plugin' );
		$columns['title']  = __( 'Event Name', 'events-child-plugin' );

		return $columns;
	}

	/**
	 * Update categories and tags from event settings page.
	 */
	public function dff_update_cat_tags() {

		$cats_array_get = filter_input( INPUT_POST, 'cat', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY );
		$tags_array_get = filter_input( INPUT_POST, 'tags', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY );

		$cat  = isset( $cats_array_get ) ? '&cats=' . implode( ',', $cats_array_get ) : '';
		$tags = isset( $tags_array_get ) ? '&tags=' . implode( ',', $tags_array_get ) : '';

		update_option( 'dff_cat', $cat );
		update_option( 'dff_tags', $tags );

		die();
	}

	/**
	 * Add a block category for "Get With Gutenberg" if it doesn't exist already.
	 *
	 * @param array $categories Array of block categories.
	 *
	 * @return array
	 */
	public function dff_block_categories( $categories ) {
		$category_slugs = wp_list_pluck( $categories, 'slug' );

		return in_array( 'dff', $category_slugs, true ) ? $categories : array_merge(
			$categories,
			array(
				array(
					'slug'  => 'dff',
					'title' => __( 'DFF', 'events-block' ),
					'icon'  => null,
				),
			)
		);
	}

	/**
	 * Add call back function for enqueue block editor assets.
	 */
	public function dff_register_blocks() {

		register_block_type(
			'dff/events', array(
				'attributes'      => array(
					'checkedCats'        => array(
						'type'    => 'array',
						'default' => [],
						'items'   => array( 'type' => 'number' ),
					),
					'checkedTags'        => array(
						'type'    => 'array',
						'default' => [],
						'items'   => array( 'type' => 'number' ),
					),
					'orderBy'            => array(
						'type'    => 'string',
						'default' => 'date',
					),
					'totalEvents'        => array(
						'type'    => 'int',
						'default' => 12,
					),
					'dateTimeToggle'     => array(
						'type'    => 'boolean',
						'default' => true,
					),
					'paginationToggle'   => array(
						'type'    => 'boolean',
						'default' => true,
					),
					'openUpcomingToggle' => array(
						'type'    => 'boolean',
						'default' => true,
					),
					'openNewTabToggle'   => array(
						'type'    => 'boolean',
						'default' => true,
					),
					'catsToggle'         => array(
						'type'    => 'boolean',
						'default' => true,
					),
					'tagsToggle'         => array(
						'type'    => 'boolean',
						'default' => true,
					),
					'featureImageToggle' => array(
						'type'    => 'boolean',
						'default' => true,
					),
					'eLayout'            => array(
						'type'    => 'string',
						'default' => 'list-view',
					),
					'bgColor'            => array(
						'type'    => 'string',
						'default' => '#000',
					),
					'titleColor'         => array(
						'type'    => 'string',
						'default' => '#000',
					),
					'textColor'          => array(
						'type'    => 'string',
						'default' => '#000',
					),
					'bgColor'            => array(
						'type'    => 'string',
						'default' => 'transparent',
					),
				),
				'render_callback' => array( $this, 'events_render_callback' ),
			)
		);
	}

	/**
	 * Function to get the Events list.
	 *
	 * @param $attributes
	 *
	 * @return false|string
	 */
	public function events_render_callback( $attributes ) {

		$html = '';
		ob_start();
		$allowed_tags = array(
			'div'    => array(
				'class' => array(),
				'style' => array(),
			),
			'img'    => array(
				'src'   => array(),
				'title' => array(),
				'alt'   => array(),
			),
			'h3'     => array( 'class' => array() ),
			'a'      => array(
				'id'     => array(),
				'href'   => array(),
				'target' => array(),
				'style'  => array(),
				'class'  => array(),
			),
			'p'      => array( 'style' => array() ),
			'span'   => array(
				'class' => array(),
				'style' => array(),
			),
			'strong' => array(),
			'input'  => array(
				'id'    => array(),
				'type'  => array(),
				'value' => array(),
			),
			'ul'     => array( 'class' => array() ),
			'li'     => array(
				'class'     => array(),
				'data-page' => array(),
			),
		);

		include plugin_dir_path( __FILE__ ) . 'partials/child-event-block.php';

		$html .= ob_get_clean();
		$data  = filter_input( INPUT_POST, 'data', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		if ( isset( $data ) ) {
			echo wp_kses( $html, $allowed_tags );
			wp_die();
		} else {
			return $html;
		}

	}

	/**
	 * Output buffer function.
	 */
	public function do_output_buffer() {
		ob_start();
	}

	/**
	 * Function to update 'upcoming' meta to yes daily.
	 */
	public static function update_upcoming_events() {

		$args = array(
			'post_type'      => 'dff-events',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		);

		$events       = new WP_Query( $args );
		$events_count = $events->found_posts;

		if ( 0 < $events_count ) {
			$current_date = current_time( 'd F Y' );
			$current_date = strtotime( $current_date );

			foreach ( $events->posts as $event ) {
				$eid    = $event;
				$e_date = get_post_meta( $eid, 'event_date_select', true );
				$e_date = ! empty( $e_date ) ? date( 'd F Y', strtotime( $e_date ) ) : '';
				$e_date = ! empty( $e_date ) ? strtotime( $e_date ) : '';
				if ( ! empty( $e_date ) && $e_date < $current_date ) {
					update_post_meta( $eid, 'upcoming', 'no' );
				} else {
					update_post_meta( $eid, 'upcoming', 'yes' );
				}
			}
		}
	}

	/**
	 * Function for manual sync and cron functionality.
	 */
	public static function sync_manually_event_ajax() {

		global $wpdb;

		$domain           = filter_input( INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_STRING );
		$entered_token    = filter_input( INPUT_POST, 'entered_token', FILTER_SANITIZE_STRING );
		$current_site_url = filter_input( INPUT_POST, 'current_site_url', FILTER_SANITIZE_STRING );
		$language         = filter_input( INPUT_POST, 'language', FILTER_SANITIZE_STRING );
		$number_of_events = filter_input( INPUT_POST, 'number_of_events', FILTER_SANITIZE_STRING );
		$sync_type        = filter_input( INPUT_POST, 'sync_type', FILTER_SANITIZE_STRING );
		$from_time        = filter_input( INPUT_POST, 'fromDate', FILTER_SANITIZE_STRING );

		$entered_token    = isset( $entered_token ) ? $entered_token : get_option( 'validate_token_true' );
		$current_site_url = isset( $current_site_url ) ? $current_site_url : $domain;
		$language         = isset( $language ) ? $language : get_option( 'dff_language' );
		$number_of_events = isset( $number_of_events ) ? (int) $number_of_events : '';
		$sync_type        = isset( $sync_type ) ? $sync_type : 'Automatic';
		$from_time        = isset( $from_time ) ? $from_time : '';
		$lang             = '';

		$cats_array_get = filter_input( INPUT_POST, 'cat', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY );
		$tags_array_get = filter_input( INPUT_POST, 'tags', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY );

		$cat  = isset( $cats_array_get ) ? '&cats=' . implode( ',', $cats_array_get ) : get_option( 'dff_cat' );
		$tags = isset( $tags_array_get ) ? '&tags=' . implode( ',', $tags_array_get ) : get_option( 'dff_tags' );

		update_option( 'validate_token_true', $entered_token );
		update_option( 'dff_cat', $cat );
		update_option( 'dff_tags', $tags );

		$the_post_id = '';
		if ( 'ar' === $language ) {
			$lang = '&lang=ar';
		}

		$db_table_name = $wpdb->prefix . 'dff_history';

		$timestamp_start_time = gmdate( 'Y-m-d H:i:s' );

		// Sync from next attempts.
		if ( empty( $number_of_events ) && 0 !== $number_of_events ) {

			/*
			 * Get from date from database history table.
			 * The latest history's start date = this cron
			 */

			$latest_history = $wpdb->get_col( $wpdb->prepare( "SELECT start_time FROM %1sdff_history WHERE status = 'Successful' AND start_time != '0000-00-00 00:00:00' ORDER BY id DESC LIMIT 1", $wpdb->prefix ) );

			$from_time = null !== $latest_history && isset( $latest_history[0] ) ? $latest_history[0] : '';
			$from_time = date( 'Y-m-d H:i:s', strtotime( $from_time ) );

			$to_time = date( 'Y-m-d H:i:s', strtotime( $timestamp_start_time ) );

			$timeframe = "&fromDate=$from_time&toDate=$to_time";

			/**
			 * Add EIDs list in the request to get filtered response from parent.
			 * Fetch EIDs already stored in the site.
			 */
			$args   = array( 'post_type' => EVENTS_POST_TYPE );
			$events = new WP_Query( $args );
			$eids   = array();
			while ( $events->have_posts() ) :
				$events->the_post();
				$eids[] = get_post_meta( get_the_ID(), 'eid', true );
			endwhile;
			$eids = implode( ',', $eids );

			if ( false !== $eids ) {
				$timeframe .= "&eids=$eids";
			}
		} else {
			// Sync - the first attempt from wizard.
			$timeframe = "&total=$number_of_events";
		}

		$request_url = EVENT_SOURCE_URL . "/wp-json/events/pull?key=$entered_token&domain=$current_site_url$cat$tags$lang$timeframe";

		/**
		 * Insert cron status first time.
		 */

		$wpdb->insert(
			$db_table_name, array(
				'request_url'   => $request_url,
				'total_event'   => 0,
				'status'        => 'Requesting',
				'sync_type'     => $sync_type,
				'end_time'      => '',
				'response_data' => '',
			)
		);

		$response = wp_remote_get( $request_url );

		$sync_data = json_decode( $response['body'] );
		$response  = wp_json_encode( (array) $sync_data );

		switch ( $sync_data->status ) {
			case 200:
			case 'No Events updated':
				$status = 'Successful';
				break;
			default:
				$status = 'Failed';
				break;
		}

		/**
		 * Update cron status.
		 */
		$last_history = $wpdb->insert_id;

		$timestamp_end_time = date( 'Y-m-d H:i:s' );
		$wpdb->update(
			$db_table_name, array(
				'request_url'   => $request_url,
				'total_event'   => $sync_data->total_events,
				'status'        => 'In Progress',
				'sync_type'     => $sync_type,
				'start_time'    => $timestamp_start_time,
				'end_time'      => $timestamp_end_time,
				'response_data' => wp_json_encode( (array) $sync_data ),
			), array( 'id' => $last_history )
		);

		if ( 200 === $sync_data->status ) {

			if ( isset( $sync_data ) && ! empty( $sync_data ) ) {

				foreach ( $sync_data->data as $key => $event ) {

					if ( 'deleted' === $event->status ) {

						$delete_args = array(
							'post_status' => 'publish',
							'post_type'   => 'dff-events',
							'meta_query'  => array(
								array(
									'key'     => 'eid',
									'value'   => $event->eid,
									'compare' => '=',
								),
							),
							'fields'      => 'ids',
						);

						$delete_posts       = new WP_Query( $delete_args );
						$delete_found_posts = $delete_posts->found_posts;

						if ( 0 < $delete_found_posts ) {
							foreach ( $delete_posts->posts as $delete_posts_data ) {
								wp_trash_post( $delete_posts_data );
							}
						}
					} else {

						$update_args = array(
							'post_status' => 'publish',
							'post_type'   => 'dff-events',
							'meta_query'  => array(
								array(
									'key'     => 'eid',
									'value'   => $event->eid,
									'compare' => '=',
								),
							),
							'fields'      => 'ids',
						);

						$update_posts       = new WP_Query( $update_args );
						$update_found_posts = $update_posts->found_posts;

						$html  = '';
						$html .= $event->overview;
						$html .= '<h3>Event Agenda</h3>';
						$html .= $event->agenda;

						if ( 0 < $update_found_posts ) {
							$my_post     = array(
								'ID'           => $update_posts->posts[0],
								'post_title'   => $event->title,
								'post_status'  => 'publish',
								'post_type'    => 'dff-events',
								'post_content' => $html,
							);
							$the_post_id = wp_update_post( $my_post );
						} else {
							$my_post     = array(
								'post_title'   => $event->title,
								'post_status'  => 'publish',
								'post_type'    => 'dff-events',
								'post_content' => $html,
							);
							$the_post_id = wp_insert_post( $my_post );
						}

											update_post_meta( $the_post_id, 'event_location', $event->location );
											update_post_meta( $the_post_id, 'event_cost_name', $event->cost );
											update_post_meta( $the_post_id, 'event_date_select', $event->date );

											update_post_meta( $the_post_id, 'event_end_date_select', $event->event_end_date_select );
											
											update_post_meta( $the_post_id, 'event_time_start_select', $event->starttime );
											update_post_meta( $the_post_id, 'event_time_end_select', $event->endtime );
											update_post_meta( $the_post_id, 'event_google_map_input', $event->gmap );
											update_post_meta( $the_post_id, 'event_slug', $event->slug );
											update_post_meta( $the_post_id, 'eid', $event->eid );
											cep_insert_feature_image_from_url( $event->featured_photo, 'feature_image', $the_post_id );
											cep_insert_feature_image_from_url( $event->detail_photo, 'detail_photo', $the_post_id );

											$current_date = current_time( 'd F Y' );
											$current_date = strtotime( $current_date );
											$e_date       = $event->date;
											$e_date       = ! empty( $e_date ) ? date( 'd F Y', strtotime( $e_date ) ) : '';
											$e_date       = ! empty( $e_date ) ? strtotime( $e_date ) : '';
						if ( ! empty( $e_date ) && $e_date < $current_date ) {
							update_post_meta( $the_post_id, 'upcoming', 'no' );
						} else {
							update_post_meta( $the_post_id, 'upcoming', 'yes' );
						}

											$new_cat_array  = (array) $event->cats;
											$new_tags_array = (array) $event->tags;

											$new_cats_ids = array();
											$new_tags_ids = array();

											$cats_wp_ids = array();
											$tags_wp_ids = array();

						if ( isset( $new_cat_array ) && ! empty( $new_cat_array ) ) {
							foreach ( $new_cat_array as $key => $new_cat_array_data ) {

								$cats_array = get_term_by( 'name', $new_cat_array_data->name, 'events_categories' );

								if ( empty( $cats_array ) ) {

									$parent_rest_id = (int) $new_cat_array_data->parent;

									$args  = array(
										'hide_empty' => false, // also retrieve terms which are not used yet
										'meta_query' => array(
											array(
												'key'     => 'eid',
												'value'   => $parent_rest_id,
												'compare' => '=',
											),
										),
										'taxonomy'   => 'events_categories',
									);
									$terms = get_terms( $args );

									// If parent not equals to 0 Find $parent_rest_id id new $new_cats_ids array.
									$parent_wp_id = isset( $terms[0]->term_id ) ? $terms[0]->term_id : 0;

									$currant_cats   = wp_insert_term( $new_cat_array_data->name, 'events_categories', array( 'parent' => $parent_wp_id ) );
									$new_cats_ids[] = $currant_cats['term_id'];
									add_term_meta( $currant_cats['term_id'], 'eid', $key );

								}
								$cats_wp_array = get_term_by( 'name', $new_cat_array_data->name, 'events_categories' );
								$cats_wp_ids[] = $cats_wp_array->term_id;
							}
						}

						if ( isset( $new_tags_array ) && ! empty( $new_tags_array ) ) {
							foreach ( $new_tags_array as $key => $new_tags_array_data ) {

								$tags_array = get_term_by( 'name', $new_tags_array_data->name, 'events_tags' );

								if ( empty( $tags_array ) ) {

									$parent_rest_id = (int) $new_tags_array_data->parent;

									$args  = array(
										'hide_empty' => false, // also retrieve terms which are not used yet
										'meta_query' => array(
											array(
												'key'     => 'eid',
												'value'   => $parent_rest_id,
												'compare' => '=',
											),
										),
										'taxonomy'   => 'events_tags',
									);
									$terms = get_terms( $args );

									// If parent not equals to 0 Find $parent_rest_id id new $new_cats_ids array.
									$parent_wp_id = isset( $terms[0]->term_id ) ? $terms[0]->term_id : 0;

									$currant_tags   = wp_insert_term( $new_tags_array_data->name, 'events_tags', array( 'parent' => $parent_wp_id ) );
									$new_tags_ids[] = $currant_tags['term_id'];
									add_term_meta( $currant_tags['term_id'], 'eid', $key );
								}
								$tags_wp_array = get_term_by( 'name', $new_tags_array_data->name, 'events_tags' );
								$tags_wp_ids[] = $tags_wp_array->term_id;
							}
						}

											wp_set_post_terms( $the_post_id, $cats_wp_ids, 'events_categories', false );
											wp_set_post_terms( $the_post_id, $tags_wp_ids, 'events_tags', false );

					}
				}
			}
		}

		$timestamp_end_time = date( 'Y-m-d H:i:s' );
		$wpdb->update(
			$db_table_name, array(
				'status'        => $status,
			), array( 'id' => $last_history )
		);

		if ( isset( $sync_data->total_events ) && ! empty( $sync_data->total_events ) && $sync_data->total_events > 1 ) {
			echo esc_html( $sync_data->total_events . ' Events synced successfully.' );
		} elseif ( 1 === $sync_data->total_events ) {
			echo esc_html( $sync_data->total_events . ' Event synced successfully.' );
		} else {
			echo esc_html( 'Everything is up to date.' );
		}

		die();
	}

	/**
	 * Function for reset plugin's data.
	 */
	public function dff_reset_plugin() {
		$post_type = 'dff-events';
		global $wpdb;
		$wpdb->query(
			$wpdb->prepare(
				'
            DELETE posts,pt,pm
            FROM wp_posts posts
            LEFT JOIN wp_term_relationships pt ON pt.object_id = posts.ID
            LEFT JOIN wp_postmeta pm ON pm.post_id = posts.ID
            WHERE posts.post_type = %s
            ',
				$post_type
			)
		);

		$wpdb->query( $wpdb->prepare( 'TRUNCATE TABLE %1sdff_history', $wpdb->prefix ) );

		delete_option( 'validate_token_true' );
		delete_option( 'dff_cat' );
		delete_option( 'dff_tags' );
		delete_option( 'dff_complete_wizard' );

		$taxonomy_name = array( 'events_categories', 'events_tags' );
		foreach ( $taxonomy_name as $taxonomy_name_single ) {
			$terms = get_terms(
				array(
					'taxonomy'   => $taxonomy_name_single,
					'hide_empty' => false,
				)
			);

			foreach ( $terms as $term ) {
				wp_delete_term( $term->term_id, $taxonomy_name_single );
			}
		}

		die();
	}

	/**
	 * Display history table server render.
	 */
	public function event_history_table_pagination() {

		global $wpdb;
		$next_page = filter_input( INPUT_POST, 'start', FILTER_SANITIZE_NUMBER_INT );
		$next_page = isset( $next_page ) && ! empty( $next_page ) ? $next_page : 0;

		$draw = filter_input( INPUT_POST, 'draw', FILTER_SANITIZE_NUMBER_INT );
		$draw = isset( $draw ) && ! empty( $draw ) ? $draw : 0;

		$history_table_result = $wpdb->get_results( $wpdb->prepare( 'SELECT `id`, `total_event`, `status`, `sync_type`, `end_time` FROM %1sdff_history ORDER BY `end_time` DESC LIMIT %d, 10', $wpdb->prefix, $next_page ), ARRAY_A );
		$array_data           = array();
		if ( isset( $history_table_result ) && ! empty( $history_table_result ) ) {
			$count = $next_page + 1;
			foreach ( $history_table_result as $history_table_result_data ) {

				$nestedData   = array();
				$nestedData[] = $count;
				$nestedData[] = date( 'd-M-Y | h:i:s A', strtotime( $history_table_result_data['end_time'] ) );
				$nestedData[] = $history_table_result_data['total_event'];
				$nestedData[] = $history_table_result_data['status'];
				$nestedData[] = $history_table_result_data['sync_type'];

				$array_data[] = $nestedData;

				$count ++;
			}
		}

		$count = $wpdb->get_results( $wpdb->prepare( 'SELECT COUNT(*) FROM %1sdff_history ', $wpdb->prefix ), ARRAY_A );

		$json_data = array(
			'draw'            => intval( $draw ),
			'recordsTotal'    => intval( $count[0]['COUNT(*)'] ),
			'recordsFiltered' => intval( $count[0]['COUNT(*)'] ),
			'data'            => $array_data,
		);

		echo wp_json_encode( $json_data );
		die();
	}

	public function add_cors_http_header() {
		header( 'Access-Control-Allow-Origin: *' );
	}

}

/**
 * Function for Display Wizard data.
 */
function event_setup_wizard_function() {
	include plugin_dir_path( __FILE__ ) . 'partials/child-event-plugin-wizard.php';
}

/**
 * Function for save event detail image.
 */
function cep_insert_feature_image_from_url( $url, $action, $the_post_id ) {

	require_once(ABSPATH . 'wp-admin/includes/media.php');
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	require_once(ABSPATH . 'wp-admin/includes/image.php');

	$attach_id = media_sideload_image( $url, $the_post_id, null, 'id' );
	
	if ( $action === 'feature_image' ) {
		set_post_thumbnail( $the_post_id, $attach_id );
	} else {
		update_post_meta( $the_post_id, 'event_detail_img', $attach_id );
	}

}
