<?php
declare(strict_types = 1);

namespace DFF\Gutenberg\Blocks\PostFeed;

class Styles {
	protected static $styles  = [];
	protected static $default = false;

	public static function register( string $name, Base_Style $class, bool $default = false ): void {
		self::$styles[ $name ] = $class;

		if ( $default ) {
			self::$default = $name;
		}
	}

	public static function get( string $style ): ?Base_Style {
		if ( $style && ! empty( self::$styles[ $style ] ) ) {
			return self::$styles[ $style ];
		}

		if ( ! self::$default ) {
			return null;
		}

		return self::$styles[ self::$default ];
	}

	public static function get_all(): array {
		return self::$styles;
	}

	public static function is_default( string $name ): bool {
		return $name === self::$default;
	}
}
