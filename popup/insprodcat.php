<?php
/**
 * Electronics Components Inventory - ECI
 *
 * Insert Product Category
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

$query = "SELECT NomeSottoCategoria, idSottoCategoriaProdotti FROM SottoCategorieProdotti";
$result_r = mysqli_query($mysqli,$query);  
 
if(isset($_POST['productcategory']) and !empty($_POST['productcategory']) and isset($_POST['subcategory']) and !empty($_POST['subcategory']))
{
	$idsubcategory = $_POST['subcategory'];
	$prodcategory = $_POST['productcategory'];
	
	// Controllo se nel DB vi è già lo stesso elemento
	$query="select COUNT(*) from TipologiaProdotti where TipologiaProdotto = '".$prodcategory ."'";
	$result=mysqli_query($mysqli,$query) or die("500 Internal Error");
	$row=$result->fetch_assoc();  

	if($row['COUNT(*)'] != 0)
	{
		$status = 'no';	
		
	}
	else
	{
		if(isset($_POST['productcategorydesc']) and !empty($_POST['productcategorydesc']))
		{
			$prodcategorydesc = $_POST['productcategorydesc'];
		
			$query="INSERT INTO TipologiaProdotti (TipologiaProdotto, Descrizione, SottoCategorieProdotti_idSottoCategoriaProdotti) VALUES ('".$prodcategory."', '".$prodcategorydesc."', '".$idsubcategory."')";
			mysqli_query($mysqli,$query) or die( "Errore nella query. Query non eseguita ". mysql_error($link));
		}
		else
		{
			$query="INSERT INTO TipologiaProdotti (TipologiaProdotto, SottoCategorieProdotti_idSottoCategoriaProdotti) VALUES ('".$prodcategory."', '".$idsubcategory."')";
			mysqli_query($mysqli,$query) or die( "Errore nella query. Query non eseguita ". mysql_error($link));	
		}
	$status='ok';
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

	<h5><em>Insert New Product Category</em></h5>
	
	 <form class="vertical" action="insprodcat.php" method="post">

	   <select id="subcategory" class="subcategory" name="subcategory">
	     <option value="">-- SubCategory --</option>
	     <?php 
	     $i=0;
	     while($i < $result_r->num_rows)
	     {
	     	$row_r=$result_r->fetch_assoc();       
		 	echo "<option value=".$row_r['idSottoCategoriaProdotti'].">".$row_r['NomeSottoCategoria']."</option>";
		 	$i++;
	     }     
	     ?>
	   </select>
	   <label for="text21">Product Category: </label>
	   <input type="text" name="productcategory" />
	   <label for="text21">Description (optional): </label>
	   <input type="text" name="productcategorydesc" />
	   <button type="submit" class="medium red">Submit</button>  
	    <?php if(isset($status) && $status== 'no') { echo "<label for=\"check1\" class=\"inline\" style=\"color: red; font-size: 30px; margin-left: 30px; font-variant: small-caps; font-weight: bold;\"> Error - Already in DB</label>"; } else if( isset($status) && $status == 'ok' ) { echo "<label for=\"check1\" class=\"inline\" style=\"color: red; font-size: 30px; margin-left: 30px; font-variant: small-caps; font-weight: bold;\"> Inserted!</label>"; } ?>
	 	 
	 </form>



</div> <!-- End Grid -->


</body>
</html>
