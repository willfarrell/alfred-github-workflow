<?php

require_once('workflows.php');

$w = new Workflows();

$url = "https://api.github.com/rate_limit";
$content = $w->request( $url );
$msg = json_decode( $content );

if ($msg->message) {
	$w->result( $msg-> message, $msg->message, 'Github Limit', $msg->message, 'icon.png' );
}
if ($msg->rate->remaining) {
	$w->result( 'Limit', 'Limit', 'Github Limit', 'Github API requests remaining '.$msg->rate->remaining, 'icon.png' );
}

echo $w->toxml();

?>