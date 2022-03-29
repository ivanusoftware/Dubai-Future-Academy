<?php

// register custom meta tag field
function dff_register_post_meta() {
	register_post_meta( 'future-talk', 'video_url', array(
		'show_in_rest' => true,
		'single'       => true,
		'type'         => 'string',
	) );

	register_post_meta( 'future-talk', 'video_length', array(
		'show_in_rest' => true,
		'single'       => true,
		'type'         => 'string',
	) );

	register_post_meta( 'page', 'theme_primary', array(
		'show_in_rest' => true,
		'single'       => true,
		'type'         => 'string',
	) );
}
add_action( 'init', 'dff_register_post_meta' );
