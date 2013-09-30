<?php

//***********
require_once('workflows.php');

$w = new Workflows();
if (!isset($query)) { $query = urlencode( "{query}" ); }

//$url = "https://api.github.com/search/repositories?q=$query"; // preview only
$url = "https://api.github.com/legacy/repos/search/$query";

$content = $w->request( $url );
$repos = json_decode( $content );

if ($repos->message) {
	$w->result( $repos->message, $repos->message, 'Github Limit', $repos->message, 'no' );
} else {
	$repos = $repos->repositories;
	foreach($repos as $repo ) {
		$w->result( 'git-'.$repo->full_name, $repo->url, $repo->name.' ('.$repo->language.')', $repo->description, 'yes' );
	}
	
	if ( count( $w->results() ) == 0 ){
		$w->result( 'git', null, 'No Repository found', 'No Repository found that match your query', 'no' );
	}
}

echo $w->toxml();

?>