<?php

require( 'workflows.php' );
$w = new Workflows();

if (!isset($query)) {
	$query = <<<EOD
{query}
EOD;
}
$w->set( 'github.username', $query, 'settings.plist' );

echo 'Set Alfred Github Username '.$query;

?>