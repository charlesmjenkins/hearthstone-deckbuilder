<?php
	// Author: Charles (C.J.) Jenkins
	// Description: This page creates a new card in the database
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

	// Generate random card ID and check that it isn't taken
	$cardIDIsValid = false;
	$cardID = "CUSTCARD_".mt_rand(1000, 99999).mt_rand(100, 999).mt_rand(0, 99);
	$sql = "SELECT caid from card WHERE caid = '$cardID'";
	$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());
	$numRows = $result->num_rows;
	
	while(!$cardIDIsValid)
	{
		if($numRows == 0)
		{
			$cardIDIsValid = true;
		}
		else
		{
			// If it is taken, regenerate the ID and check again
			$cardID = "CUSTCARD_".mt_rand(1000, 99999).mt_rand(100, 999).mt_rand(0, 99);
			$sql = "SELECT caid from card WHERE caid = '$cardID'";
			$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());
			$numRows = $result->num_rows;
		}
	}

	$cardCost = NULL;
	$cardAttack = NULL;
	$cardHealth = NULL;
	$cardDurability = NULL;
	$cardText = NULL;

	$cardName = $mysqli->real_escape_string(cleanString($_POST['cardName']));
	$cardRarity = $_POST['cardRarity'];
	if(isset($_POST['cardCost']))
		$cardCost = $_POST['cardCost'];
	if(isset($_POST['cardAttack']))
		$cardAttack = $_POST['cardAttack'];
	if(isset($_POST['cardHealth']))
		$cardHealth = $_POST['cardHealth'];
	if(isset($_POST['cardDurability']))
		$cardDurability = $_POST['cardDurability'];
	$cardText = $mysqli->real_escape_string(cleanString($_POST['cardText']));
	$cardClass = $_POST['cardClass'];

	if($cardRarity == "Legendary")
		$cardElite = 1;
	else
		$cardElite = 0;

	$sql = "INSERT INTO card 
			VALUES ('$cardID', '$cardName', 'Custom', '$cardRarity', ";

	if($cardCost === NULL)
		$sql .= "NULL";
	else
		$sql .= "$cardCost";

	$sql .= ", ";

	if($cardAttack === NULL)
		$sql .= "NULL";
	else
		$sql .= "$cardAttack";

	$sql .= ", ";

	if($cardHealth === NULL)
		$sql .= "NULL";
	else
		$sql .= "$cardHealth";

	$sql .= ", ";

	if($cardDurability === NULL)
		$sql .= "NULL";
	else
		$sql .= "$cardDurability";

	$sql .= ", ";

	if($cardText !== NULL)
		$sql .= "'";

	$sql .= $cardText;

	if($cardText !== NULL)
		$sql .= "'";

	$sql .= ", NULL, $cardElite, $cardClass)";

	$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());

	header('Location: showcustominserts.php');			
?>