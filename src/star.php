<?php

//***********

if (!isset($query)) {
	$query = <<<EOD
{query}
EOD;
}

function star($full_name) {
	global $username, $password;
	$url = "https://api.github.com/user/starred/$full_name";
	exec('sh auth.sh -X PUT -u '.escapeshellarg($username).' -p '.escapeshellarg($password).' --url '.escapeshellarg($url), $output);
}

// URL to username/repo
//preg_match("/\w*\/\w*$/", $query, $matches);
//$query = $matches[0];

star($query);
echo $query;
?>