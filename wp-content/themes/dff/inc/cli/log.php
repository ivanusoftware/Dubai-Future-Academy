<?php

function dff_log( $string, $prefix = '[LOG]    ' ) {
	if ( is_array( $string ) || is_object( $string ) ) {
		$string = json_encode( $string );
	}

	WP_CLI::log( WP_CLI::colorize( $prefix . ' ' . $string ) );
}

function dff_success( $string ) {
	dff_log( $string, '%G[SUCCESS]%n' );
}

function dff_info( $string ) {
	dff_log( $string, '%C[INFO]   %n' );
}

function dff_error( $string ) {
	dff_log( $string, '%r[ERROR]  %n' );
}

function dff_warning( $string ) {
	dff_log( $string, '%y[WARNING]%n' );
}

function dff_debug( $string ) {
	if ( defined( 'DFF_DEBUG' ) && DFF_DEBUG ) {
		dff_log( $string, '%B[DEBUG]%n  ' );
	}
}
