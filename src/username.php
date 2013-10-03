<?php

require( 'workflows.php' );
$w = new Workflows();

if (!isset($query)) { $query = "{query}"; } 
$w->set( 'github.username', $query, 'settings.plist' );

echo 'Set Alfred Github Username '.$query;

?>