<?php

/**
 * Add post type for Exams.
 *
 * @since 1.0.0
 * @return void
 */
if (!function_exists('dff_register_exams_post_type')) {
	function dff_register_exams_post_type()
	{
		register_post_type(
			'exams',
			array(
				'labels'             => [
					'name'          => __('Exams', 'dff'),
					'singular_name' => __('Examen', 'dff'),
				],
				'public'             => true,
				'has_archive'        => false,
				'menu_icon'          => 'dashicons-media-interactive',
				'supports'           => array(
					'title',
					'editor',
					'author',
					'thumbnail',
					'excerpt',
					'featured-image',
					'custom-fields',
				),
				'rewrite'            => array(
					'slug'       => 'exams',
					'with_front' => false,
				),
				'show_ui' => true,
				'show_in_menu' => true,
				'menu_position' => 5,
				'show_in_admin_bar' => true,
				'show_in_nav_menus' => true,
				'can_export' => true,
				'has_archive' => false,
				'hierarchical' => true,
				'exclude_from_search' => false,
				'show_in_rest' => true,
				'publicly_queryable' => true,
				'capability_type' => 'post',
				'taxonomies'         => array('exams-categories'),
			)

		);
	}
}

if (!function_exists('dff_register_exams_taxonomy')) {
	/**
	 * Register the Category taxonomy.
	 */
	function dff_register_exams_taxonomy()
	{
		$labels = array(
			'name'                       => _x('Categories', 'Taxonomy General Name', 'dff'),
			'singular_name'              => _x('Category', 'Taxonomy Singular Name', 'dff'),
			'menu_name'                  => __('Category', 'dff'),
			'all_items'                  => __('All Categories', 'dff'),
			'parent_item'                => __('Parent Category', 'dff'),
			'parent_item_colon'          => __('Parent Category:', 'dff'),
			'new_item_name'              => __('New Category Name', 'dff'),
			'add_new_item'               => __('Add New Category', 'dff'),
			'edit_item'                  => __('Edit Category', 'dff'),
			'update_item'                => __('Update Category', 'dff'),
			'view_item'                  => __('View Category', 'dff'),
			'separate_items_with_commas' => __('Separate event categories with commas', 'dff'),
			'add_or_remove_items'        => __('Add or remove event categories', 'dff'),
			'choose_from_most_used'      => __('Choose from the most used', 'dff'),
			'popular_items'              => __('Popular Categories', 'dff'),
			'search_items'               => __('Search Categories', 'dff'),
			'not_found'                  => __('Not Found', 'dff'),
			'no_terms'                   => __('No Categories', 'dff'),
			'items_list'                 => __('Categories list', 'dff'),
			'items_list_navigation'      => __('Categories list navigation', 'dff'),
		);
		$args   = array(
			'labels'            => $labels,
			'hierarchical' => true,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => true,
			'show_in_rest' => true,
			'show_tagcloud' => true,
			'query_var'    => true,
			'show_in_quick_edit' => true,
			'show_admin_column' => false,
			'rewrite'           => array(
				'slug'       => 'exams/categories',
				'with_front' => false,
			),
		);

		register_taxonomy('exams-categories', array('exams'), $args);
	}
}

add_action('init', 'dff_register_exams_post_type');
add_action('init', 'dff_register_exams_taxonomy');
