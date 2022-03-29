<?php

$pid = get_post_meta( get_the_ID(), '_external_id', true );
$url = sprintf( 'https://programs.dubaifuture.ae/programs/%s', $pid );

header( 'Location: ' . $url );
exit;

