<?php


/**
 * Add post type for future talk.
 *
 * @since 1.0.0
 * @return void
 */
if ( ! function_exists( 'dff_register_future_talk_post_type' ) ) {
	function dff_register_future_talk_post_type() {
		register_post_type( 'future-talk',
			array(
				'labels'             => [
					'name'          => __( 'Future Talks', 'dff' ),
					'singular_name' => __( 'Future Talk', 'dff' ),
				],
				'public'             => true,
				'has_archive'        => true,
				'menu_icon'          => 'dashicons-video-alt2',
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
					'slug'       => 'future-talk',
					'with_front' => false,
				],
				'taxonomies'         => [ 'category' ],
			)
		);
	}
}

add_action( 'init', 'dff_register_future_talk_post_type' );
