<?php
/**
 * Electronics Components Inventory - ECI
 *
 * Invoices
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

// Get the Header
get_header(); 

$mysqli = mysqli_connect($server, $username, $password, $database) or die("Error:" . mysqli_error($link));

if(isset($_GET["page"])) 
{ 
	$page  = $_GET["page"]; 
} 
else 
{ 
	// Default page is always Page 1
	$page = 1; 
};
$start_from = ($page - 1) * $NUMBER_OF_ROWS;

// Select all the Invoices
$query = "SELECT FattureAcquisti.*, Fornitori.NomeFornitore, Fornitori.Sito, COUNT(InfoFattura.idInfoFattura) as NumeroArticoliRegistrati, SUM(InfoFattura.PrezzodiAcquisto) as CostoTotaleArticoli FROM FattureAcquisti INNER JOIN Fornitori ON FattureAcquisti.Fornitori_idFornitori = Fornitori.idFornitori LEFT JOIN  InfoFattura ON InfoFattura.FattureAcquisti_idFatture = FattureAcquisti.idFatture GROUP BY FattureAcquisti.idFatture ORDER BY FattureAcquisti.DataFattVendor DESC
";

$result = mysqli_query($mysqli, $query);  
$num = $result->num_rows; 

while($row[] = $result->fetch_assoc());

$total_records = $num;
$total_pages = ceil($total_records / $NUMBER_OF_ROWS);

mysqli_close($mysqli);  
 
?>
<!-- ===================================== START BODY ===================================== -->
<div class="grid"><br />
<div id="icon-description" class="clearfix">
	<div class="col_12" style="margin-top:0px;">
	<h2 class="tHeader"><?php echo $lang['INVOICE_HEADER_TITLE'] ?></h2>
	
	<table class="sortable" cellspacing="0" cellpadding="0">
	
	<!-- NAVIGATION PAGES LINK -->
	<caption class="navigator-pages">
		<ul id="navigation-pages">
			<li><?php echo $lang['TABLE_NAVI_PAGE'];  ?>: &nbsp;</li>
		  <?php		
		  	if($page < 10 and $total_pages < 10)
			{
				//stampa normalmente senza abbreviatori (...)
				for($i=1; $i<=$total_pages; $i++)
				{	
					if($page == $i) 
		  			{
		  				echo "<li><span class='paging-current'>$i</span></li>";
		  			}
		  			else
		  			{
		  				// Non sono in navigazione in nessuna categoria
		  				echo "<li><a href='invoices.php?page=".$i."'>".$i."</a></li>";		  				
		  			}
	
					if($i == $total_pages) 
					{
						break;
					}
				}
			}
			elseif($page < 10 and $total_pages >= 10)
			{
				//stampa normalmente con abbreviatore (...) finale
				for($i=1; $i<=9; $i++)
				{	
					if($page == $i) 
		  			{
		  				echo "<li><span class='paging-current'>$i</span></li>";
		  			}
		  			else
		  			{
		  				// Non sono in navigazione in nessuna categoria
		  				echo "<li><a href='invoices.php?page=".$i."'>".$i."</a></li>";
		  			}
				}	
				echo "<li><a href='invoices.php?page=".$i."'>...</a>";
				echo "<li><a href='invoices.php?page=".$total_pages."'>".$total_pages."</a></li>";	
			}
			elseif($page >= 10 and (floor($page/10)*10) == (floor($total_pages/10)*10) )
			{
				// Non serve l'abbreviatore finale (...) ma solo l'iniziale
				echo "<li><a href='invoices.php?page=1'>1</a></li>";
				// Determino la prima pagina da stampare
				$index=floor($page/10)*10;
				echo "<li><a href='invoices.php?page=".($index-1)."'>...</a></li>";
				for($i=$index; $i<=($index + 9); $i++)
				{	
					if($page == $i) 
		  			{
		  				echo "<li><span class='paging-current'>$i</span></li>";
		  			}
		  			else
		  			{
		  				// Non sono in navigazione in nessuna categoria
		  				echo "<li><a href='invoices.php?page=".$i."'>".$i."</a></li>";
		  			}
	
					if($i == $total_pages) 
					{
						break;
					}
				}

			}
			elseif($page >= 10 )
			{
				// Serve l'abbreviatore finale (...)
				echo "<li><a href='invoices.php?page=1'>1</a></li>";
				// Determino la prima pagina da stampare
				$index=floor($page/10)*10;
				echo "<li><a href='invoices.php?page=".($index-1)."'>...</a></li>";
				for($i=$index; $i<=($index + 9); $i++)
				{	
					if($page == $i) 
		  			{
		  				echo "<li><span class='paging-current'>$i</span></li>";
		  			}
		  			else
		  			{
		  				// Non sono in navigazione in nessuna categoria
		  				echo "<li><a href='invoices.php?page=".$i."'>".$i."</a></li>";
		  			}
	
					if($i == $total_pages) 
					{
						break;
					}
				}
				echo "<li><a href='invoices.php?page=".$i."'>...</a></li>";
				echo "<li><a href='invoices.php?page=".$total_pages."'>".$total_pages."</a></li>";	
			}
		  ?>
		</ul>
	</caption>
	<thead>
		<tr>
			<th><?php echo $lang['INVOICE_VEND_NO']; ?></th>
	    	<th><?php echo $lang['INVOICE_VEND_DATE']; ?></th>
			<th><?php echo $lang['INVOICE_VENDOR']; ?></th>
			<th><?php echo $lang['INVOICE_NO']; ?></th>
			<th><?php echo $lang['INVOICE_REG_DATE']; ?></th>
			<th><?php echo $lang['INVOICE_ITEMS_NO']; ?></th>
			<th><?php echo $lang['INVOICE_TOTAL']." (".$lang['PRODUCT_CURRENCY'].")"; ?></th>
		</tr>
	</thead>
	<tbody>
	
	<?php
	 $i=0;	
	 while ($i < $num) 
	 {
	 	// Date Format
	 	if(!empty($row[$i]['DataFattVendor']))
	 	{
	 		$dateFattVendor = date_format( date_create($row[$i]['DataFattVendor']) , 'd/m/y');
	 	}
	 	else
	 	{
	 		$dateFattVendor = '';
	 	}
	 	
	 	if(!empty($row[$i]['DataFattRegistrata']))
	 	{
	 		$dateFattReg = date_format( date_create($row[$i]['DataFattRegistrata']) , 'd/m/y');
	 	}
	 	else
	 	{
	 		$dateFattReg = '';
	 	}
	 	    
		 echo "<tr>
			<td class=\"center\"><a href=\"".$row[$i]['URLPdf']."\">".$row[$i]['NumFattVendor']."</a></td>
			<td class=\"center\">".$dateFattVendor."</td>
			<td class=\"center\"><a href=\"".$row[$i]['Sito']."\" target=\"_blank\">".$row[$i]['NomeFornitore']."</a></td>		
			<td class=\"center\">".$row[$i]['NumFattRegistrata']."</td>
			<td class=\"center\">".$dateFattReg."</td>
			<td class=\"center\">".$row[$i]['NumeroArticoliRegistrati']."</td>
			<td class=\"center\">".number_format($row[$i]['CostoTotaleArticoli'], 2, ".", ",")."</td>
		</tr>";
	
      $i++;
 	}
	
	?>

	
	</tbody>
	<tfoot class="navigator-pages">
		<tr>
			<td colspan="7" id="navigation-pages">
				<ul id="navigation-pages">
			<li><?php echo $lang['TABLE_NAVI_PAGE'];  ?>: &nbsp;</li>
		  <?php		
		  	if($page < 10 and $total_pages < 10)
			{
				//stampa normalmente senza abbreviatori (...)
				for($i=1; $i<=$total_pages; $i++)
				{	
					if($page == $i) 
		  			{
		  				echo "<li><span class='paging-current'>$i</span></li>";
		  			}
		  			else
		  			{
		  				// Non sono in navigazione in nessuna categoria
		  				echo "<li><a href='invoices.php?page=".$i."'>".$i."</a></li>";		  				
		  			}
	
					if($i == $total_pages) 
					{
						break;
					}
				}
			}
			elseif($page < 10 and $total_pages >= 10)
			{
				//stampa normalmente con abbreviatore (...) finale
				for($i=1; $i<=9; $i++)
				{	
					if($page == $i) 
		  			{
		  				echo "<li><span class='paging-current'>$i</span></li>";
		  			}
		  			else
		  			{
		  				// Non sono in navigazione in nessuna categoria
		  				echo "<li><a href='invoices.php?page=".$i."'>".$i."</a></li>";
		  			}
				}	
				echo "<li><a href='invoices.php?page=".$i."'>...</a>";
				echo "<li><a href='invoices.php?page=".$total_pages."'>".$total_pages."</a></li>";	
			}
			elseif($page >= 10 and (floor($page/10)*10) == (floor($total_pages/10)*10) )
			{
				// Non serve l'abbreviatore finale (...) ma solo l'iniziale
				echo "<li><a href='invoices.php?page=1'>1</a></li>";
				// Determino la prima pagina da stampare
				$index=floor($page/10)*10;
				echo "<li><a href='invoices.php?page=".($index-1)."'>...</a></li>";
				for($i=$index; $i<=($index + 9); $i++)
				{	
					if($page == $i) 
		  			{
		  				echo "<li><span class='paging-current'>$i</span></li>";
		  			}
		  			else
		  			{
		  				// Non sono in navigazione in nessuna categoria
		  				echo "<li><a href='invoices.php?page=".$i."'>".$i."</a></li>";
		  			}
	
					if($i == $total_pages) 
					{
						break;
					}
				}

			}
			elseif($page >= 10 )
			{
				// Serve l'abbreviatore finale (...)
				echo "<li><a href='invoices.php?page=1'>1</a></li>";
				// Determino la prima pagina da stampare
				$index=floor($page/10)*10;
				echo "<li><a href='invoices.php?page=".($index-1)."'>...</a></li>";
				for($i=$index; $i<=($index + 9); $i++)
				{	
					if($page == $i) 
		  			{
		  				echo "<li><span class='paging-current'>$i</span></li>";
		  			}
		  			else
		  			{
		  				// Non sono in navigazione in nessuna categoria
		  				echo "<li><a href='invoices.php?page=".$i."'>".$i."</a></li>";
		  			}
	
					if($i == $total_pages) 
					{
						break;
					}
				}
				echo "<li><a href='invoices.php?page=".$i."'>...</a></li>";
				echo "<li><a href='invoices.php?page=".$total_pages."'>".$total_pages."</a></li>";	
			}
		  ?>
		</ul>
			</td>
		</tr>
	</tfoot>
	
	</table>
	</div>
		
	</div>
	<br />
</div> <!-- End Grid -->


<!-- ===================================== START FOOTER ===================================== -->
<?php get_footer(); ?>

</body>
</html>

