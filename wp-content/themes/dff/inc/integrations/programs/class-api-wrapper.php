<?php

namespace DFF\Integrations\Programs;

use DFF\Integrations\Request;

/**
 * Api Wrapper Class.
 * A wrapper for the Programs API.
 */
class Api_Wrapper {
	/**
	 * API Url.
	 *
	 * @var string
	 */
	protected static $api_url = 'https://api.programs.dubaifuture.ae/api/challenges';

	/**
	 * Return the all programs from the api.
	 *
	 * @return array|\WP_Error
	 */
	public static function get_programs() {
		return Request::get( self::$api_url, [
			'headers' => [
				'Origin: https://programs.dubaifuture.ae',
			],
		] );
	}
}
