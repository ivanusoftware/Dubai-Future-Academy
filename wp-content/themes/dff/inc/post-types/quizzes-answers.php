<?php

/**
 * Add post type for quizzes answers.
 *
 * @since 1.0.0
 * @return void
 */
if (!function_exists('dff_register_quizzes_answers_post_type')) {
	function dff_register_quizzes_answers_post_type()
	{
		register_post_type(
			'quizzes_answers',
			array(
				'labels'             => [
					'name'          => __('Quizzes Answers', 'dff'),
					'singular_name' => __('Quizzes Answer', 'dff'),
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
					'slug'       => 'quizzes_answers',
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
				// 'taxonomies'         => array('quizzes_answers-categories'),
			)

		);
	}
}

add_action('init', 'dff_register_quizzes_answers_post_type');

