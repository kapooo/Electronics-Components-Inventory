<?php
/**
 * Electronics Components Inventory - ECI
 *
 * Navigator
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

if(isset($_GET["page"])) 
{ 
	$page  = $_GET["page"]; 
} 
else 
{ 
	// Pagina di Default è sempre la 1
	$page = 1; 
};
$start_from = ($page - 1) * $NUMBER_OF_ROWS;

if( isset($_GET["idc"]) AND is_numeric($_GET["idc"]) )
{
	$idCategory = $_GET["idc"];
	
	// Query per la determinazione dei prodotti di una specifica categoria
	$query = "SELECT Prodotti.idProdotti, Prodotti.ManufacturerPartNo, Prodotti.Description, Prodotti.PezziinMagazzino, Prodotti.DataUltimaModifica, Produttore.nomeProduttore, Produttore.idProduttore, SottoCategorieProdotti.idSottoCategoriaProdotti, SottoCategorieProdotti.NomeSottoCategoria, CategorieProdotti.NomeCategoria, TipologiaProdotti.idTipologiaProdotti, TipologiaProdotti.TipologiaProdotto FROM Prodotti, Produttore, SottoCategorieProdotti, CategorieProdotti, TipologiaProdotti WHERE Produttore.idProduttore =  Prodotti.Produttore_idProduttore AND SottoCategorieProdotti.idSottoCategoriaProdotti = TipologiaProdotti.SottoCategorieProdotti_idSottoCategoriaProdotti AND CategorieProdotti.idCategorieProdotti = SottoCategorieProdotti.CategorieProdotti_idCategorieProdotti AND TipologiaProdotti.idTipologiaProdotti = Prodotti.TipologiaProdotti_idTipologiaProdotti AND CategorieProdotti.idCategorieProdotti = ".$idCategory." ORDER BY DataUltimaModifica desc limit $start_from, $NUMBER_OF_ROWS";
	
	// Determino il numero di record totali da visualizzare
	$query_count = "SELECT COUNT(*) FROM Prodotti, Produttore, SottoCategorieProdotti, CategorieProdotti, TipologiaProdotti WHERE  Produttore.idProduttore =  Prodotti.Produttore_idProduttore AND SottoCategorieProdotti.idSottoCategoriaProdotti = TipologiaProdotti.SottoCategorieProdotti_idSottoCategoriaProdotti AND CategorieProdotti.idCategorieProdotti = SottoCategorieProdotti.CategorieProdotti_idCategorieProdotti AND TipologiaProdotti.idTipologiaProdotti = Prodotti.TipologiaProdotti_idTipologiaProdotti AND CategorieProdotti.idCategorieProdotti = ".$idCategory."" ; 
	
	// Query per la determinazione di tutte le sottocategorie presenti nel DB che abbiano ALMENO 1 prodotto
	$query_subcat = "SELECT * FROM SottoCategorieProdotti, CategorieProdotti WHERE CategorieProdotti.idCategorieProdotti = SottoCategorieProdotti.CategorieProdotti_idCategorieProdotti AND CategorieProdotti.idCategorieProdotti = ".$idCategory." ORDER BY NomeSottoCategoria ASC";
	
	$query_subcat = "SELECT DISTINCT CategorieProdotti.*, SottoCategorieProdotti.* FROM CategorieProdotti, SottoCategorieProdotti, TipologiaProdotti, Prodotti WHERE CategorieProdotti.idCategorieProdotti = SottoCategorieProdotti.CategorieProdotti_idCategorieProdotti AND SottoCategorieProdotti.idSottoCategoriaProdotti = TipologiaProdotti.SottoCategorieProdotti_idSottoCategoriaProdotti AND TipologiaProdotti.idTipologiaProdotti = Prodotti.TipologiaProdotti_idTipologiaProdotti AND CategorieProdotti.idCategorieProdotti = ".$idCategory." ORDER BY NomeSottoCategoria ASC";
	
	$result_subcat = mysqli_query($mysqli, $query_subcat);  
	$num_subcat = $result_subcat->num_rows;
	while( $row_subcat[]=$result_subcat->fetch_assoc() );
	
}
elseif(isset($_GET["ids"]) AND is_numeric($_GET["ids"]) )
{
	$idSubCategory = $_GET["ids"];
	
	// Query per la determinazione dei prodotti di una specifica subcategory
	$query = "SELECT Prodotti.idProdotti, Prodotti.ManufacturerPartNo, Prodotti.Description, Prodotti.PezziinMagazzino, Prodotti.DataUltimaModifica, Produttore.nomeProduttore, Produttore.idProduttore, SottoCategorieProdotti.idSottoCategoriaProdotti, SottoCategorieProdotti.NomeSottoCategoria, CategorieProdotti.NomeCategoria, CategorieProdotti.idCategorieProdotti, TipologiaProdotti.idTipologiaProdotti, TipologiaProdotti.TipologiaProdotto FROM Prodotti, Produttore, SottoCategorieProdotti, CategorieProdotti, TipologiaProdotti WHERE Produttore.idProduttore =  Prodotti.Produttore_idProduttore AND SottoCategorieProdotti.idSottoCategoriaProdotti = TipologiaProdotti.SottoCategorieProdotti_idSottoCategoriaProdotti AND CategorieProdotti.idCategorieProdotti = SottoCategorieProdotti.CategorieProdotti_idCategorieProdotti AND TipologiaProdotti.idTipologiaProdotti = Prodotti.TipologiaProdotti_idTipologiaProdotti AND SottoCategorieProdotti.idSottoCategoriaProdotti = ".$idSubCategory." ORDER BY DataUltimaModifica desc limit $start_from, $NUMBER_OF_ROWS";
	
	// Determino il numero di record totali da visualizzare
	$query_count = "SELECT COUNT(*) FROM Prodotti, Produttore, SottoCategorieProdotti, CategorieProdotti, TipologiaProdotti WHERE Produttore.idProduttore =  Prodotti.Produttore_idProduttore AND SottoCategorieProdotti.idSottoCategoriaProdotti = TipologiaProdotti.SottoCategorieProdotti_idSottoCategoriaProdotti AND CategorieProdotti.idCategorieProdotti = SottoCategorieProdotti.CategorieProdotti_idCategorieProdotti AND TipologiaProdotti.idTipologiaProdotti = Prodotti.TipologiaProdotti_idTipologiaProdotti AND SottoCategorieProdotti.idSottoCategoriaProdotti = ".$idSubCategory.""; 
	
	$query_prodcat = "SELECT DISTINCT SottoCategorieProdotti.idSottoCategoriaProdotti, SottoCategorieProdotti.NomeSottoCategoria, Prodotti.TipologiaProdotti_idTipologiaProdotti, TipologiaProdotti.TipologiaProdotto FROM Prodotti, SottoCategorieProdotti, TipologiaProdotti WHERE SottoCategorieProdotti.idSottoCategoriaProdotti = TipologiaProdotti.SottoCategorieProdotti_idSottoCategoriaProdotti AND TipologiaProdotti.idTipologiaProdotti = Prodotti.TipologiaProdotti_idTipologiaProdotti AND SottoCategorieProdotti.idSottoCategoriaProdotti = ".$idSubCategory."";
		
	$result_prodcat = mysqli_query($mysqli, $query_prodcat);  
	$num_prodcat = $result_prodcat->num_rows;
	while( $row_prodcat[] = $result_prodcat->fetch_assoc() );
	
} 
else
{
	// Query generica ordinata secondo i prodotti più recenti
	$query = "SELECT Prodotti.idProdotti, Prodotti.ManufacturerPartNo, Prodotti.Description, Prodotti.PezziinMagazzino, Prodotti.DataUltimaModifica, Produttore.nomeProduttore, Produttore.idProduttore, SottoCategorieProdotti.idSottoCategoriaProdotti, SottoCategorieProdotti.NomeSottoCategoria, CategorieProdotti.NomeCategoria, TipologiaProdotti.idTipologiaProdotti, TipologiaProdotti.TipologiaProdotto FROM Prodotti, Produttore, SottoCategorieProdotti, CategorieProdotti, TipologiaProdotti WHERE Produttore.idProduttore = Prodotti.Produttore_idProduttore AND SottoCategorieProdotti.idSottoCategoriaProdotti = TipologiaProdotti.SottoCategorieProdotti_idSottoCategoriaProdotti AND CategorieProdotti.idCategorieProdotti = SottoCategorieProdotti.CategorieProdotti_idCategorieProdotti AND TipologiaProdotti.idTipologiaProdotti = Prodotti.TipologiaProdotti_idTipologiaProdotti order by DataUltimaModifica desc limit $start_from, $NUMBER_OF_ROWS";
	
	// Determino il numero di record totali da visualizzare
	$query_count = "SELECT COUNT(*) FROM Prodotti, Produttore, SottoCategorieProdotti, CategorieProdotti, TipologiaProdotti WHERE Produttore.idProduttore = Prodotti.Produttore_idProduttore AND SottoCategorieProdotti.idSottoCategoriaProdotti = TipologiaProdotti.SottoCategorieProdotti_idSottoCategoriaProdotti AND CategorieProdotti.idCategorieProdotti = SottoCategorieProdotti.CategorieProdotti_idCategorieProdotti AND TipologiaProdotti.idTipologiaProdotti = Prodotti.TipologiaProdotti_idTipologiaProdotti"; 

}
 

$result = mysqli_query($mysqli, $query);  
$num = $result->num_rows; 

// Salvo in row il risultato della query così da avere id ati disponibili per più utilizzi senza dover fare una nuova query
while($row[]=$result->fetch_assoc());


$rs_result = mysqli_query($mysqli, $query_count); 
$row_count = mysqli_fetch_row($rs_result); 
$total_records = $row_count[0]; 
$total_pages = ceil($total_records / $NUMBER_OF_ROWS);
 

get_header();
 
?>
<!-- ===================================== START BODY ===================================== -->
<div class="grid"><br />
<div id="icon-description" class="clearfix">
	<div class="col_12" style="margin-top:0px;">
	
	<!-- Breadcrumbs -->
		 <ul class="breadcrumbs">
			 <li><a href="nav.php"><?php echo $lang['BREAD_ALL']; ?></a></li>
			 <?php
			 	if(isset($idManufacturer) AND !is_null($idManufacturer) AND isset($idProductCategory) AND !is_null($idProductCategory))
			 	{
			 		echo "<li><a href=\"list.php?idpcat=".$idProductCategory."\">".$row[0]['TipologiaProdotto']."</a></li>";
			 		echo "<li><a href=\"list.php?idmanu=".$idManufacturer."\">".$row[0]['nomeProduttore']."</a></li>";
			 	}
			 	elseif(isset($idCategory) AND !is_null($idCategory) )
			 	{
			 		echo "<li><a href=\"nav.php?idc=".$idCategory."\">".$row[0]['NomeCategoria']."</a></li>";
			 	}
			 	elseif(isset($idSubCategory) AND !is_null($idSubCategory))
			 	{
			 		echo "<li><a href=\"nav.php?idc=".$row[0]['idCategorieProdotti']."\">".$row[0]['NomeCategoria']."</a></li>"; 
			 		echo "<li><a href=\"nav.php?ids=".$idSubCategory."\">".$row_prodcat[0]['NomeSottoCategoria']."</a></li>";
			 	}
			 
			 ?>
			 <li></li>
		</ul>
	<?php
		if(isset($idCategory) AND !is_null($idCategory))
		{
			// Tabella on tutte le sottocategorie della categoria scelta
			echo "<table class='all-products' cellspacing='0' cellpadding='0'>
					<caption class= 'navigator-pages'>".$row_subcat[0]['NomeCategoria']."</caption>
						<tbody>
						<tr>
							<td>
								<img class='catImg' src='images/icons/2525".$idCategory.".png'></img>
									<div id='catTitle'>
										<h2 class='tCategory'>
											<a href='nav.php?idc=".$idCategory."'>".$row_subcat[0]['NomeCategoria']."</a>
										</h2>
									</div>

								<table class='sub-Category' cellspacing='0' cellpadding='0'>
									<tbody>";
			
			$i = 0;
			while ($i < $num_subcat) 
			{				
				$idSubCategoryy = $row_subcat[$i]['idSottoCategoriaProdotti'];	
				$nameSubCategory = $row_subcat[$i]['NomeSottoCategoria'];
				$nameCategory = $row_subcat[$i]['NomeCategoria'];
				
				
						if( ($i % 3) == 0 ) 
						{ 
							echo "<tr>
									<td><a href='nav.php?ids=".$idSubCategoryy."'>".$nameSubCategory."</a></td>"; 
						}
						elseif( ($i % 3) == 2 )
						{
							echo "<td><a href='nav.php?ids=".$idSubCategoryy."'>".$nameSubCategory."</a></td>
								</tr>"; 
						}
						else
						{
							echo "<td><a href='nav.php?ids=".$idSubCategoryy."'>".$nameSubCategory."</a></td>";
						}
				$i++;
			}
			// Aggiungo 1 o 2 colonne nel caso ci sia solo una sottocategoria per far tornare la grafica
			if( ($num_subcat % 3) == 1) { echo "<td></td><td></td></tr>"; }
			elseif( ($num_subcat % 3) == 2) { echo "<td></td></tr>";  }
			
			echo "</tbody></table></td></tr></tbody></table>";
				

		
		}
		elseif(isset($idSubCategory) AND !is_null($idSubCategory))
		{
			// Creo la tabella con le product category di quella determinata sottocategoria
			echo "<table class='all-products' cellspacing='0' cellpadding='0'>
					<caption class= 'navigator-pages'>".$row_prodcat[0]['NomeSottoCategoria']."</caption>
					<tbody>
						<tr>
							<td>
							<table class='sub-Category' cellspacing='0' cellpadding='0'>
								<tbody>";
		
			$i = 0;
			while ($i < $num_prodcat) 
			{				
				$idProdCategory = $row_prodcat[$i]['TipologiaProdotti_idTipologiaProdotti'];	
				$nameProdCategory = $row_prodcat[$i]['TipologiaProdotto'];				
				
				if( ($i % 3) == 0 ) 
				{ 
					echo "<tr>
							<td><a href='list.php?idpcat=".$idProdCategory."'>".$nameProdCategory."</a></td>"; 
				}
				elseif( ($i % 3) == 2 )
				{
					echo "<td><a href='list.php?idpcat=".$idProdCategory."'>".$nameProdCategory."</a></td>
						</tr>"; 
				}
				else
				{
					echo "<td><a href='list.php?idpcat=".$idProdCategory."'>".$nameProdCategory."</a></td>";
				}
				$i++;
			}							
			// Aggiungo 1 o 2 colonne nel caso ci sia solo una sottocategoria per far tornare la grafica
			if( ($num_prodcat % 3) == 1) { echo "<td></td><td></td></tr>"; }
			elseif( ($num_prodcat % 3) == 2) { echo "<td></td></tr>";  }	
			
			echo "</tbody></table></td></tr></tbody></table>";		
		
		}
		elseif(isset($idt) AND !is_null($idt))
		{
		
		}
		else
		{
			
			// Query per la determinazione di tutte le categorie presenti nel DB che abbiano ALMENO 1 prodotto
			$query_cat = "SELECT DISTINCT CategorieProdotti.* FROM CategorieProdotti, SottoCategorieProdotti, TipologiaProdotti, Prodotti WHERE CategorieProdotti.idCategorieProdotti = SottoCategorieProdotti.CategorieProdotti_idCategorieProdotti AND SottoCategorieProdotti.idSottoCategoriaProdotti = TipologiaProdotti.SottoCategorieProdotti_idSottoCategoriaProdotti AND Prodotti.TipologiaProdotti_idTipologiaProdotti = TipologiaProdotti.idTipologiaProdotti ORDER BY NomeCategoria ASC";

			$result_cat = mysqli_query($mysqli, $query_cat);  
			$num_cat = $result_cat->num_rows; 

			// Tabella completa
			echo "<table class='all-products' cellspacing='0' cellpadding='0'>
					<caption class= 'navigator-pages'>All Products</caption>
						<tbody>";
	 		$i=0;	
			while ($i < $num_cat) 
			{
				$row_cat = $result_cat->fetch_assoc();
				
				$idCategoryy = $row_cat['idCategorieProdotti'];	
				$nameCategory = $row_cat['NomeCategoria'];
				
				// Determino tutte le sottocategorie che hanno almeno 1 prodotto
				$query_subcat = "SELECT DISTINCT SottoCategorieProdotti.NomeSottoCategoria, SottoCategorieProdotti.idSottoCategoriaProdotti FROM SottoCategorieProdotti, TipologiaProdotti, Prodotti WHERE Prodotti.TipologiaProdotti_idTipologiaProdotti = TipologiaProdotti.idTipologiaProdotti AND SottoCategorieProdotti.idSottoCategoriaProdotti = TipologiaProdotti.SottoCategorieProdotti_idSottoCategoriaProdotti AND CategorieProdotti_idCategorieProdotti = ".$idCategoryy." ORDER by NomeSottoCategoria ASC";
				
				$result_subcat = mysqli_query($mysqli, $query_subcat);  
				$num_subcat = $result_subcat->num_rows; 
				
					echo"<tr>
						<td>
							<img class='catImg' src='images/icons/2525".$idCategoryy.".png' />
								<div id='catTitle'>
									<h2 class='tCategory'>
										<a href='nav.php?idc=".$idCategoryy."'>".$nameCategory."</a>
									</h2>
								</div>

							<table class='sub-Category' cellspacing='0' cellpadding='0'>
								<tbody>";
								
					$m = 0;
					while($m < $num_subcat)
					{
						$row_subcat = $result_subcat->fetch_assoc();
						$idSubCategory_1 = $row_subcat['idSottoCategoriaProdotti'];	
						$nameSubCategory = $row_subcat['NomeSottoCategoria'];
					
						if( ($m % 3) == 0 ) 
						{ 
							echo "<tr>
									<td><a href='nav.php?ids=".$idSubCategory_1."'>".$nameSubCategory."</a></td>"; 
						}
						elseif( ($m % 3) == 2 )
						{
							echo "<td><a href='nav.php?ids=".$idSubCategory_1."'>".$nameSubCategory."</a></td>
								</tr>"; 
						}
						else
						{
							echo "<td><a href='nav.php?ids=".$idSubCategory_1."'>".$nameSubCategory."</a></td>";
						}
						$m++; 
					}
					
					// Aggiungo 1 o 2 colonne nel caso ci sia solo una sottocategoria per far tornare la grafica
					if( ($num_subcat % 3) == 1) { echo "<td></td><td></td></tr>"; }
					elseif( ($num_subcat % 3) == 2) { echo "<td></td></tr>";  }
				echo "</tbody></table></td></tr>";
				
				$i++; 
			}
	
			echo "</tbody>
			</table>";
		}
	
	mysqli_close($mysqli); 
	?>	

		
	
	
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
		  				if( isset($idCategory) AND !is_null($idCategory) ) 
		  				{
		  					// Sono in navigazione tra i Manufacturer
		  					echo "<li><a href='nav.php?page=".$i."&idc=".$idCategory."'>".$i."</a></li>";
		  				}
		  				elseif( isset($idSubCategory) AND !is_null($idSubCategory) )
		  				{
		  					// Sono in navigazione tra i Product Category
		  					echo "<li><a href='nav.php?page=".$i."&ids=".$idSubCategory."'>".$i."</a></li>";
		  				}
		  				elseif( isset($idProductCategory) AND !is_null($idProductCategory) AND isset($idManufacturer) AND !is_null($idManufacturer) )
		  				{
		  					// Sono in navigazione sia tra i Manufacturer che tra i Product Category
		  					echo "<li><a href='nav.php?page=".$i."&idpcat=".$idProductCategory."&idmanu=".$idManufacturer."'>".$i."</a></li>";
		  				}
		  				else
		  				{
		  					// Non sono in navigazione in nessuna categoria
		  					echo "<li><a href='nav.php?page=".$i."'>".$i."</a></li>";
		  				}
		  				
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
		  				if(isset($idManufacturer) AND !is_null($idManufacturer) AND !isset($idProductCategory) ) 
		  				{
		  					// Sono in navigazione tra i Manufacturer
		  					echo "<li><a href='list.php?page=".$i."&idmanu=".$idManufacturer."'>".$i."</a></li>";
		  				}
		  				elseif( isset($idProductCategory) AND !is_null($idProductCategory)  AND !isset($idManufacturer)  )
		  				{
		  					// Sono in navigazione tra i Product Category
		  					echo "<li><a href='list.php?page=".$i."&idpcat=".$idProductCategory."'>".$i."</a></li>";
		  				}
		  				elseif( isset($idProductCategory) AND !is_null($idProductCategory) AND isset($idManufacturer) AND !is_null($idManufacturer) )
		  				{
		  					// Sono in navigazione sia tra i Manufacturer che tra i Product Category
		  					echo "<li><a href='list.php?page=".$i."&idpcat=".$idProductCategory."&idmanu=".$idManufacturer."'>".$i."</a></li>";
		  				}
		  				else
		  				{
		  					// Non sono in navigazione in nessuna categoria
		  					echo "<li><a href='list.php?page=".$i."'>".$i."</a></li>";
		  				}
		  			}
				}	
				echo "<li><a href='list.php?page=".$i."'>...</a>";
				echo "<li><a href='list.php?page=".$total_pages."'>".$total_pages."</a></li>";	
			}
			elseif($page >= 10 and (floor($page/10)*10) == (floor($total_pages/10)*10) )
			{
				// Non serve l'abbreviatore finale (...) ma solo l'iniziale
				echo "<li><a href='list.php?page=1'>1</a></li>";
				// Determino la prima pagina da stampare
				$index=floor($page/10)*10;
				echo "<li><a href='list.php?page=".($index-1)."'>...</a></li>";
				for($i=$index; $i<=($index + 9); $i++)
				{	
					if($page == $i) 
		  			{
		  				echo "<li><span class='paging-current'>$i</span></li>";
		  			}
		  			else
		  			{
		  				if(isset($idManufacturer) AND !is_null($idManufacturer) AND !isset($idProductCategory) ) 
		  				{
		  					// Sono in navigazione tra i Manufacturer
		  					echo "<li><a href='list.php?page=".$i."&idmanu=".$idManufacturer."'>".$i."</a></li>";
		  				}
		  				elseif( isset($idProductCategory) AND !is_null($idProductCategory)  AND !isset($idManufacturer)  )
		  				{
		  					// Sono in navigazione tra i Product Category
		  					echo "<li><a href='list.php?page=".$i."&idpcat=".$idProductCategory."'>".$i."</a></li>";
		  				}
		  				elseif( isset($idProductCategory) AND !is_null($idProductCategory) AND isset($idManufacturer) AND !is_null($idManufacturer) )
		  				{
		  					// Sono in navigazione sia tra i Manufacturer che tra i Product Category
		  					echo "<li><a href='list.php?page=".$i."&idpcat=".$idProductCategory."&idmanu=".$idManufacturer."'>".$i."</a></li>";
		  				}
		  				else
		  				{
		  					// Non sono in navigazione in nessuna categoria
		  					echo "<li><a href='list.php?page=".$i."'>".$i."</a></li>";
		  				}
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
				echo "<li><a href='list.php?page=1'>1</a></li>";
				// Determino la prima pagina da stampare
				$index=floor($page/10)*10;
				echo "<li><a href='list.php?page=".($index-1)."'>...</a></li>";
				for($i=$index; $i<=($index + 9); $i++)
				{	
					if($page == $i) 
		  			{
		  				echo "<li><span class='paging-current'>$i</span></li>";
		  			}
		  			else
		  			{
		  				if(isset($idManufacturer) AND !is_null($idManufacturer) AND !isset($idProductCategory) ) 
		  				{
		  					// Sono in navigazione tra i Manufacturer
		  					echo "<li><a href='list.php?page=".$i."&idmanu=".$idManufacturer."'>".$i."</a></li>";
		  				}
		  				elseif( isset($idProductCategory) AND !is_null($idProductCategory)  AND !isset($idManufacturer)  )
		  				{
		  					// Sono in navigazione tra i Product Category
		  					echo "<li><a href='list.php?page=".$i."&idpcat=".$idProductCategory."'>".$i."</a></li>";
		  				}
		  				elseif( isset($idProductCategory) AND !is_null($idProductCategory) AND isset($idManufacturer) AND !is_null($idManufacturer) )
		  				{
		  					// Sono in navigazione sia tra i Manufacturer che tra i Product Category
		  					echo "<li><a href='list.php?page=".$i."&idpcat=".$idProductCategory."&idmanu=".$idManufacturer."'>".$i."</a></li>";
		  				}
		  				else
		  				{
		  					// Non sono in navigazione in nessuna categoria
		  					echo "<li><a href='list.php?page=".$i."'>".$i."</a></li>";
		  				}
		  			}
	
					if($i == $total_pages) 
					{
						break;
					}
				}
				echo "<li><a href='list.php?page=".$i."'>...</a></li>";
				echo "<li><a href='list.php?page=".$total_pages."'>".$total_pages."</a></li>";	
			}
		  ?>
		</ul>
	</caption>
	<thead>
		<tr>
			<th><?php echo $lang['TABLE_PROD_MANUPARTNUM']; ?></th>
	    	<th><?php echo $lang['TABLE_PROD_MANU']; ?></th>
			<th><?php echo $lang['TABLE_PROD_DESCR']; ?></th>
			<th><?php echo $lang['TABLE_PROD_STOCK']; ?></th>
			<th><?php echo $lang['TABLE_PROD_PRODCAT']; ?></th>
			<th><?php echo $lang['TABLE_PROD_EDIT']; ?></th>
		</tr>
	</thead>
	<tbody>
	
	<?php
	 $i=0;	
	 while ($i < $num) {
	 	 
	 $idProd=$row[$i]['idProdotti'];
	 $idManu=$row[$i]['idProduttore']; 
	 $idSott=$row[$i]['idSottoCategoriaProdotti']; 
     $ManuPartNo=$row[$i]['ManufacturerPartNo'];
     $Manu=$row[$i]['nomeProduttore'];
     $Descr=$row[$i]['Description'];
     $StockAv=$row[$i]['PezziinMagazzino'];
     $ProdCate=$row[$i]['TipologiaProdotto'];
     $idPCat=$row[$i]['idTipologiaProdotti'];
     
     echo "<tr>
		<td><a href=\"product.php?idProd=".$idProd."\">".$ManuPartNo."</a></td>";
		
		if(isset($idProductCategory) AND !is_null($idProductCategory)  AND !isset($idManufacturer)  )
		{
			echo "<td><a href=\"list.php?idmanu=".$idManu."&idpcat=".$idPCat."\">".$Manu."</a></td>";
		}
		else
		{
			echo "<td><a href=\"list.php?idmanu=".$idManu."\">".$Manu."</a></td>";
		}

		
	echo "<td>".$Descr."</td>
		<td class=\"center\">".$StockAv."</td>";
		
		if(isset($idManufacturer) AND !is_null($idManufacturer) )
		{
			echo "<td><a href=\"list.php?idmanu=".$idManufacturer."&idpcat=".$idPCat."\">".$ProdCate."</a></td>"; 
		}
		else
		{
			echo "<td><a href=\"list.php?idpcat=".$idPCat."\">".$ProdCate."</a></td>"; 
		}
				
	echo "<td class=\"center\"><a href=\"edit.php?idProd=".$idProd."\"><i class=\"icon-pencil\"></i></a></td>
	</tr>";
	
      $i++;
 	}
	
	?>

	
	</tbody>
	<tfoot class="navigator-pages">
		<tr>
			<td colspan="6" id="navigation-pages">
				<ul>
				<li><?php echo $lang['TABLE_NAVI_PAGE'];  ?> &nbsp;</li>
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
		  				if( isset($idCategory) AND !is_null($idCategory) ) 
		  				{
		  					// Sono in navigazione tra i Manufacturer
		  					echo "<li><a href='nav.php?page=".$i."&idc=".$idCategory."'>".$i."</a></li>";
		  				}
		  				elseif( isset($idSubCategory) AND !is_null($idSubCategory) )
		  				{
		  					// Sono in navigazione tra i Product Category
		  					echo "<li><a href='nav.php?page=".$i."&ids=".$idSubCategory."'>".$i."</a></li>";
		  				}
		  				elseif( isset($idProductCategory) AND !is_null($idProductCategory) AND isset($idManufacturer) AND !is_null($idManufacturer) )
		  				{
		  					// Sono in navigazione sia tra i Manufacturer che tra i Product Category
		  					echo "<li><a href='nav.php?page=".$i."&idpcat=".$idProductCategory."&idmanu=".$idManufacturer."'>".$i."</a></li>";
		  				}
		  				else
		  				{
		  					// Non sono in navigazione in nessuna categoria
		  					echo "<li><a href='nav.php?page=".$i."'>".$i."</a></li>";
		  				}
		  				
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
		  				if(isset($idManufacturer) AND !is_null($idManufacturer) AND !isset($idProductCategory) ) 
		  				{
		  					// Sono in navigazione tra i Manufacturer
		  					echo "<li><a href='list.php?page=".$i."&idmanu=".$idManufacturer."'>".$i."</a></li>";
		  				}
		  				elseif( isset($idProductCategory) AND !is_null($idProductCategory)  AND !isset($idManufacturer)  )
		  				{
		  					// Sono in navigazione tra i Product Category
		  					echo "<li><a href='list.php?page=".$i."&idpcat=".$idProductCategory."'>".$i."</a></li>";
		  				}
		  				elseif( isset($idProductCategory) AND !is_null($idProductCategory) AND isset($idManufacturer) AND !is_null($idManufacturer) )
		  				{
		  					// Sono in navigazione sia tra i Manufacturer che tra i Product Category
		  					echo "<li><a href='list.php?page=".$i."&idpcat=".$idProductCategory."&idmanu=".$idManufacturer."'>".$i."</a></li>";
		  				}
		  				else
		  				{
		  					// Non sono in navigazione in nessuna categoria
		  					echo "<li><a href='list.php?page=".$i."'>".$i."</a></li>";
		  				}
		  			}
				}	
				echo "<li><a href='list.php?page=".$i."'>...</a>";
				echo "<li><a href='list.php?page=".$total_pages."'>".$total_pages."</a></li>";	
			}
			elseif($page >= 10 and (floor($page/10)*10) == (floor($total_pages/10)*10) )
			{
				// Non serve l'abbreviatore finale (...) ma solo l'iniziale
				echo "<li><a href='list.php?page=1'>1</a></li>";
				// Determino la prima pagina da stampare
				$index=floor($page/10)*10;
				echo "<li><a href='list.php?page=".($index-1)."'>...</a></li>";
				for($i=$index; $i<=($index + 9); $i++)
				{	
					if($page == $i) 
		  			{
		  				echo "<li><span class='paging-current'>$i</span></li>";
		  			}
		  			else
		  			{
		  				if(isset($idManufacturer) AND !is_null($idManufacturer) AND !isset($idProductCategory) ) 
		  				{
		  					// Sono in navigazione tra i Manufacturer
		  					echo "<li><a href='list.php?page=".$i."&idmanu=".$idManufacturer."'>".$i."</a></li>";
		  				}
		  				elseif( isset($idProductCategory) AND !is_null($idProductCategory)  AND !isset($idManufacturer)  )
		  				{
		  					// Sono in navigazione tra i Product Category
		  					echo "<li><a href='list.php?page=".$i."&idpcat=".$idProductCategory."'>".$i."</a></li>";
		  				}
		  				elseif( isset($idProductCategory) AND !is_null($idProductCategory) AND isset($idManufacturer) AND !is_null($idManufacturer) )
		  				{
		  					// Sono in navigazione sia tra i Manufacturer che tra i Product Category
		  					echo "<li><a href='list.php?page=".$i."&idpcat=".$idProductCategory."&idmanu=".$idManufacturer."'>".$i."</a></li>";
		  				}
		  				else
		  				{
		  					// Non sono in navigazione in nessuna categoria
		  					echo "<li><a href='list.php?page=".$i."'>".$i."</a></li>";
		  				}
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
				echo "<li><a href='list.php?page=1'>1</a></li>";
				// Determino la prima pagina da stampare
				$index=floor($page/10)*10;
				echo "<li><a href='list.php?page=".($index-1)."'>...</a></li>";
				for($i=$index; $i<=($index + 9); $i++)
				{	
					if($page == $i) 
		  			{
		  				echo "<li><span class='paging-current'>$i</span></li>";
		  			}
		  			else
		  			{
		  				if(isset($idManufacturer) AND !is_null($idManufacturer) AND !isset($idProductCategory) ) 
		  				{
		  					// Sono in navigazione tra i Manufacturer
		  					echo "<li><a href='list.php?page=".$i."&idmanu=".$idManufacturer."'>".$i."</a></li>";
		  				}
		  				elseif( isset($idProductCategory) AND !is_null($idProductCategory)  AND !isset($idManufacturer)  )
		  				{
		  					// Sono in navigazione tra i Product Category
		  					echo "<li><a href='list.php?page=".$i."&idpcat=".$idProductCategory."'>".$i."</a></li>";
		  				}
		  				elseif( isset($idProductCategory) AND !is_null($idProductCategory) AND isset($idManufacturer) AND !is_null($idManufacturer) )
		  				{
		  					// Sono in navigazione sia tra i Manufacturer che tra i Product Category
		  					echo "<li><a href='list.php?page=".$i."&idpcat=".$idProductCategory."&idmanu=".$idManufacturer."'>".$i."</a></li>";
		  				}
		  				else
		  				{
		  					// Non sono in navigazione in nessuna categoria
		  					echo "<li><a href='list.php?page=".$i."'>".$i."</a></li>";
		  				}
		  			}
	
					if($i == $total_pages) 
					{
						break;
					}
				}
				echo "<li><a href='list.php?page=".$i."'>...</a></li>";
				echo "<li><a href='list.php?page=".$total_pages."'>".$total_pages."</a></li>";	
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

<script>
	$( "#navigation-pages a" ).mouseover(function() {

		$(this).addClass('scrollover');
	
	});
	$( "#navigation-pages a" ).mouseout(function() {

		$(this).removeClass('scrollover');
	});
</script>

<!-- ===================================== START FOOTER ===================================== -->
<?php get_footer(); ?>

</body>
</html>

