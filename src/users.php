<?php

//***********
require_once('workflows.php');

$w = new Workflows();
if (!isset($query)) { $query = urlencode( "{query}" ); }
if (!isset($icon)) { $icon = "icon.png"; }

$username = $w->get( 'github.username', 'settings.plist' );

$url = "https://api.github.com/users/$username/$api";

$content = $w->request( $url );
$repos = json_decode( $content );

if (!$username) {
	$w->result( 'git-username', 'https://github.com/willfarrell/alfred-github-workflow', 'Github Username Required', 'Press Enter to see documentation on how to set up.', 'no' );
}

if ($repos->message) {
	$w->result( $repos->message, $repos->message, 'Github Limit', $repos->message, 'no' );
} else {
	foreach($repos as $repo ) {
		if (!strlen($query) || strpos( strtolower($repo->full_name), strtolower($query)) !== false) {
			$w->result( 'git-'.$repo->full_name, $repo->html_url, $repo->full_name, $repo->description, 'yes', $icon );
		}
	}
	
	if ( count( $w->results() ) == 0 ){
		$w->result( 'git', null, 'No Repository found', 'No Repository found that match your query', 'no' );
	}
}

echo $w->toxml();

?>