<?php
/**
 * Electronics Components Inventory - ECI
 *
 * Add Server Side
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

if(isset($_POST['ManuPartNo'])) { $ManuPartNo = $_POST['ManuPartNo']; } 
if(isset($_POST['VendorPartNo'])) { $VendorPartNo = $_POST['VendorPartNo']; } 
if(isset($_POST['manufacturer'])) { $idManufacturer = $_POST['manufacturer']; } 
if(isset($_POST['termination'])) { $idTermination = $_POST['termination']; } 
if(isset($_POST['Descr'])) { $Descr = $_POST['Descr']; } 
if(isset($_POST['vendorURL'])) { $vendorURL = $_POST['vendorURL']; } 
if(isset($_POST['DocName'])) { $DocName = $_POST['DocName']; }
if(isset($_POST['DocDesc'])) { $DocDesc = $_POST['DocDesc']; }

if(isset($_FILES['DataShe'])) 
{
	$userfile_ds_tmp = $_FILES['DataShe']['tmp_name'];
	$userfile_ds_name = $_FILES['DataShe']['name'];
	$userfile_ds_size = $_FILES['DataShe']['size'];
	$userfile_ds_type = $_FILES['DataShe']['type'];
}

if(isset($_POST['category'])) { $idCategory = $_POST['category']; }  
if(isset($_POST['subCategory'])) { $idsubCategory = $_POST['subCategory']; } 
if(isset($_POST['ProdCat'])) { $idProductCategory = $_POST['ProdCat']; }  
if(isset($_POST['iddepot'])) { $idDepot = $_POST['iddepot']; }
if(isset($_POST['MyNote'])) { $MyNote = $_POST['MyNote']; }

if(isset($_FILES['photo'])) 
{
	$userfile_ph_tmp = $_FILES['photo']['tmp_name'];
	$userfile_ph_name = $_FILES['photo']['name'];
	$userfile_ph_size = $_FILES['photo']['size'];
	$userfile_ph_type = $_FILES['photo']['type'];
}

if(isset($_POST['Stock'])) { $valueStock = $_POST['Stock']; }  
if(isset($_POST['invoice'])) { $idInvoice = $_POST['invoice']; }  
if(isset($_POST['purparts']) AND !empty($_POST['purparts'])) { $purparts = $_POST['purparts']; } else { $purparts = 'NULL'; }
if(isset($_POST['purprice']) AND !empty($_POST['purprice'])) { $purprice = $_POST['purprice']; } else { $purprice = 'NULL'; }
if(isset($_POST['projref']) AND !empty($_POST['projref'])) { $projref = $_POST['projref']; } else { $projref = 'NULL'; }


// Connect to DB
$mysqli = mysqli_connect($server, $username, $password, $database) or die("Error:" . mysqli_error($link));


// Insert only Simple Product (NO Invoice, NO Document, NO Photo)
if(!empty($ManuPartNo) and !empty($idManufacturer) and !empty($idTermination) and !empty($Descr) and !empty($idCategory) and !empty($idsubCategory) and !empty($idProductCategory) and ($valueStock != '') and !empty($idDepot))
{ 
	$query = "INSERT INTO Prodotti (VendorPartNo, vendorURL, ManufacturerPartNo, Description, TipologiaProdotti_idTipologiaProdotti, PezziinMagazzino, MyNote, DataInserimento, TerminationsStyle_idTerminationsStyle, Produttore_idProduttore, Magazzino_idMagazzino) VALUES ('".$VendorPartNo."','".$vendorURL."','".$ManuPartNo."', '".$Descr."','".$idProductCategory."', '".$valueStock. "', '".$MyNote."', CURRENT_TIMESTAMP, '".$idTermination."', '".$idManufacturer. "', '".$idDepot."')";

	mysqli_query($mysqli, $query) or die("500 Internal Error # 1"); 
	

	// Insert Invoice
	if(!empty($idInvoice))
	{	
		// Select the ID of Product just inserted	
		$query_invoice_id = "SELECT idProdotti FROM Prodotti WHERE ManufacturerPartNo = '".$ManuPartNo."'";	
		$result_invoice_id = mysqli_query($mysqli, $query_invoice_id) or die("500 Internal Error"); 
		$row_invoice_id=$result_invoice_id->fetch_assoc();
		$idProduct_invoice = $row_invoice_id['idProdotti'];
		
		if($purparts != 'NULL') { $purparts = "'".$purparts."'"; }
		if($purprice != 'NULL') { $purprice = "'".$purprice."'"; }
		if($projref != 'NULL') { $projref = "'".$projref."'"; }
		
		$query_insert_invoice="INSERT INTO InfoFattura (PezziAcquistati, PrezzodiAcquisto, MotivodelAcquisto, FattureAcquisti_idFatture, Prodotti_idProdotti) VALUES (".$purparts.", ".$purprice.", ".$projref.", '".$idInvoice."', '".$idProduct_invoice."')";
		
		mysqli_query($mysqli, $query_insert_invoice) or die("500 Internal Error");
	}

	
	// Insert Photo
	if(isset($_FILES['photo']) and $userfile_ph_size != "" and TRIM($userfile_ph_tmp) != "" and $_FILES['photo']['error'] == UPLOAD_ERR_OK and is_uploaded_file($userfile_ph_tmp))
	{
		// Select the Category and the subCategory of the Product just inserted
		$query_photo_cat = "SELECT CategorieProdotti.NomeCategoria, SottoCategorieProdotti.NomeSottoCategoria FROM CategorieProdotti, SottoCategorieProdotti WHERE SottoCategorieProdotti.idSottoCategoriaProdotti = ".$idsubCategory." and CategorieProdotti.idCategorieProdotti = SottoCategorieProdotti.CategorieProdotti_idCategorieProdotti";		
		
		$result_photo_cat = mysqli_query($mysqli, $query_photo_cat) or die("500 Internal Error");
		$row_photo_cat=$result_photo_cat->fetch_assoc();
				
		// Filter Category and Subcategory name to make folder
		$find = array('/[^A-Za-z0-9- ]/', '!\s+!', '/[ ]/', '/-{2,}/');
		$replace = array('' , ' ', '-', '-');
		$nomeCategoria_photo = strtolower(preg_replace($find, $replace, stripcslashes(trim($row_photo_cat['NomeCategoria']))));
		$nomeSottoCategoria_photo = strtolower(preg_replace($find, $replace, stripcslashes(trim($row_photo_cat['NomeSottoCategoria']))));
		
		// Find the file extension
		$estensione_photo = strtolower(substr($userfile_ph_name, strrpos($userfile_ph_name, "."), strlen($userfile_ph_name)-strrpos($userfile_ph_name, ".")));
        
        if($estensione_photo == '.jpg' or $estensione_photo == '.png') // Check photo extension
        {
	        if(is_dir("photos/$nomeCategoria_photo/$nomeSottoCategoria_photo") == TRUE) 
	        {
		        // Nothing
	        }
	        else
	        {
		        if(is_dir("photos/$nomeCategoria_photo") == TRUE)  
                {
                	Mkdir("photos/$nomeCategoria_photo/$nomeSottoCategoria_photo", 0777);
                } 
                else 
                {
                    Mkdir("photos/$nomeCategoria_photo", 0777); 
                    Mkdir("photos/$nomeCategoria_photo/$nomeSottoCategoria_photo", 0777);
                }
			}
	        
	        // Clean the photo filename
	        $photoName = preg_replace($find, $replace, stripcslashes($ManuPartNo));
            // Insert the phot file in the folder
            move_uploaded_file($userfile_ph_tmp, "photos/$nomeCategoria_photo/$nomeSottoCategoria_photo/".$photoName.$estensione_photo);
            
            $query_photo_idProd = "SELECT idProdotti FROM Prodotti WHERE ManufacturerPartNo = '".$ManuPartNo."'";
			$result_photo_idProd = mysqli_query($mysqli, $query_photo_idProd) or die("500 Internal Error");
			$row_photo_idProd=$result_photo_idProd->fetch_assoc();
			$idProd_photo = $row_photo_idProd['idProdotti'];
			          
            // Insert photo in DB
            $query_photo_update = "UPDATE Prodotti SET URLFoto='photos/".$nomeCategoria_photo."/".$nomeSottoCategoria_photo."/".$photoName.$estensione_photo."' WHERE idProdotti=".$idProd_photo."";

			mysqli_query($mysqli, $query_photo_update) or die("500 Internal Error");
        }
        else
        {
	       echo "<b>Warning: Product Inserted but Photo NOT Inserted. File Extension NOT Admitted!!</b><br/>";
		   unlink($userfile_ph_tmp); // Delete tmp file
        }
	}
	
	
	// Insert Document
	if(isset($_FILES['DataShe']) and isset($_POST['DocDesc']) and isset($_POST['DocName']) and $userfile_ds_size != "" && TRIM($userfile_ds_tmp) != "" and $_FILES['DataShe']['error'] == UPLOAD_ERR_OK and is_uploaded_file($userfile_ds_tmp))
	{
		// Select the Category and the subCategory of the Product just inserted
		$query_docu_cat = "SELECT CategorieProdotti.NomeCategoria, SottoCategorieProdotti.NomeSottoCategoria FROM CategorieProdotti, SottoCategorieProdotti WHERE SottoCategorieProdotti.idSottoCategoriaProdotti = ".$idsubCategory." and CategorieProdotti.idCategorieProdotti = SottoCategorieProdotti.CategorieProdotti_idCategorieProdotti";		
		
		$result_docu_cat = mysqli_query($mysqli, $query_docu_cat) or die("500 Internal Error");
		$row_docu_cat=$result_docu_cat->fetch_assoc();
		
		// Filter Category and Subcategory name to make folder
		$find = array('/[^A-Za-z0-9- ]/', '!\s+!', '/[ ]/', '/-{2,}/');
		$replace = array('' , ' ', '-', '-');
		$nomeCategoria_docu = strtolower(preg_replace($find, $replace, stripcslashes(trim($row_docu_cat['NomeCategoria']))));
		$nomeSottoCategoria_docu = strtolower(preg_replace($find, $replace, stripcslashes(trim($row_docu_cat['NomeSottoCategoria']))));
		
		// File extension
        $estensione_file = strtolower(substr($userfile_ds_name, strrpos($userfile_ds_name, "."), strlen($userfile_ds_name)-strrpos($userfile_ds_name, ".")));
        
        if($estensione_file == '.zip' or $estensione_file == '.pdf' or $estensione_file == '.stp' or $estensione_file == '.bxl' or $estensione_file == '.doc' or $estensione_file == '.odt') //Check extension
        {
	        if(is_dir("documents/$nomeCategoria_docu/$nomeSottoCategoria_docu") == TRUE) 
	        {
		        // Nothing
	        }
	        else
	        {
		        if(is_dir("documents/$nomeCategoria_docu") == TRUE) // Se c'è la categoria 
                {
                	Mkdir("documents/$nomeCategoria_docu/$nomeSottoCategoria_docu", 0777);
                } 
                else 
                {
                    Mkdir("documents/$nomeCategoria_docu", 0777); 
                    Mkdir("documents/$nomeCategoria_docu/$nomeSottoCategoria_docu", 0777);
                }
	        }
	        
	        // Clean document filename
	        $DocNameFile = preg_replace($find, $replace, stripcslashes($DocName));
            //Inserisco la foto rinominata nella cartella
            $urlfile = "documents/$nomeCategoria_docu/$nomeSottoCategoria_docu/".$DocNameFile.$estensione_file;
            
            // Check in DB for documents with same URL
            $query_docu_check_url = "SELECT URLLink FROM Documenti WHERE URLLink = '".$urlfile."'";
            $result_docu_check_url = mysqli_query($mysqli, $query_docu_check_url) or die("500 Internal Error");
            $numrow_docu=$result_docu_check_url->num_rows;
            if($numrow_docu > 0)
            {
	            $urlfile= "documents/$nomeCategoria_docu/$nomeSottoCategoria_docu/".$DocNameFile."_".($numrow_docu).$estensione_file;
            }

            move_uploaded_file($userfile_ds_tmp, $urlfile);
            
            // Id of Product just Inserted
            $query_docu_idProd = "SELECT idProdotti FROM Prodotti WHERE ManufacturerPartNo = '".$ManuPartNo."'";
			$result_docu_idProd = mysqli_query($mysqli, $query_docu_idProd) or die("500 Internal Error");
			$row_docu_idProd=$result_docu_idProd->fetch_assoc();
			$idProd_docu = $row_docu_idProd['idProdotti'];
					
			// Insert document in DB
			$query_docu_insert = "INSERT INTO Documenti (Titolo, URLLink, DataInserimento, Descrizione, SizeKB, Type) VALUES ('".$DocName."', '".$urlfile."', CURRENT_TIMESTAMP, '".$DocDesc."', '".($userfile_ds_size/1024)."', '".strtoupper(substr($estensione_file,1))."')";
			mysqli_query($mysqli, $query_docu_insert) or die("500 Internal Error");
			
			// ID of Document just Inserted
			$query_docu_idDocu = "SELECT idDocumenti FROM Documenti WHERE Titolo = '".$DocName."' and URLLink = '".$urlfile."'";
			$result_docu_idDocu = mysqli_query($mysqli, $query_docu_idDocu) or die("500 Internal Error");
			$row_docu_idDocu = $result_docu_idDocu->fetch_assoc();
			$idDocu_docu = $row_docu_idDocu['idDocumenti'];
          
            // Insert Document and Product in Link Table
            $query_docu_insert_link = "INSERT INTO Documenti_has_Prodotti (Documenti_idDocumenti, Prodotti_idProdotti) VALUES ('".$idDocu_docu."', '".$idProd_docu."')";
			mysqli_query($mysqli, $query_docu_insert_link) or die("500 Internal Error");        
        }
        else
        {
        	echo "<b>Warning: Product Inserted but Document NOT Inserted. File Extension NOT Admitted!!</b><br/>";
		    unlink($userfile_ds_tmp); //Rimuovo il file dal tmp       
        }	
		
	}
 
header("Location: insert.php?status=ok");
mysqli_close($mysqli);	

}
else
{
	header("Location: insert.php?status=no");
	echo "Error Missing Required Arguments";
}

?>






