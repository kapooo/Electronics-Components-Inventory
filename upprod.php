<?php
/**
 * Electronics Components Inventory - ECI
 *
 * Update Product
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
 
if(isset($_POST['idProd']) and !empty($_POST['idProd']) and is_numeric($_POST['idProd']) and isset($_POST['Stock']) and is_numeric($_POST['Stock']) and
	isset($_POST['partsinstock']) and is_numeric($_POST['partsinstock']))
{ 
	if($_POST['partsinstock'] + $_POST['Stock'] < 0)
	{
		echo "Error";
	}
	else
	{
		$diff = $_POST['partsinstock'] + $_POST['Stock']; 
		$mysqli = mysqli_connect($server, $username, $password, $database) or die("Error:" . mysqli_error($link));
		$query = "UPDATE Prodotti SET PezziinMagazzino='". $diff."' where Prodotti.idProdotti='".$_POST['idProd']."'";
		
		mysqli_query($mysqli, $query) or die( "Errore nella query. Query non eseguita ". mysqli_error($mysqli));
	
		$mysqli->close();
	}
}

get_header();

?>




<div class="grid"><br />
<div id="icon-description" class="clearfix">
	<div class="col_12" style="margin-top:0px;">

	<h2 class="tCategory"><?php echo $lang['UPDATE_CHANGE_QUANTITY'] ?></h2>
	
	 <form class="vertical" action="upprod.php" method="post" enctype="multipart/form-data">
 	
	 <div class="col_12" style="border: 2px solid #CCCCCC; padding: 10px;">
	 
	 <div class="col_4">
	   <label name="ManufacturerPartNo" for="text21"><?php echo $lang['TABLE_PROD_MANUPARTNUM']; ?>:* </label>
	   <input type="text" name="ManufacturerPartNo" id="ManufacturerPartNo" />
	 </div>
	 
	 	 
	  <div class="col_3">
	   <label for="text21"><?php echo $lang['UPDATE_PARTS']; ?>:* </label>
	   <input type="text" name="Stock" id="Stock" /> 
	  </div>
	 
	  <div class="col_2">
		  <button id="submitBt" type="button" class="medium red" style="margin: 14px 0px 0px 10px;"><?php echo $lang['FORM_SUBMIT']; ?></button> 
	  </div>
	  <div id="catchedData" class="col_12">

	  </div>
	  </div>
	 </form>
	</div>
	</div>	
	<br />
</div> <!-- End Grid -->

<script type="text/javascript">
	$(document).ready(function()
	{
	
		// Disable Submit button
		$("#submitBt").attr("type", "button");
		$("input[name='ManufacturerPartNo']").focus();
		
		// Import string from PHP
		var error_min_length = <?php echo json_encode($lang['UPDATE_PARTS_MIN_LEN']); ?>;
		var manu_part_no = <?php echo json_encode($lang['TABLE_PROD_MANUPARTNUM']); ?>;
		var error_not_present = <?php echo json_encode($lang['UPDATE_PARTS_NOT_PRES']); ?>;	
		
		// Check if Manufacturer Part No already is in DB
		$("input[name='ManufacturerPartNo']").change(function()
		{ 
			// Replace ' with -
			document.getElementById("ManufacturerPartNo").value = $("input[name='ManufacturerPartNo']").val().replace(/'/g,"-");
			
			// Check if MPN is in form
			if($("input[name='ManufacturerPartNo']").val().length < 4)
			{
				$("input[name='ManufacturerPartNo']").addClass('error');  
				$("label[name='ManufacturerPartNo']").addClass('error');
				$("label[name='ManufacturerPartNo']").html(manu_part_no+':* ('+error_min_length+' 4)');
				return false;
			}
			else
			{
				$("input[name='ManufacturerPartNo']").removeClass('error');
				$("label[name='ManufacturerPartNo']").removeClass('error');
				$("label[name='ManufacturerPartNo']").html(manu_part_no+':*');
			}
			
		
			var manufacturerPN=$("input[name='ManufacturerPartNo']").val();
			var dataString = 'mpn=' + manufacturerPN; 

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
						$("input[name='ManufacturerPartNo']").removeClass('error');
						$("label[name='ManufacturerPartNo']").removeClass('error');
						$("label[name='ManufacturerPartNo']").html(manu_part_no+':*');
						
						// Display the Item
						$.ajax
						({
							type: 'POST',
							url: 'ajax_ckprod.php',
							data: dataString,
							cache: false,
							success: function(html)
							{
								$('#catchedData').html(html);
							}
						});
					}
					else 
					{	
						$("input[name='ManufacturerPartNo']").addClass('error');  
						$("label[name='ManufacturerPartNo']").addClass('error');
						$("label[name='ManufacturerPartNo']").html(manu_part_no+':* ('+error_not_present+')');
						return false;
					}
				}
			});
		});
		
		
		// Check form validity
		$('#submitBt').click(function()
		{ 
			if( $("input[name='ManufacturerPartNo']").val() === "" || $("input[name='ManufacturerPartNo']").attr('class') === 'error')
			{
				$("input[name='ManufacturerPartNo']").addClass('error');  
				$("label[name='ManufacturerPartNo']").addClass('error');
				$("label[name='ManufacturerPartNo']").html(manu_part_no+':* ');
				return false
			}
			if( $('#partsinstock').val() === "" || $('#partsinstock').val() === 'undefined' || $('#Stock').val() === "")
			{
				$('#Stock').addClass('error');
				return false;
			}
			else
			{
				if( parseInt($('#partsinstock').val(), 10) + parseInt($('#Stock').val(), 10) < 0)
				{
					$('#Stock').addClass('error');
					return false;
				}
				else
				{
					$('#Stock').removeClass('error');
				}
			}
						
			// Enable Submit button
			$("#submitBt").attr("type", "submit"); 

		});
		
	});
	</script>

<!-- ===================================== START FOOTER ===================================== -->
<?php get_footer(); ?>

</body>
</html>
