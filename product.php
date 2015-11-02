<?php
/**
 * Electronics Components Inventory - ECI
 *
 * Product
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


if( isset($_GET['idProd']) AND is_numeric($_GET['idProd']) )
{
	$idProdotto = $_GET['idProd'];
	
	// Connessiona al database
	$mysqli = mysqli_connect($server, $username, $password, $database) or die("Error:" . mysqli_error($link));	
	
	$query="SELECT Prodotti.*, Produttore.nomeProduttore, Produttore.idProduttore, SottoCategorieProdotti.idSottoCategoriaProdotti, SottoCategorieProdotti.NomeSottoCategoria, CategorieProdotti.NomeCategoria, CategorieProdotti.idCategorieProdotti, TerminationsStyle.TerminationStyle, Magazzino.Nome, Magazzino.idMagazzino, Magazzino.Settore, Magazzino.Scaffale, Magazzino.Identificazione, Magazzino.Descrizione, Magazzino.Piano, TipologiaProdotti.TipologiaProdotto FROM Prodotti, Produttore, SottoCategorieProdotti, CategorieProdotti, TerminationsStyle, Magazzino, TipologiaProdotti WHERE Produttore.idProduttore = Prodotti.Produttore_idProduttore AND SottoCategorieProdotti.idSottoCategoriaProdotti = TipologiaProdotti.SottoCategorieProdotti_idSottoCategoriaProdotti AND CategorieProdotti.idCategorieProdotti = SottoCategorieProdotti.CategorieProdotti_idCategorieProdotti AND Magazzino.idMagazzino = Prodotti.Magazzino_idMagazzino AND TerminationsStyle.idTerminationsStyle = Prodotti.TerminationsStyle_idTerminationsStyle AND TipologiaProdotti.idTipologiaProdotti = Prodotti.TipologiaProdotti_idTipologiaProdotti AND Prodotti.idProdotti = '".$idProdotto."'";
	 
	$result= mysqli_query($mysqli, $query) or die("500 Internal Error");
	$row=$result->fetch_assoc();  

	//$rq=mysql_query($query);
	//$num=mysql_numrows($rq);
	
	$ManufacturerPartNo = $row['ManufacturerPartNo']; 
	$Description=$row['Description']; 
	$ProdCategory=$row['TipologiaProdotto'];
	$PezziinMagazzino=$row['PezziinMagazzino']; 
	$URLFoto=$row['URLFoto']; 
	$DataInserimento=$row['DataInserimento']; 
	$DataUltimaModifica=$row['DataUltimaModifica']; 
	$idCategoria=$row['idCategorieProdotti']; 
	$idSottoCategoria=$row['idSottoCategoriaProdotti']; 
	$SottoCategoria=$row['NomeSottoCategoria']; 
	$idTerminationStyle=$row['TerminationsStyle_idTerminationsStyle']; 
	$TerminationStyle=$row['TerminationStyle']; 
	$idProduttore=$row['Produttore_idProduttore']; 
	$Produttore=$row['nomeProduttore']; 
	$nameDepot=$row['Nome'];
	$sectDepot=$row['Settore'];
	$shelfDepot=$row['Scaffale'];
	$trackNumDepot=$row['Identificazione'];
	$depotFloor = $row['Piano'];
	$depotDescription = $row['Descrizione'];
	$categoria=$row['NomeCategoria']; 
	$MyNote=$row['MyNote']; 
	$VendorPartNo=$row['VendorPartNo'];
	$VendorDetailURL=$row['VendorURL'];
	
	$query="select InfoFattura.*, FattureAcquisti.* ,Fornitori.NomeFornitore, Fornitori.Sito from InfoFattura, FattureAcquisti, Fornitori where Prodotti_idProdotti = ".$idProdotto." and FattureAcquisti_idFatture = FattureAcquisti.idFatture AND Fornitori.idFornitori = FattureAcquisti.Fornitori_idFornitori order by DataFattVendor DESC";
	
	$result_2= mysqli_query($mysqli, $query) or die("500 Internal Error");
		
	if($result_2->num_rows)
	{
		$row=$result_2->fetch_assoc(); 
		$lastInvoiceNumber=$row['NumFattVendor']; 
		$lastInvoiceNumberR=$row['NumFattRegistrata'];
		$lastInvoicePrice=$row['PrezzodiAcquisto'];
		$lastInvoiceDate=$row['DataFattVendor']; 
		$lastInvoiceFornitoreR=$row['DataFattRegistrata'];
		$lastInvoiceFornitore=$row['NomeFornitore']; 
	}
	
	$query="select Documenti.* from Documenti, Documenti_has_Prodotti where Documenti_has_Prodotti.Documenti_idDocumenti = Documenti.idDocumenti AND Documenti_has_Prodotti.Prodotti_idProdotti = ".$idProdotto." order by DataInserimento DESC";
	
	$result_3= mysqli_query($mysqli, $query) or die("500 Internal Error");
	
	mysqli_close($mysqli);
}

else
{
	header("Location: list.php"); 
}

get_header();

?>


<div class="grid"><br />
 <div id="icon-description" class="clearfix">
	 <div class="col_12" style="margin-top:0px;">
	
		 <!-- Breadcrumbs -->
		 <ul class="breadcrumbs">
			 <li><a href="nav.php"><?php echo $lang['BREAD_ALL']; ?></a></li>
			 <li><a href="nav.php?idc=<?php echo $idCategoria ?>"><?php echo $categoria; ?></a></li>
			 <li><a href="nav.php?ids=<?php echo $idSottoCategoria ?>"><?php echo $SottoCategoria; ?></a></li>
			 <li><span class="last-breadcrumb"><?php echo $ProdCategory; ?></span></li>
		 </ul>
	
		 <div class="col_9">
		 	<!-- Gallery -->
		 	<div class="col_4">
			 	<a href="<?php echo $URLFoto; ?>" >
			 		<img id="product_image" src="<?php echo $URLFoto; ?>" />
			 	</a>
			 	 <p style="font-size:8pt; line-height:2pt; margin-top:160px; margin-left:-5px;">&nbsp;&nbsp;<?php echo $lang['PRODUCT_NOTICE_IMM1']; ?></p>
			 	 <p style="font-size:8pt; line-height:2pt; margin-left:-5px;">&nbsp;&nbsp;<?php echo $lang['PRODUCT_NOTICE_IMM2']; ?> </p>
		 	</div>
		 	
		 	<div class="col_3">
		 	<p style="font-size:10pt;"><strong><?php echo $lang['TABLE_PROD_MANUPARTNUM']; ?>:</strong></p>
		 	<p style="font-size:10pt;"><strong><?php echo $lang['TABLE_PROD_MANU']; ?>:</strong></p>
		 	<p style="font-size:10pt;"><strong><?php echo $lang['TABLE_PROD_DESCR']; ?>:</strong></p>
		
		 	</div>
		 	
		 	<div class="col_5">
		 	<p style="font-size:10pt;"><?php echo $ManufacturerPartNo; ?></p>
		 	<p style="font-size:10pt;"><?php echo $Produttore; ?></p>
		 	<p style="font-size:10pt;"><?php echo $Description; ?></p>
	
		 	</div>
		 
		 	<!-- Tabs Left -->
		 	<ul class="tabs left">
			 	<li><a href="#tabr1"><strong><?php echo $lang['PRODUCT_TABLE_SPECIF']; ?></strong></a></li>
			 	<li><a href="#tabr2"><strong><?php echo $lang['PRODUCT_TABLE_DOCU']; ?></strong></a></li>
			 	<li><a href="#tabr3"><strong><?php echo $lang['PRODUCT_TABLE_INVOICE']; ?></strong></a></li>
			 	<li><a href="#tabr4"><strong><?php echo $lang['PRODUCT_TABLE_MYNOTES']; ?></strong></a></li>
			</ul>

			<div id="tabr1" class="tab-content">
				<table class="sortable" cellspacing="0" cellpadding="0">
				 	<tbody>
						<tr style="font-size:10pt; line-height:6pt;"><td width="45%"><strong><?php echo $lang['TABLE_PROD_MANUPARTNUM']; ?>:</strong></td>
						<td width="55%"><?php echo $ManufacturerPartNo; ?></td></tr>
						<tr style="font-size:10pt; line-height:6pt;"><td width="45%"><strong><?php echo $lang['PRODUCT_TABLE_VENDORPARTNUM']; ?>:</strong></td>
						<td width="55%"><?php echo $VendorPartNo; ?></td></tr>
						<tr style="background-color:#efefef; font-size:10pt; line-height:6pt;"><td width="45%"><strong><?php echo $lang['TABLE_PROD_PRODCAT']; ?>:</strong></td>
						<td width="55%"><?php echo $ProdCategory; ?></td></tr>
						<tr style="font-size:10pt; line-height:6pt;"><td width="45%"><strong><?php echo $lang['TABLE_PROD_MANU']; ?>:</strong></td>
						<td width="55%"><?php echo $Produttore; ?></td></tr>
						<tr style="background-color:#efefef; font-size:10pt; line-height:6pt;"><td width="45%"><strong><?php echo $lang['PRODUCT_TABLE_TERMSTYLE']; ?>:</strong></td>
						<td width="55%"><?php echo $TerminationStyle; ?></td></tr>
						<tr style="font-size:10pt; line-height:6pt;"><td width="45%"><strong><?php echo $lang['PRODUCT_TABLE_VENDORURL']; ?>:</strong></td>
						<td width="55%"><a href="<?php echo $VendorDetailURL; ?>" target="_blank"><?php echo $ManufacturerPartNo; ?></a></td></tr>
						<tr style="background-color:#efefef; font-size:10pt; line-height:6pt;"><td width="45%"><strong><?php echo $lang['PRODUCT_TABLE_DATEINSERT']; ?>:</strong></td>
						<td width="55%"><?php echo date('d-m-Y', strtotime($DataInserimento)); ?></td></tr>
					</tbody>
				</table>
			</div>
			<div id="tabr2" class="tab-content">
				<table class="sortable" cellspacing="0" cellpadding="0">
			 		<thead>
			 			<tr><th width="60%" style="background-color:#efefef; font-size:10pt; line-height:15pt;">Title</th>
			 			<th width="10%" style="background-color:#efefef; font-size:10pt; line-height:15pt;">Type</th>
			 			<th width="13%" style="background-color:#efefef; font-size:10pt; line-height:15pt;">Size (KB)</th>
			 			<th width="17%" style="background-color:#efefef; font-size:10pt; line-height:15pt;">Date</th></tr>
				 	</thead>
				 	<tbody>
						<?php
						$i=0;
						while ($i < $result_3->num_rows) {
						
							$row=$result_3->fetch_assoc(); 
							
							$Nome=$row['Titolo']; 
	 
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
							</tr>";
						$i++;
						}
						?>
					</tbody>
				</table>
			</div>
			
			<div id="tabr3" class="tab-content">
				<table class="sortable" cellspacing="0" cellpadding="0">
			 	<thead>
			 		<tr>
				 		<th width="30%" style="font-size:10pt;"><?php echo $lang['PRODUCT_TABLE_INVOICENUM']; ?>:</th>
				 		<th width="30%" style="font-size:10pt;"><?php echo $lang['PRODUCT_TABLE_SUPPLIER']; ?>:</th>
				 		<th width="20%" style="font-size:10pt;"><?php echo $lang['PRODUCT_TABLE_DATE']; ?>:</th>
				 		<th width="20%" style="font-size:10pt;"><?php echo $lang['PRODUCT_TABLE_PRICE']; ?>:</th>
				 	</tr>
				</thead>
				<tbody>
									
					<?php
						$i=0;
						// Pointer reset 
						$result_2->data_seek(0);
						while ($i < $result_2->num_rows) {
							
							$row=$result_2->fetch_assoc(); 
	 
							$PezziAcq=$row['PezziAcquistati']; 
							$DatadiAcq=$row['DataFattVendor'];
							$PrezzidiAcq=$row['PrezzodiAcquisto']; 
							$Desc=$row['ExtraInfo']; 
							$NumeroFatt=$row['NumFattVendor']; 
							$Motivo=$row['MotivodelAcquisto']; 
							$NomeF=$row['NomeFornitore']; 
							$Sito=$row['Sito']; 
							$linkFatt=$row['URLPdf'];
     
						echo "<tr>
								<td style=\"padding-bottom:0px;\"><a href=\"".$linkFatt."\">".$NumeroFatt."</a></td>
								<td style=\"padding-bottom:0px;\"><a href=\"".$Sito."\">".$NomeF."</a></td>
								<td style=\"padding-bottom:0px;\">".$DatadiAcq."</td>
								<td style=\"padding-bottom:0px;\">".$PrezzidiAcq." ".$lang['PRODUCT_CURRENCY']."</td>
							</tr>
							<tr style=\"border-top:solid 1px #FFF;\">
								<td style=\"padding-top:0px; padding-bottom:0px;\">
									<p style=\"font-size:10pt; line-height:1pt; padding:0px;\"><strong>".$lang['PRODUCT_TABLE_PURPARTS'].": </strong></p>
								</td>
								<td colspan=\"3\" style=\"padding-top:0px; padding-bottom:0px;\"><p style=\"font-size:9pt; line-height:1pt; padding:0px;\">".$PezziAcq."</p></td>
							</tr>
							<tr style=\"border-top:solid 1px #FFF;\">
								<td style=\"padding-top:0px; padding-bottom:0px;\"><p style=\"font-size:10pt; line-height:1pt;\"><strong>".$lang['PRODUCT_TABLE_PUR_REASON'].":</strong></p></td>
								<td colspan=\"3\" style=\"padding-top:0px; padding-bottom:0px;\"><p style=\"font-size:9pt; line-height:1pt;\">".$Motivo."</p></td>
							</tr>
							<tr style=\"border-top:solid 1px #FFF;\">
								<td style=\"padding-top:0px; padding-bottom:0px;\"><p style=\"font-size:10pt; line-height:1pt;\"><strong>".$lang['PRODUCT_TABLE_NOTE'].":</strong></p></td>
								<td colspan=\"3\" style=\"padding-top:0px; padding-bottom:0px;\"><p style=\"font-size:9pt; line-height:1pt;\">".$Desc."</p></td>
							</tr>";
						$i++;
     
						}
					?>
										
				</tbody>
		 	</table>
			</div>
			
			<div id="tabr4" class="tab-content">
				<p style="font-size:10pt; line-height:2pt;"><?php echo $MyNote; ?></p>
			</div>
	
		 </div>
	
		 <div class="col_3">
		 	<table class="sortable" cellspacing="0" cellpadding="0">
			 	<thead>
			 		<tr>
				 		<th style="background-color:#efefef;"><?php echo $lang['PRODUCT_REAL_AVAIL']; ?></th>
				 	</tr>
				</thead>
				<tbody>
					<tr><td><b><?php echo $lang['PRODUCT_STOCK']; ?>: </b><?php echo $PezziinMagazzino; ?><p style="font-size:9pt; line-height:2pt;"><strong><?php echo $lang['PRODUCT_LAST_MOD']; ?>: </strong><?php echo date('d-m-Y', strtotime($DataUltimaModifica)); ?></p></td></tr>
				</tbody>
		 	</table>
		 	<table class="sortable" cellspacing="0" cellpadding="0">
			 	<thead>
			 		<tr>
				 		<th style="background-color:#efefef;">Last Invoice</th>
				 	</tr>
				</thead>
				<tbody>
					<tr>
						<td><b>Number:</b> <?php if(isset($lastInvoiceNumber)) { echo $lastInvoiceNumber; } ?>
						<p style="font-size:9pt; line-height:4pt;"><strong><?php echo $lang['PRODUCT_VENDOR']; ?>: </strong><?php if(isset($lastInvoiceFornitore)) { echo $lastInvoiceFornitore; } ?></p>
						<p style="font-size:9pt; line-height:4pt;"><strong><?php echo $lang['PRODUCT_TABLE_PRICE']; ?>: </strong><?php if(isset($lastInvoicePrice)) { echo $lastInvoicePrice; } ?> <?php echo $lang['PRODUCT_CURRENCY']; ?></p>
						<p style="font-size:9pt; line-height:4pt;"><strong><?php echo $lang['PRODUCT_TABLE_DATE']; ?>: </strong><?php if(isset($lastInvoiceDate)) { echo date('d-m-Y', strtotime($lastInvoiceDate)); } ?></p>
						</td>
					</tr>
				</tbody>
		 	</table>
		 	<table class="sortable" cellspacing="0" cellpadding="0">
			 	<thead>
			 		<tr>
				 		<th style="background-color:#efefef;"><?php echo $lang['PRODUCT_LOCATION']; ?></th>
				 	</tr>
				</thead>
				<tbody>
					<tr>
						<td><b><?php echo $lang['PRODUCT_DEPOT_NAME']; ?>: </b><?php echo $nameDepot; ?>
						<p style="font-size:9pt; line-height:4pt;"><b><?php echo $lang['PRODUCT_DEPOT_TRACKN']; ?>: </b><?php echo $trackNumDepot; ?></p>
						<p style="font-size:9pt; line-height:4pt;"><b><?php echo $lang['PRODUCT_DEPOT_FLOOR']; ?>: </b><?php echo $depotFloor; ?></p>
						<p style="font-size:9pt; line-height:4pt;"><b><?php echo $lang['PRODUCT_DEPOT_SECTION']; ?>: </b><?php echo $sectDepot; ?></p>
						<p style="font-size:9pt; line-height:4pt;"><b><?php echo $lang['PRODUCT_DEPOT_SHELF']; ?>: </b><?php echo $shelfDepot; ?></p>
						<p style="font-size:9pt; line-height:4pt;"><b><?php echo $lang['PRODUCT_DEPOT_DESCRIPTION']; ?>: </b><?php echo $depotDescription; ?></p>
					</tr>
				</tbody>
		 	</table>


		 </div>
		
		
	</div>
		
 </div>
 <br />
</div> <!-- End Grid -->


<!-- ===================================== START FOOTER ===================================== -->
<?php get_footer(); ?>

</body>
</html>
