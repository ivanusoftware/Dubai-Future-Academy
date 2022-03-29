<?php


/**
 * Add post type for program.
 *
 * @since 1.0.0
 * @return void
 */
if ( ! function_exists( 'dff_register_program_post_type' ) ) {
	function dff_register_program_post_type() {
		register_post_type( 'program',
			array(
				'labels'             => [
					'name'          => __( 'Programmes', 'dff' ),
					'singular_name' => __( 'Programme', 'dff' ),
				],
				'public'             => true,
				'has_archive'        => false,
				'menu_icon'          => 'dashicons-welcome-learn-more',
				'supports'           => [
					'title',
					'editor',
					'author',
					'thumbnail',
					'excerpt',
					'featured-image',
					'custom-fields',
				],
				'show_in_rest'       => true,
				'publicly_queryable' => true,
				'rewrite'            => [
					'slug'       => 'program',
					'with_front' => false,
				],
				'taxonomies'         => [ 'facilitator', 'challenge-owners' ],
			)
		);
	}
}

if ( ! function_exists( 'dff_register_programs_facilitators_taxonomy' ) ) {
	/**
	 * Register the Facilitator taxonomy.
	 */
	function dff_register_programs_facilitators_taxonomy() {
		$labels = array(
			'name'                       => _x( 'Facilitators', 'Taxonomy General Name', 'dff' ),
			'singular_name'              => _x( 'Facilitator', 'Taxonomy Singular Name', 'dff' ),
			'menu_name'                  => __( 'Facilitator', 'dff' ),
			'all_items'                  => __( 'All Facilitators', 'dff' ),
			'parent_item'                => __( 'Parent Facilitator', 'dff' ),
			'parent_item_colon'          => __( 'Parent Facilitator:', 'dff' ),
			'new_item_name'              => __( 'New Facilitator Name', 'dff' ),
			'add_new_item'               => __( 'Add New Facilitator', 'dff' ),
			'edit_item'                  => __( 'Edit Facilitator', 'dff' ),
			'update_item'                => __( 'Update Facilitator', 'dff' ),
			'view_item'                  => __( 'View Facilitator', 'dff' ),
			'separate_items_with_commas' => __( 'Separate event categories with commas', 'dff' ),
			'add_or_remove_items'        => __( 'Add or remove event categories', 'dff' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'dff' ),
			'popular_items'              => __( 'Popular Facilitators', 'dff' ),
			'search_items'               => __( 'Search Facilitators', 'dff' ),
			'not_found'                  => __( 'Not Found', 'dff' ),
			'no_terms'                   => __( 'No Facilitators', 'dff' ),
			'items_list'                 => __( 'Facilitators list', 'dff' ),
			'items_list_navigation'      => __( 'Facilitators list navigation', 'dff' ),
		);
		$args   = [
			'labels'            => $labels,
			'hierarchical'      => false,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
			'show_in_rest'      => true,
			'rewrite'           => [
				'slug'       => 'programs/facilitator',
				'with_front' => false,
			],
		];

		register_taxonomy( 'facilitator', array( 'program' ), $args );
	}
}

if ( ! function_exists( 'dff_register_programs_challenge_owners_taxonomy' ) ) {
	/**
	 * Register the Challenge Owner taxonomy.
	 */
	function dff_register_programs_challenge_owners_taxonomy() {
		$labels = array(
			'name'                       => _x( 'Challenge Owners', 'Taxonomy General Name', 'dff' ),
			'singular_name'              => _x( 'Challenge Owner', 'Taxonomy Singular Name', 'dff' ),
			'menu_name'                  => __( 'Challenge Owner', 'dff' ),
			'all_items'                  => __( 'All Challenge Owners', 'dff' ),
			'parent_item'                => __( 'Parent Challenge Owner', 'dff' ),
			'parent_item_colon'          => __( 'Parent Challenge Owner:', 'dff' ),
			'new_item_name'              => __( 'New Challenge Owner Name', 'dff' ),
			'add_new_item'               => __( 'Add New Challenge Owner', 'dff' ),
			'edit_item'                  => __( 'Edit Challenge Owner', 'dff' ),
			'update_item'                => __( 'Update Challenge Owner', 'dff' ),
			'view_item'                  => __( 'View Challenge Owner', 'dff' ),
			'separate_items_with_commas' => __( 'Separate event categories with commas', 'dff' ),
			'add_or_remove_items'        => __( 'Add or remove event categories', 'dff' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'dff' ),
			'popular_items'              => __( 'Popular Challenge Owners', 'dff' ),
			'search_items'               => __( 'Search Challenge Owners', 'dff' ),
			'not_found'                  => __( 'Not Found', 'dff' ),
			'no_terms'                   => __( 'No Challenge Owners', 'dff' ),
			'items_list'                 => __( 'Challenge Owners list', 'dff' ),
			'items_list_navigation'      => __( 'Challenge Owners list navigation', 'dff' ),
		);
		$args   = [
			'labels'            => $labels,
			'hierarchical'      => false,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
			'show_in_rest'      => true,
			'rewrite'           => [
				'slug'       => 'programs/challenge',
				'with_front' => false,
			],
		];

		register_taxonomy( 'challenge-owners', array( 'program' ), $args );
	}
}


add_action( 'init', 'dff_register_program_post_type' );
add_action( 'init', 'dff_register_programs_facilitators_taxonomy' );
add_action( 'init', 'dff_register_programs_challenge_owners_taxonomy' );
