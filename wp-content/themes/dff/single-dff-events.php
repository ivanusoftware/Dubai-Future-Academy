<?php

$eid        = get_the_ID();
$slug       = trim( get_post_meta( $eid, 'event_slug', true ), '/' );
$base_url   = trim( EVENT_SOURCE_URL, '/' );
$source_eid = get_post_meta( $eid, 'eid', true );

$url = sprintf( '%s/%s', $base_url, $slug ?: '?p=' . $source_eid );

header( 'Location: ' . $url );
exit;
