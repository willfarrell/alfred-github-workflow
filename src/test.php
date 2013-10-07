<?php

//***********
require_once('workflows.php');

$w = new Workflows();

$username = $w->get( 'github.username', 'settings.plist' );
$password = $w->get( 'github.password', 'settings.plist' );

if (!$username) {
	$w->result( 'git-username', 'https://github.com/willfarrell/alfred-github-workflow#setup', 'Github Username Required', 'Press Enter to see documentation on how to set up.', 'icon.png', 'yes' );
}
if (!$password) {
	$w->result( 'git-password', 'https://github.com/willfarrell/alfred-github-workflow#setup', 'Github Password/Token Required', 'Press Enter to see documentation on how to set up.', 'icon.png', 'yes' );
}

if ( count( $w->results() ) == 0 ){
	// Test
	$url = "https://api.github.com/rate_limit";
	if($username && $password) {
		exec('sh auth.sh -u '.escapeshellarg($username).' -p '.escapeshellarg($password).' --url '.escapeshellarg($url), $output, $return_var);
		
		$data = implode($output);
		
		$data = substr($data, strrpos($data, "X-GitHub-Request-Id")); // clean string
		preg_match("/([\[{])/", $data, $matches, PREG_OFFSET_CAPTURE);
		$start = $matches[0][1];
		$end = max(strrpos($data, "}"), strrpos($data, "]"))+1;
		$data = substr($data, $start, $end-$start);
		
		$msg = json_decode( $data );
	} else {
		$data = $w->request( $url );
		$msg = json_decode( $data );
	}
	
	if (isset($msg->message)) {
		$w->result( $msg-> message, $msg->message, 'Github Limit', $msg->message, 'icon.png', 'no' );
	}
	if (isset($msg->rate->remaining) && $msg->rate->remaining <= 60) {
		$w->result( 'git-test', null, 'Test Unsuccessful', 'Want more Workflows? Check out a few below.', 'icon.png', 'no' );
	} else {
		$w->result( 'git-test', null, 'Test Successful', 'Want more Workflows? Check out a few below.', 'icon.png', 'no' );
	}

	// Support
	$query = "alfred @willfarrell";
	$url = "https://api.github.com/legacy/repos/search/".urlencode($query);
	
	if($username && $password) {
		exec('clear; sh auth.sh -u '.escapeshellarg($username).' -p '.escapeshellarg($password).' --url '.escapeshellarg($url), $output, $return_var);
	
		$data = implode($output);
		
		$data = substr($data, strrpos($data, "X-GitHub-Request-Id")); // clean string
		preg_match("/([\[{])/", $data, $matches, PREG_OFFSET_CAPTURE);
		$start = $matches[0][1];
		$end = max(strrpos($data, "}"), strrpos($data, "]"))+1;
		$data = substr($data, $start, $end-$start);
		
		$return = json_decode( $data );
	} else {
		$data = $w->request( $url );
		$return = json_decode( $data );
	}
	
	if (isset($return->repositories)) {
		// test passed
		$repos = $return->repositories;
		foreach($repos as $repo ) {
			$repo->full_name = $repo->username."/".$repo->name;
			$repo->html_url = $repo->url;
			$w->result( 'git-'.$repo->full_name, $repo->html_url, $repo->full_name, $repo->description, 'icon.png', 'yes' );
		}
	}

}

if ( count( $w->results() ) == 0 ){
	$w->result( 'git', null, 'No Repository found', 'No Repository found that match your query', 'icon.png', 'no' );
}

echo $w->toxml();

?>