<?php
/**
 * Electronics Components Inventory - ECI
 *
 * Ajax Product Category
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

if($_POST['id'])
{
	$id=$_POST['id'];
	
	// Recupero l'array con le Product Category
	$results = sendProductCategory($id);
	 	
 	if(isset($_POST['idselected']))
 	{
 		foreach ($results as $key => $value) 
 		{
 			if($key == $_POST['idselected'])
			{
				echo "<option value=".$key." selected=\"selected\">".$value."</option>";
			}
			else
			{
				echo "<option value=".$key.">".$value."</option>";
			}
 		}
 	}
 	else
 	{
 		foreach ($results as $key => $value) 
 		{		
			echo "<option value=".$key.">".$value."</option>";
 		}
 	}	
}


?>