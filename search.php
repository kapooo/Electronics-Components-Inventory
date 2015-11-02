<?php
/**
 * Electronics Components Inventory - ECI
 *
 * Search
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

if(isset($_POST["page"])) 
{ 
	$page  = $_POST["page"]; 
} 
else 
{ 
	// Pagina di Default è sempre la 1
	$page = 1; 
};
$start_from = ($page - 1) * $NUMBER_OF_ROWS_SEARCH;

if(isset($_POST['search']) and $_POST['search'] != "") 
{
	$search = $_POST['search'];

	// Connessiona al database
	$mysqli = mysqli_connect($server, $username, $password, $database) or die("Error:" . mysqli_error($link));
 
	$query="SELECT Prodotti.idProdotti, Prodotti.ManufacturerPartNo, Prodotti.Description, Prodotti.PezziinMagazzino, Prodotti.TipologiaProdotti_idTipologiaProdotti, Prodotti.Produttore_idProduttore, Produttore.NomeProduttore, TipologiaProdotti.TipologiaProdotto, TipologiaProdotti.idTipologiaProdotti, Produttore.idProduttore FROM Prodotti, Produttore, TipologiaProdotti WHERE Produttore.idProduttore = Prodotti.Produttore_idProduttore AND TipologiaProdotti.idTipologiaProdotti = Prodotti.TipologiaProdotti_idTipologiaProdotti AND (Prodotti.Description LIKE '%".$search."%' OR Prodotti.ManufacturerPartNo LIKE '%".$search."%') ORDER BY DataUltimaModifica DESC LIMIT $start_from, $NUMBER_OF_ROWS_SEARCH";
	
	// Determino il numero di record totali da visualizzare
	$query_count = "SELECT COUNT(*) FROM Prodotti, Produttore, TipologiaProdotti WHERE Produttore.idProduttore = Prodotti.Produttore_idProduttore AND TipologiaProdotti.idTipologiaProdotti = Prodotti.TipologiaProdotti_idTipologiaProdotti AND (Prodotti.Description LIKE '%".$search."%' OR Prodotti.ManufacturerPartNo LIKE '%".$search."%')"; 

	$result=mysqli_query($mysqli,$query);
	
}
else
{
	//header("Location: index.php"); 
	mysqli_close($mysqli);
	die();
}

while($row[] = $result->fetch_assoc()); 

$rs_result = mysqli_query($mysqli, $query_count); 
$row_count = mysqli_fetch_row($rs_result); 
$total_records = $row_count[0]; 
$total_pages = ceil($total_records / $NUMBER_OF_ROWS_SEARCH);
mysqli_close($mysqli);

 
get_header();
 
?>

<!-- ===================================== START BODY ===================================== -->
<div class="grid"><br />
<div id="icon-description" class="clearfix">
	<div class="col_12" style="margin-top:0px;">

	<h6  class="tHeader">Search Results</h6>
	
<form action="search.php" method="post" enctype="multipart/form-data" class="searchFor">	
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
		  					echo "<li><a href='search.php?page=".$i."' class='post' value='".$i."'>".$i."</a></li>";
		  				
		  				
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
		  					echo "<li><a href='search.php?page=".$i."' class='post' value='".$i."'>".$i."</a></li>";
		  				
		  			}
				}	
				echo "<li><a href='search.php?page=".$i."' class='post' value='".$i."'>...</a>";
				echo "<li><a href='search.php?page=".$total_pages."' class='post' value='".$totale_pages."'>".$total_pages."</a></li>";	
			}
			elseif($page >= 10 and (floor($page/10)*10) == (floor($total_pages/10)*10) )
			{
				// Non serve l'abbreviatore finale (...) ma solo l'iniziale
				echo "<li><a href='search.php?page=1'>1</a></li>";
				// Determino la prima pagina da stampare
				$index=floor($page/10)*10;
				echo "<li><a href='search.php?page=".($index-1)."' class='post' value='".($index-1)."'>...</a></li>";
				for($i=$index; $i<=($index + 9); $i++)
				{	
					if($page == $i) 
		  			{
		  				echo "<li><span class='paging-current'>$i</span></li>";
		  			}
		  			else
		  			{
		  				
		  					// Non sono in navigazione in nessuna categoria
		  					echo "<li><a href='search.php?page=".$i."' class='post' value='".$i."'>".$i."</a></li>";
		  				
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
				echo "<li><a href='search.php?page=1'>1</a></li>";
				// Determino la prima pagina da stampare
				$index=floor($page/10)*10;
				echo "<li><a href='search.php?page=".($index-1)."' class='post' value='".($index-1)."'>...</a></li>";
				for($i=$index; $i<=($index + 9); $i++)
				{	
					if($page == $i) 
		  			{
		  				echo "<li><span class='paging-current'>$i</span></li>";
		  			}
		  			else
		  			{
		  				
		  					// Non sono in navigazione in nessuna categoria
		  					echo "<li><a href='search.php?page=".$i."' class='post' value='".$i."'>".$i."</a></li>";
		  				
		  			}
	
					if($i == $total_pages) 
					{
						break;
					}
				}
				echo "<li><a href='search.php?page=".$i."' class='post' value='".$i."'>...</a></li>";
				echo "<li><a href='search.php?page=".$total_pages."' class='post' value='".$total_pages."'>".$total_pages."</a></li>";	
			}
		  ?>
		</ul>
	</caption>
	
	<thead><tr>
		<th><?php echo $lang['TABLE_PROD_MANUPARTNUM']; ?></th>
	    <th><?php echo $lang['TABLE_PROD_MANU']; ?></th>
		<th><?php echo $lang['TABLE_PROD_DESCR']; ?></th>
		<th><?php echo $lang['TABLE_PROD_STOCK']; ?></th>
		<th><?php echo $lang['TABLE_PROD_PRODCAT']; ?></th>
		<th><?php echo $lang['TABLE_PROD_EDIT']; ?></th>
	</tr></thead>
	<tbody>
	
	<?php
		if(isset($search))
		{
			$i=0;
			while ($i < $result->num_rows) 
			{     
				echo "<tr>
				<td><a href=\"product.php?idProd=".$row[$i]['idProdotti']."\">".$row[$i]['ManufacturerPartNo']."</a></td>
				<td><a href=\"list.php?idmanu=".$row[$i]['idProduttore']."\">".$row[$i]['NomeProduttore']."</a></td>
				<td>".$row[$i]['Description']."</td>
				<td class=\"center\">".$row[$i]['PezziinMagazzino']."</td>
				<td><a href=\"list.php?idpcat=".$row[$i]['idTipologiaProdotti']."\">".$row[$i]['TipologiaProdotto']."</a></td>
				<td class=\"center\"><a href=\"edit.php?idProd=".$row[$i]['idProdotti']."\"><i class=\"icon-pencil\"></i></a></td>
				</tr>";
				$i++;
			}
		}
	?>
	
	</tbody>
	</table>
</form>
	</div>
		
	</div>
	<br />
</div> <!-- End Grid -->

<script type="text/javascript">
 $(document).ready(function() {

	$("a.post").click(function(e) {
		e.stopPropagation();
        e.preventDefault();
        var search = <?php echo json_encode($search); ?>;
        var page = this.getAttribute('value');
		
		$("form.searchFor").append('<input type="hidden" name="page" value="' + page + '" /><input type="hidden" name="search" value="' + search + '" />');
		
		$("form.searchFor").submit();

	});
});
</script>


<!-- ===================================== START FOOTER ===================================== -->
<?php get_footer(); ?>

</body>
</html>