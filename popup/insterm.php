<?php
/**
 * Electronics Components Inventory - ECI
 *
 * Insert new Termination Style
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

 
if(isset($_POST['termstyle']) and !empty($_POST['termstyle']))
{
	$termstyle = $_POST['termstyle'];
	
	// Controllo se nel DB vi è già lo stesso elemento
	$query="select COUNT(*) from TerminationsStyle where TerminationStyle = '".$termstyle."'";
	$result=mysqli_query($mysqli,$query) or die("500 Internal Error");;
	$row=$result->fetch_assoc();  

	if($row['COUNT(*)'] != 0)
	{
		$status = 'no';	
		
	}
	else
	{
		if(isset($_POST['termstyledesc']) and !empty($_POST['termstyledesc']))
		{
			$termstyledesc = $_POST['termstyledesc'];
		
			$query="INSERT INTO TerminationsStyle (TerminationStyle, Descrizione) VALUES ('".$termstyle."', '".$termstyledesc."')";
			mysqli_query($mysqli,$query) or die( "Errore nella query. Query non eseguita ". mysqli_error($link));
		}
		else
		{
			$termstyledesc = $_POST['termstyledesc'];
		
			$query="INSERT INTO TerminationsStyle (TerminationStyle) VALUES ('".$termstyle."')";
			mysqli_query($mysqli,$query) or die( "Errore nella query. Query non eseguita ". mysqli_error($link));
		}
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

	<h5  ><em>Insert New Termination Style</em></h5>
	
	 <form class="vertical" action="insterm.php" method="post">

	   <label for="text21">Termination Style: </label>
	   <input type="text" name="termstyle" />
	   <label for="text21">Description (optional): </label>
	   <input type="text" name="termstyledesc" />
	   <button type="submit" class="medium red">Submit</button>  
	   <?php if(isset($status) && $status== 'no') { echo "<label for=\"check1\" class=\"inline\" style=\"color: red; font-size: 30px; margin-left: 30px; font-variant: small-caps; font-weight: bold;\"> Error - Already in DB</label>"; } else if( isset($status) && $status == 'ok' ) { echo "<label for=\"check1\" class=\"inline\" style=\"color: red; font-size: 30px; margin-left: 30px; font-variant: small-caps; font-weight: bold;\"> Inserted!</label>"; } ?>
	 	 
	 </form>


</div> <!-- End Grid -->


</body>
</html>
