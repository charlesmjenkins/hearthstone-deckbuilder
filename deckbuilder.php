<?php
	session_start();

	include_once "connection.php";
	include "utilities.php";

	if(!isLoggedIn())
	{
		header('Location: login.php');
		die();
	}

	$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<!--
Author: Charles (C.J.) Jenkins
Description: The deckbuilder main page.
Due Date: 08-31-14
Acknowledgements:
-->

<html>

	<head>
		<meta charset="UTF-8">
		<title>Hearthstone Deckbuilder</title>
		<link rel="stylesheet" type="text/css" href="final_project.css">
		<link rel="stylesheet" type="text/css" href="tooltipster.css" />
		<script src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.tooltipster.min.js"></script>
		<script type="text/javascript">
			var card_id = '';
			var card_img_str = '';

			// Function to display card image when hovering over card
			function hover(element) {
			    card_id = element.getAttribute('id');
			    card_img_str = '<img src="http://wow.zamimg.com/images/hearthstone/cards/enus/original/' + card_id + '.png" />';
			    $('.cardtip').tooltipster('content', card_img_str);
			}

			// Tooltipster Setup
			$(document).ready(function() {
	            $('.cardtip').tooltipster({
	            	content: $(card_img_str),
	            	contentAsHTML: true,
	            	position: 'top-left',
	            	theme: 'none'
	            });
	        });
		</script>

	</head>

	<body>
		<div id="content">
			<h1>Hearthst<img src="images/hearthstone_swirl_glow_2.png" alt="hearthstone swirl"/>ne Deckbuilder</h1>

			<form action="showcustominserts.php" class="menuBtn">
				<input type="submit" value="Admin" />
			</form>
			<form action="logout.php" class="menuBtn">
				<input type="submit" value="Log Out" />
			</form>

			<h4>Click a deck to edit it or create a new deck.</h4>

			<h2 class="leftHeading"><?php echo $username; ?>'s Decks</h2>

			<?php
				// Get the name, class, and ID of the deck
				$sql = "SELECT name, class, did FROM deck WHERE user = (SELECT uid FROM user WHERE username = '$username')";
				$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());

				// Build deck thumbnail table
				echo '<div class="centered">';
				echo '<table class="outerTable"><tr>';
				
				while ($row = $result->fetch_assoc()) {
					echo '<td class="outerTd">';
					echo '<table class="innerTable">';
					echo '<tr><th>' . $row['name'] .'</th></tr>';

					$sql = "SELECT COUNT(caid) as count FROM deck_card WHERE did = ".$row['did'];
					$result2 = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());
					$currentCountOfDeck = $result2->fetch_assoc();

				  	echo '<tr><td><a href="deckedit.php?did='.$row['did'].'"><img src="images/class_icon2_' . $row['class'] . '_100x100.jpg" alt="class icon" /></a></td></tr>';
				  	echo '<tr><th>' . $currentCountOfDeck['count'] .'/30</th></tr>';
				  	echo '</table>';
				  	echo '</td>';
				}

				// Build 'create deck' button table
				echo '<td class="outerTd">';
				echo '<table class="innerTable">';
				echo '<tr><th>New Deck</th></tr>';
			  	echo '<tr><td><a href="classandname.php"><img src="images/add_icon_100x100_3.png"/></td></a></tr>';
			  	echo '<tr><th>-/30</th></tr>';
			  	echo '</table>';
				echo '</td>';
				echo '</tr>';
				echo '</table>';

				echo '<h2 class="leftHeading">Browse All Cards</h2>';

				// Build table of all cards
				$sql = "SELECT caid, name, type, rarity, class, cost, attack, health, durability, `text` FROM card WHERE collectible = true AND type <> 'Hero' ORDER BY class ASC, cost ASC";
				$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());
				$rowCount = $result->num_rows;

				echo "<h3 class='leftHeading'>Showing ". $rowCount ." cards:</h3>";

				echo '<div id="allCardDiv">';
				echo '<table id="allCardTable" class="outerTable">';
				echo '<tr><th class="nameCell">Name</th><th class="typeCell">Type</th><th class="classCell">Class</th><th class="costCell"><img src="images/mana.png" alt="mana cost icon"/></th><th class="attackCell"><img src="images/attack.png" alt="attack icon"/></th><th class="healthCell"><img src="images/health.png" alt="health icon"/></th><th class="durabilityCell"><img src="images/durability.png" alt="durability icon"/></th><th class="textCell">Text</th></tr>';

				while ($row = $result->fetch_assoc()) {
					echo '<tr onmouseover="hover(this);" class="browseRow cardtip" id="';
					echo $row['caid'];
					echo '"><td class="nameCell ';
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
						echo '<img src="images/common_gem.png" alt="common gem"/>';
					else if ($row['rarity'] == "Rare")
						echo '<img src="images/rare_gem.png" alt="rare gem"/>';
					else if ($row['rarity'] == "Epic")
						echo '<img src="images/epic_gem.png" alt="epic gem"/>';
					else if ($row['rarity'] == "Legendary")
						echo '<img src="images/legendary_gem.png" alt="legendary gem"/>';
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
					echo '_30x30.png" /></td><td class="costCell">'.$row['cost'].'</td><td class="attackCell">'.$row['attack'].'</td><td class="healthCell">'.$row['health'].'</td><td class="durabilityCell">'.$row['durability'].'</td><td class="textCell">'.$row['text'].'</td></tr>';
				}

				echo "</table>";
				echo '</div>';
				echo '</div>';
			?>
		</div>
	</body>
</html>