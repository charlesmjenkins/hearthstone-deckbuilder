<?php
	// Author: Charles (C.J.) Jenkins
	// Description: This page deletes a deck from the database
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

	$did = $_GET['did'];
	$username = $_SESSION['username'];

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

	// Security Feature: Check that the user isn't trying to delete a 
	// deck that doesn't exist by means of the URL GET arguments
	$sql = "SELECT did FROM deck WHERE did = $did";
	$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());
	if($result->num_rows == 0)
	{
		break;
	}	
	else
	{
		$sql = "DELETE FROM deck WHERE did = $did";
		$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());
	}

	header('Location: deckbuilder.php');			
?>