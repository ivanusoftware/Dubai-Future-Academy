<?php
namespace DFF\CLI;

class UrlMapper {
	protected static $map = [];

	public static function add( $from, $to ) {
		self::$map[ $from ] = $to;
	}

	public static function get() {
		return self::$map;
	}
}
