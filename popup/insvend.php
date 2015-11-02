<?php
/**
 * Electronics Components Inventory - ECI
 *
 * Insert new Vendor
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

 
if(isset($_POST['vendorname']) and !empty($_POST['vendorname']))
{
	$vendorName = $_POST['vendorname'];
	
	// Controllo se nel DB vi è già lo stesso elemento
	$query = "SELECT COUNT(*) FROM Fornitori WHERE NomeFornitore = '".$vendorName."'";
	$result=mysqli_query($mysqli,$query) or die("500 Internal Error");;
	$row=$result->fetch_assoc();  

	if($row['COUNT(*)'] != 0)
	{
		$status = 'no';	
		
	}
	else
	{
		if(isset($_POST['vendorsite']) and !empty($_POST['vendorsite']))
		{
			$vendorSite = $_POST['vendorsite'];
		
			$query="INSERT INTO Fornitori (NomeFornitore, Sito) VALUES ('".$vendorName."', '".$vendorSite."')";
			mysqli_query($mysqli,$query) or die( "Errore nella query. Query non eseguita ". mysqli_error($link));
		}
		else
		{		
			$query="INSERT INTO Fornitori (NomeFornitore) VALUES ('".$vendorName."')";
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

	<h5  ><em>Insert New Vendor</em></h5>
	
	 <form class="vertical" action="insvend.php" method="post">

	   <label for="text21">Vendor Name: </label>
	   <input type="text" name="vendorname" />
	   <label for="text21">Web Site (optional): </label>
	   <input type="text" name="vendorsite" />
	   <button type="submit" class="medium red">Submit</button>  
	   <?php if(isset($status) && $status== 'no') { echo "<label for=\"check1\" class=\"inline\" style=\"color: red; font-size: 30px; margin-left: 30px; font-variant: small-caps; font-weight: bold;\"> Error - Already in DB</label>"; } else if( isset($status) && $status == 'ok' ) { echo "<label for=\"check1\" class=\"inline\" style=\"color: red; font-size: 30px; margin-left: 30px; font-variant: small-caps; font-weight: bold;\"> Inserted!</label>"; header("Location: insinvo.php"); } ?>
	 	 
	 </form>


</div> <!-- End Grid -->


</body>
</html>
