 <?php
/**
 * Electronics Components Inventory - ECI
 *
 * Config file (to Edit)
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

 
 /* Database Config
 	$username -> Database Username
 	$password -> Database Password
 	$database -> Database name
 	$server -> 	 Database server address
 */
 $username="";
 $password="";
 $database= ""; 
 $server="";
 
 // Defines the number of rows in generic table
 $NUMBER_OF_ROWS = 20;
  // Defines the number of rows in index table
 $NUMBER_OF_ROWS_INDEX = 50;
// Defines the number of rows in search table
 $NUMBER_OF_ROWS_SEARCH = 20;
 // Define the local language of ECI
 /* 	English: en
  *		Italian: it
  *	
  */
 $LANGUAGE = 'en';
 
 /* "Add Item From Vendor" Configuration
  *	
  *  - MOUSER Vendor -
  * Choose the language when you insert Items from Mouser:
  *	 	English: en
  *		Italian: it
  *		German:  de
  *		French:  fr
  *		Spanish: es	
  *		
  *	Choose the Currency when you Insert Items from Mouser
  *		USD: usd
  *		EUR: eur
  *
  * NOTE: The Language and the Currency depend of what you have chosen during 
  * the request of API Partner ID from Mouser 
  * (http://eu.mouser.com/Mymouser/MouserSearchApplication.aspx)
  */
 $MOUSER_PARTNER_ID = '';
 $MOUSER_LANGUAGE = 'en';
 $MOUSER_CURRENCY = 'eur'; 
 
 ?>