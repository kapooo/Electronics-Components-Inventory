<?php
/**
 * Electronics Components Inventory - ECI
 *
 * Insert Manufacturer
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

 
if(isset($_POST['manufacturer']) and !empty($_POST['manufacturer']))
{
	$manufacturer = $_POST['manufacturer'];
	
	if(isset($_POST['sito']) and !empty($_POST['sito']))
	{
		$sito = $_POST['sito'];
		
		$query="INSERT INTO Produttore (NomeProduttore, Sito) VALUES ('".$manufacturer."', '".$sito."')";
		mysqli_query($mysqli,$query) or die( "Errore nella query. Query non eseguita ". mysqli_error($link));
		
		echo "INSERTED!!";
	}
	else
	{
		
		$query="INSERT INTO Produttore (NomeProduttore) VALUES ('".$manufacturer."')";
		mysqli_query($mysqli,$query) or die( "Errore nella query. Query non eseguita ". mysqli_error($link));
		
		echo "INSERTED!!";	
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

	<h5  ><em>Insert New Manufacturer</em></h5>
	
	 <form class="vertical" action="insmanu.php" method="post">

	   <label for="text21">Manufacturer: </label>
	   <input type="text" name="manufacturer" />
	   <label for="text21">Web Site (optional): </label>
	   <input type="text" name="sito" />
	   <button type="submit" class="medium red">Submit</button>  
	 	 
	 </form>


</div> <!-- End Grid -->


</body>
</html>
