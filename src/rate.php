<?php

require_once('workflows.php');

$w = new Workflows();

$username = $w->get( 'github.username', 'settings.plist' );
$password = $w->get( 'github.password', 'settings.plist' );

$url = "https://api.github.com/rate_limit";
if($username && $password) {
	exec('sh auth.sh -u '.escapeshellarg($username).' -p '.escapeshellarg($password).' --url '.escapeshellarg($url), $output, $return_var);

	$data = implode($output);
	$content = substr($data, strpos($data, "{"));
	$content = substr($content, 0, strrpos($content, "}")+1);
	$msg = json_decode( $content );
} else {
	$content = $w->request( $url );
	$msg = json_decode( $content );
}

if (isset($msg->message)) {
	$w->result( $msg-> message, $msg->message, 'Github Limit', $msg->message, 'icon.png', 'no' );
}
if (isset($msg->rate->remaining)) {
	$w->result( 'Limit', 'Limit', 'Github Limit', 'Github API requests remaining '.$msg->rate->remaining, 'icon.png', 'no' );
}

echo $w->toxml();

?>