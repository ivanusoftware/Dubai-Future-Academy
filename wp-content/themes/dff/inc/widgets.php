<?php

if ( ! function_exists( 'wpdocs_remove_wpe_widget' ) ) {
	function wpdocs_remove_wpe_widget() {
		unregister_widget( 'wpe_widget_powered_by' );
		wp_unregister_sidebar_widget( 'wpe_widget_powered_by' );
	}
}


add_action( 'widgets_init', 'wpdocs_remove_wpe_widget' );
