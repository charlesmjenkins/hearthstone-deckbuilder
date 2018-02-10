<?php
	// Author: Charles (C.J.) Jenkins
	// Description: This page creates a new deck in the database
	//              and then redirects to the main deckbuilder page.
	// Due Date: 08-31-14
	// Acknowledgements:

	session_start();

	include_once "connection.php";
	include "utilities.php";

	if(!isLoggedIn())
	{
		header('Location: login.php');
		die();
	}

	$username = $_SESSION['username'];
	$class = $_POST['classRadio'];
	$deckName = $mysqli->real_escape_string(cleanString($_POST['deckName']));

	$sql = "INSERT INTO deck VALUES (DEFAULT, '$deckName', $class, (SELECT uid FROM user WHERE username = '$username'))";
	$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());

	header('Location: deckbuilder.php');			
?>