<?php
require_once __DIR__ . '/class-redirect-styles.php';

if ( ! function_exists( 'frontend_enqueue_assets' ) ) {
	function frontend_enqueue_assets() {
		if ( ! is_admin() ) {
			wp_register_script( 'frontend-scripts', get_template_directory_uri() . '/dist/scripts/' . DFF_BUNDLE_JS, [], filemtime( get_template_directory() . '/dist/scripts/' . DFF_BUNDLE_JS ), true );
			wp_register_script( 'frontend-header-scripts', get_template_directory_uri() . '/dist/scripts/' . DFF_HEADER_JS, [], filemtime( get_template_directory() . '/dist/scripts/' . DFF_HEADER_JS ), true );
			wp_register_script( 'js-cookie', get_template_directory_uri() . '/dist/static/scripts/js.cookie-2.2.1.min.js', [], '2.2.1', false );
			wp_enqueue_script( 'js-cookie' );
			wp_enqueue_script( 'frontend-header-scripts' );
			wp_enqueue_script( 'frontend-scripts' );
			wp_localize_script( 'frontend-scripts', 'i18n', [
				'search'            => __( 'Search', 'dff' ),
				'topSearch'         => __( 'Top search results', 'dff' ),
				'allSearch'         => __( 'All search results ›', 'dff' ),
				'Sort By'           => __( 'Sort By', 'dff' ),
				'Date (OLDEST)'     => __( 'Date (OLDEST)', 'dff' ),
				'Date (NEWEST)'     => __( 'Date (NEWEST)', 'dff' ),
				'filterByType'      => __( 'Filter by content type', 'dff' ),
				'filterBy'          => __( 'Filter by', 'dff' ),
				'Clear Filter'      => __( 'Clear Filter', 'dff' ),
				'Loading...'        => __( 'Loading...', 'dff' ),
				'Load More'         => __( 'Load More', 'dff' ),
				'login'             => __( 'Login / Register', 'dff' ),
				'logout'            => __( 'Logout', 'dff' ),
				'profile'           => __( 'My Profile', 'dff' ),
				'programsDashboard' => __( 'Programmes Dashboard', 'dff' ),
				'noSearchResults'   => __( 'Your query yielded no results, please try again.', 'dff' ),
				'searchFilters'     => __( 'Filter search results', 'dff' ),
				'Filtering By'      => __( 'Filtering By', 'dff' ),
				'noResultsFound'    => __( 'No results found for the search term', 'dff' ),
				'searching'         => __( 'Searching', 'dff' ),
			] );

			$options = get_option( 'global_options', '' );

			$locations  = get_nav_menu_locations();
			$menu       = wp_get_nav_menu_object( $locations['future-id'] );
			$menu_items = wp_get_nav_menu_items( $menu->term_id );

			wp_localize_script( 'frontend-scripts', 'searchParams', [
				'site'         => multilingualpress_get_current_site_lang(),
				'homeUrl'      => home_url( '/' ),
				'restUrl'      => rest_url( '/' ),
				'loginUrl'     => home_url( '/oauth/login' ),
				'logoutUrl'    => home_url( '/oauth/logout' ),
				'dashboardUrl' => $options['dashboard_url'] ?? '',
				'programsUrl'  => $options['programs_url'] ?? '',
				'menuItems'    => array_map( function ( $item ) {
					return [
						'title' => $item->title,
						'link'  => $item->url,
					];
				}, $menu_items ),
			] );
		}

		wp_enqueue_style( 'frontend-header-styles', get_template_directory_uri() . '/dist/styles/' . DFF_HEADER_CSS, [], filemtime( get_template_directory() . '/dist/styles/' . DFF_HEADER_CSS ), 'all' );
		wp_enqueue_style( 'frontend-styles', get_template_directory_uri() . '/dist/styles/' . DFF_BUNDLE_CSS, [], filemtime( get_template_directory() . '/dist/styles/' . DFF_BUNDLE_CSS ), 'all' );


		if ( $options ) {
			$gmaps_api_key = $options['google_maps_api_key'] ?? false;
		}

		if ( $gmaps_api_key && ( is_single() || is_page() ) && has_block( 'dff/google-map' ) ) {
			wp_enqueue_script( 'maps-scripts', 'https://maps.googleapis.com/maps/api/js?key=' . $gmaps_api_key . '&libraries=places', [], '1.1.0', false );
		}
	}
}

add_action( 'wp_enqueue_scripts', 'frontend_enqueue_assets' );
