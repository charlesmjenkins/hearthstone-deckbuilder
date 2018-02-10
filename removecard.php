<?php
	// Author: Charles (C.J.) Jenkins
	// Description: This page removes a card from a user's deck
	//              and sends back the updated database table 
	//				to the requesting page.
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

	$caid = $_GET['caid'];
	$did = $_GET['did'];
	$username = $_SESSION['username'];

	// Security Feature: Check that the user isn't trying to delete a card 
	// that doesn't exist in the deck by means of the URL GET arguments
	$sql = "SELECT COUNT(caid) as count FROM deck_card WHERE did = $did AND caid = '$caid'";
	$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());
	if($result->num_rows == 0)
	{
		break;
	}	
	else
	{
		// Delete only the first copy found
		$sql = "DELETE FROM deck_card WHERE did = $did AND caid = '$caid' LIMIT 1";
		$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());
	}

	// Get the updated number of cards in the deck
	$sql = "SELECT COUNT(caid) as count FROM deck_card WHERE did = $did";
	$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());
	$currentCountOfDeck = $result->fetch_assoc();

	// Get the name and class of the deck
	$sql = "SELECT name, class FROM deck WHERE user = (SELECT uid FROM user WHERE username = '$username') AND did = $did";
	$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());

	// Rebuild deck thumbnail table, delete button, and rename form
	echo '<table class="outerTable"><tr>';
	
	while ($row = $result->fetch_assoc()) {
		echo '<td class="outerTd">';
		echo '<table class="innerTable">';
		echo '<tr><th>' . $row['name'] .'</th></tr>';
	  	echo '<tr><td><img src="images/class_icon2_' . $row['class'] . '_100x100"/></td></tr>';
	  	echo '<tr><th>' . $currentCountOfDeck['count'] .'/30</th></tr>';
	  	echo '</table>';
	  	echo '</td>';
	}

	echo '<td class="outerTd">';
	echo '<table class="innerTable">';
	echo '<tr><th>Delete Deck</th></tr>';
  	echo '<tr><td><a href="deckdelete.php?did='.$did.'"><img src="images/delete_icon_100x100"/></td></a></tr>';
  	echo '<tr><th>-/-</th></tr>';
  	echo '</table>';
	echo '</td>';

	echo '<td class="outerTd">';
	echo '<table class="innerTable">';
	echo '<tr><th>Rename Deck</th></tr>';
  	echo '<tr><td id="renameLink">';
  	echo '<form action="deckrename.php?did='.$did.'" method="post" id="renameForm"><input type="text" placeholder="New Deck Name" name="newDeckName" maxlength="20" /><input type="submit" value="Rename Deck" /></form>';
  	echo '</td></tr>';
  	echo '<tr><th>-/-</th></tr>';
  	echo '</table>';
	echo '</td>';

	echo '</tr>';
	echo '</table>';

	// Rebuild table of cards in deck
	$sql = "SELECT card.caid, name, type, rarity, class, cost, COUNT(deck_card.caid) as count FROM card 
			INNER JOIN deck_card ON card.caid = deck_card.caid
			WHERE did = $did
			GROUP BY name";
	$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());

	echo '<div id="cardsInDeck">';
	echo '<table id="deckCardTable" class="outerTable">';
	echo '<tr><th class="countCell"></th><th class="nameCell">Name</th><th class="typeCell">Type</th><th class="classCell">Class</th><th class="costCell"><img src="images/mana.png" /></th></tr>';

	while ($row = $result->fetch_assoc()) {
		echo '<tr onmouseover="hover(this);" class="browseRow cardtip" onclick="removeFromDeck(this.id)" id="';
		echo $row['caid'];
		echo '"><td class="countCell">'.$row['count'].'x</td><td class="nameCell ';
		if ($row['rarity'] == "Common")
			echo 'common';
		else if ($row['rarity'] == "Rare")
			echo 'rare';
		else if ($row['rarity'] == "Epic")
			echo 'epic';
		else if ($row['rarity'] == "Legendary")
			echo 'legendary';
		echo '">';
		if ($row['rarity'] == "Common")
			echo '<img src="images/common_gem.png" />';
		else if ($row['rarity'] == "Rare")
			echo '<img src="images/rare_gem.png" />';
		else if ($row['rarity'] == "Epic")
			echo '<img src="images/epic_gem.png" />';
		else if ($row['rarity'] == "Legendary")
			echo '<img src="images/legendary_gem.png" />';
		else
			echo '<img src="images/empty_24x26.png" />';
		echo ' '.$row['name'].'</td><td class="typeCell">'.$row['type'].'</td><td class="classCell"><img src="images/class_icon_';
		if ($row['class'] == NULL)
			echo '1';
		else if ($row['class'] == 2)
			echo '2';
		else if ($row['class'] == 3)
			echo '3';
		else if ($row['class'] == 4)
			echo '4';
		else if ($row['class'] == 5)
			echo '5';
		else if ($row['class'] == 6)
			echo '6';
		else if ($row['class'] == 7)
			echo '7';
		else if ($row['class'] == 8)
			echo '8';
		else if ($row['class'] == 9)
			echo '9';
		else if ($row['class'] == 10)
			echo '10';
		echo '_30x30.png" /></td><td class="costCell">'.$row['cost'].'</td></tr>';
	}

	echo "</table>";

	// Create prompt to reload mana curve
	echo '<div id="deckData">';
	echo '<h3>Mana Curve</h3>';
	echo '<form method="GET" action="deckedit.php" class="menuBtn" id="reloadMana">';
	echo '<input type="hidden" value="'.$did.'" name="did" />';
	echo '<input type="submit" value="Reload Mana Curve" />';
	echo '</form>';
	echo '</div>';
	echo '</div>';
?>