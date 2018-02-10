<?php
	// Author: Charles (C.J.) Jenkins
	// Description: Establish MySQL DB connection for deckbuilder.
	// Due Date: 08-31-14
	// Acknowledgements:

		$dbhost = 'localhost';
		$dbname = ''; //Insert database name here
		$dbuser = ''; //Insert username here
		$dbpass = ''; //Insert password here

		$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname); 

		if ($mysqli->connect_errno) {
		  echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		  exit;
		}
?>