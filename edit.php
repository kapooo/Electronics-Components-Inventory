<?php
/**
 * Electronics Components Inventory - ECI
 *
 * Edit
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

$idProd = $_GET['idProd'];

if(!empty($idProd))
{
	 $mysqli = mysqli_connect($server, $username, $password, $database) or die("Error:" . mysqli_error($link));

	 $isempty = 0;
	
	 $query = "SELECT Prodotti.*, TipologiaProdotti.idTipologiaProdotti, TipologiaProdotti.TipologiaProdotto, CategorieProdotti.idCategorieProdotti, CategorieProdotti.NomeCategoria, SottoCategorieProdotti.idSottoCategoriaProdotti, SottoCategorieProdotti.NomeSottoCategoria FROM Prodotti, TipologiaProdotti, CategorieProdotti, SottoCategorieProdotti WHERE SottoCategorieProdotti.idSottoCategoriaProdotti = TipologiaProdotti.SottoCategorieProdotti_idSottoCategoriaProdotti AND CategorieProdotti.idCategorieProdotti = SottoCategorieProdotti.CategorieProdotti_idCategorieProdotti AND TipologiaProdotti.idTipologiaProdotti = Prodotti.TipologiaProdotti_idTipologiaProdotti AND Prodotti.idProdotti = ".$idProd."";


	 $result=mysqli_query($mysqli, $query);  
	 $num=$result->num_rows;
	 $row=$result->fetch_assoc();  
	  
	 $ManuPartNo=$row['ManufacturerPartNo']; 
	 $Description=$row['Description'];
	 $Settore=$row['idTipologiaProdotti'];
	 $PezziinMagazzino=$row['PezziinMagazzino'];
	 $URLFoto=$row['URLFoto'];
	 $DataInserimento=$row['DataInserimento'];
	 $DataUltimaModifica=$row['DataUltimaModifica'];
	 $idSottoCategoria=$row['idSottoCategoriaProdotti'];
	 $idTerminationStyle=$row['TerminationsStyle_idTerminationsStyle'];
	 $idProduttore=$row['Produttore_idProduttore'];
	 $MyNote=$row['MyNote'];
	 $VendorURL=$row['VendorURL'];
	 $VendorPartNo=$row['VendorPartNo'];
	 $idDepot = $row['Magazzino_idMagazzino']; 
	 $nomeTipologiaProdotto=$row['TipologiaProdotto'];
	 $nomeSottoCategoria=$row['NomeSottoCategoria'];
	 $idCategoriaProdotti=$row['idCategorieProdotti']; 
	 $nomeCategoria=$row['NomeCategoria']; 
	 
	 //Select the Invoice 
	 $query = "select InfoFattura.*, FattureAcquisti.* ,Fornitori.NomeFornitore, Fornitori.Sito from InfoFattura, FattureAcquisti, Fornitori where Prodotti_idProdotti = ".$idProd." and FattureAcquisti_idFatture = FattureAcquisti.idFatture AND Fornitori.idFornitori = FattureAcquisti.Fornitori_idFornitori order by DataFattVendor DESC";
	 
	 $result=mysqli_query($mysqli, $query);   
	 
	 $rows_10 = $result->num_rows;
	 $i = 0;
	 while($i < $rows_10)
	 {	
	 	 $row=$result->fetch_assoc(); 
		 $idAcquisto[$i]=$row['idFatture'];
		 $NumeroFatturaAcquisto[$i]=$row['NumFattVendor'];
		 $purparts[$i]=$row['PezziAcquistati'];
		 $purprice[$i]=$row['PrezzodiAcquisto'];
		 $projref[$i]=$row['MotivodelAcquisto'];
		 $i++;
	 }
	 
	 
	 /* Select all the info */
	 $query_2="select nomeProduttore, idProduttore from Produttore order by nomeProduttore";
	 $query_3="select idSottoCategoriaProdotti, NomeSottoCategoria from SottoCategorieProdotti"; 
	 $query_4="select NomeCategoria, idCategorieProdotti from CategorieProdotti";
	 $query_5="select idTerminationsStyle, TerminationStyle from TerminationsStyle order by TerminationStyle";
	 $query_6="select * from FattureAcquisti order by DataFattVendor DESC";
	 $query_7="select * from Magazzino";
	 $result_2=mysqli_query($mysqli, $query_2); 
	 $result_3=mysqli_query($mysqli, $query_3); 
	 $result_4=mysqli_query($mysqli, $query_4); 
	 $result_5=mysqli_query($mysqli, $query_5); 
	 $result_6=mysqli_query($mysqli, $query_6);
	 $result_7=mysqli_query($mysqli, $query_7);
	 $rows_2 = $result_2->num_rows;  
	 $rows_3 = $result_3->num_rows;  
	 $rows_4 = $result_4->num_rows;  
	 $rows_5 = $result_5->num_rows;  
	 $rows_6 = $result_6->num_rows;  
	 $rows_7 = $result_7->num_rows;  
	 
	 
	 
	 $query="select Documenti.* from Documenti, Documenti_has_Prodotti where Documenti_has_Prodotti.Documenti_idDocumenti = Documenti.idDocumenti AND Documenti_has_Prodotti.Prodotti_idProdotti = ".$idProd." order by DataInserimento DESC";
	
	$result=mysqli_query($mysqli, $query);
	$rows =$result->num_rows;
	 
	mysqli_close($mysqli);

}
else
{
	$isempty = 1; die();
}

get_header();
 
?>


	<script type="text/javascript">
	$(document).ready(function()
	{
		// Select the Subcategory and the Product Category when load page
		var idCateg1 = $(".category").val(); 
		var idSottoCateg1 = <?php echo($idSottoCategoria); ?>;
		var dataString1 = 'id='+ idCateg1 + '&idselected=' + idSottoCateg1;
		$.ajax 
		({
			type: "POST",
			url: "ajax_subcat.php",
			data: dataString1,
			cache: false,
			success: function(html)
			{
				$(".subCategory").html(html);
			}
		});
		var idTipologiaProd = <?php echo $Settore; ?>;
		var dataString2 = 'id='+ idSottoCateg1 + '&idselected=' + idTipologiaProd;
		$.ajax 
		({
			type: "POST",
			url: "ajax_prodcat.php",
			data: dataString2,
			cache: false,
			success: function(html)
			{
				$(".ProdCat").html(html);
			}
		});
		
		// SubCategory Update
		$(".category").change(function()
		{
			var idCateg=$(this).val();
			var dataString = 'id='+ idCateg;

			$.ajax
			({
				type: "POST",
				url: "ajax_subcat.php",
				data: dataString,
				cache: false,
				success: function(html)
				{
					$(".subCategory").html(html);
				}
			});
		});
		
		// Product Category Update
		$(".subCategory").change(function()
		{
			var idSubCateg = $(this).val();
			var dataString = 'id='+ idSubCateg;

			$.ajax
			({
				type: "POST",
				url: "ajax_prodcat.php",
				data: dataString,
				cache: false,
				success: function(html)
				{
					$(".ProdCat").html(html);
				}
			});
		});
		
		$("#trashinvoice").click(function()
		{
			if(confirm('Delete Invoice? (No file on disk will be erased)'))
			{
				var datatoSend= 'id=1' + '&idInvoice=' + $(this).attr('value') + '&idProd=' + <?php echo $idProd ?>;
				
				$.ajax
				({
					type: "POST",
					url: "ajax_delete.php",
					data: datatoSend,
					cache: false,
					success: function(html)
					{
						$("#operations-result").html(html);
					}
				}); 
				
			}
			else
			{
				//Nothing to do
			}
			
		});
		
		$("#trashdocument").click(function()
		{
			if(confirm('Delete Document? (No file on disk will be erased)'))
			{
				var datatoSend= 'id=2' + '&idDocument=' + $(this).attr('value') + '&idProd=' + <?php echo $idProd ?>;
				
				$.ajax
				({
					type: "POST",
					url: "ajax_delete.php",
					data: datatoSend,
					cache: false,
					success: function(html)
					{
						$("#operations-result").html(html);
					}
				}); 
				
			}
			else
			{
				//Nothing to do
			}
			
		});
		
	});
	
	</script>



<div class="grid"><br />
<div id="icon-description" class="clearfix">
	<div class="col_12" style="margin-top:0px;">

	<h5  class="tCategory">Edit Item <?php if($isempty == 1) { echo "- Wrong ID Item -"; } ?></h5>
	
	 <form class="vertical" action="update.php" method="post" enctype="multipart/form-data">
	 <div class="col_4">
	   <label for="text21">Manufacturer Part No: </label>
	   <input type="text" name="ManuPartNo" value="<?php echo $ManuPartNo; ?>"/>
	   <label for="text21">Vendor Part No: </label>
	   <input type="text" name="VendorPartNo" value="<?php echo $VendorPartNo; ?>"/>
	   <label for="text21">Manufacturer (<a href="javascript:;" style="text-decoration: none;" onClick="window.open('popup/insmanu.php', 'titolo', 'width=400, height=250, resizable, status, scrollbars=1,location');">add</a>):  </label>
	   <select id="manufacturer" class="manufacturer" name="manufacturer">
	     <?php 
	     $i=0;
	     while($i < $rows_2)  
	     {
	     	$row=$result_2->fetch_assoc();  
	     	
	       $idProdutt=$row['idProduttore']; 
	       $nomProdutt=$row['nomeProduttore'];
	       if($idProdutt == $idProduttore)
	       {
	       		echo "<option value=".$idProdutt." selected=\"selected\">".$nomProdutt."</option>";
	       }
	       else
	       {
	       		echo "<option value=".$idProdutt.">".$nomProdutt."</option>"; 	
	       }
	       $i++;
	     }     
	     ?>
	   </select>
	   <label for="text21">Termination Style (<a href="javascript:;" style="text-decoration: none;" onClick="window.open('popup/insterm.php', 'titolo', 'width=400, height=250, resizable, status, scrollbars=1,location');">add</a>):  </label>
	   <select id="termination" class="termination" name="termination">
	     <?php 
	     $i=0;
	
	     while($i < $rows_5)
	     {
	     	$row=$result_5->fetch_assoc();
	     	
	       $idtermStyle=$row['idTerminationsStyle'];
	       $termStyle=$row['TerminationStyle'];
	       if($idtermStyle == $idTerminationStyle)
	       {
		       echo "<option value=".$idtermStyle." selected=\"selected\">".$termStyle."</option>";
	       }   
	       else
	       {
		       echo "<option value=".$idtermStyle.">".$termStyle."</option>";
	       } 
	       $i++;
	     }     
	     ?>
	   </select>
	   <label for="text21">Description: </label>
	   <textarea id="textarea1" name="Descr"  placeholder="Placeholder Text"><?php echo $Description; ?></textarea>
	   <label for="text21">Vendor Detail URL: </label>
	   <input type="text" name="VendorURL" value="<?php echo $VendorURL; ?>"/>
	 </div>  
	 
	 <div class="col_4">     
	   <label for="text21">Category (<a href="javascript:;" style="text-decoration: none;" onClick="window.open('popup/inscate.php', 'titolo', 'width=400, height=250, resizable, status, scrollbars=1,location');">add</a>):  </label>
	   <select id="category" class="category" name="category">
	     <?php 
	     $i=0;
	     while($i < $rows_4)
	     {
	     	$row=$result_4->fetch_assoc();
	     	
	       $idCateg=$row['idCategorieProdotti']; 
	       $nomCateg=$row['NomeCategoria'];        
	       if($idCateg == $idCategoriaProdotti)
	       {
		       echo "<option value=".$idCateg." selected=\"selected\">".$nomCateg."</option>";
	       }
	       else
	       {
		       echo "<option value=".$idCateg.">".$nomCateg."</option>";
	       }
	       $i++;
	     }     
	     ?>
	   </select>
	    <label for="text21">SubCategory (<a href="javascript:;" style="text-decoration: none;" onClick="window.open('popup/inssubc.php', 'titolo', 'width=400, height=300, resizable, status, scrollbars=1,location');">add</a>):  </label>
	   <select id="subCategory" class="subCategory" name="subCategory">
	    <option value=" ">-- SubCategory --</option>
	   </select>
	   <label for="text21">Product Category (<a href="javascript:;" style="text-decoration: none;" onClick="window.open('popup/insprodcat.php', 'titolo', 'width=400, height=300, resizable, status, scrollbars=1,location');">add</a>):</label>
	    <select id="ProdCat" class="ProdCat" name="ProdCat">
	    	<option value=" ">-- Product Category --</option>
	   	</select>
	   
	   <label for="text21">Depot (<a href="javascript:;" style="text-decoration: none;" onClick="window.open('popup/insdepsect.php', 'titolo', 'width=400, height=490, resizable, status, scrollbars=1,location');">add</a>):  </label>
	   <select id="iddepot" class="iddepot" name="iddepot">
	    <?php 
	     $i=0;
	     while($i < $rows_7)
	     {
	     	$row=$result_7->fetch_assoc();
	     	
	     	$print[0] = (!empty($row['Piano'])) ? 1 : 0 ; 
	     	$print[1] = (!empty($row['Settore'])) ? 1 : 0 ; 
	     	$print[2] = (!empty($row['Scaffale'])) ? 1 : 0 ; 
	     	
	     	if($row['idMagazzino'] == $idDepot)
	       {
		       $print_select = "<option value=".$row['idMagazzino']." selected=\"selected\">".$row['Nome']." - ".$row['Identificazione']."";
	       }      
	       else
	       {
		       $print_select = "<option value=".$row['idMagazzino'].">".$row['Nome']." - ".$row['Identificazione']."";
	       }
	     		     	
			if($print[0] == 1)
			{
				// Add Floor Info
     			$print_select .= " Floor: ".$row['Piano']."";
     		}
     		if($print[1] == 1)
			{
				// Add Floor Info
     			$print_select .= " Section: ".$row['Settore']."";
     		}
     		if($print[2] == 1)
			{
				// Add Floor Info
     			$print_select .= " Shelf: ".$row['Scaffale']."";
     		}
     			
     		$print_select .= "</option>";
     				
		 	echo $print_select;

	       $i++;
	     }     
	     ?>
	    </select>
	    <label for="text21">My Note: </label>
	    <textarea id="textarea1" name="mynote" placeholder="My Note"><?php echo $MyNote; ?></textarea>
	    <label for="text21">Replace Item Photo:  </label>
	      <!-- Gallery -->
		  <div class="gallery">
		  <a href="<?php echo $URLFoto; ?>"><img src="<?php echo $URLFoto; ?>" width="100" height="100" /></a>
		  </div>
	    <input type="file" name="photo"/>  
	 </div>
	 
	 <div class="col_4"> 
	   <label for="text21">Stock: </label>
	   <input type="text" name="Stock" value="<?php echo $PezziinMagazzino; ?>"/>  
	   <table cellspacing="0" cellpadding="0" style="border:1px solid #AAA;"> <tr><td>
	   
	   <label for="text21">Invoice to Add  (<a href="javascript:;" style="text-decoration: none;" onClick="window.open('popup/insinvo.php', 'titolo', 'width=400, height=500, resizable, status, scrollbars=1,location');">add</a>):  </label>
	   <select id="invoice" class="invoice" name="invoice">
	     <option value="">-- Invoice --</option>
	     <?php 
	     $i=0;
	     $ii=0;
	     $m=0;
	     while($i < $result_6->num_rows )
	     {
	     	$row=$result_6->fetch_assoc();
	     	
		 	$idAcqu=$row['idFatture']; 
		 	$numFattAcqu=$row['NumFattVendor']; 
		 	$DataFattVendor=$row['DataFattVendor'];
		 	$DataFattReg=$row['NumFattRegistrata'];
		 	$URLPdf=$row['URLPdf'];
		 	
		 	if(isset($idAcquisto))
		 	{
		 		for($k=0; $k < sizeof($idAcquisto); $k++)
		 		{
		      	 if($idAcqu == $idAcquisto[$k])
			  	 {
			   			$idAcquTable[$m]=$idAcqu;
			   			$numFattAcquTable[$m]=$numFattAcqu;
			   			$DataFattRegTable[$m]=$DataFattReg;
			   			$URLPdfTable[$m]=$URLPdf;

			   			$m++;
			  	 		$ii = 1;
			 	  }      
			   }
		   }
		   
		   // Non stampo nel Select la fattura già associato al prodotto
		   if($ii != 1)
		   {
			   echo "<option value=".$idAcqu.">".$numFattAcqu. " - ".date('d-m-Y', strtotime($DataFattVendor))." (Reg. ".$DataFattReg.")</option>";
		   }
		   $ii = 0;
		   $i++;
	     }     
	     ?>
	   </select>
	   <label for="text21">Purchased Parts*: </label>
	   <input type="text" name="purparts"  /> 
	   <label for="text21">Purchase Price* € (each): </label>
	   <input type="text" name="purprice"  /> 
	   <label for="text21">Purchase Reason: </label>
	   <input type="text" name="projref"  /> 
	   
	   </td></tr></table>

	   <table class="sortable" cellspacing="0" cellpadding="0">
	   <thead><tr>
		  <th>Invoice Number</th>
		  <th>Replace?</th>
		  <th>Delete</th>
	   </tr></thead>
	   <tbody>
	   <?php 
	   if(isset($idAcquTable))
	   {
		   $i=0;
		   while($i < count($idAcquTable))
		   {
			   echo "<tr>
			   			<td><a href=\"".$URLPdfTable[$i]."\">".$numFattAcquTable[$i]." (R.".$DataFattRegTable[$i].")</a></td>
			   			<td><input type=\"checkbox\" id=\"check1\" name=\"AddorUpd\" value=\"".$idAcquTable[$i]."\" /></td>
			   			<td class=\"center\"><a id=\"trashinvoice\" href=\"\" value=\"".$idAcquTable[$i]."\"><i class=\"icon-trash\"></i></a></td>
			   		</tr>";
			   $i++;
		   }
	   }	   
		   
	   ?>
	   </tbody>
	   </table>

	 </div>
 
    
    
    
    <div class="col_12"> 
    <label for="text21">Documents in database for the product:<b><i> <?php echo $ManuPartNo; ?> </i></b></label><br>
	<table class="sortable" cellspacing="0" cellpadding="0">
	   <thead><tr>
		  <th width="51%">Name</th>
		  <th width="11%">Type</th>
		  <th width="11%">Size (KB)</th>
		  <th width="11%">Date</th>
		  <th width="8%">Replace?</th>
		  <th width="8%">Delete</th>
	   </tr></thead>
	   <tbody>
	   <?php
		$i=0;
		while ($i < $rows) 
		{
			$row=$result->fetch_assoc();		
	 
			$Nome=$row['Titolo']; 
			$idDocu=$row['idDocumenti'];
			$URLLink=$row['URLLink']; 
			$Desc=$row['Descrizione']; 
			$DataIns=$row['DataInserimento'];
			$SizeKB=$row['SizeKB']; 
			$Type=$row['Type']; 
     
     		echo "<tr>
					<td><a href=\"$URLLink\">$Nome</a>
					<p style=\"font-size:9pt; line-height:2pt;\">$Desc</p></td>
					<td>$Type</td>
					<td>".round($SizeKB, 2)."</td>
					<td>".date('d-m-Y', strtotime($DataIns))."</td>
					<td class=\"center\"><input type=\"checkbox\" id=\"check2\" name=\"DocReplace\" value=\"".$idDocu."\" /></td>
					<td class=\"center\"><a href=\"\" value=\"".$idDocu."\" id=\"trashdocument\"><i class=\"icon-trash\"></i></a></td>
	  			 </tr>";
			$i++;
		}
		?>
		
	   </tbody>
	   </table>	
    </div>
    
    
    <div class="col_4">
    
    	<label for="text21"><strong><i>Add or Replace Document: </i></strong></label>
		<label for="text21">Document Name* <i>(without extension)</i>: </label>
		<input type="text" name="nomeDocu" />  
		<label for="text21">Document Description: </label>
		<input type="text" name="descDocu" />  
		<input type="file" name="DataShe" /><br />
    
		<input type="hidden" name="idProdotto" value="<?php echo $idProd ?>"/><br />  
		<button type="submit" class="medium red">Update</button> 

	
		<div id="operations-result">
		</div>
	</div>

 
	 </form>

	</div>

</div>
		
	<br />
</div> <!-- End Grid -->


<!-- ===================================== START FOOTER ===================================== -->
<?php get_footer() ?>

</body>
</html>
