<?php
/**
 * Electronics Components Inventory - ECI
 *
 * Ajax check Product
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
 
require_once 'config.ini.php';
require_once 'functions.php';

if(isset($_POST['mpn'])) 
{
	// Manufacturer Part Number
	$strQuery = $_POST['mpn']; 

	$mysqli = mysqli_connect($server, $username, $password, $database) or die("Error:" . mysqli_error($link));
	
	// Check if MPN is present in DB
	$query="select COUNT(*) from Prodotti where ManufacturerPartNo = '".$strQuery."'";
	$result=mysqli_query($mysqli, $query) or die("502 Internal Error". mysqli_error($mysqli));
	$row=$result->fetch_assoc();  

	if($row['COUNT(*)'] > 1) 
	{
		// Database DEEP ERROR MNP not UNIQUE 
		mysqli_close($mysqli);
		die("ERROR(101): Not Unique Manufacturer Part No in DB.");
		
	}
	elseif($row['COUNT(*)'] == 0)
	{
		mysqli_close($mysqli);
		// Can't happen already check
		die("ERROR(201): Missing Manufactuer Part No in DB");
	}
	elseif($row['COUNT(*)'] == 1)
	{
		// Select the values in DB
		$query = "SELECT Prodotti.*, Produttore.nomeProduttore, Produttore.idProduttore, SottoCategorieProdotti.idSottoCategoriaProdotti, SottoCategorieProdotti.NomeSottoCategoria, CategorieProdotti.NomeCategoria, CategorieProdotti.idCategorieProdotti, TerminationsStyle.TerminationStyle, Magazzino.Nome, TipologiaProdotti.TipologiaProdotto FROM Prodotti, Produttore, SottoCategorieProdotti, CategorieProdotti, TerminationsStyle, Magazzino, TipologiaProdotti WHERE Produttore.idProduttore = Prodotti.Produttore_idProduttore AND SottoCategorieProdotti.idSottoCategoriaProdotti = TipologiaProdotti.SottoCategorieProdotti_idSottoCategoriaProdotti AND CategorieProdotti.idCategorieProdotti = SottoCategorieProdotti.CategorieProdotti_idCategorieProdotti AND Magazzino.idMagazzino = Prodotti.Magazzino_idMagazzino AND TerminationsStyle.idTerminationsStyle = Prodotti.TerminationsStyle_idTerminationsStyle AND TipologiaProdotti.idTipologiaProdotti = Prodotti.TipologiaProdotti_idTipologiaProdotti AND Prodotti.ManufacturerPartNo = '".$strQuery."'";
		
		$result=mysqli_query($mysqli, $query) or die("502 Internal Error". mysqli_error($mysqli));
		$row=$result->fetch_assoc(); 
		
		
		// Stampo le variabili in HTML
		echo "<ul class=\"breadcrumbs\">
		<li><a href=\"\">Home</a></li>
		<li><a href=\"nav.php?ids=".$row['idCategorieProdotti']."\">" . $row['NomeCategoria'] ." </a></li>
		<li><a href=\"nav.php?ids=".$row['idSottoCategoriaProdotti']."\">" . $row['NomeSottoCategoria'] . "</a></li>
		<li><a href=\"list.php?idpcat=".$row['TipologiaProdotti_idTipologiaProdotti']."\">" . $row['TipologiaProdotto'] . "</a></li>
		</ul>
		<div class=\"col_3\">
		<img class=\"caption\" title=\"". $row['ManufacturerPartNo'] ."\" src=\"". $row['URLFoto'] ." \"width=\"150\" height=\"73\" />
		</div>
		<div class=\"col_9\">
		<strong><br>Parts in Stock: " . $row['PezziinMagazzino'] . "</strong>
		<br><strong>Manufacturer Part No: </strong>" . $row['ManufacturerPartNo'] . "
		<br><strong>Manufacturer: </strong>" . $row['nomeProduttore'] . "
		<br><strong>Description: </strong>" . $row['Description'] . "
		<br><strong>Termination Style: </strong>" . $row['TerminationStyle'] . "
		<br><strong>Product Detail: <a href=\"". $row['VendorURL'] ."\" target=\"_blank\">" . $row['ManufacturerPartNo'] . "</a></strong></div>
		
		<input type=\"hidden\" id=\"idProd\" name=\"idProd\" value=\"" . $row['idProdotti'] . "\"/>
		<input type=\"hidden\" id=\"partsinstock\" name=\"partsinstock\" value=\"" . $row['PezziinMagazzino'] . "\"/>";
	}
	else
	{
		mysqli_close($mysqli);
		die("501 Internal Error");
	}		
	
}
else 
{
	mysqli_close($mysqli);	
	echo "Missing Manufacturer Part Number";
}


?>

