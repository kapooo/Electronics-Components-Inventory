<?php
/**
 * Electronics Components Inventory - ECI
 *
 * Ajax delete
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

// Invoice Delete
if(isset($_POST['id']) and $_POST['id'] == 1) 
{	
	if(!isset($_POST['idInvoice']) OR empty($_POST['idInvoice']) OR !isset($_POST['idProd']) OR empty($_POST['idProd']) )
	{
		echo "Deleted Error";
		die("ERROR");
	}
	else
	{
		$idInvoice = $_POST['idInvoice'];
		$idProduct = $_POST['idProd'];
		
		$mysqli = mysqli_connect($server, $username, $password, $database) or die("Error:" . mysqli_error($link));
		
		// Id of InfoFattura to update
		$queryinfo = "SELECT idInfoFattura FROM InfoFattura WHERE FattureAcquisti_idFatture = '".$idInvoice."' AND Prodotti_idProdotti = '".$idProduct."'";
		$rqinfo = mysqli_query($mysqli, $queryinfo) or die("500 Internal Error");
		$row_invoice=$rqinfo->fetch_assoc();
		$idInfoFattura = $row_invoice['idInfoFattura']; 
		
		$querydel="DELETE FROM InfoFattura WHERE idInfoFattura = '".$idInfoFattura."'";
		
		mysqli_query($mysqli, $querydel) or die("Error:" .  mysqli_error($mysqli));
		
		mysqli_close($mysqli);
		
		echo "Deleted Invoice with Success";
	}
	
}
elseif(isset($_POST['id']) and $_POST['id'] == 2) // Document Delete
{
	if(!isset($_POST['idDocument']) OR empty($_POST['idDocument']) OR !isset($_POST['idProd']) OR empty($_POST['idProd']) )
	{
		echo "Deleted Error";
		die("ERROR");
	}	
	else
	{
		$idDocument = $_POST['idDocument'];
		$idProduct = $_POST['idProd'];
		
		$mysqli = mysqli_connect($server, $username, $password, $database) or die("Error:" . mysqli_error($link));
		
		// Id of InfoFattura to update
		$querydocu = "SELECT idDocumenti_has_Prodotti FROM Documenti_has_Prodotti WHERE Documenti_idDocumenti = '".$idDocument."' AND Prodotti_idProdotti = '".$idProduct."'";
		$rqdocu = mysqli_query($mysqli, $querydocu) or die("500 Internal Error");
		$row_document=$rqdocu->fetch_assoc();
		$idDocumenti_has_Prodotti = $row_document['idDocumenti_has_Prodotti']; 
		
		$querydel="DELETE FROM Documenti_has_Prodotti WHERE idDocumenti_has_Prodotti = '".$idDocumenti_has_Prodotti."'";
		
		mysqli_query($mysqli, $querydel) or die("Error:" .  mysqli_error($mysqli));
		
		mysqli_close($mysqli);
		
		echo "Deleted Document with Success";
	
	}

}
else 
{
	echo "Deleted Error";
	die("ERROR");
}


?>

