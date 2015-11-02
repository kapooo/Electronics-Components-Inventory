<?php
/**
 * Electronics Components Inventory - ECI
 *
 * Insert Depot Sector
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons 
 * Attribution-NonCommercial-ShareAlike 4.0 International License (CC BY-NC-SA 4.0)
 * that is bundled with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-sa/4.0/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@aceinnova.com so we can send you a copy immediately.
 *
 *
 * @copyright   Copyright (c) 2015 Alessio Carpini (http://www.electronicsinv.com)
 * @license     http://creativecommons.org/licenses/by-nc-sa/4.0/  (CC BY-NC-SA 4.0)
 *
 */
 
require_once '../config.ini.php';

$mysqli = mysqli_connect($server, $username, $password, $database) or die("Error:" . mysqli_error($link));

 
if(isset($_POST['dep_name']) and !empty($_POST['dep_name']) AND isset($_POST['dep_id']) and !empty($_POST['dep_id']))
{
	$dep_name = $_POST['dep_name'];
	$dep_id = $_POST['dep_id'];
	// Check if Depot is already in DB
	$query="select COUNT(*) from Magazzino where Nome = '".$dep_name."' AND Identificazione  = '".$dep_id."'";
	$result=mysqli_query($mysqli,$query) or die("500 Internal Error");;
	$row=$result->fetch_assoc();  

	if($row['COUNT(*)'] != 0)
	{
		$status = 'no';	
		
	}
	else
	{
		// Insert new Depot Section
		if( isset($_POST['dep_section']) AND !empty($_POST['dep_section']) )
		{
			$dep_section = "'".$_POST['dep_section']."'";
		}
		else
		{
			$dep_section = 'NULL';
		}
		if( isset($_POST['dep_shelf']) AND !empty($_POST['dep_shelf']) )
		{
			$dep_shelf = "'".$_POST['dep_shelf']."'";
		}
		else
		{
			$dep_shelf = 'NULL';
		}
		if( isset($_POST['dep_floor']) AND !empty($_POST['dep_floor']) )
		{
			$dep_floor = "'".$_POST['dep_floor']."'";
		}
		else
		{
			$dep_floor = 'NULL';
		}
		if( isset($_POST['dep_description']) AND !empty($_POST['dep_description']) )
		{
			$dep_description = "'".$_POST['dep_description']."'";
		}
		else
		{
			$dep_description = 'NULL';
		}
		
		
		$query = "INSERT INTO Magazzino (Nome, Settore, Scaffale, Piano, Identificazione, Descrizione) VALUES ('".$dep_name."', ".$dep_section.", ".$dep_shelf.", ".$dep_floor.", '".$dep_id."', ".$dep_description.")";
		
		
		mysqli_query($mysqli, $query) or die( "Errore nella query. Query non eseguita ". mysql_error($mysqli));
		
		$status = 'ok';
	}

	 
}

 

mysqli_close($mysqli);
 
?>

<!DOCTYPE html>
<html>
<head>
	<!-- META -->
	<title>ECI</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="description" content="" />
	
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="../css/kickstart.css" media="all" />
	<link rel="stylesheet" type="text/css" href="../style.css" media="all" /> 
	
	</head>
<body>



<div class="grid">

	<h5  ><em>Insert New Depot Section</em></h5>
	
	 <form class="vertical" action="insdepsect.php" method="post">

	   <label for="text21">Depot Section ID Number:* (unique in DB) </label>
	   <input type="text" name="dep_id" />
	   <label for="text21">Depot Name:* </label>
	   <input type="text" name="dep_name" />
	   <label for="text21">Depot Floor: </label>
	   <input type="text" name="dep_floor" />
	   <label for="text21">Depot Section: </label>
	   <input type="text" name="dep_section" />
	   <label for="text21">Depot Shelf: </label>
	   <input type="text" name="dep_shelf" />
	   <label for="text21">Description: </label>
	   <input type="text" name="dep_description" />
	   
	   
	   
	   <button type="submit" class="medium red">Submit</button>
	   <?php if(isset($status) && $status== 'no') { echo "<label for=\"check1\" class=\"inline\" style=\"color: red; font-size: 30px; margin-left: 30px; font-variant: small-caps; font-weight: bold;\"> Error - Already in DB</label>"; } else if( isset($status) && $status == 'ok' ) { echo "<label for=\"check1\" class=\"inline\" style=\"color: red; font-size: 30px; margin-left: 30px; font-variant: small-caps; font-weight: bold;\"> Inserted!</label>"; } ?>  
	 	 
	 </form>


</div> <!-- End Grid -->


</body>
</html>
