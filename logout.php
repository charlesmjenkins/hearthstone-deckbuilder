<?php
	// Author: Charles (C.J.) Jenkins
	// Description: Logout for deckbuilder.
	// Due Date: 08-31-14
	// Acknowledgements: http://tinsology.net/2009/06/creating-a-secure-login-system-the-right-way/

	include_once "connection.php";
	include "utilities.php";
	
	session_start(); 
	
	//if the user has not logged in
	if(!isLoggedIn())
	{
		header('Location: login.php');
		die();
	}
	
	logout();
?>