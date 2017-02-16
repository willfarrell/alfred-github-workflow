<?php

//***********
require_once('workflows.php');

$w = new Workflows();
if (!isset($query)) {
	$query = <<<EOD
{query}
EOD;
}
$query = urlencode( $query );

$username = $w->get( 'github.username', 'settings.plist' );
$password = $w->get( 'github.password', 'settings.plist' );
$proxy = $w->get( 'github.proxy', 'settings.plist' );

$url = "https://api.github.com/search/repositories?q=$query";

if($username && $password) {
	$shell_command = 'sh auth.sh -u '.escapeshellarg($username).' -p '.escapeshellarg($password).' --url '.escapeshellarg($url);
	if ($proxy) {
		$shell_command .= ' --proxy '.escapeshellarg($proxy);
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
	$w->result( $repos->message, $repos->message, 'Github Limit', $repos->message, 'icon.png', 'no' );
} else {
	$repos = $repos->items;
	foreach($repos as $repo ) {
		$lang = $repo->language ? ' ('.$repo->language.')' : '';
		$repo->full_name = $repo->username.'/'.$repo->name;
		$w->result( 'git-'.$repo->full_name, $repo->html_url, $repo->name.''.$lang, $repo->description, 'icon.png', 'yes' );
	}
	
	if ( count( $w->results() ) == 0 ){
		$w->result( 'git', null, 'No Repository found', 'No Repository found that match your query', 'icon.png', 'no' );
	}
}

echo $w->toxml();

?>
