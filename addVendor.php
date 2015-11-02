<?php
/**
 * Electronics Components Inventory - ECI
 *
 * Add Vendor
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

if( isset($_POST['ManuPartNo']) ) { $ManuPartNo = trim($_POST['ManuPartNo']); } 
if( isset($_POST['VendorPartNo']) ) { $VendorPartNo = $_POST['VendorPartNo']; } 
if( isset($_POST['manufacturer']) ) { $manufacturer = trim($_POST['manufacturer']); } 
if( isset($_POST['termination'])) { $termination = trim($_POST['termination']); } 
if( isset($_POST['Descr']) ) { $Descr = trim($_POST['Descr']); } 	
if( isset($_POST['vendorURL']) ) { $vendorURL = trim($_POST['vendorURL']); }
if( isset($_POST['category']) ) { $category = trim($_POST['category']); } 	
if( isset($_POST['subCategory']) ) { $subCategory = trim($_POST['subCategory']); } 
if( isset($_POST['ProdCat']) ) { $ProdCat = trim($_POST['ProdCat']); } 	
if( isset($_POST['iddepot'])) { $idDepot = $_POST['iddepot']; }
if( isset($_POST['ImagePath']) ) { $ImagePath = trim($_POST['ImagePath']); }
if( isset($_POST['Stock']) ) { $Stock = $_POST['Stock']; }	
if( isset($_POST['invoice'])) { $invoice = $_POST['invoice']; } 
if( isset($_POST['purparts']) AND !empty($_POST['purparts'])) { $purparts = $_POST['purparts']; } else { $purparts = 'NULL'; }
if( isset($_POST['projref']) and !empty($_POST['projref'])) { $projref = $_POST['projref']; } else { $projref = 'NULL'; } 
if( isset($_POST['purprice']) AND !empty($_POST['purprice'])) { $purprice = $_POST['purprice']; } else { $purprice = 'NULL'; }

// Connect to DB
$mysqli = mysqli_connect($server, $username, $password, $database) or die("Error:" . mysqli_error($link));

// Check (in Server side) if Manufacturer Part No is already present in DB
$query="select COUNT(*) from Prodotti where ManufacturerPartNo ='" . $ManuPartNo . "'";
$result= mysqli_query($mysqli, $query) or die("500 Internal Error - 1");
$row=$result->fetch_assoc();  

if($row['COUNT(*)'] != 0)
{
	mysqli_close($mysqli);
	die("Notice: Manufacturer Part No already in database");
}

// If Terminaston Style is not present in SOAP request, TS = 'Undefined'
if(empty($termination))
{
	$termination = 'Undefined';
}

// Check variables validity
if( !empty($VendorPartNo) and !empty($ManuPartNo) and !empty($manufacturer) and !empty($Descr) and !empty($category) and !empty($subCategory) and !empty($ProdCat) and ($Stock != '') and !empty($vendorURL) and !empty($ImagePath) )
{
	
	// 1 - Check if Category/SubCategory are already present in DB otherwise they are added
	$query_cat_id = "select idCategorieProdotti from CategorieProdotti where NomeCategoria ='" . $category . "'"; 
	$result_cat_id = $mysqli->query($query_cat_id); 

	if($result_cat_id->num_rows == 0)
	{
		// Insert new Category
		$query = "INSERT INTO CategorieProdotti (NomeCategoria) VALUES ('".$category."')";
		$result=mysqli_query($mysqli, $query) or die("500 Internal Error - 2");
		// Select the ID of Category just added
		$query = "SELECT idCategorieProdotti from CategorieProdotti where NomeCategoria = '" . $category . "'";
		$result=mysqli_query($mysqli, $query) or die("500 Internal Error - 3");
		$row=$result->fetch_assoc();  
		$idcategory = $row['idCategorieProdotti']; 
		// Insert new SubCategory
		$query="INSERT INTO SottoCategorieProdotti (NomeSottoCategoria, CategorieProdotti_idCategorieProdotti) VALUES ('".$subCategory."', '".$idcategory."')";
		mysqli_query($mysqli, $query) or die("500 Internal Error - 4". mysql_error());	
		// Select the ID of subCategory just added
		$query_subCat_id = "SELECT idSottoCategoriaProdotti FROM SottoCategorieProdotti WHERE NomeSottoCategoria = '".$subCategory."'";
		$result_subCat_id = mysqli_query($mysqli, $query_subCat_id) or die("500 Internal Error");
		$row_subCat_id = $result_subCat_id->fetch_assoc();  
		$idSubCategory = $row_subCat_id['idSottoCategoriaProdotti']; 
		// Insert new Product Category
		$query_ins_pcat = "INSERT INTO TipologiaProdotti (TipologiaProdotto, SottoCategorieProdotti_idSottoCategoriaProdotti) VALUES ('".$ProdCat."', '".$idSubCategory."')";
		mysqli_query($mysqli, $query_ins_pcat) or die("500 Internal Error - 5". mysql_error());	
		
	}
	else
	{	
		$row_cat_id = $result_cat_id->fetch_assoc();
		$idCategorytoIns = $row_cat_id['idCategorieProdotti'];
		
		// Check if subCategory is present in DB
		$query_check_sub = "SELECT idSottoCategoriaProdotti FROM SottoCategorieProdotti WHERE NomeSottoCategoria ='" . $subCategory . "' AND CategorieProdotti_idCategorieProdotti = '".$idCategorytoIns."'";
		$result_check_sub = mysqli_query($mysqli, $query_check_sub) or die("500 Internal Error - 6");
		
		if($result_check_sub->num_rows == 0)
		{
			// Insert new subCategory
			$query="INSERT INTO SottoCategorieProdotti (NomeSottoCategoria, CategorieProdotti_idCategorieProdotti) VALUES ('".$subCategory."', '".$idCategorytoIns."')";
			mysqli_query($mysqli, $query) or die("500 Internal Error - 7". mysqli_error($mysqli));	
			// Select the ID of subCategory just added
			$query_subCat_id = "SELECT idSottoCategoriaProdotti FROM SottoCategorieProdotti WHERE NomeSottoCategoria = '".$subCategory."'";
			$result_subCat_id = mysqli_query($mysqli, $query_subCat_id) or die("500 Internal Error - 8");
			$row_subCat_id = $result_subCat_id->fetch_assoc();  
			$idSubCategory = $row_subCat_id['idSottoCategoriaProdotti']; 
			// Insert new Product Category
			$query_ins_pcat = "INSERT INTO TipologiaProdotti (TipologiaProdotto, SottoCategorieProdotti_idSottoCategoriaProdotti) VALUES ('".$ProdCat."', '".$idSubCategory."')";
			mysqli_query($mysqli, $query_ins_pcat) or die("500 Internal Error - 9". mysql_error());	
		}
		else
		{
			$row_check_sub = $result_check_sub->fetch_assoc();
			$idSubCategorytoIns = $row_check_sub['idSottoCategoriaProdotti'];
		
			// Check if Product Category is already in DB
			$query_check_prod = "SELECT idTipologiaProdotti FROM TipologiaProdotti WHERE TipologiaProdotto ='".$ProdCat."' AND SottoCategorieProdotti_idSottoCategoriaProdotti ='".$idSubCategorytoIns."'";
			$result_check_prod = mysqli_query($mysqli, $query_check_prod) or die("500 Internal Error - 10");
			echo $result_check_prod->num_rows."<br>";
			echo $ProdCat."<br>";
			echo $idSubCategorytoIns."<br>";
						
			if($result_check_prod->num_rows == 0)
			{
				// Insert new Product Category
				$query_ins_pcat = "INSERT INTO TipologiaProdotti (TipologiaProdotto, SottoCategorieProdotti_idSottoCategoriaProdotti) VALUES ('".$ProdCat."', '".$idSubCategorytoIns."')";
				mysqli_query($mysqli, $query_ins_pcat) or die("500 Internal Error". mysql_error());	
			}
			
		}
	}
	
	
	// 2 - Check if Manufacturer is already present in DB
	$query = "select COUNT(*) from Produttore where NomeProduttore='" . $manufacturer . "'";
	$result=mysqli_query($mysqli, $query) or die("500 Internal Error");
	$row=$result->fetch_assoc(); 
	if($row['COUNT(*)'] == 0)
	{
		// Insert new Manufacturer
		$query="INSERT INTO Produttore (NomeProduttore) VALUES ('".$manufacturer."')";		
		mysqli_query($mysqli, $query) or die("500 Internal Error". mysql_error());	
	}
	
	// 3 - Check if Terminastion Style is already present in DB
	$query = "select COUNT(*) from TerminationsStyle where TerminationStyle ='" . $termination . "'";
	$result=mysqli_query($mysqli, $query) or die("500 Internal Error");
	$row=$result->fetch_assoc(); 
	if($row['COUNT(*)'] == 0)
	{
		$query="INSERT INTO TerminationsStyle (TerminationStyle) VALUES ('".$termination."')";
		mysqli_query($mysqli, $query) or die("500 Internal Error". mysql_error());	
	}
	
	// INSERT THE ITEM IN DB
	// 1 - Select the IDs 
	$query = "SELECT idTipologiaProdotti FROM TipologiaProdotti WHERE TipologiaProdotto = '".$ProdCat."'";
	$result=mysqli_query($mysqli, $query) or die("500 Internal Error");
	$row=$result->fetch_assoc();  
	$idProdCategory= $row['idTipologiaProdotti']; 
	
	$query = "SELECT idTerminationsStyle from TerminationsStyle where TerminationStyle = '" . $termination . "'";
	$result=mysqli_query($mysqli, $query) or die("500 Internal Error");
	$row=$result->fetch_assoc();  
	$idtermination = $row['idTerminationsStyle']; 
	
	$query = "SELECT idProduttore from Produttore where NomeProduttore = '" . $manufacturer . "'";
	$result=mysqli_query($mysqli, $query) or die("500 Internal Error");
	$row=$result->fetch_assoc();  
	$idmanufacturer = $row['idProduttore']; 

	// 2 - Insert the item
	if( isset($_POST['iddepot']) AND !empty($_POST['iddepot']) )
	{
		$query = "INSERT INTO Prodotti (ManufacturerPartNo, Description, PezziinMagazzino, DataInserimento, TerminationsStyle_idTerminationsStyle, Produttore_idProduttore, VendorURL, VendorPartNo, TipologiaProdotti_idTipologiaProdotti, Magazzino_idMagazzino) VALUES ('".$ManuPartNo."', '".$Descr."', '".$Stock."', CURRENT_TIMESTAMP, '".$idtermination."', '".$idmanufacturer. "', '".$vendorURL."', '".$VendorPartNo."', '".$idProdCategory."', '".$idDepot."')";
	}
	else
	{
		$query = "INSERT INTO Prodotti (ManufacturerPartNo, Description, PezziinMagazzino, DataInserimento, TerminationsStyle_idTerminationsStyle, Produttore_idProduttore, VendorURL, VendorPartNo, TipologiaProdotti_idTipologiaProdotti) VALUES ('".$ManuPartNo."', '".$Descr."', '".$Stock."', CURRENT_TIMESTAMP, '".$idtermination."', '".$idmanufacturer. "', '".$vendorURL."', '".$VendorPartNo."', '".$idProdCategory."')";
	}
	
	mysqli_query($mysqli, $query) or die( "Error Query 2 ". mysqli_error($mysqli));
		
	// Select the ID of just inserted Item
	$query = "SELECT idProdotti FROM Prodotti WHERE ManufacturerPartNo = '".$ManuPartNo."'";	
	$result=mysqli_query($mysqli, $query) or die("500 Internal Error");
	$row=$result->fetch_assoc();  
	$idProductInserted = $row['idProdotti']; 
	
	// Insert the Invoice if selected
	if( isset($_POST['invoice']) AND !empty($invoice) )
	{	
		if($purparts != 'NULL') { $purparts = "'".$purparts."'"; }
		if($purprice != 'NULL') { $purprice = "'".$purprice."'"; }
		if($projref != 'NULL') { $projref = "'".$projref."'"; }
		
		// Insert the link between Invoice and Item
		$query="INSERT INTO InfoFattura (PezziAcquistati, PrezzodiAcquisto, MotivodelAcquisto, FattureAcquisti_idFatture, Prodotti_idProdotti) VALUES (".$purparts.", ".$purprice.", ".$projref.", '".$invoice."', '".$idProductInserted."')";
		mysqli_query($mysqli, $query) or die( "Errore nella query. Query non eseguita ". mysql_error());
	}
	
	// Insert the Product Image
	// 1 - Extension of image
	$estensione_photo = strtolower(substr($ImagePath, strrpos($ImagePath, "."), strlen($ImagePath)-strrpos($ImagePath, ".")));
	
	// 2 - Create the folder and insert the image
	// Filter Category and Subcategory name to make folder
	$find = array('/[^A-Za-z0-9- ]/', '!\s+!', '/[ ]/', '/-{2,}/');
	$replace = array('' , ' ', '-', '-');
	$category_photo = strtolower(preg_replace($find, $replace, stripcslashes($category)));
	$subCategory_photo = strtolower(preg_replace($find, $replace, stripcslashes($subCategory)));
	
	// Check extension
	if($estensione_photo == '.jpg' or $estensione_photo == '.png' or $estensione_photo == '.gif') 
    {
    	if(is_dir("photos/$category_photo/$subCategory_photo") == TRUE) 
	    {
	    	// Nothing
	    }
	    else
	    {
			if(is_dir("photos/$category_photo") == TRUE) // Se c'è la categoria 
            {
            	Mkdir("photos/$category_photo/$subCategory_photo", 0777);
            } 
            else 
            {
            	Mkdir("photos/$category_photo", 0777); 
                Mkdir("photos/$category/$subCategory_photo", 0777);
            }
		}
	        
	    // Create the image name Filtered
	    $photoName = preg_replace($find, $replace, stripcslashes($ManuPartNo));
        // Catch the image with cURL
        // cURL doesn't love SPACE in URL. Replace it with '%20'
        $ImagePath = str_replace ( ' ', '%20', $ImagePath);
        $ch = curl_init($ImagePath);
        $photoDest = "photos/$category_photo/$subCategory_photo/".$photoName.$estensione_photo;
        $fp = fopen($photoDest, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
		                      
        // Update the Item with Image
        $query = "UPDATE Prodotti SET URLFoto='photos/".$category_photo."/".$subCategory_photo."/".$photoName.$estensione_photo."' WHERE idProdotti=".$idProductInserted."";
        mysqli_query($mysqli, $query) or die("500 Internal Error");
        
       
	}
	
	 mysqli_close($mysqli);
	 
	 header("Location: vendorInsert.php?invoice=".$invoice."&iddepot=".$idDepot.""); 
}
else
{	
	mysqli_close($mysqli);
	die("Internal Error: Missing Parameters");
}


?>
 