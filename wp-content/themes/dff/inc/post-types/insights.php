<?php
/**
 * Change labels for default post type.
 *
 * @package octopus
 */
if ( ! function_exists( 'dff_change_post_label' ) ) {
	function dff_change_post_label() {
		global $menu;
		global $submenu;
		// @codingStandardsIgnoreStart
		$menu[5][0]                 = __( 'Insights', 'dff' );
		$submenu['edit.php'][5][0]  = __( 'Insights', 'dff' );
		$submenu['edit.php'][10][0] = __( 'Add Insight', 'dff' );
		$submenu['edit.php'][16][0] = __( 'Tags', 'dff' );
		// @codingStandardsIgnoreEnd
	}
}
if ( ! function_exists( 'dff_change_post_object' ) ) {
	function dff_change_post_object() {
		global $wp_post_types;
		$labels                     = &$wp_post_types['post']->labels;
		$labels->name               = __( 'Insights', 'dff' );
		$labels->singular_name      = __( 'Insight', 'dff' );
		$labels->add_new            = __( 'Add Insight', 'dff' );
		$labels->add_new_item       = __( 'Add Insight', 'dff' );
		$labels->edit_item          = __( 'Edit Insight', 'dff' );
		$labels->new_item           = __( 'Insight', 'dff' );
		$labels->view_item          = __( 'View Insight', 'dff' );
		$labels->search_items       = __( 'Search Insights', 'dff' );
		$labels->not_found          = __( 'No Insight found', 'dff' );
		$labels->not_found_in_trash = __( 'No Insight found in Trash', 'dff' );
		$labels->all_items          = __( 'All Insights', 'dff' );
		$labels->menu_name          = __( 'Insights', 'dff' );
		$labels->name_admin_bar     = __( 'Insights', 'dff' );
	}
}
add_action( 'admin_menu', 'dff_change_post_label' );
add_action( 'init', 'dff_change_post_object' );


if ( ! function_exists( 'dff_register_article_type_taxonomy' ) ) {
	/**
	 * Register the Article Type taxonomy.
	 */
	function dff_register_article_type_taxonomy() {
		$labels = array(
			'name'                       => _x( 'Article Types', 'Taxonomy General Name', 'dff' ),
			'singular_name'              => _x( 'Article Type', 'Taxonomy Singular Name', 'dff' ),
			'menu_name'                  => __( 'Article Type', 'dff' ),
			'all_items'                  => __( 'All Article Types', 'dff' ),
			'parent_item'                => __( 'Parent Article Type', 'dff' ),
			'parent_item_colon'          => __( 'Parent Article Type:', 'dff' ),
			'new_item_name'              => __( 'New Article Type Name', 'dff' ),
			'add_new_item'               => __( 'Add New Article Type', 'dff' ),
			'edit_item'                  => __( 'Edit Article Type', 'dff' ),
			'update_item'                => __( 'Update Article Type', 'dff' ),
			'view_item'                  => __( 'View Article Type', 'dff' ),
			'separate_items_with_commas' => __( 'Separate event categories with commas', 'dff' ),
			'add_or_remove_items'        => __( 'Add or remove event categories', 'dff' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'dff' ),
			'popular_items'              => __( 'Popular Article Types', 'dff' ),
			'search_items'               => __( 'Search Article Types', 'dff' ),
			'not_found'                  => __( 'Not Found', 'dff' ),
			'no_terms'                   => __( 'No Article Types', 'dff' ),
			'items_list'                 => __( 'Article Types list', 'dff' ),
			'items_list_navigation'      => __( 'Article Types list navigation', 'dff' ),
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
				'slug'       => 'insights/article-type',
				'with_front' => false,
			],
		];

		register_taxonomy( 'article-type', array( 'post' ), $args );
	}
}

add_action( 'init', 'dff_register_article_type_taxonomy' );
