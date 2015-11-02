<?php
/**
 * Electronics Components Inventory - ECI
 *
 * Index
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
 
$query="SELECT Prodotti.idProdotti, Prodotti.ManufacturerPartNo, Prodotti.Description, Prodotti.PezziinMagazzino, Prodotti.DataUltimaModifica, Produttore.nomeProduttore, Produttore.idProduttore, TipologiaProdotti.idTipologiaProdotti, TipologiaProdotti.TipologiaProdotto FROM Prodotti, Produttore, TipologiaProdotti WHERE Produttore.idProduttore = Prodotti.Produttore_idProduttore AND TipologiaProdotti.idTipologiaProdotti = Prodotti.TipologiaProdotti_idTipologiaProdotti order by DataUltimaModifica desc limit ". $NUMBER_OF_ROWS_INDEX."";
 
// Query per la popolazione del menu
$queryMenu = "SELECT DISTINCT CategorieProdotti.idCategorieProdotti, CategorieProdotti.NomeCategoria, SottoCategorieProdotti.idSottoCategoriaProdotti, SottoCategorieProdotti.NomeSottoCategoria, TipologiaProdotti.TipologiaProdotto, TipologiaProdotti.idTipologiaProdotti FROM SottoCategorieProdotti, CategorieProdotti, TipologiaProdotti, Prodotti WHERE TipologiaProdotti.idTipologiaProdotti = Prodotti.TipologiaProdotti_idTipologiaProdotti AND SottoCategorieProdotti.idSottoCategoriaProdotti = TipologiaProdotti.SottoCategorieProdotti_idSottoCategoriaProdotti AND CategorieProdotti.idCategorieProdotti = SottoCategorieProdotti.CategorieProdotti_idCategorieProdotti ORDER BY NomeCategoria, NomeSottoCategoria";

$result=mysqli_query($mysqli, $query);  
$num=$result->num_rows;
 
$resultMenu=mysqli_query($mysqli, $queryMenu);  
$numMenu=$resultMenu->num_rows;

mysqli_close($mysqli);

 
?>


<div class="grid"><br />
<div id="icon-description" class="clearfix">
	<!-- FIRST COLUMN-->
	<div class="col_3" style="margin-top:0px;">
		<!-- Menu Vertical Left -->
		<div id="navMenu" >
			<div class="navMenuheader"><a href="nav.php"><?php echo $lang['MENU_HEADER']; ?></a></div>
			<?php
			$i = 0;
			while($i < $numMenu)
			{ 
				$row = $resultMenu->fetch_assoc();
				
				$idCategory[$i] = $row['idCategorieProdotti'];	
				$nameCategory[$i] = $row['NomeCategoria'];
				$idSubCategory[$i] = $row['idSottoCategoriaProdotti'];	
				$nameSubCategory[$i] = $row['NomeSottoCategoria'];
				$idTipo[$i] = $row['idTipologiaProdotti'];
				$nameTipo[$i] = $row['TipologiaProdotto'];

				if($i == 0)
				{
					echo "<ul class=\"navMenuList\">	
					<li><a href=\"nav.php?idc=".$idCategory[$i]."\">".$nameCategory[$i]."</a>
					<ul>
						<div class=\"navMenuheader\"><a href=\"nav.php?idc=".$idCategory[$i]."\">".$nameCategory[$i]."</a></div>
						<li><a href=\"nav.php?ids=".$idSubCategory[$i]."\">".$nameSubCategory[$i]."</a>
					<ul>
						<div class=\"navMenuheader\"><a href=\"nav.php?ids=".$idSubCategory[$i]."\">".$nameSubCategory[$i]."</a></div>
						<li><a href=\"list.php?idpcat=".$idTipo[$i]."\">".$nameTipo[$i]."</a>
					";	
				}
				else
				{
					if( $idCategory[$i] == $idCategory[$i - 1] )
					{
						if( $idSubCategory[$i] == $idSubCategory[$i - 1] )
						{
							if( $idTipo[$i] == $idTipo[$i - 1] )
							{
								echo "ERROR DB. 2 or MORE ITEMS IN SAME SUBCATEGORY WITH SAME Product Category!!!";
							}
							else
							{
								echo "</li><li><a href=\"list.php?idpcat=".$idTipo[$i]."\">".$nameTipo[$i]."</a>";
							}
						
						}
						else
						{
							echo "</li></ul>
							</li>
								<li><a href=\"nav.php?ids=".$idSubCategory[$i]."\">".$nameSubCategory[$i]."</a>
								<ul>
								<div class=\"navMenuheader\"><a href=\"nav.php?ids=".$idSubCategory[$i]."\">".$nameSubCategory[$i]."</a></div>
									<li><a href=\"list.php?idpcat=".$idTipo[$i]."\">".$nameTipo[$i]."</a>
								";
						}
					}
					else
					{
						echo "
						</li>
						</ul>
						</li>
						</ul>
						</li>
							<li><a href=\"nav.php?idc=".$idCategory[$i]."\">".$nameCategory[$i]."</a>
						<ul>
							<div class=\"navMenuheader\"><a href=\"nav.php?idc=".$idCategory[$i]."\">".$nameCategory[$i]."</a></div>
							<li><a href=\"nav.php?ids=".$idSubCategory[$i]."\">".$nameSubCategory[$i]."</a>
						<ul>
							<div class=\"navMenuheader\"><a href=\"nav.php?ids=".$idSubCategory[$i]."\">".$nameSubCategory[$i]."</a></div>
							<li><a href=\"list.php?idpcat=".$idTipo[$i]."\">".$nameTipo[$i]."</a>
							";	
					}
				}
				
				$i++;
			}
			echo "</ul>
			</ul>
			</ul>";
			?>
			
	</div>
	<!-- End Vertical Menu -->
	
	</div>
	<!-- SECOND COLUMN-->
	<div class="col_9" style="margin-top:0px;">
		
	<table class="sortable" cellspacing="0" cellpadding="0">
	<caption class="navigator-pages"><strong><?php echo $lang['TABLE_INDEX_HEADER']; ?></strong></caption>
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
	$i = 0;
	 while ($i < $num) {
	 
	 $row=$result->fetch_assoc();  
	 
	 $idProd=$row['idProdotti'];
	 $idManu=$row['idProduttore']; 
	 $idProdCat=$row['idTipologiaProdotti']; 
     $ManuPartNo=$row['ManufacturerPartNo'];
     $Manu=$row['nomeProduttore'];
     $Descr=$row['Description'];
     $StockAv=$row['PezziinMagazzino'];
     $ProdCate=$row['TipologiaProdotto'];
     
     echo "<tr>
		<td><a href=\"product.php?idProd=".$idProd."\">".$ManuPartNo."</a></td>
		<td><a href=\"list.php?idmanu=".$idManu."\">".$Manu."</a></td>
		<td>".$Descr."</td>
		<td class=\"center\">".$StockAv."</td>
		<td><a href=\"list.php?idpcat=".$idProdCat."\">".$ProdCate."</a></td>
		<td class=\"center\"><a href=\"edit.php?idProd=".$idProd."\"><i class=\"icon-pencil\"></i></a></td>
	</tr>";
      $i++;
     
 	}
	
	?>
	
	</tbody>
	</table>
	</div>
		
	</div>
	<br />
</div> <!-- End Grid -->


<!-- ===================================== START FOOTER ===================================== -->
<?php get_footer(); ?>

</body>
</html>
