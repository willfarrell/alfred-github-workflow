<?php

//***********

if (!isset($query)) { $query = '{query}'; }

function star($full_name) {
	global $username, $password;
	$url = "https://api.github.com/user/starred/$full_name";
	exec('sh auth.sh -X PUT -u '.escapeshellarg($username).' -p '.escapeshellarg($password).' --url '.escapeshellarg($url), $output);
}

star($query);
echo $query;
?>