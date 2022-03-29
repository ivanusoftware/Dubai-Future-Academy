<?php
if ( ! function_exists( 'dff_asset' ) ) {
	/**
	 * Require an asset.
	 *
	 * @param string $name the file name.
	 *
	 * @return void
	 */
	function dff_asset( $name = '', $echo = true ) {
		$file = get_template_directory() . '/dist/static/' . $name;

		$content = '';

		if ( file_exists( $file ) ) {
			// phpcs:ignore
			$content = file_get_contents( $file );
		}

		if ( $echo ) {
			// phpcs:ignore
			echo $content;
		} else {
			return $content;
		}
	}
}
