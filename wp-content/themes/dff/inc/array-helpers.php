<?php

function array_find( $array, $callable, $default = false ) {
	foreach ( $array as $key => $value ) {
		if ( $callable( $value, $key ) ) {
			return $value;
		}
	}

	return $default;
}
