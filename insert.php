<?php
/**
 * Electronics Components Inventory - ECI
 *
 * Insert new Product
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
require_once get_language($LANGUAGE);

 
 $mysqli = mysqli_connect($server, $username, $password, $database) or die("Error:" . mysqli_error($link));
 
 $query2="select nomeProduttore, idProduttore from Produttore order by nomeProduttore";
 $query3="select idSottoCategoriaProdotti, NomeSottoCategoria from SottoCategorieProdotti"; 
 $query4="select NomeCategoria, idCategorieProdotti from CategorieProdotti";
 $query5="select idTerminationsStyle, TerminationStyle from TerminationsStyle order by TerminationStyle";
 $query6="select * from FattureAcquisti order by DataFattVendor DESC";
 $query7="select * from Magazzino";
 
 $rq2=mysqli_query($mysqli, $query2) or die("500 Internal Error");  
 $rq3=mysqli_query($mysqli, $query3) or die("500 Internal Error");  
 $rq4=mysqli_query($mysqli, $query4) or die("500 Internal Error");  
 $rq5=mysqli_query($mysqli, $query5) or die("500 Internal Error"); 
 $rq6=mysqli_query($mysqli, $query6) or die("500 Internal Error");  
 $rq7=mysqli_query($mysqli, $query7) or die("500 Internal Error");  

 
 mysqli_close($mysqli);
 
if(isset($_GET['status']))
{
	$status = $_GET['status'];
}
else
{
	$status = NULL;
}

get_header();

?>


	<script type="text/javascript">
	$(document).ready(function()
	{
		// Disable Submit Button
		$("#submitBt").attr("type", "button");
		
		// Manufacturer Part No in DB is unique?
		$("input[name='ManuPartNo']").change(function()
		{ 
			// Check if Manufacturer Part is present in form
			if($("input[name='ManuPartNo']").val().length < 4)
			{
				$("input[name='ManuPartNo']").addClass('error');  
				$("label[name='ManuPartNo']").addClass('error');
				$("label[name='ManuPartNo']").html('Manufacturer Part No:* (Min Length 4)');
				return false;
			}
			else
			{
				$("input[name='ManuPartNo']").removeClass('error');
				$("label[name='ManuPartNo']").removeClass('error');
				$("label[name='ManuPartNo']").html('Manufacturer Part No:*');
			}
			
		
			var manuPN=$("input[name='ManuPartNo']").val();
			var dataString = 'mpn=' + manuPN; 

			$.ajax
			({
			type: 'POST',
			url: 'ajax_checkmpn.php',
			data: dataString,
			cache: false,
			success: function(html)
				{	
					var obj = jQuery.parseJSON(html); 
					if(obj === 1)
					{
						$("input[name='ManuPartNo']").addClass('error');  
						$("label[name='ManuPartNo']").addClass('error');
						$("label[name='ManuPartNo']").html('Manufacturer Part No:* (Already in DB)');
					}
					else 
					{	
						$("input[name='ManuPartNo']").removeClass('error');
						$("label[name='ManuPartNo']").removeClass('error');
						$("label[name='ManuPartNo']").html('Manufacturer Part No:*');
					}
				}
			});
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
		
		// Check Form validity
		$('#submitBt').click(function()
		{ 
			if( $('#ManufacturerPartNo').hasClass('error') ||  $('#ManufacturerPartNo').val() === "")
			{
				return false;
			}
			else
			{
				// Enable submit button
				$("#submitBt").attr("type", "submit"); 
			}
		});
		
	});
	</script>

<div class="grid"><br />
<div id="icon-description" class="clearfix">
	<div class="col_12" style="margin-top:0px;">

	<h5  class="tCategory"><?php echo $lang['INSERT_TITLE'];  ?></h5>
	
	 <form class="vertical" action="add.php" method="post" enctype="multipart/form-data">
	 <div class="col_4">
	   <label for="text21" name="ManuPartNo">Manufacturer Part No:* </label>
	   <input name="ManuPartNo" id="ManufacturerPartNo" />
	   <label for="text21">Vendor Part No: </label>
	   <input type="text" name="VendorPartNo" />
	   <label for="text21">Manufacturer (<a href="javascript:;" style="text-decoration: none;" onClick="window.open('popup/insmanu.php', 'titolo', 'width=400, height=250, resizable, status, scrollbars=1,location');">add</a>):  </label>
	   <select id="manufacturer" class="manufacturer" name="manufacturer">
	     <option value="1">-- Manufacturer --</option>
	     <?php 
	     $i=0;
	     $k=1;
	     while($i < $rq2->num_rows)
	     {
	     	$row=$rq2->fetch_assoc(); 
	      
		 	echo "<option value=".$row['idProduttore'].">".$row['nomeProduttore']."</option>";
		 	$i++;
		 	$k++;
	     }     
	     ?>
	   </select>
	   <label for="text21">Termination Style (<a href="javascript:;" style="text-decoration: none;" onClick="window.open('popup/insterm.php', 'titolo', 'width=400, height=250, resizable, status, scrollbars=1,location');">add</a>):  </label>
	   <select id="termination" class="termination" name="termination">
	     <option value="1">-- Termination Style --</option>
	     <?php 
	     $i=0;
	     $k=1;
	     while($i < $rq5->num_rows)
	     {
	     	$row=$rq5->fetch_assoc(); 
	     	      
		 	echo "<option value=".$row['idTerminationsStyle'].">".$row['TerminationStyle']."</option>";
		 	$i++;
		 	$k++;
	     }     
	     ?>
	   </select>
	   
	   <label for="text21">Description:* </label>
	   <textarea id="textarea1" name="Descr"  placeholder="Placeholder Text"></textarea>
	   <label for="text21">Vendor Detail URL: </label>
	   <input type="text" name="vendorURL" /><br />
	
	   <label for="text21"><strong><i>Document to Upload: </i></strong></label>
	   <label for="text21">Document Name* <i>(without extension)</i>: </label>
	   <input type="text" name="DocName" />  
	   <label for="text21">Document Description: </label>
	   <input type="text" name="DocDesc" />  
	   <input type="file" name="DataShe" /><br />
	   <button type="submit" class="medium red" id="submitBt">Submit</button> 
	   <?php if(isset($status) && $status== 'no') { echo "<label for=\"check1\" class=\"inline\" style=\"color: red; font-size: 30px; margin-left: 30px; font-variant: small-caps; font-weight: bold;\"> Error!</label>"; } else if( isset($status) && $status == 'ok' ) { echo "<label for=\"check1\" class=\"inline\" style=\"color: red; font-size: 30px; margin-left: 30px; font-variant: small-caps; font-weight: bold;\"> Inserted!</label>"; } ?>
	 </div>  
	 
	 <div class="col_4">     
	   <label for="text21">Category (<a href="javascript:;" style="text-decoration: none;" onClick="window.open('popup/inscate.php', 'titolo', 'width=400, height=250, resizable, status, scrollbars=1,location');">add</a>):  </label>
	   <select id="category" class="category" name="category">
	     <option value="1">-- Category --</option>
	     <?php 
	     $i=0;
	     $k=1;
	     while($i < $rq4->num_rows)
	     {
	     	$row=$rq4->fetch_assoc(); 
      
		 	echo "<option value=".$row['idCategorieProdotti'].">".$row['NomeCategoria']."</option>";
		 	$i++;
		 	$k++;
	     }     
	     ?>
	   </select>
	    <label for="text21">SubCategory (<a href="javascript:;" style="text-decoration: none;" onClick="window.open('popup/inssubc.php', 'titolo', 'width=400, height=300, resizable, status, scrollbars=1,location');">add</a>): </label>
	   <select id="subCategory" class="subCategory" name="subCategory">
	    <option value="1">-- SubCategory --</option>
	   </select>
	   <label for="text21">Product Category (<a href="javascript:;" style="text-decoration: none;" onClick="window.open('popup/insprodcat.php', 'titolo', 'width=400, height=300, resizable, status, scrollbars=1,location');">add</a>): </label>
	   <select id="ProdCat" class="ProdCat" name="ProdCat">
	    <option value="1">-- Product Category --</option>
	   </select>
	   <label for="text21">Depot (<a href="javascript:;" style="text-decoration: none;" onClick="window.open('popup/insdepsect.php', 'titolo', 'width=400, height=490, resizable, status, scrollbars=1,location');">add</a>): </label>
	   <select id="iddepot" class="iddepot" name="iddepot">
	   	<option value="1">-- Depot --</option>
	    <?php 
	     $i=0;
	     while($i < $rq7->num_rows)
	     {
	     	$row=$rq7->fetch_assoc();
	     	
	     	$print[0] = (!empty($row['Piano'])) ? 1 : 0 ; 
	     	$print[1] = (!empty($row['Settore'])) ? 1 : 0 ; 
	     	$print[2] = (!empty($row['Scaffale'])) ? 1 : 0 ; 
	     	
	     	$print_select = "<option value=".$row['idMagazzino'].">".$row['Nome']." - ".$row['Identificazione']."";
	     	
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
	    <textarea id="textarea1" name="MyNote" placeholder="My Note"></textarea>
	    <label for="text21">Photo of Item:  </label>
	    <input type="file" name="photo" />  

	 </div>
	 
	 <div class="col_4"> 
	   <label for="text21">Available in Stock:* </label>
	   <input type="text" name="Stock" /> 
	   <table cellspacing="0" cellpadding="0" style="border:1px solid #AAA;"> <tr><td>
	   
	   <label for="text21">Invoice (<a href="javascript:;" style="text-decoration: none;" onClick="window.open('popup/insinvo.php', 'titolo', 'width=400, height=500, resizable, status, scrollbars=1,location');">add</a>):  </label>
	   <select id="invoice" class="invoice" name="invoice">
	     <option value="">-- Invoice --</option>
	     <?php 
	     $i=0;
	     while($i < $rq6->num_rows)
	     {
	     	$row=$rq6->fetch_assoc();
	     	    
		 	echo "<option value=".$row['idFatture'].">".$row['NumFattVendor']." - ".date('d-m-Y', strtotime($row['DataFattVendor']))." (Reg. ".$row['NumFattRegistrata'].")</option>";
		 	$i++;
	     }     
	     ?>
	   </select>
	   <label for="text21">Total Purchased Parts:* </label>
	   <input type="text" name="purparts" /> 
	   <label for="text21">Purchase Price € (each):* </label>
	   <input type="text" name="purprice" /> 
	   <label for="text21">Purchase Reason: </label>
	   <input type="text" name="projref" /> 
	   
	  </td> </tr> </table>
	 </div>
	 
	   </div>
	 </form>
	</div>

	</div>
		
	<br />
</div> <!-- End Grid -->


<!-- ===================================== START FOOTER ===================================== -->
<?php get_footer(); ?>

</body>
</html>
