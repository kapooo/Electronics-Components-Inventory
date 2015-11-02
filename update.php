<?php
/**
 * Electronics Components Inventory - ECI
 *
 * Update Product Server Side
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

$idProdotto = $_POST['idProdotto']; //id
$ManuPartNo = $_POST['ManuPartNo'];
$VendorPartNo= $_POST['VendorPartNo'];
$idManufacturer = $_POST['manufacturer']; //id manufacturer
$idTermination = $_POST['termination']; //id termination
$Descr = $_POST['Descr'];
$VendorURL = $_POST['VendorURL'];
$idCategory = $_POST['category']; //id category
$idsubCategory = $_POST['subCategory']; //id subCategory
$idProductCategory = $_POST['ProdCat']; //id ProdCat
$idDepot = $_POST['iddepot'];
$myNote = $_POST['mynote'];

if(isset($_FILES['photo']))
{
	$userfile_ph_tmp = $_FILES['photo']['tmp_name'];
	$userfile_ph_name = $_FILES['photo']['name'];
	$userfile_ph_size = $_FILES['photo']['size'];
	$userfile_ph_type = $_FILES['photo']['type'];

}

$valueStock = $_POST['Stock']; //Stock
$idInvoice = $_POST['invoice']; //id invoice

if(isset($_POST['purparts']) AND !empty($_POST['purparts'])) { $purparts = $_POST['purparts']; } else { $purparts = 'NULL'; }
if(isset($_POST['purprice']) AND !empty($_POST['purprice'])) { $purprice = $_POST['purprice']; } else { $purprice = 'NULL'; }
if(isset($_POST['projref']) AND !empty($_POST['projref'])) { $projref = $_POST['projref']; } else { $projref = 'NULL'; }

if(isset($_FILES['DataShe']))
{
	$userfile_ds_tmp = $_FILES['DataShe']['tmp_name'];
	$userfile_ds_name = $_FILES['DataShe']['name'];
	$userfile_ds_size = $_FILES['DataShe']['size'];
	$userfile_ds_type = $_FILES['DataShe']['type'];
	
	if(isset($_POST['nomeDocu'])) { $nomeDocu = $_POST['nomeDocu']; }
	if(isset($_POST['descDocu'])) { $descDocu = $_POST['descDocu']; }
	if(isset($_POST['DocReplace'])) { $idDocu = $_POST['DocReplace']; }
}



if(!empty($idProdotto) && !empty($ManuPartNo) && !empty($idManufacturer) && !empty($idTermination) && !empty($Descr) && !empty($idCategory) && !empty($idsubCategory) && !empty($idProductCategory) && ($valueStock != '') && !empty($idDepot))
{
		
	$mysqli = mysqli_connect($server, $username, $password, $database) or die("Error:" . mysqli_error($link));
 	
 	$query ="UPDATE Prodotti SET ManufacturerPartNo='".$ManuPartNo."', VendorPartNo='".$VendorPartNo."', Description='".$Descr."', TipologiaProdotti_idTipologiaProdotti='".$idProductCategory."', PezziinMagazzino='".$valueStock."', DataUltimaModifica=CURRENT_TIMESTAMP, TipologiaProdotti_idTipologiaProdotti='".$idProductCategory."', TerminationsStyle_idTerminationsStyle='".$idTermination."', Produttore_idProduttore='".$idManufacturer."', Magazzino_idMagazzino='".$idDepot."', VendorURL='".$VendorURL."', MyNote='".$myNote."' WHERE idProdotti='".$idProdotto."'";
  	
	mysqli_query($mysqli, $query) or die("500 Internal Error");
	
	
	if(!empty($idInvoice)) // Edit of Invoice
	{
		if(isset($_POST['AddorUpd']) && !empty($_POST['AddorUpd'])) // Update of Invoice
		{
			$idInvoiceToChange = $_POST['AddorUpd'];
			
			// Id of InfoFattura to update
			$queryinfo = "SELECT idInfoFattura FROM InfoFattura WHERE FattureAcquisti_idFatture = '".$idInvoiceToChange."' AND Prodotti_idProdotti = '".$idProdotto."'";
			$rqinfo = mysqli_query($mysqli, $queryinfo) or die("500 Internal Error");
			$row_invoice=$rqinfo->fetch_assoc();
			$idInfoFattura = $row_invoice['idInfoFattura']; 
			
			if($purparts != 'NULL') { $purparts = "'".$purparts."'"; }
			if($purprice != 'NULL') { $purprice = "'".$purprice."'"; }
			if($projref != 'NULL') { $projref = "'".$projref."'"; }
						
			$query1="UPDATE InfoFattura SET FattureAcquisti_idFatture=".$idInvoice.", PezziAcquistati=".$purparts.", PrezzodiAcquisto=".$purprice.", MotivodelAcquisto=".$projref." WHERE idInfoFattura = '".$idInfoFattura."'";

			mysqli_query($mysqli, $query1) or die("Error:" .  mysqli_error($mysqli));
		} 
		else // Add new Invoice
		{
			if($purparts != 'NULL') { $purparts = "'".$purparts."'"; }
			if($purprice != 'NULL') { $purprice = "'".$purprice."'"; }
			if($projref != 'NULL') { $projref = "'".$projref."'"; }
			
			$query2="INSERT INTO InfoFattura (PezziAcquistati, PrezzodiAcquisto, MotivodelAcquisto, FattureAcquisti_idFatture, Prodotti_idProdotti) VALUES (".$purparts.", ".$purprice.", ".$projref.", ".$idInvoice.", ".$idProdotto.")";
			
			mysqli_query($mysqli, $query2) or die("500 Internal Error");
		}
		
	}
	 
	// Edit of item image
	if(isset($_FILES['photo']) and $userfile_ph_size != "" and TRIM($userfile_ph_tmp) != "" and $_FILES['photo']['error'] == UPLOAD_ERR_OK and is_uploaded_file($userfile_ph_tmp))
	{
		// Select the category and the subcategory
		$query3 = "SELECT CategorieProdotti.NomeCategoria, SottoCategorieProdotti.NomeSottoCategoria FROM CategorieProdotti, SottoCategorieProdotti WHERE SottoCategorieProdotti.idSottoCategoriaProdotti = ".$idsubCategory." and CategorieProdotti.idCategorieProdotti = SottoCategorieProdotti.CategorieProdotti_idCategorieProdotti";	
			
		$rquery3 = mysqli_query($mysqli, $query3) or die("500 Internal Error");
		$row3=$rquery3->fetch_assoc();				
		// Filter Category and Subcategory name to make folder
		$find = array('/[^A-Za-z0-9- ]/', '!\s+!', '/[ ]/', '/-{2,}/');
		$replace = array('' , ' ', '-', '-');
		$category_photo = strtolower(preg_replace($find, $replace, stripcslashes(trim($row3['NomeCategoria']))));
		$subCategory_photo = strtolower(preg_replace($find, $replace, stripcslashes(trim($row3['NomeSottoCategoria']))));
	
		// Extension of image
		$estensione_photo = strtolower(substr($userfile_ph_name, strrpos($userfile_ph_name, "."), strlen($userfile_ph_name)-strrpos($userfile_ph_name, ".")));
        
        if($estensione_photo == '.jpg' or $estensione_photo == '.png') // Check extension
        {
	        if(is_dir("photos/$category_photo/$subCategory_photo") == TRUE) // If the folder exists
	        {
		        // Nothing
	        }
	        else
	        {
		        if(is_dir("photos/$category_photo") == TRUE) // If category Exists
                {
                	Mkdir("photos/$category_photo/$subCategory_photo", 0777);
                } 
                else 
                {
                    Mkdir("photos/$category_photo", 0777); 
                    Mkdir("photos/$category_photo/$subCategory_photo", 0777);
                }
			}
	        
	        // Create name of image file
	        $photoName = preg_replace($find, $replace, stripcslashes($ManuPartNo));
            // Insert the file in the created folder
            move_uploaded_file($userfile_ph_tmp, "photos/$category_photo/$subCategory_photo/".$photoName.$estensione_photo);
          
            //Query inserimento foto
            $query5 = "UPDATE Prodotti SET URLFoto='photos/".$category_photo."/".$subCategory_photo."/".$photoName.$estensione_photo."' WHERE idProdotti=".$idProdotto."";
            //Eseguo la query sul database
			mysqli_query($mysqli, $query5) or die("500 Internal Error");

		}		
		
	}
	
	// Edit of documents
	if(isset($_FILES['DataShe']) and isset($_POST['nomeDocu']) and isset($_POST['descDocu']) and $userfile_ds_size != "" && TRIM($userfile_ds_tmp) != "" and $_FILES['DataShe']['error'] == UPLOAD_ERR_OK and is_uploaded_file($userfile_ds_tmp))
	{
		if(isset($_POST['DocReplace']) and !empty($_POST['DocReplace'])) // Update of the document
		{
			$query3 = "SELECT CategorieProdotti.NomeCategoria, SottoCategorieProdotti.NomeSottoCategoria FROM CategorieProdotti, SottoCategorieProdotti WHERE SottoCategorieProdotti.idSottoCategoriaProdotti = ".$idsubCategory." and CategorieProdotti.idCategorieProdotti = SottoCategorieProdotti.CategorieProdotti_idCategorieProdotti";		
			
			$rquery3 = mysqli_query($mysqli, $query3) or die("500 Internal Error");
			$row3=$rquery3->fetch_assoc();				
						
			// Filter Category and Subcategory name to make folder
			$find = array('/[^A-Za-z0-9- ]/', '!\s+!', '/[ ]/', '/-{2,}/');
			$replace = array('' , ' ', '-', '-');
			$category_doc = strtolower(preg_replace($find, $replace, stripcslashes(trim($row3['NomeCategoria']))));
			$subCategory_doc = strtolower(preg_replace($find, $replace, stripcslashes(trim($row3['NomeSottoCategoria']))));
		
			// Extension of file
			$estensione_file = strtolower(substr($userfile_ds_name, strrpos($userfile_ds_name, "."), strlen($userfile_ds_name)-strrpos($userfile_ds_name, ".")));
        
			if($estensione_file == '.zip' or $estensione_file == '.pdf' or $estensione_file == '.stp' or $estensione_file == '.bxl' or $estensione_file == '.doc' or $estensione_file == '.odt') // Check extension
			{
				if(is_dir("documents/$category_doc/$subCategory_doc") == TRUE) 
				{
		        	// Nothing
				}
				else
				{
		        	if(is_dir("documents/$category_doc") == TRUE) 
					{
                		Mkdir("documents/$category_doc/$subCategory_doc", 0777);
					} 
					else 
					{
                    	Mkdir("documents/$category_doc", 0777); 
						Mkdir("documents/$category_doc/$subCategory_doc", 0777);
					}
				}
				
			// Check file name
	        $DocNameFile = preg_replace($find, $replace, stripcslashes($nomeDocu));
            $urlfile = "documents/$category_doc/$subCategory_doc/".$DocNameFile.$estensione_file;
				
			// Old document (to replace) URLLink 
			$query10 = "SELECT URLLink FROM Documenti WHERE idDocumenti = '".$idDocu."'";
			$rquery10 = mysqli_query($mysqli, $query10) or die("500 Internal Error");
			$row10=$rquery10->fetch_assoc();				
			$oldurlfile = $row10['URLLink'];
       
				if($oldurlfile == $urlfile)
				{
	           		// Overwrite the file
			   	}
			   	else
			   	{
            		// Check in db others Documents with same URL
					$query9 = "SELECT URLLink FROM Documenti WHERE URLLink = '".$urlfile."'";
					$rquery9 = mysqli_query($mysqli, $query9) or die("500 Internal Error");
					$numrow=$rquery9->num_rows;
					
					if($numrow > 0)
					{
	            		$urlfile= "documents/$category_doc/$subCategory_doc/".$DocNameFile."_".($numrow+1).$estensione_file;
					}    
				}
            
            // Insert the file
            move_uploaded_file($userfile_ds_tmp, $urlfile);
            
            $query6 = "UPDATE Documenti SET Titolo='".$nomeDocu."', URLLink='".$urlfile."', Descrizione='".$descDocu."', SizeKB='".($userfile_ds_size/1024)."', Type='".strtoupper(substr($estensione_file, 1))."', DataInserimento=CURRENT_TIMESTAMP WHERE idDocumenti='".$idDocu."'";
            mysqli_query($mysqli, $query6) or die("500 Internal Error");
		
			}
				
		}
		else // Insert new document
		{
			// Prendo il nome della categoria e della sottocategoria per fare le cartelle
			$query3 = "SELECT CategorieProdotti.NomeCategoria, SottoCategorieProdotti.NomeSottoCategoria FROM CategorieProdotti, SottoCategorieProdotti WHERE SottoCategorieProdotti.idSottoCategoriaProdotti = ".$idsubCategory." and CategorieProdotti.idCategorieProdotti = SottoCategorieProdotti.CategorieProdotti_idCategorieProdotti";			
			$rquery3 = mysqli_query($mysqli, $query3) or die("500 Internal Error");
			$row3=$rquery3->fetch_assoc();
			
			// Filter Category and Subcategory name to make folder
			$find = array('/[^A-Za-z0-9- ]/', '!\s+!', '/[ ]/', '/-{2,}/');
			$replace = array('' , ' ', '-', '-');
			$category_doc = strtolower(preg_replace($find, $replace, stripcslashes(trim($row3['NomeCategoria']))));
			$subCategory_doc = strtolower(preg_replace($find, $replace, stripcslashes(trim($row3['NomeSottoCategoria']))));
		
			$estensione_file = strtolower(substr($userfile_ds_name, strrpos($userfile_ds_name, "."), strlen($userfile_ds_name)-strrpos($userfile_ds_name, ".")));
        
			if($estensione_file == '.zip' or $estensione_file == '.pdf' or $estensione_file == '.stp' or $estensione_file == '.bxl' or $estensione_file == '.doc' or $estensione_file == '.odt') //Controllo l'estensione
			{
	        	if(is_dir("documents/$category_doc/$subCategory_doc") == TRUE)
				{
		        	// Nothing
				}
				else
				{
		        	if(is_dir("documents/$category_doc") == TRUE)
					{
                		Mkdir("documents/$category_doc/$subCategory_doc", 0777);
					} 
					else 
					{
                    	Mkdir("documents/$category_doc", 0777); 
						Mkdir("documents/$category_doc/$subCategory_doc", 0777);
					}
				}
			
	        $DocNameFile = preg_replace($find, $replace, stripcslashes($nomeDocu));
            $urlfile = "documents/$category_doc/$subCategory_doc/".$DocNameFile.$estensione_file;

            
            $query9 = "SELECT URLLink FROM Documenti WHERE URLLink = '".$urlfile."'";
            $rquery9 = mysqli_query($mysqli, $query9) or die("500 Internal Error");
            $numrow=$rquery9->num_rows;
            
            if($numrow > 0)
            {
	            $urlfile= "documents/$category_doc/$subCategory_doc/".$DocNameFile."_".($numrow).$estensione_file;
            }

            move_uploaded_file($userfile_ds_tmp, $urlfile);
            
			// Insert the document in DB
			$query6 = "INSERT INTO Documenti (Titolo, URLLink, DataInserimento, Descrizione, SizeKB, Type) VALUES ('".$nomeDocu."', '".$urlfile."', CURRENT_TIMESTAMP, '".$descDocu."', '".($userfile_ds_size/1024)."', '".strtoupper(substr($estensione_file,1))."')";
			mysqli_query($mysqli, $query6) or die("500 Internal Error");
			
			// ID of new documents
			$query7 = "SELECT idDocumenti FROM Documenti WHERE Titolo = '".$nomeDocu."' and URLLink = '".$urlfile."'";
			$rquery7 =  mysqli_query($mysqli, $query7) or die("500 Internal Error");
			$row7=$rquery7->fetch_assoc();
			$idDocu = $row7['idDocumenti'];
          
            //Query inserimento foto
            $query8 = "INSERT INTO Documenti_has_Prodotti (Documenti_idDocumenti, Prodotti_idProdotti) VALUES ('".$idDocu."', '".$idProdotto."')";
			mysqli_query($mysqli, $query8) or die("500 Internal Error");        
        }
        else
        {
        	echo "<b>Warning: Product Inserted but Document NOT Inserted. File Extension NOT Admitted!!</b><br/>";
		    unlink($userfile_ds_tmp); //Rimuovo il file dal tmp       
        }	
	
		}	
		
	}
	
	
		
	mysqli_close($mysqli); 
	
header("Location: product.php?idProd=".$idProdotto.""); 
	
	 
}
else
{
	echo "Error: Parameter Missing";
}


?>