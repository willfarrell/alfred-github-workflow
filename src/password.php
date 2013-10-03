<?php

require( 'workflows.php' );
$w = new Workflows();

if (!isset($query)) { $query = "{query}"; } 
$w->set( 'github.password', $query, 'settings.plist' );

echo 'Set Alfred Github Password ********';

?>