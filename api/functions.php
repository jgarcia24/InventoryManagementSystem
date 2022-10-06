<?php

function db_iconnect($dbname) {
	$username = "web user";
	$password = "JIZ2VsmC4FfuqwUt";
	$db = $dbname;
	$hostname = "localhost";
	$dblink = new mysqli($hostname, $username, $password, $db);
	
	return $dblink;
}

?>