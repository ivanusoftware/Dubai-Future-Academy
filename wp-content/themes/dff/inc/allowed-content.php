<?php
add_filter( 'wp_kses_allowed_html', function ( $allowed = [] ) {
	$allowed['script']['async']  = false;
	$allowed['script']['defer']  = false;
	$allowed['style']            = [];
	$allowed['picture']          = [];
	$allowed['source']           = [];
	$allowed['source']['media']  = true;
	$allowed['source']['src']    = true;
	$allowed['source']['srcset'] = true;
	$allowed['img']['srcset']    = true;

	$svg_tags = [ 'svg', 'g', 'circle', 'ellipse', 'line', 'path', 'polygon', 'polyline', 'rect', 'text', 'textPath' ];
	$svg_atts = [
		'xmlns'             => true,
		'id'                => true,
		'class'             => true,
		'width'             => true,
		'height'            => true,
		'fill'              => true,
		'transform'         => true,
		'opacity'           => true,
		'data-name'         => true,
		'stroke'            => true,
		'stroke-miterlimit' => true,
		'stroke-width'      => true,
		'style'             => true,
		'points'            => true,
		'viewbox'           => true,
		'd'                 => true,
		'x'                 => true,
		'y'                 => true,
		'rx '               => true,
		'ry'                => true,
		'cx '               => true,
		'cy'                => true,
		'r'                 => true,
	];

	foreach ( $svg_tags as $svg_tag ) {
		$allowed[ $svg_tag ] = $svg_atts;
	}

	return $allowed;
}, 10, 2);
