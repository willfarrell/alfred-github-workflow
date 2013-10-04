<?php

//***********
require_once('workflows.php');

$w = new Workflows();

$query = "alfred @willfarrell";
$url = "https://api.github.com/legacy/repos/search/".urlencode($query);

$username = $w->get( 'github.username', 'settings.plist' );
$password = $w->get( 'github.password', 'settings.plist' );

if (!$username) {
	$w->result( 'git-username', 'https://github.com/willfarrell/alfred-github-workflow#setup', 'Github Username Required', 'Press Enter to see documentation on how to set up.', 'icon.png', 'yes' );
}
if (!$password) {
	$w->result( 'git-password', 'https://github.com/willfarrell/alfred-github-workflow#setup', 'Github Password Required', 'Press Enter to see documentation on how to set up.', 'icon.png', 'yes' );
}

if($username && $password) {
	exec('sh auth.sh -u '.escapeshellarg($username).' -p '.escapeshellarg($password).' --url '.escapeshellarg($url), $output, $return_var);

	$data = implode($output);
	$content = substr($data, strpos($data, "{"));
	$content = substr($content, 0, strrpos($content, "}")+1);
	$return = json_decode( $content );
}

if (isset($return->message)) {
	$w->result( $return->message, $return->message, 'Github Limit', $return->message, 'icon.png', 'no' );
} else if (isset($return->repositories)) {
	// test passed
	$repos = $return->repositories;
	foreach($repos as $repo ) {
		$repo->full_name = $repo->username."/".$repo->name;
		$repo->html_url = $repo->url;
		$w->result( 'git-'.$repo->full_name, $repo->html_url, $repo->full_name, $repo->description, 'icon.png', 'yes' );
		star($repo->full_name);
	}
}

if ( count( $w->results() ) == 0 ){
	$w->result( 'git', null, 'No Repository found', 'No Repository found that match your query', 'icon.png', 'no' );
}

echo $w->toxml();

function star($full_name) {
	global $username, $password;
	$url = "https://api.github.com/user/starred/$full_name";
	exec('sh auth.sh -X PUT -u '.escapeshellarg($username).' -p '.escapeshellarg($password).' --url '.escapeshellarg($url), $output);
}
?>