<?php
/**
 * Electronics Components Inventory - ECI
 *
 * Insert new Invoice
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

require_once '../config.ini.php';

 
$mysqli = mysqli_connect($server, $username, $password, $database) or die("Error:" . mysqli_error($link));
 
$query="select NomeFornitore, idFornitori from Fornitori";
$result=mysqli_query($mysqli, $query) or die("500 Internal Error");
 
if(isset($_FILES['pdfinv'])) // Invoice PDF is present
{
		$userfile_in_tmp = $_FILES['pdfinv']['tmp_name'];
		$userfile_in_name = $_FILES['pdfinv']['name'];
		$userfile_in_size = $_FILES['pdfinv']['size'];
		$userfile_in_type = $_FILES['pdfinv']['type'];
} 
 
 
 
if(isset($_POST['invoice']) and !empty($_POST['invoice']) and isset($_POST['date']) and !empty($_POST['date']) and isset($_POST['idForn']) and !empty($_POST['idForn']))
{
	$invoice = $_POST['invoice'];
	$date = $_POST['date'];
	$idForn = $_POST['idForn'];
	
	// Check if Invoice is already in DB
	$query_check = "SELECT COUNT(*) FROM FattureAcquisti WHERE Fornitori_idFornitori = '".$idForn."' AND NumFattVendor='".$invoice."'";
	$result_check = mysqli_query($mysqli, $query_check) or die( "Error". mysql_error() );
	$row_check=$result_check->fetch_assoc();  

	if($row_check['COUNT(*)'] != 0)
	{
		$status = 'no_duplicate';	
		
	}
	else
	{
	
	if(isset($_FILES['pdfinv']) and $userfile_in_size != "" and TRIM($userfile_in_tmp) != "" and $_FILES['pdfinv']['error'] == UPLOAD_ERR_OK and is_uploaded_file($userfile_in_tmp))
	{
		// Extension of invoice file
		$estensione = strtolower(substr($userfile_in_name, strrpos($userfile_in_name, "."), strlen($userfile_in_name)-strrpos($userfile_in_name, ".")));
		
		$annocorrente = date("Y");
        
        if($estensione == '.pdf') // Check extension
        {
	        if(is_dir("../invoices/$annocorrente") == TRUE) 
	        {
		        // Nothing
	        }
	        else
	        {
		        // Create the folder
                Mkdir("../invoices/$annocorrente", 0777);
            }
            
        // Select the Vendor Name from DB
        $query1 = "SELECT NomeFornitore FROM Fornitori WHERE idFornitori = '".$idForn."'";
        $rq1 = mysqli_query($mysqli, $query1) or die( "Errore nella query. Query non eseguita 1 ". mysql_error());
        $row1 = $result->fetch_assoc();  
            
        // Filter the Name of Manufacturer
		$find = array('/[^A-Za-z0-9- ]/', '!\s+!', '/[ ]/', '/-{2,}/');
		$replace = array('' , ' ', '-', '-');
		$nomeForn = strtolower(preg_replace($find, $replace, stripcslashes(trim($row1['NomeFornitore']))));
            
        // Create the name of invoice: VendorName_daymonthyear.pdf    
        $invoiceName = $nomeForn."_".date('d-m-Y', strtotime($date)).$estensione;

        move_uploaded_file($userfile_in_tmp, "../invoices/$annocorrente/".$invoiceName); 
        
        $urlinvoice = "invoices/$annocorrente/$invoiceName"; 
        
        if(isset($_POST['reg_invoice_date']) AND !empty($_POST['reg_invoice_date']) AND isset($_POST['reg_invoice_number']) AND !empty($_POST['reg_invoice_number']) )
        {
        	$reg_invoice_date = $_POST['reg_invoice_date'];
        	$reg_invoice_no = $_POST['reg_invoice_number'];
        	$query="INSERT INTO FattureAcquisti (NumFattVendor, DataFattVendor, URLPdf, Fornitori_idFornitori, NumFattRegistrata, DataFattRegistrata) VALUES ('".$invoice."', '".$date."', '".$urlinvoice."', '".$idForn."', '".$reg_invoice_no."', '".$reg_invoice_date."')";
        }
    	elseif(isset($_POST['reg_invoice_number']) AND !empty($_POST['reg_invoice_number']))
    	{
    		$reg_invoice_no = $_POST['reg_invoice_number'];
    		$query="INSERT INTO FattureAcquisti (NumFattVendor, DataFattVendor, URLPdf, Fornitori_idFornitori, NumFattRegistrata) VALUES ('".$invoice."', '".$date."', '".$urlinvoice."','".$idForn."', '".$reg_invoice_no."')";
    	}
    	elseif(isset($_POST['reg_invoice_date']) AND !empty($_POST['reg_invoice_date']))
    	{
    		$reg_invoice_date = $_POST['reg_invoice_date'];
    		$query="INSERT INTO FattureAcquisti (NumFattVendor, DataFattVendor, URLPdf, Fornitori_idFornitori, DataFattRegistrata) VALUES ('".$invoice."', '".$date."', '".$urlinvoice."','".$idForn."', '".$reg_invoice_date."')";
    	}
    	else
    	{
    		$query="INSERT INTO FattureAcquisti (NumFattVendor, DataFattVendor, URLPdf, Fornitori_idFornitori) VALUES ('".$invoice."', '".$date."', '".$urlinvoice."','".$idForn."')";
    	}
        
		mysqli_query($mysqli,$query) or die( "Errore nella query. Query non eseguita 2". mysql_error());
		
		$status="ok";	  
            
         }
         else
         {
	         $status="no_pdf";
         }

	}
	else
	{
		$query="INSERT INTO FattureAcquisti (NumFattVendor, DataFattVendor, Fornitori_idFornitori) VALUES ('".$invoice."', '".$date."', '".$idForn."')";
		mysqli_query($mysqli,$query) or die( "Errore nella query. Query non eseguita 3". mysql_error());
		
		$status="ok";	
	}
	} 
}

 

mysqli_close($mysqli);
 
?>

<!DOCTYPE html>




<html>
<head>
	<!-- META -->
	<title>ECI</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="description" content="" />
	
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="../css/kickstart.css" media="all" />
	<link rel="stylesheet" type="text/css" href="../style.css" media="all" /> 
	
	<script language="javascript" type="text/javascript" src="../js/jquery.min.js"></script>
	<script type="text/javascript" src="../js/jquery.ui.core.js"></script>
	<script type="text/javascript" src="../js/jquery.ui.datepicker.js"></script>
    <script type="text/javascript" src="../js/ui.datepicker-it.js"></script>

    <link href="../css/jquery-ui.css" rel="stylesheet" type="text/css" />
    <link href="../css/ui.datepicker.css" rel="stylesheet" type="text/css" />
    
    <script type="text/javascript">
		$(document).ready(function(){
			$('#datepickerId').datepicker({ dateFormat: 'yy/mm/dd 00:00:00' });
			$('#datepickerId1').datepicker({ dateFormat: 'yy/mm/dd 00:00:00' });
		});
	</script>


	</head>
<body>



<div class="grid">

	<h5  ><em>Insert New Invoice</em></h5>
	
	 <form class="vertical" action="insinvo.php" method="post" enctype="multipart/form-data">
	 
	   <label for="text21">Vendor* (<a href="insvend.php" style="text-decoration: none;">add</a>): </label>
	   <select id="idForn" class="idForn" name="idForn">
	     <?php 
	     $i=0;
	     while($i < $result->num_rows)
	     {
	     	$row=$result->fetch_assoc();     
		 	echo "<option value=".$row['idFornitori'].">".$row['NomeFornitore']."</option>";
		 	$i++;
	     }     
	     ?>
	   </select>
	   <label for="text21">Vendor Invoice Number*: </label>
	   <input type="text" name="invoice" />
	   <label for="text21">Vendor Invoice Date*: </label>
	   <input name="date" id="datepickerId" type="text" />
	   <label for="text21">Registered Invoice Number: </label>
	   <input name="reg_invoice_number" type="text" />
	   <label for="text21">Registered Invoice Date: </label>
	   <input name="reg_invoice_date" id="datepickerId1" type="text" />
	   <label for="text21">Invoice in PDF (optional): </label>
	   <input type="file" name="pdfinv" />
	   <button type="submit" class="medium red">Submit</button>  
	   <?php if(isset($status) && $status== 'no_pdf') { echo "<label for=\"check1\" class=\"inline\" style=\"color: red; font-size: 20px; margin-left: 30px; font-variant: small-caps; font-weight: bold;\"> Error - No .pdf file</label>"; } elseif( isset($status) && $status == 'ok' ) { echo "<label for=\"check1\" class=\"inline\" style=\"color: red; font-size: 20px; margin-left: 30px; font-variant: small-caps; font-weight: bold;\"> Inserted!</label>"; } elseif( isset($status) && $status == 'no_duplicate' ) { echo "<label for=\"check1\" class=\"inline\" style=\"color: red; font-size: 20px; margin-left: 30px; font-variant: small-caps; font-weight: bold;\"> Error - Invoice already in DB!</label>"; }  ?>
	   
	 	 
	 </form>


</div> <!-- End Grid -->


</body>
</html>
