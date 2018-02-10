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
Description: Page to pick class and name for new deck.
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
		<script src="js/jquery.validate.min.js"></script>
		<script type="text/javascript" src="js/jquery.tooltipster.min.js"></script>

		<script type="text/javascript">
			var card_id = '';
			var card_img_str = '';

			// Function to display class card image when hovering over class icon
			function hover(element) {
			    card_id = element.getAttribute('id');
			    card_img_str = '<img src="http://wow.zamimg.com/images/hearthstone/cards/enus/animated/' + card_id + '_premium.gif" />';
			    $('.cardtip').tooltipster('content', card_img_str);
			}

			// Function to display deck name prompt after selecting a class
			function showDiv() {
			    document.getElementById('nameAndSubmit').style.display = 'block';
			}

			// Tooltipster and Validation Setup
			$(document).ready(function() {
	            $('.cardtip').tooltipster({
	            	content: $(card_img_str),
	            	contentAsHTML: true,
	            	position: 'left',
	            	theme: 'none'
	            });

			    $('input[type="text"], input[type="radio"]').tooltipster({
			        trigger: 'custom',
			        onlyOne: false,
			        position: 'right'
			    });

			    jQuery.validator.setDefaults({
				  debug: true,
				  success: "valid"
				});

			    $('#classNameForm').validate({
			        errorPlacement: function (error, element) {
			            $(element).tooltipster('update', $(error).text());
			            $(element).tooltipster('show');
			        },
			        success: function (label, element) {
			            $(element).tooltipster('hide');
			        },
			        rules: {
			        	classRadio: {
			        		required: true
			        	},
			            deckName: {
			                required: true,
			                minlength: 1
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
			<h1>Hearthst<img src="images/hearthstone_swirl_glow_2.png" />ne Deckbuilder</h1>
			
			<form action="deckbuilder.php" class="menuBtn">
				<input type="submit" value="Back" />
			</form>
			<form action="logout.php" class="menuBtn">
				<input type="submit" value="Log Out" />
			</form>

			<form id="classNameForm" method="POST" action="deckcreate.php">
			   <h4>Pick a class, then name your deck: </h4>

			   <table class="classTable">
			   		<tr>
			   			<td id="HERO_06" onmouseover="hover(this);" class="cardtip" onclick="showDiv();"><label><input type="radio" name="classRadio" value="2" />
			   				<img src="images/class_icon_2.png" /></label></td>
			   			<td id="HERO_05" onmouseover="hover(this);" class="cardtip" onclick="showDiv();"><label><input type="radio" name="classRadio" value="3" />
			   				<img src="images/class_icon_3.png" /></label></td>
			   			<td id="HERO_08" onmouseover="hover(this);" class="cardtip" onclick="showDiv();"><label><input type="radio" name="classRadio" value="4" />
			   				<img src="images/class_icon_4.png" /></label></td>
			   		</tr>
			   		<tr>
			   			<td id="HERO_04" onmouseover="hover(this);" class="cardtip" onclick="showDiv();"><label><input type="radio" name="classRadio" value="5" />
			   				<img src="images/class_icon_5.png" /></label></td>
			   			<td id="HERO_09" onmouseover="hover(this);" class="cardtip" onclick="showDiv();"><label><input type="radio" name="classRadio" value="6" />
			   				<img src="images/class_icon_6.png" /></label></td>
			   			<td id="HERO_03" onmouseover="hover(this);" class="cardtip" onclick="showDiv();"><label><input type="radio" name="classRadio" value="7" />
			   				<img src="images/class_icon_7.png" /></label></td>
			   		</tr>
			   		<tr>
			   			<td id="HERO_02" onmouseover="hover(this);" class="cardtip" onclick="showDiv();"><label><input type="radio" name="classRadio" value="8" />
			   				<img src="images/class_icon_8.png" /></label></td>
			   			<td id="HERO_07" onmouseover="hover(this);" class="cardtip" onclick="showDiv();"><label><input type="radio" name="classRadio" value="9" />
			   				<img src="images/class_icon_9.png" /></label></td>
			   			<td id="HERO_01" onmouseover="hover(this);" class="cardtip" onclick="showDiv();"><label><input type="radio" name="classRadio" value="10" />
			   				<img src="images/class_icon_10.png" /></label></td>
			   		</tr>
			   </table>
			   <div id="nameAndSubmit">
				   <input type="text" name="deckName" id="deckName" placeholder="Deck Name" maxlength="20" />
				   <br />
				   <br />
				   <input type="submit" value="Create New Deck" />
				</div>
			</form>
		</div>
	</body>
</html>