<?php
	// Author: Charles (C.J.) Jenkins
	// Description: Script to parse the JSON card data and insert it into the database
	// Due Date: 08-31-14

	//---Connect to database--- 
	$dbhost = 'localhost';
	$dbname = ''; //Insert database name here
	$dbuser = ''; //Insert username here
	$dbpass = ''; //Insert password here

	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname); 

	if ($mysqli->connect_errno) {
	  echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	  exit;
	}

	//---PICK CARD JSON---
	$f = file_get_contents('json/combined_core_sets.json');
	$arr = explode('},',$f);  // Prepare for json_decode BUT last } missing

	if(!function_exists('json_decode')) die('Your host does not support json');

	for($i=0; $i<count($arr); $i++)
	{
		$decoded = json_decode($arr[$i].'}'); // Reappend last } or it will return NULL

	    // ---INSERT INTO DATABASE---
	   $sql = 'INSERT INTO card VALUES (';

	    if($decoded->{'id'} !== NULL)
	    {
	    	$sql .= "'";
	    	$sql .= $mysqli->real_escape_string($decoded->{'id'});
	    	$sql .= "', ";
	    }
	    else
	    {
	    	$sql .= "NULL";
	    	$sql .= ", ";
	    }
	    
	    if($decoded->{'name'} !== NULL)
	    {
	    	$sql .= '"';
		    $sql .= $mysqli->real_escape_string($decoded->{'name'});
		    $sql .= '", ';
		}
		else
	    {
	    	$sql .= "NULL";
	    	$sql .= ", ";
	    }

		if($decoded->{'type'} !== NULL)
	    {
	    	$sql .= "'";
		    $sql .= $mysqli->real_escape_string($decoded->{'type'});
		    $sql .= "', ";
		}
		else
	    {
	    	$sql .= "NULL";
	    	$sql .= ", ";
	    }

		if($decoded->{'rarity'} !== NULL)
	    {
	    	$sql .= "'";
		    $sql .= $mysqli->real_escape_string($decoded->{'rarity'});
		    $sql .= "', ";
		}
		else
	    {
	    	$sql .= "NULL";
	    	$sql .= ", ";
	    }

		if($decoded->{'cost'} !== NULL)
	    {
		    $sql .= $decoded->{'cost'};
		    $sql .= ", ";
		}
		else
	    {
	    	$sql .= "NULL";
	    	$sql .= ", ";
	    }

		if($decoded->{'attack'} !== NULL)
	    {
		    $sql .= $decoded->{'attack'};
		    $sql .= ", ";
	    }
	    else
	    {
	    	$sql .= "NULL";
	    	$sql .= ", ";
	    }

	    if($decoded->{'health'} !== NULL)
	    {
		    $sql .= $decoded->{'health'};
		    $sql .= ", ";
		}
		else
	    {
	    	$sql .= "NULL";
	    	$sql .= ", ";
	    }

		if($decoded->{'durability'} !== NULL)
	    {
		    $sql .= $decoded->{'durability'};
		    $sql .= ", ";
	    }
	    else
	    {
	    	$sql .= "NULL";
	    	$sql .= ", ";
	    }

	    if($decoded->{'text'} !== NULL)
	    {
	    	$sql .= '"';
	    	$sql .= $mysqli->real_escape_string($decoded->{'text'});
	    	$sql .= '", ';
		}
		else
	    {
	    	$sql .= "NULL";
	    	$sql .= ", ";
	    }

	    if($decoded->{'collectible'} !== NULL)
	    {
		    $sql .= $decoded->{'collectible'};
		    $sql .= ", ";
		}
		else
	    {
	    	$sql .= "NULL";
	    	$sql .= ", ";
	    }

		if($decoded->{'elite'} !== NULL)
	    {
		    $sql .= $decoded->{'elite'};
		    $sql .= ", ";
		}
		else
	    {
	    	$sql .= "NULL";
	    	$sql .= ", ";
	    }

		if($decoded->{'playerClass'} !== NULL)
	    {
	    	if($decoded->{'playerClass'} == "Neutral")
	    		$sql .= 1;
	    	else if($decoded->{'playerClass'} == "Druid")
	    		$sql .= 2;
	    	else if($decoded->{'playerClass'} == "Hunter")
	    		$sql .= 3;
	    	else if($decoded->{'playerClass'} == "Mage")
	    		$sql .= 4;
	    	else if($decoded->{'playerClass'} == "Paladin")
	    		$sql .= 5;
	    	else if($decoded->{'playerClass'} == "Priest")
	    		$sql .= 6;
	    	else if($decoded->{'playerClass'} == "Rogue")
	    		$sql .= 7;
	    	else if($decoded->{'playerClass'} == "Shaman")
	    		$sql .= 8;
	    	else if($decoded->{'playerClass'} == "Warlock")
	    		$sql .= 9;
	    	else if($decoded->{'playerClass'} == "Warrior")
	    		$sql .= 10;
	    	else if($decoded->{'playerClass'} == "Dream")
	    		$sql .= 11;
		}
		else
	    {
	    	$sql .= "NULL";
	    }

		$sql .= ")";

		//echo $sql.";<br />";
	    $result = $mysqli->query($sql) or die('SQL syntax error: ' . mysql_error());
		echo $i." - ".$decoded->{'name'}.' <font color="green">successfully added to the database!<br /></font>';
	}
?>