<?php
	// Author: Charles (C.J.) Jenkins
	// Description: Utilities for deckbuilder.
	// Due Date: 08-31-14
	// Acknowledgements: http://tinsology.net/2009/06/creating-a-secure-login-system-the-right-way/

	function cleanString($UserInput) {
        $UserInput = strip_tags($UserInput);
        $UserInput = str_replace("'", "", $UserInput);
        $UserInput = str_replace('"', "", $UserInput);
        $UserInput = htmlspecialchars($UserInput);
        return $UserInput;
    }
	function validateUser($username)
	{
		session_regenerate_id (); //this is a security measure

		$_SESSION['valid'] = 1;
		$_SESSION['username'] = $username;
	}
	function isLoggedIn()
	{
		if(isset($_SESSION['valid']) && $_SESSION['valid'])
			return true;
		else
			return false;
	}
	function logout()
	{
		$_SESSION = array(); //destroy all of the session variables
		session_destroy();
		
		header('Location: login.php');
	}
?>