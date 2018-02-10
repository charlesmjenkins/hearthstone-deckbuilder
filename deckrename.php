<?php
	// Author: Charles (C.J.) Jenkins
	// Description: This page updates a deck's name in the database
	//              and then redirects to the deck edit page.
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

	$did = $_GET['did'];
	$username = $_SESSION['username'];
	$newDeckName = $mysqli->real_escape_string(cleanString($_POST['newDeckName']));

	// Security Feature: Make sure this deck belongs to current user 
	// (in case deck id argument is modified in URL)
	$sql = "SELECT username FROM user WHERE user.uid = (SELECT user FROM deck WHERE did = $did)";
	$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());
	$row = $result->fetch_assoc();
	$correctUser = $row['username'];

	if($username != $correctUser)
	{
		header('Location: deckbuilder.php');
	}

	// Security Feature: Check that the user isn't trying to update a 
	// deck that doesn't exist by means of the URL GET arguments
	$sql = "SELECT did FROM deck WHERE did = $did";
	$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());
	if($result->num_rows == 0)
	{
		break;
	}	
	else
	{
		// Update the deck's name in the database
		$sql = "UPDATE deck SET name = '$newDeckName' WHERE did = $did";
		$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());
	}

	header("Location: deckedit.php?did=$did");		
?>