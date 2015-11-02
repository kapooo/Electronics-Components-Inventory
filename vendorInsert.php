<?php
/**
 * Electronics Components Inventory - ECI
 *
 * Vendor Insert
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
 

 $query_invoice="SELECT * FROM FattureAcquisti ORDER BY DataFattVendor DESC";
 $result_invoice=$mysqli->query($query_invoice);  
 
 $query_depot = "SELECT * FROM Magazzino ORDER BY Nome";
 $result_depot=$mysqli->query($query_depot);  
  
 $mysqli->close();
 
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
	
		// Disable Submit button
		$("#submitBt").attr("type", "button");
		$("input[name='VendorPartNo']").focus();
		
		// Check if Vendor Part No is already present in DB
		$("input[name='VendorPartNo']").change(function()
		{ 
			// Replace ' with -
			document.getElementById("VendorPartNo").value = $("input[name='VendorPartNo']").val().replace(/'/g,"-");
			
			// Check if Vendor Part No is valid
			if($("input[name='VendorPartNo']").val().length < 4)
			{
				$("input[name='VendorPartNo']").addClass('error');  
				$("label[name='VendorPartNo']").addClass('error');
				$("label[name='VendorPartNo']").html('Mouser Part No:* (Min Length 4)');
				return false;
			}
			else
			{
				$("input[name='VendorPartNo']").removeClass('error');
				$("label[name='VendorPartNo']").removeClass('error');
				$("label[name='VendorPartNo']").html('Mouser Part No:*');
			}
			
			// Check if Vendor Part No is already present in DB
			var vendorPN=$("input[name='VendorPartNo']").val();
			var dataString = 'vpn=' + vendorPN; 

			$.ajax
			({
			type: 'POST',
			url: 'ajax_checkvpn.php',
			data: dataString,
			cache: false,
			success: function(html)
				{	
					var obj = jQuery.parseJSON(html); 
					if(obj === 1)
					{
						$("input[name='VendorPartNo']").addClass('error');  
						$("label[name='VendorPartNo']").addClass('error');
						$("label[name='VendorPartNo']").html('Mouser Part No:* (Already in DB)');
					}
					else 
					{	
						$("input[name='VendorPartNo']").removeClass('error');
						$("label[name='VendorPartNo']").removeClass('error');
						$("label[name='VendorPartNo']").html('Mouser Part No:*');
					}
				}
			});
		});
		
		
		// Go button
		$('#catchData').click(function()
		{
			if($('#VendorPartNo').val().length < 4)
			{
				$("input[name='VendorPartNo']").addClass('error');  
				$("label[name='VendorPartNo']").addClass('error');
				$("label[name='VendorPartNo']").html('Mouser Part No:* (Min Length 4)');
				return false;			}
			else
			{
				$("input[name='VendorPartNo']").removeClass('error');
				$("label[name='VendorPartNo']").removeClass('error');
				$("label[name='VendorPartNo']").html('Mouser Part No:*');
			}
			
			// Recover data from Mouser
			var vendorPN=$('#VendorPartNo').val();
			var dataString = 'vpn=' + vendorPN; 

			$.ajax
			({
			type: 'POST',
			url: 'mouser.php',
			data: dataString,
			cache: false,
			success: function(html)
				{
					$('#catchedData').html(html);
		
			// Check if Product is found on Vendor SOAP request
			if( $('#soap_manupartno').length == 0 )
			{
				$("input[name='VendorPartNo']").addClass('error');  
				$("label[name='VendorPartNo']").addClass('error');
				$("label[name='VendorPartNo']").html('Mouser Part No:* (Not Found)');
				return false;				
			}
			else
			{	// Check if Vendor Part No is present
				if($('#soap_manupartno').val() === "")
				{
					$("input[name='VendorPartNo']").addClass('error');  
					$("label[name='VendorPartNo']").addClass('error');
					$("label[name='VendorPartNo']").html('Mouser Part No:* (Not Found)');
					return false;
				}
				else
				{
					// Check if Manufacturer Part No is already in DB
					$.ajax
					({
					type: 'POST',
					url: 'ajax_checkmpn.php',
					data: 'mpn=' + $('#soap_manupartno').val(),
					cache: false,
					success: function(html)
						{
							var result = jQuery.parseJSON(html);  
							if(result === 1)
							{								
								$("input[name='VendorPartNo']").addClass('error');  
								$("label[name='VendorPartNo']").addClass('error');
								$("label[name='VendorPartNo']").html('Mouser Part No:* (MPN already inserted)');
								return false;
							}
							else
							{
								$('#VendorPartNo').removeClass('error');
								$("label[name='VendorPartNo']").html('Mouser Part No:*');
							}
						}
					});
				}
			}
		}
	});		
		
				
		});
		
		// Check the form on click submit button
		$('#submitBt').click(function()
		{ 
			if( $('#VendorPartNo').hasClass('error') ||  $('#VendorPartNo').val() === "")
			{
				$("input[name='VendorPartNo']").addClass('error');  
				$("label[name='VendorPartNo']").addClass('error');
				$("label[name='VendorPartNo']").html('Mouser Part No:*');
				return false;
			}
			else
			{
				$('#VendorPartNo').removeClass('error');
			}
			
			// Check if Product is found on Vendor SOAP request
			if( $('#soap_manupartno').length == 0 || $('#soap_manupartno').val() === "")
			{
				$("input[name='VendorPartNo']").addClass('error');  
				$("label[name='VendorPartNo']").addClass('error');
				$("label[name='VendorPartNo']").html('Mouser Part No:*');
				return false;				
			}
			else
			{	
				$('#VendorPartNo').removeClass('error');
			}
			
			if($('#invoice').val() !="")
			{
				if($('#purparts').val().length < 1)
				{
					$('#purparts').addClass('error');
					$('#purprice').removeClass('error');
					$('#Stock').removeClass('error');
					return false;
				
				}
				else
				{
					$('#purparts').removeClass('error');
				}
			

						
				if($('#purprice').val().length < 1)
				{
					$('#purprice').addClass('error');
					$('#Stock').removeClass('error');
					$('#purparts').removeClass('error');
					return false;
				
				}
				else
				{
					$('#purprice').removeClass('error');
				}
				
				if(check_stock() == false) { return false; }
				
			}
			else
			{
				$('#purprice').removeClass('error');
				$('#purparts').removeClass('error');
				
				if(check_stock() == false) { return false; }
						
			}
									
			// Enable submit button
			$("#submitBt").attr("type", "submit"); 

		}		
		)
	});
	
	function check_stock()
		{
			if( $('#Stock').val().length < 1 )
			{
				$('#Stock').addClass('error');
				return false;
				
			}
			else
			{
				$('#Stock').removeClass('error');
				return true;
			}
		}
	
	</script>


<div class="grid"><br />
<div id="icon-description" class="clearfix">
	<div class="col_12" style="margin-top:0px;">

	<h2 class="tCategory"><?php echo $lang['VENDOR_INSERT_TITLE'] ?>: <a href="http://www.mouser.com" target="_blank">Mouser</a></h2>
	
	 <form class="vertical" action="addVendor.php" method="post" enctype="multipart/form-data">
	 
	  <div class="col_12" style="border: 2px solid #CCCCCC; padding: 10px;">
	 <div class="col_3">
	   <label for="text21"><?php echo $lang['PRODUCT_TABLE_INVOICE'] ?> (<a href="javascript:;" style="text-decoration: none;" onClick="window.open('popup/insinvo.php', 'titolo', 'width=400, height=500, resizable, status, scrollbars=1,location');"><?php echo $lang['FORM_ADD'] ?></a>):  </label>
	   <select id="invoice" class="invoice" name="invoice">
	     <option value="">-- <?php echo $lang['PRODUCT_TABLE_INVOICE'] ?> --</option>
	     <?php 
	     $i=0;
	     while($i < $result_invoice->num_rows)
	     {
	       $row = $result_invoice->fetch_assoc();   
	       if(isset($_GET['invoice']) and !empty($_GET['invoice']) and $_GET['invoice'] == $row['idFatture']) {
	       		echo "<option value=".$row['idFatture']." selected=\"selected\">".$row['NumFattVendor']." - ".date('d-m-Y', strtotime($row['DataFattVendor']))."</option>";
	       }
	       else {
	       		echo "<option value=".$row['idFatture'].">".$row['NumFattVendor']." - ".date('d-m-Y', strtotime($row['DataFattVendor']))."</option>";
	       }
	       $i++;
	     }     
	     ?>
	   </select>
	</div>
	
	<div class="col_2">
   		<label for="text21"><?php echo $lang['VENDOR_PUR_PARTS'] ?>:* </label>
	   	<input type="text" name="purparts" id="purparts" /> 	   
	</div>  
 
	<div class="col_2">
	  	<label for="text21"><?php echo $lang['VENDOR_PUR_PRICE'] ?> <?php echo $lang['PRODUCT_CURRENCY'] ?>:* </label>
	  	<input type="text" name="purprice" id="purprice" /> 
	</div>
	  
	<div class="col_2" >
	   <label for="text21"><?php echo $lang['PRODUCT_TABLE_PUR_REASON'] ?>:</label>
	   <?php 
	   		if(isset($_GET['projref']) and !empty($_GET['projref'])) {
	   			echo "<input type=\"text\" name=\"projref\" value=\"".$_GET['projref']."\">";
	   		}
	   		else {
	   			echo "<input type=\"text\" name=\"projref\" />";
	   		}
	   ?>
	</div>
	  
	</div>
	 	 
	 <div class="clear"></div>
	 
	 <div class="col_12" style="border: 2px solid #CCCCCC; padding: 10px;">
	 
	 <div class="col_3">
	   <label name="VendorPartNo" for="text21"><a href="http://www.mouser.com" target="_blank">Mouser</a> <?php echo $lang['FORM_PART_NO'] ?>:* </label>
	   <input type="text" name="VendorPartNo" id="VendorPartNo" />
	 </div>
	 
	 <div class="col_1">
	 	<button id="catchData" type="button" class="small green" style="margin: 22px 0px 0px -6px;">Go</button> 
	 </div>
	 	 
	  <div class="col_2">
	   <label for="text21"><?php echo $lang['VENDOR_AVAIL_STOCK'] ?>:* </label>
	   <input type="text" name="Stock" id="Stock" /> 
	  </div>
	  
	  <div class="col_3" >
	   <label for="text21"><?php echo $lang['INSERT_FAST_DEPOT']  ?> (<a href="javascript:;" style="text-decoration: none;" onClick="window.open('popup/insdepsect.php', 'titolo', 'width=400, height=490, resizable, status, scrollbars=1,location');">add</a>): </label>
	   <select id="iddepot" class="iddepot" name="iddepot">
	   	<option value="1">-- Depot --</option>
	    <?php 
	     $i=0;
	     while($i < $result_depot->num_rows)
	     {
	     	$row = $result_depot->fetch_assoc();
	     	
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
	 </div>
	 	 
	  <div class="col_2">
		  <button id="submitBt" type="" class="medium red" style="margin: 14px 0px 0px 10px;"><?php echo $lang['FORM_SUBMIT'] ?></button> 
	  </div>
	  
	  
	  <div id="catchedData" class="col_12">

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
