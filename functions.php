<?php
/**
 * Electronics Components Inventory - ECI
 *
 * Functions file
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

// Return subCategory array passing the Category ID
function sendSubCategory($idCategory)
{	
	// Include DB configuration
	require 'config.ini.php';

	$mysqli = mysqli_connect($server, $username, $password, $database) or die("Error:" . mysqli_error($link));

	$query="select idSottoCategoriaProdotti, NomeSottoCategoria from SottoCategorieProdotti where CategorieProdotti_idCategorieProdotti = ".$idCategory." ";

	$result=mysqli_query($mysqli, $query) or die("500 Internal Error");  
		
	// Array with the values (idSubCategory,NomeSottoCategoria) 
	$i=0;
	$array = array();
	while($i < $result->num_rows)
	{
		$row=$result->fetch_assoc(); 
		$array[$row['idSottoCategoriaProdotti']] = $row['NomeSottoCategoria'];
		$i++;
	}
	
	mysqli_close($mysqli);

	return $array;
}

// Return Product Category array passing the subCategory ID 
function sendProductCategory($idSubCategory)
{	

	require 'config.ini.php';

	$mysqli = mysqli_connect($server, $username, $password, $database) or die("Error:" . mysqli_error($link));
	// Query SQL
	$query = "SELECT DISTINCT TipologiaProdotti.idTipologiaProdotti, TipologiaProdotti.TipologiaProdotto FROM SottoCategorieProdotti, TipologiaProdotti WHERE SottoCategorieProdotti.idSottoCategoriaProdotti = TipologiaProdotti.SottoCategorieProdotti_idSottoCategoriaProdotti AND SottoCategorieProdotti.idSottoCategoriaProdotti = ".$idSubCategory." "; 
	
	$result=mysqli_query($mysqli, $query) or die("500 Internal Error");  
		
	// Array with the values (idProductCategory, NameProductCategory) 
	$i=0;
	$array = array();
	while($i < $result->num_rows)
	{
		$row=$result->fetch_assoc(); 
		$array[$row['idTipologiaProdotti']] = $row['TipologiaProdotto'];
		$i++;
	}
	
	mysqli_close($mysqli);

	return $array;
}

/* 	Check if Manufacturer Part No is present in DB 
 *	Return:
 *			- true: if present
 *			- false: if absent
 */ 
function isManufacturerPartNoInDb($ManufacturerPartNo)
{
	// Include DB configuration
	require 'config.ini.php';
	$mysqli = mysqli_connect($server, $username, $password, $database) or die("Error:" . mysqli_error($link));
	$query="select COUNT(*) from Prodotti where ManufacturerPartNo ='" . $ManufacturerPartNo . "'";
	$result=mysqli_query($mysqli, $query) or die("500 Internal Error");
	$row=$result->fetch_assoc();  

	if($row['COUNT(*)'] != 0) // Manu Part No Already in DB
	{
		return true;
	}
	else
	{
		return false;
	}
}

/* 	Check if Vendor Part No is present in DB 
 *	Return:
 *			- true: if present
 *			- false: if absent
 */ 
function isVendorPartNoInDb($VendorPartNo)
{
	require 'config.ini.php';
	$mysqli = mysqli_connect($server, $username, $password, $database) or die("Error:" . mysqli_error($link));
	$query="select COUNT(*) from Prodotti where VendorPartNo ='" . $VendorPartNo . "'";
	$result=mysqli_query($mysqli, $query) or die("500 Internal Error");
	$row=$result->fetch_assoc();  

	if($row['COUNT(*)'] != 0) // Manu Part No Already in DB
	{
		return true;
	}
	else
	{
		return false;
	}
}

/* Check if ECI is installed or die
 * 
 *			
 *			
 */
function check_eci_db()
{
	require 'config.ini.php';
	
	if(empty($server) OR empty($username) OR empty($password) OR empty($database))
	{
		echo "Please EDIT config.ini.php in main directory";
		exit();
	}
	else
	{
		$link = @mysqli_connect($server, $username, $password, $database);
	
		// Can't connect to MySQL server
		if (mysqli_connect_errno())
		{
			$error =  mysqli_connect_errno(); 
			switch ($error) {
				case 2002:
					echo "Can't connect to Database Server ".$server."<br>Check file config.ini.php";
					break;
				case 2003:
					echo "Can't connect to Database Server ".$server."<br>Check file config.ini.php";
					break;
				case 1045:
					echo "Username or Password NOT valid for Database ".$database." on ".$server."<br>Check file config.ini.php";
					break;
				case 1049:
					echo "Database <b>".$database."</b> NOT exists! <br>Check file config.ini.php";
					break;
				default:
					echo "Database Error: ".$error;
			}
			
    		exit();
		}
		else
		{
			if (!mysqli_query($link, "SELECT version FROM Options"))
 			{
  				echo "Seems to be the FIRST time you use ECI.<br>Database ".$database." EXISTS<br><br>If you want to install ECI click here: <a href=\"install.php\"><b></b>INSTALL</a>";
  				mysqli_close($link);
  				exit();
 		 	}
		}
	}
		
}

/* Check if install.php file is present in directory
 * Return true if it is present, false otherwise
 *
 */
function check_install_file()
{
	// Check if install.php is present in directory
 	if(file_exists ('install.php') )
 	{
 		return true;
 	}
 	else
 	{
 		return false;
 	}
 }

function get_header()
{
	require_once('pages/header.php');
}

function get_footer()
{
	require_once('pages/footer.php');
}

function get_version()
{
	require 'config.ini.php';
	$mysqli = mysqli_connect($server, $username, $password, $database) or die("Error:" . mysqli_error($link));
	$query = "SELECT version FROM Options";
	$result = mysqli_query($mysqli, $query) or die("500 Internal Error");
	$version=$result->fetch_assoc();  
	echo $version['version'];
}

function get_language($lang)
{ 
	switch ($lang) {
	  case 'en':
	  $lang_file = 'languages/lang.en.php';
	  break;
 
	  case 'it':
	  $lang_file = 'languages/lang.it.php';
	  break;
 
	  case 'de':
	  $lang_file = 'languages/lang.de.php';
	  break;
	  
	  case 'fr':
	  $lang_file = 'languages/lang.fr.php';
	  break;
	  
	  case 'es':
	  $lang_file = 'languages/lang.es.php';
	  break;
 
	  default:
	  $lang_file = 'languages/lang.en.php';
	  }
	  
	  return $lang_file;
}

?>