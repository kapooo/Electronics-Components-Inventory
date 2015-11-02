<?php
/**
 * Electronics Components Inventory - ECI
 *
 * Header Template
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
 
require 'config.ini.php';
require get_language($LANGUAGE);

check_eci_db();

?>
<!DOCTYPE html>
<html>
<head>
	<!-- META -->
	<title>Electronics Components Inventory</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="description" content="" />
	
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="css/kickstart.css" media="all" />
	<link rel="stylesheet" type="text/css" href="style.css" media="all" /> 
	
	<!-- Javascript -->
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script type="text/javascript" src="js/kickstart.js"></script>
	<script type="text/javascript">
   		var check_install = '<?php echo check_install_file(); ?>';
	</script>
	<script type="text/javascript" src="js/advice.js"></script>
</head>
<body>

<div class="callout callout-top clearfix" style="border-width:0px; margin-bottom:-20px">

		<div class="col_2" style="margin-top:-2.2em;">
			<div>
				<a href="index.php"><img src="images/logo.png" alt="Logo" height="90px" /></a>
				</div>
			<div class="header_logo_title">
				<a href="index.php" class="logo">ACE Innovation</a>
			</div>
		</div>

		<div class="col_10" style="margin-top:-0.5em;">
		<label style="margin-bottom:-5px; margin-left:-5%;"><?php echo $lang['HEADER_SEARCH_PART']; ?></label><br />
		
		<form action="search.php" method="post">
		<input type="text" placeholder="Search" class="col_4" name="search" \>
		<label class=".col_2"><button type="submit" class="small red"><?php echo $lang['HEADER_SEARCH_BUTTON']; ?></button></label>
		</form>
		</div>

</div>


<!-- Menu Horizontal -->
<ul class="menu">
	<li class="current">
		<a href="index.php"><i class="icon-home"></i><?php echo $lang['HEADER_MENU_HOME']; ?></a>
	</li>
	<li>
		<a href="nav.php"><i class="icon-search"></i><?php echo $lang['HEADER_MENU_PROD_FIND']; ?></a>
	<li>
	<li>
		<a href="#"><i class="icon-file"></i><?php echo $lang['HEADER_MENU_ITEMS']; ?></a>
		<ul>
			<li class="divider">
				<a href="insert.php"><i class="icon-plus"></i><?php echo $lang['HEADER_MENU_ADD_ITEM']; ?></a>
			</li>
			<li class="divider">
				<a href="upprod.php"><i class="icon-edit"></i><?php echo $lang['HEADER_MENU_CHANGE_STOCK']; ?></a>
			</li>
			<li class="divider">
				<a href="vendorInsert.php"><i class="icon-bolt"></i><?php echo $lang['HEADER_MENU_ADD_FROM_VENDOR']; ?></a>
			</li>
		</ul>
	<li>
	<li>
		<a href="#"><i class="icon-bar-chart"></i><?php echo $lang['HEADER_MENU_REPORTS']; ?></a>
		<ul>
			<li>
				<a href="invoices.php"><i class="icon-reorder"></i><?php echo $lang['HEADER_MENU_INVOICES']; ?></a>
			</li>
		</ul>
	</li>
</ul>


<div class="clear"></div>


