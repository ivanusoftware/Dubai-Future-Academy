<?php

namespace DFF\Integrations\FutureID;

use InvalidArgumentException;


/**
 * Add http basic auth into access token request options
 * @link https://tools.ietf.org/html/rfc6749#section-2.3.1
 */
class HttpHeaderAuthOptionProvider extends \League\OAuth2\Client\OptionProvider\PostAuthOptionProvider {
	/**
	 * @inheritdoc
	*/
	public function getAccessTokenOptions( $method, array $params ) {
		if ( empty( $params['client_id'] ) || empty( $params['client_secret'] ) ) {
			throw new InvalidArgumentException( 'clientId and clientSecret are required for http header auth' );
		}

		$secret = $params['client_secret'];

		unset( $params['client_secret'] );

		$options = parent::getAccessTokenOptions( $method, $params );

		$options['headers']['api_key'] = $secret;
		$options['headers']['origin']  = 'https://dubaifuture.ae';

		return $options;
	}
}
