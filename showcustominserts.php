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
Description: Somewhat contrived admin page to meet class's CRUD requirements.
Due Date: 08-31-14
Acknowledgements:
-->

<html>

	<head>
		<meta charset="UTF-8">
		<title>Hearthstone Deckbuilder</title>
		<link rel="stylesheet" type="text/css" href="final_project.css">
		<link rel="stylesheet" type="text/css" href="tooltipster.css">
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.validate.min.js"></script>
		<script type="text/javascript" src="js/jquery.tooltipster.min.js"></script>
		<script type="text/javascript">
			// Tooltipster and Validation Setup
			$(document).ready(function() {
			    $('input[type="text"]').tooltipster({
			        trigger: 'custom',
			        onlyOne: false,
			        position: 'right'
			    });

			    $('#addClassForm').validate({
			        errorPlacement: function (error, element) {
			            $(element).tooltipster('update', $(error).text());
			            $(element).tooltipster('show');
			        },
			        success: function (label, element) {
			            $(element).tooltipster('hide');
			        },
			        rules: {
			            className: {
			                required: true
			            }
			        },
			        submitHandler: function (form) {
			            form.submit();
			        }
			    });

			    $('#addCardForm').validate({
			        errorPlacement: function (error, element) {
			            $(element).tooltipster('update', $(error).text());
			            $(element).tooltipster('show');
			        },
			        success: function (label, element) {
			            $(element).tooltipster('hide');
			        },
			        rules: {
			            cardName: {
			                required: true
			            },
			            cardCost: {
			                digits: true
			            },
			            cardAttack: {
			                digits: true
			            },
			            cardHealth: {
			                digits: true
			            },
			            cardDurability: {
			                digits: true
			            }
			        },
			        submitHandler: function (form) {
			            form.submit();
			        }
			    });
			});
		</script>

	</head>

	<body>
		<div id="content">
			<h1>Hearthst<img src="images/hearthstone_swirl_glow_2.png" alt="hearthstone swirl"/>ne Deckbuilder</h1>
			<form action="deckbuilder.php" class="menuBtn">
				<input type="submit" value="Back" />
			</form>
			<form action="logout.php" class="menuBtn">
				<input type="submit" value="Log Out" />
			</form>
			<h4>This page acts as an admin-like interface for adding new classes and cards to the database.</h4>
			<h4>NOTE: The custom additions will only appear on this page as this page simply fulfills the CS275 requirement of being able to insert into every table in the database.</h4>

			<h2>Add a Class</h2>
			<form id="addClassForm" method="POST" action="classcreate.php">
			   <input type="text" name="className" id="className" placeholder="Class Name" maxlength="20" />
			   <br />
			   <br />
			   <input type="submit" value="Add Class" />
			</form>

			<?php
				// Build custom classes table
				echo '<h2 class="leftHeading">Custom Classes</h2>';

				$sql = "SELECT name FROM class WHERE clid > 11 ORDER BY clid ASC";
				$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());
				$rowCount = $result->num_rows;

				echo "<h3 class='leftHeading'>Showing all ". $rowCount ." custom classes:</h3>";

				echo '<div id="allClassDiv">';
				echo '<table id="allClassTable" class="outerTable">';
				echo '<tr><th class="nameCell">Class Name</th></tr>';

				while ($row = $result->fetch_assoc()) {
					echo '<tr class="browseRow">';
					echo '<td class="nameCell">'.$row['name'].'</td></tr>';
				}

				echo "</table>";
				echo '</div>';
			?>

			<h2>Add a Card</h2>
			<form id="addCardForm" method="POST" action="cardcreate.php">
			   <input type="text" name="cardName" id="cardName" placeholder="Name" maxlength="20"  />
			   <br />
			   <select name="cardRarity" id="cardRarity">
			   		<option value="Free">Free</option>
					<option value="Common">Common</option>
					<option value="Rare">Rare</option>
					<option value="Epic">Epic</option>
					<option value="Legendary">Legendary</option>
			   </select>
			   <br />
			   <input type="text" name="cardCost" id="cardCost" placeholder="Mana Cost" maxlength="3"  />
			   <br />
			   <input type="text" name="cardAttack" id="cardAttack" placeholder="Attack" maxlength="3"  />
			   <br />
			   <input type="text" name="cardHealth" id="cardHealth" placeholder="Health" maxlength="3"  />
			   <br />
			   <input type="text" name="cardDurability" id="cardDurability" placeholder="Durability" maxlength="3"  />
			   <br />
			   <input type="text" name="cardText" id="cardText" placeholder="Card Text" maxlength="255"  />
			   <br />
			   <select name="cardClass" id="cardClass">
			   		<?php
			   			$sql = "SELECT clid, name FROM class ORDER BY clid ASC";
						$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());

						while ($row = $result->fetch_assoc()) {
							echo '<option value="'.$row['clid'].'">'.$row['name'].'</option>';
						}
					?>
			   </select>
			   <br />
			   <br />
			   <input type="submit" value="Add Card" />
			</form>

			<?php
				// Build custom cards table
				echo '<h2 class="leftHeading">Custom Cards</h2>';

				$sql = "SELECT caid, name, type, rarity, class, cost, attack, health, durability, `text` FROM card WHERE `type` = 'Custom' ORDER BY class ASC, cost ASC";
				$result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());
				$rowCount = $result->num_rows;

				echo "<h3 class='leftHeading'>Showing all ". $rowCount ." custom cards:</h3>";

				echo '<div id="allCardDiv">';
				echo '<table id="allCardTable" class="outerTable">';
				echo '<tr><th class="nameCell">Name</th><th class="typeCell">Type</th><th class="classCell">Class</th><th class="costCell"><img src="images/mana.png" alt="mana cost icon"/></th><th class="attackCell"><img src="images/attack.png" alt="attack icon"/></th><th class="healthCell"><img src="images/health.png" alt="health icon"/></th><th class="durabilityCell"><img src="images/durability.png" alt="durability icon"/></th><th class="textCell">Text</th></tr>';

				while ($row = $result->fetch_assoc()) {
					echo '<tr class="browseRow" id="';
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
					if ($row['class'] == 2)
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
					else
						echo '1';
					echo '_30x30.png" /></td><td class="costCell">'.$row['cost'].'</td><td class="attackCell">'.$row['attack'].'</td><td class="healthCell">'.$row['health'].'</td><td class="durabilityCell">'.$row['durability'].'</td><td class="textCell">'.$row['text'].'</td></tr>';
				}

				echo "</table>";
				echo '</div>';
			?>
		</div>
	</body>
</html>