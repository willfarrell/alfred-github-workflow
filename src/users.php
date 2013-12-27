<?php

//***********
require_once('workflows.php');

$w = new Workflows();
if (!isset($query)) {
	$query = <<<EOD
{query}
EOD;
}
if (!isset($api)) { // repos | starred | gists
	$parts = explode(" ", $query);
	$api = array_shift($parts);
	$query = implode(" ", $parts);
}
if (!isset($icon)) { $icon = "icon.png"; }

$username = $w->get( 'github.username', 'settings.plist' );
$password = $w->get( 'github.password', 'settings.plist' );
$proxy = $w->get( 'github.proxy', 'settings.plist' );

$url = "https://api.github.com/users/$username/$api";

if (!$username) {
	$w->result( 'git-username', 'https://github.com/willfarrell/alfred-github-workflow', 'Github Username Required', 'Press Enter to see documentation on how to set up.', 'yes', 'icon.png' );
} else {
	if($username && $password) {
		$shell_command = 'sh auth.sh -u '.escapeshellarg($username).' -p '.escapeshellarg($password).' --url '.escapeshellarg($url);
		if ($proxy) {
			$shell_command .= ' --proxy '.escapeshellarg($proxy);
			file_put_contents("/tmp/alfred", $shell_command."\n", FILE_APPEND);
		}
		exec($shell_command, $output, $return_var);
	
		$data = implode($output);
		
		$data = substr($data, strrpos($data, "X-GitHub-Request-Id")); // clean string
		preg_match("/([\[{])/", $data, $matches, PREG_OFFSET_CAPTURE);
		$start = $matches[0][1];
		$end = max(strrpos($data, "}"), strrpos($data, "]"))+1;
		$data = substr($data, $start, $end-$start);
		
		$repos = json_decode( $data );
	} else {
		$data = $w->request( $url );
		$repos = json_decode( $data );
	}
	
	if (isset($repos->message)) {
		$w->result( $repos->message, $repos->message, 'Github Limit', $repos->message, $icon, 'no' );
	} else {
		foreach($repos as $repo ) {
			// repos
			if (isset($repo->full_name) && (!strlen($query) || strpos( strtolower($repo->full_name), strtolower($query)) !== false)) {
				$w->result( 'git-'.$repo->full_name, $repo->html_url, $repo->name, $repo->description, $icon, 'yes' );
			}
			//gists
			else if (!strlen($query) || strpos( strtolower($repo->description), strtolower($query)) !== false) {
				$w->result( 'git-'.$repo->id, $repo->html_url, $repo->description, $repo->html_url, $icon, 'yes' );
			}
		}
		
		if ( count( $w->results() ) == 0 ){
			$w->result( 'git', null, 'No Repository found', 'No Repository found that match your query', $icon, 'no' );
		}
	}
}

echo $w->toxml();

?>