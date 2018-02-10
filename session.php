<?php
	// Author: Charles (C.J.) Jenkins
	// Description: Session page for deckbuilder.
	// Due Date: 08-31-14
	// Acknowledgements:

	include_once "connection.php";
	include "utilities.php";

	session_start();

	$username1 = $mysqli->real_escape_string(cleanString($_POST['username1']));
	$password1 = $mysqli->real_escape_string(cleanString($_POST['password1']));
	$username2 = $mysqli->real_escape_string(cleanString($_POST['username2']));
	$password2 = $mysqli->real_escape_string(cleanString($_POST['password2']));
	$returningUser = $_POST['returningUser'];
	$newUser = $_POST['newUser'];
	$salt = "WorkWorkJobsDone";

	// Determine if a session needs to be created
	// and where to redirect
	if($returningUser)
	{
		$sql = "SELECT password FROM user WHERE username = '$username1'";

		$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());

		if($result->num_rows < 1) // No such user exists
		{
			echo "No account exists with that username.";
			die();
		}

		$userData = $result->fetch_assoc();
		$hash = hash('sha256', $salt.hash('sha256', $password1));

		if($hash != $userData['password']) // Incorrect password
		{
			echo "Incorrect password.";
			die();
		}
		else
		{
			validateUser($username1); // Sets the session data for this user
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL=deckbuilder.php">'; 
		}
	}
	else if($newUser)
	{
		$sql = "SELECT password FROM user WHERE username = '$username2'";

		$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());

		if($result->num_rows >= 1) // Username taken
		{
			echo "That username is taken.";
			die();
		}

		// Hash password and add account to database
		$hash = hash('sha256', $salt.hash('sha256', $password2));
		$sql = "INSERT INTO user VALUES(DEFAULT, '$username2', '$hash')";

		$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());

		validateUser($username2); // Sets the session data for this user
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=deckbuilder.php">'; 
		die();
	}
	else if(isset($_SESSION['username']))
	{
		header('Location: deckbuilder.php');
		die();
	}
	else if(!(isset($_SESSION['username'])))
	{
		header('Location: login.php');
		die();
	}
?>