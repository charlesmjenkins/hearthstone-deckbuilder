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
	$deckID = $_GET['did'];

	// Security Feature: Make sure this deck belongs to current user 
	// (in case deck id argument is modified in URL)
	$sql = "SELECT username FROM user WHERE user.uid = (SELECT user FROM deck WHERE did = $deckID)";
	$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());
	$row = $result->fetch_assoc();
	$correctUser = $row['username'];

	if($username != $correctUser)
	{
		header('Location: deckbuilder.php');
	}

	$sql = "SELECT name, class FROM deck WHERE did = $deckID";
	$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());
	$row = $result->fetch_assoc();
	$deckName = $row['name'];
	$deckClass = $row['class'];
?>
<!DOCTYPE html>
<!--
Author: Charles (C.J.) Jenkins
Description: The deck editor page.
Due Date: 08-31-14
Acknowledgements: AJAX code: http://www.w3schools.com/php/php_ajax_database.asp
-->

<html>

	<head>
		<meta charset="UTF-8">
		<title>Hearthstone Deckbuilder: Edit Deck</title>
		<link rel="stylesheet" type="text/css" href="final_project.css">
		<link rel="stylesheet" type="text/css" href="tooltipster.css" />
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.validate.min.js"></script>
		<script type="text/javascript" src="js/jquery.tooltipster.min.js"></script>
		<script type="text/javascript" src="js/Chart.js"></script>

		<script type="text/javascript">
		// AJAX function for adding card to user deck
		function addToDeck(caid) {
		  if (caid=="") {
		    document.getElementById("deckContents").innerHTML="";
		    return;
		  } 
		  if (window.XMLHttpRequest) {
		    // code for IE7+, Firefox, Chrome, Opera, Safari
		    xmlhttp=new XMLHttpRequest();
		  } else { // code for IE6, IE5
		    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		  xmlhttp.onreadystatechange=function() {
		    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		      document.getElementById("deckContents").innerHTML=xmlhttp.responseText;
		    }
		  }
		  xmlhttp.open("GET", "addcard.php?did=<?php echo $deckID; ?>&caid="+caid, true);
		  xmlhttp.send();
		}

		// AJAX function for removing card from user deck
		function removeFromDeck(caid) {
		  if (caid=="") {
		    document.getElementById("deckContents").innerHTML="";
		    return;
		  } 
		  if (window.XMLHttpRequest) {
		    // code for IE7+, Firefox, Chrome, Opera, Safari
		    xmlhttp=new XMLHttpRequest();
		  } else { // code for IE6, IE5
		    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		  xmlhttp.onreadystatechange=function() {
		    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		      document.getElementById("deckContents").innerHTML=xmlhttp.responseText;
		    }
		  }
		  xmlhttp.open("GET", "removecard.php?did=<?php echo $deckID; ?>&caid="+caid, true);
		  xmlhttp.send();
		}

		var card_id = '';
		var card_img_str = '';

		// Function to display card image when hovering over card
		function hover(element) {
		    card_id = element.getAttribute('id');
		    card_img_str = '<img src="http://wow.zamimg.com/images/hearthstone/cards/enus/original/' + card_id + '.png" />';
		    $('.cardtip').tooltipster('content', card_img_str);
		}

		// Tooltipster and Validation Setup
		$(document).ready(function() {
            $('.cardtip').tooltipster({
            	content: $(card_img_str),
            	contentAsHTML: true,
            	position: 'top-left',
            	theme: 'none'
            });

		    $('input[type="text"]').tooltipster({
		        trigger: 'custom',
		        onlyOne: false,
		        position: 'right'
		    });

		    $('#renameForm').validate({
		        errorPlacement: function (error, element) {
		            $(element).tooltipster('update', $(error).text());
		            $(element).tooltipster('show');
		        },
		        success: function (label, element) {
		            $(element).tooltipster('hide');
		        },
		        rules: {
		            newDeckName: {
		                required: true
		            }
		        },
		        submitHandler: function (form) {
		            form.submit();
		        }
		    });
        });
		</script>

	</head>

	<body onload="createChart();">
		<div id="content">
			<h1>Hearthst<img src="images/hearthstone_swirl_glow_2.png" alt="hearthstone swirl"/>ne Deckbuilder</h1>
			<form action="deckbuilder.php" class="menuBtn">
				<input type="submit" value="Return to Account Overview" />
			</form>
			<form action="logout.php" class="menuBtn">
				<input type="submit" value="Log Out" />
			</form>

			<h4>Click an available card to add it to your deck. Click a card in your deck to remove it.</h4>

			<?php
				echo '<div class="centered">';

				echo '<div id="deckContents">';

				// Get the number of cards currently in the deck
				$sql = "SELECT COUNT(caid) as count FROM deck_card WHERE did = $deckID";
				$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());
				$currentCountOfDeck = $result->fetch_assoc();
				$currentCountOfDeck = $currentCountOfDeck['count'];

				// Get the name and class of the deck
				$sql = "SELECT name, class FROM deck WHERE user = (SELECT uid FROM user WHERE username = '$username') AND did = $deckID";
				$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());

				// Build deck's class thumbnail, delete button, and rename form
				echo '<table class="outerTable"><tr>';
				
				while ($row = $result->fetch_assoc()) {
					echo '<td class="outerTd">';
					echo '<table class="innerTable">';
					echo '<tr><th>' . $row['name'] .'</th></tr>';
				  	echo '<tr><td><img src="images/class_icon2_' . $row['class'] . '_100x100.jpg"/></td></tr>';
				  	echo '<tr><th>' . $currentCountOfDeck .'/30</th></tr>';
				  	echo '</table>';
				  	echo '</td>';
				}

				echo '<td class="outerTd">';
				echo '<table class="innerTable">';
				echo '<tr><th>Delete Deck</th></tr>';
			  	echo '<tr><td><a href="deckdelete.php?did='.$deckID.'"><img src="images/delete_icon_100x100.png"/></td></a></tr>';
			  	echo '<tr><th>-/-</th></tr>';
			  	echo '</table>';
				echo '</td>';

				echo '<td class="outerTd">';
				echo '<table class="innerTable">';
				echo '<tr><th>Rename Deck</th></tr>';
			  	echo '<tr><td id="renameLink">';
			  	echo '<form action="deckrename.php?did='.$deckID.'" method="post" id="renameForm"><input type="text" placeholder="New Deck Name" name="newDeckName" maxlength="20" /><input type="submit" value="Rename Deck" /></form>';
			  	echo '</td></tr>';
			  	echo '<tr><th>-/-</th></tr>';
			  	echo '</table>';
				echo '</td>';

				echo '</tr>';
				echo '</table>';

				// Build deck contents table
				$sql = "SELECT card.caid, name, type, rarity, class, cost, COUNT(deck_card.caid) as count FROM card 
						INNER JOIN deck_card ON card.caid = deck_card.caid
						WHERE did = $deckID
						GROUP BY name";
				$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());

				if($result->num_rows == 0)
					echo 'There are no cards in this deck.';
				else
				{
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

						// Keep track of mana costs for generating data charts
						if((int)$row['count'] == 1)
						{
							$manaCostArray[] = (int)$row['cost'];
						}	
						else
						{
							$manaCostArray[] = (int)$row['cost'];
							$manaCostArray[] = (int)$row['cost'];
						}
					}

					echo "</table>";
				}

				// Build mana chart canvas element
				echo '<div id="deckData">';
				echo '<h3>Mana Curve</h3>';
				echo '<canvas id="manaChart" width="480" height="300"></canvas>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
				
				// Get the name of the class of the deck
				$sql = "SELECT name FROM class WHERE clid = $deckClass";
				$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());
				$className = $result->fetch_assoc();

				echo '<h2 class="leftHeading">Available Cards for '. $className['name'] .' Decks</h2>';

				// Build table of available cards
				$sql = "SELECT caid, name, type, rarity, class, cost, attack, health, durability, `text` FROM card WHERE collectible = true AND type <> 'Hero' AND (class IS NULL OR class = $deckClass) ORDER BY class DESC, cost ASC";
				$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());
				$rowCount = $result->num_rows;

				echo "<h3 class='leftHeading'>Showing ". $rowCount ." cards:</h3>";

				echo '<div id="availableCardDiv">';
				echo '<table id="availableCardTable" class="outerTable">';
				echo '<tr><th class="nameCell">Name</th><th class="typeCell">Type</th><th class="classCell">Class</th><th class="costCell"><img src="images/mana.png" /></th><th class="attackCell"><img src="images/attack.png" /></th><th class="healthCell"><img src="images/health.png" /></th><th class="durabilityCell"><img src="images/durability.png" /></th><th class="textCell">Text</th></tr>';

				while ($row = $result->fetch_assoc()) {
					echo '<tr onmouseover="hover(this);" class="browseRow cardtip" onclick="addToDeck(this.id)" id="';
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
					echo '_30x30.png" /></td><td class="costCell">'.$row['cost'].'</td><td class="attackCell">'.$row['attack'].'</td><td class="healthCell">'.$row['health'].'</td><td class="durabilityCell">'.$row['durability'].'</td><td class="textCell">'.$row['text'].'</td></tr>';
				}

				echo "</table>";
				echo '</div>';
				echo '</div>';
			?>

			<script type="text/javascript">
				// This function creates a bar chart of the mana cost distribution
				// of the current deck using Chart.js
				function createChart()
		        {
		            var ctx = document.getElementById("manaChart").getContext("2d");
		 
		            var data = {
		                labels : ["0", "1", "2", "3", "4", "5", "6", "7+"],
		                datasets : [
		                            {
		                                fillColor : "rgba(220,220,220,0.5)",
		                                strokeColor : "rgba(220,220,220,1)",
		                                data : [0, 0, 0, 0, 0, 0, 0, 0]
		                            }
		                           ]
		                      };
		 
		            options = {
		                barDatasetSpacing : 15,
		                barValueSpacing: 10
		            };
		 
		           	var manaChart = new Chart(ctx).Bar(data, options);

		           	<?php
			           	$manaCostArray2 = array(0, 0, 0, 0, 0, 0, 0, 0);

			           	// Count the instances of mana costs
			           	 for($i = 0; $i <= $currentCountOfDeck; $i++)
			           	{
			           		if($manaCostArray[$i] === NULL)
			           			break;
			           		else if($manaCostArray[$i] == 0)
			           		{
			           			$manaCostArray2[0] += 1;
			           		}
			           		else if($manaCostArray[$i] == 1)
			           		{
			           			$manaCostArray2[1] += 1;
			           		}
			           		else if($manaCostArray[$i] == 2)
			           		{
			           			$manaCostArray2[2] += 1;
			           		}
			           		else if($manaCostArray[$i] == 3)
			           		{
			           			$manaCostArray2[3] += 1;
			           		}
			           		else if($manaCostArray[$i] == 4)
			           		{
			           			$manaCostArray2[4] += 1;
			           		}
			           		else if($manaCostArray[$i] == 5)
			           		{
			           			$manaCostArray2[5] += 1;
			           		}
			           		else if($manaCostArray[$i] == 6)
			           		{
			           			$manaCostArray2[6] += 1;
			           		}
			           		else if($manaCostArray[$i] >= 7)
			           		{
			           			$manaCostArray2[7] += 1;
			           		}
			           	}
		           	?>

		           	// Update mana cost chart
		            manaChart.datasets[0].bars[0].value = <?php echo $manaCostArray2[0];?>;
		            manaChart.datasets[0].bars[1].value = <?php echo $manaCostArray2[1];?>;
		            manaChart.datasets[0].bars[2].value = <?php echo $manaCostArray2[2];?>;
		            manaChart.datasets[0].bars[3].value = <?php echo $manaCostArray2[3];?>;
		            manaChart.datasets[0].bars[4].value = <?php echo $manaCostArray2[4];?>;
		            manaChart.datasets[0].bars[5].value = <?php echo $manaCostArray2[5];?>;
		            manaChart.datasets[0].bars[6].value = <?php echo $manaCostArray2[6];?>;
		            manaChart.datasets[0].bars[7].value = <?php echo $manaCostArray2[7];?>;
					manaChart.update();
		        }
			</script>
	</body>
</html>			