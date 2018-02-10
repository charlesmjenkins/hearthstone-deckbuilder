<?php
// Author: Charles (C.J.) Jenkins
// Description: This page creates a new class in the database
//              and then redirects to the 'show custom inserts' page.
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

	$className = $mysqli->real_escape_string(cleanString($_POST['className']));

	$sql = "INSERT INTO class VALUES (DEFAULT, '$className')";
	$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());

	header('Location: showcustominserts.php');			
?>