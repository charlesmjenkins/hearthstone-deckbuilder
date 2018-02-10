<?php session_start(); 

	include_once "connection.php";
	include "utilities.php";

	if(isLoggedIn())
	{
		header('Location: deckbuilder.php');
		die();
	}
?>
<!DOCTYPE html>
<!--
Author: Charles (C.J.) Jenkins
Description: Login to the deckbuilder.
Due Date: 08-31-14
Acknowledgements:
-->

<html>

	<head>
		<meta charset="UTF-8">
		<title>Hearthstone Deckbuilder: Login</title>
		<link rel="stylesheet" type="text/css" href="final_project.css">
		<link rel="stylesheet" type="text/css" href="tooltipster.css" />
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.validate.min.js"></script>
		<script type="text/javascript" src="js/jquery.tooltipster.min.js"></script>
		<script>
			// Tooltipster and Validation Setup
	        $(document).ready(function() {
			    $('input[type="text"], input[type="password"]').tooltipster({
			        trigger: 'custom',
			        onlyOne: false,
			        position: 'right'
			    });

			    $('#loginForm').validate({
			        errorPlacement: function (error, element) {
			            $(element).tooltipster('update', $(error).text());
			            $(element).tooltipster('show');
			        },
			        success: function (label, element) {
			            $(element).tooltipster('hide');
			        },
			        rules: {
			            username1: {
			                required: true,
			                minlength: 2
			            },
			            password1: {
			                required: true,
			                minlength: 5
			            }
			        },
			        submitHandler: function (form) {
			        	$.ajax({
				            url: form.action,
				            type: form.method,
				            data: $(form).serialize(),
				            success: function(response) {
				                $('#status1').html(response);
				            }            
				        });
			        }
			    });

			    $('#createAccountForm').validate({
			        errorPlacement: function (error, element) {
			            $(element).tooltipster('update', $(error).text());
			            $(element).tooltipster('show');
			        },
			        success: function (label, element) {
			            $(element).tooltipster('hide');
			        },
			        rules: {
			            username2: {
			                required: true,
			                minlength: 2
			            },
			            password2: {
			                required: true,
			                minlength: 5
			            },
			            password2Again: {
			                required: true,
			                minlength: 5,
			                equalTo: "#password2"
			            }
			        },
			        submitHandler: function (form) {
			            $.ajax({
				            url: form.action,
				            type: form.method,
				            data: $(form).serialize(),
				            success: function(response) {
				                $('#status2').html(response);
				            }            
				        });
			        }
			    });
	        });
    	</script>
	</head>

	<body>

		<div id="contentLogin">
			<h1>HEARTHST<img src="images/hearthstone_swirl_glow_2.png" alt="hearthstone swirl" />NE DECKBUILDER</h1>

			<form id="loginForm" method="POST" action="session.php">
			   <h2>Log in: </h2>
			   <input type="text" name="username1" id="username1" placeholder="Username" maxlength="20" />
			   <br />
			   <input type="password" name="password1" id="password1" placeholder="Password" maxlength="20" />
			   <br />
			   <br />
			   <input type="hidden" name="returningUser" value="true">
			   <input type="submit" value="Log In" />
			</form>

			<div id="status1"></div>

			<form id="createAccountForm" method="POST" action="session.php">
			   <h2>Create an account: </h2>
			   <input type="text" name="username2" id="username2" placeholder="Username" maxlength="20"  />
			   <br />
			   <input type="password" name="password2" id="password2" placeholder="Password" maxlength="20"  />
			   <br />
			   <input type="password" name="password2Again" id="password2Again" placeholder="Re-enter Password"/>
			   <br />
			   <br />
			   <input type="hidden" name="newUser" value="true">
			   <input type="submit" value="Create Account" />
			</form>

			<div id="status2"></div>
		</div>
	</body>
</html>