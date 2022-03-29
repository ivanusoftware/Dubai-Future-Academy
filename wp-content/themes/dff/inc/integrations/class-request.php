<?php

namespace DFF\Integrations;

use WP_Error;

class Request {
	/**
	 * Creates the remote get and Provides default options/arguments
	 * for the request.
	 *
	 * @param string $remote_url The url to send the request to.
	 * @param array $remote_options Options to provide to the request.
	 * @return array|WP_Error
	 */
	public static function get( $remote_url, $remote_options = [] ) {
		$response = self::remote_request( $remote_url, 'GET', $remote_options );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$body = $response['body'];

		return json_decode( $body, true );
	}

	/**
	 * Creates the remote POST and Provides default options/arguments
	 * for the request.
	 *
	 * @param string $remote_url The url to send the request to.
	 * @param array $remote_options Options to provide to the request.
	 * @return array|WP_Error
	 */
	public static function post( $remote_url, $remote_options = [] ) {
		$response = self::remote_request( $remote_url, 'POST', $remote_options );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$body = $response['body'];

		return json_decode( $body, true );
	}

	/**
	 * Merge two arrays together.
	 *
	 * @param array $array1 - array 1 to be merged.
	 * @param array $array2 - array 2 to be merged.
	 *
	 * @return array
	 */
	private static function array_distinct_merge( &$array1, &$array2 ) {
		$merged = $array1;

		foreach ( $array2 as $key => &$value ) {
			if ( is_array( $value ) && isset( $merged[ $key ] ) && is_array( $merged[ $key ] ) ) {
				$merged[ $key ] = self::array_distinct_merge( $merged[ $key ], $value );
				continue;
			}

			$merged[ $key ] = $value;
		}

		return $merged;
	}

	/**
	 * Creates the remote request.
	 *
	 * @param string $remote_url The url to send the request to.
	 * @param string $remote_method The Request method, defaults to GET.
	 * @param array $remote_options Options to provide to the request.
	 * @return array|WP_Error
	 */
	private static function remote_request( $remote_url, $remote_method = 'GET', $remote_options = [] ) {
		$default_options = [
			'method' => $remote_method,
		];

		$remote_options = self::array_distinct_merge( $default_options, $remote_options );

		$response = wp_remote_request( $remote_url, $remote_options );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		if ( 200 !== $response['response']['code'] ) {
			return new WP_Error( 'request_error', $response['body'], [ 'code' => $response['response']['code'] ] );
		}

		return $response;
	}
}
