<?php
/**
 * Electronics Components Inventory - ECI
 *
 * Ajax check Vendor Part No.
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

if($_POST['vpn'])
{
	$VendorPartNo=$_POST['vpn'];
	
	// Recupero l'array con le sottocategorie (idSubCat, nomeSubCat)
	$result = isVendorPartNoInDb($VendorPartNo);
	 	
 	if($result == false)
 	{
 		echo json_encode(0);
 	}
 	else
 	{
 		echo json_encode(1);
 	}	
}


?>