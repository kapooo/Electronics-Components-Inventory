<?php
/**
 * Electronics Components Inventory - ECI
 *
 * Install 
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
		
$mysqli = mysqli_connect($server, $username, $password, $database) or die("Error:" . mysqli_error($link));

/*
-- -----------------------------------------------------
-- Database Creation
-- -----------------------------------------------------
*/
//$query = "CREATE SCHEMA IF NOT EXISTS `".$database."` DEFAULT CHARACTER SET utf8";
//mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 1"); 

/*
-- -----------------------------------------------------
-- Table `CategorieProdotti`
-- -----------------------------------------------------
*/
$query ="CREATE TABLE IF NOT EXISTS `".$database."`.`CategorieProdotti` (
  `idCategorieProdotti` INT(11) NOT NULL AUTO_INCREMENT,
  `NomeCategoria` VARCHAR(65) NOT NULL COMMENT '	',
  `Descrizione` VARCHAR(100) NULL DEFAULT NULL,
  `Immagine` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`idCategorieProdotti`))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 2". mysqli_error($mysqli)); 

/*
-- -----------------------------------------------------
-- Table `Magazzino`
-- -----------------------------------------------------
*/
$query = "CREATE TABLE IF NOT EXISTS `".$database."`.`Magazzino` (
  `idMagazzino` INT(11) NOT NULL AUTO_INCREMENT,
  `Nome` VARCHAR(50) NOT NULL,
  `Settore` VARCHAR(45) NULL,
  `Scaffale` VARCHAR(45) NULL,
  `Piano` VARCHAR(45) NULL,
  `Identificazione` VARCHAR(45) NULL,
  `Descrizione` VARCHAR(45) NULL,
  `Extra` VARCHAR(45) NULL,
  PRIMARY KEY (`idMagazzino`))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 3"); 

/*
-- -----------------------------------------------------
-- Table `Documenti`
-- -----------------------------------------------------
*/
$query="CREATE TABLE IF NOT EXISTS `".$database."`.`Documenti` (
  `idDocumenti` INT(11) NOT NULL AUTO_INCREMENT,
  `Titolo` VARCHAR(200) NOT NULL,
  `URLLink` VARCHAR(100) NOT NULL,
  `DataInserimento` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Descrizione` VARCHAR(200) NULL DEFAULT NULL,
  `SizeKB` FLOAT NULL DEFAULT NULL,
  `Type` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`idDocumenti`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 4"); 
/*
-- -----------------------------------------------------
-- Table `Produttore`
-- -----------------------------------------------------
*/
$query="CREATE TABLE IF NOT EXISTS `".$database."`.`Produttore` (
  `idProduttore` INT(11) NOT NULL AUTO_INCREMENT,
  `NomeProduttore` VARCHAR(45) NOT NULL,
  `Sito` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`idProduttore`))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 5"); 

/*
-- -----------------------------------------------------
-- Table `TerminationsStyle`
-- -----------------------------------------------------
*/
$query="CREATE TABLE IF NOT EXISTS `".$database."`.`TerminationsStyle` (
  `idTerminationsStyle` INT(11) NOT NULL AUTO_INCREMENT,
  `TerminationStyle` VARCHAR(100) NOT NULL,
  `Descrizione` VARCHAR(100) NULL DEFAULT NULL,
  PRIMARY KEY (`idTerminationsStyle`))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 6"); 

/*
-- -----------------------------------------------------
-- Table `SottoCategorieProdotti`
-- -----------------------------------------------------
*/
$query="CREATE TABLE IF NOT EXISTS `".$database."`.`SottoCategorieProdotti` (
  `idSottoCategoriaProdotti` INT(11) NOT NULL AUTO_INCREMENT,
  `NomeSottoCategoria` VARCHAR(65) NOT NULL,
  `Descrizione` VARCHAR(100) NULL DEFAULT NULL,
  `CategorieProdotti_idCategorieProdotti` INT(11) NOT NULL,
  PRIMARY KEY (`idSottoCategoriaProdotti`, `CategorieProdotti_idCategorieProdotti`),
  INDEX `fk_SottoCategorieProdotti_CategorieProdotti1_idx` (`CategorieProdotti_idCategorieProdotti` ASC),
  CONSTRAINT `fk_SottoCategorieProdotti_CategorieProdotti1`
    FOREIGN KEY (`CategorieProdotti_idCategorieProdotti`)
    REFERENCES `".$database."`.`CategorieProdotti` (`idCategorieProdotti`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 7"); 

/*
-- -----------------------------------------------------
-- Table `TipologiaProdotti`
-- -----------------------------------------------------
*/
$query="CREATE TABLE IF NOT EXISTS `".$database."`.`TipologiaProdotti` (
  `idTipologiaProdotti` INT NOT NULL AUTO_INCREMENT,
  `TipologiaProdotto` VARCHAR(65) NOT NULL,
  `Descrizione` VARCHAR(100) NULL,
  `SottoCategorieProdotti_idSottoCategoriaProdotti` INT(11) NOT NULL,
  PRIMARY KEY (`idTipologiaProdotti`, `SottoCategorieProdotti_idSottoCategoriaProdotti`),
  UNIQUE INDEX `TipologiaProdotto_UNIQUE` (`TipologiaProdotto` ASC),
  INDEX `fk_TipologiaProdotti_SottoCategorieProdotti1_idx` (`SottoCategorieProdotti_idSottoCategoriaProdotti` ASC),
  CONSTRAINT `fk_TipologiaProdotti_SottoCategorieProdotti1`
    FOREIGN KEY (`SottoCategorieProdotti_idSottoCategoriaProdotti`)
    REFERENCES `".$database."`.`SottoCategorieProdotti` (`idSottoCategoriaProdotti`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 8"); 

/*
-- -----------------------------------------------------
-- Table Prodotti`
-- -----------------------------------------------------
*/
$query="CREATE TABLE IF NOT EXISTS `".$database."`.`Prodotti` (
  `idProdotti` INT(11) NOT NULL AUTO_INCREMENT,
  `ManufacturerPartNo` VARCHAR(100) NOT NULL,
  `Description` VARCHAR(200) NOT NULL,
  `PezziinMagazzino` INT(11) NOT NULL,
  `DataInserimento` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DataUltimaModifica` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `URLFoto` VARCHAR(100) NULL DEFAULT NULL,
  `MyNote` VARCHAR(500) NULL DEFAULT NULL,
  `TerminationsStyle_idTerminationsStyle` INT(11) NOT NULL,
  `Produttore_idProduttore` INT(11) NOT NULL,
  `VendorURL` VARCHAR(500) NULL,
  `VendorPartNo` VARCHAR(100) NULL,
  `TipologiaProdotti_idTipologiaProdotti` INT NOT NULL,
  `Magazzino_idMagazzino` INT(11) NOT NULL,
  PRIMARY KEY (`idProdotti`),
  UNIQUE INDEX `ManufacturerPartNo_UNIQUE` (`ManufacturerPartNo` ASC),
  INDEX `fk_Prodotti_TerminationsStyle1_idx` (`TerminationsStyle_idTerminationsStyle` ASC),
  INDEX `fk_Prodotti_Produttore1_idx` (`Produttore_idProduttore` ASC),
  INDEX `fk_Prodotti_TipologiaProdotti1_idx` (`TipologiaProdotti_idTipologiaProdotti` ASC),
  INDEX `fk_Prodotti_Magazzino1_idx` (`Magazzino_idMagazzino` ASC),
  CONSTRAINT `fk_Prodotti_Produttore1`
    FOREIGN KEY (`Produttore_idProduttore`)
    REFERENCES `".$database."`.`Produttore` (`idProduttore`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Prodotti_TerminationsStyle1`
    FOREIGN KEY (`TerminationsStyle_idTerminationsStyle`)
    REFERENCES `".$database."`.`TerminationsStyle` (`idTerminationsStyle`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Prodotti_TipologiaProdotti1`
    FOREIGN KEY (`TipologiaProdotti_idTipologiaProdotti`)
    REFERENCES `".$database."`.`TipologiaProdotti` (`idTipologiaProdotti`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Prodotti_Magazzino1`
    FOREIGN KEY (`Magazzino_idMagazzino`)
    REFERENCES `".$database."`.`Magazzino` (`idMagazzino`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 9"); 

/*
-- -----------------------------------------------------
-- Table `Documenti_has_Prodotti`
-- -----------------------------------------------------
*/
$query="CREATE TABLE IF NOT EXISTS `".$database."`.`Documenti_has_Prodotti` (
  `idDocumenti_has_Prodotti` INT(11) NOT NULL AUTO_INCREMENT,
  `Documenti_idDocumenti` INT(11) NOT NULL,
  `Prodotti_idProdotti` INT(11) NOT NULL,
  PRIMARY KEY (`idDocumenti_has_Prodotti`, `Documenti_idDocumenti`, `Prodotti_idProdotti`),
  INDEX `fk_Documenti_has_Prodotti_Prodotti1_idx` (`Prodotti_idProdotti` ASC),
  INDEX `fk_Documenti_has_Prodotti_Documenti1_idx` (`Documenti_idDocumenti` ASC),
  CONSTRAINT `fk_Documenti_has_Prodotti_Documenti1`
    FOREIGN KEY (`Documenti_idDocumenti`)
    REFERENCES `".$database."`.`Documenti` (`idDocumenti`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Documenti_has_Prodotti_Prodotti1`
    FOREIGN KEY (`Prodotti_idProdotti`)
    REFERENCES `".$database."`.`Prodotti` (`idProdotti`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 10"); 

/*
-- -----------------------------------------------------
-- Table `Fornitori`
-- -----------------------------------------------------
*/
$query="CREATE TABLE IF NOT EXISTS `".$database."`.`Fornitori` (
  `idFornitori` INT(11) NOT NULL AUTO_INCREMENT,
  `NomeFornitore` VARCHAR(200) NOT NULL,
  `Sito` VARCHAR(100) NULL DEFAULT NULL,
  PRIMARY KEY (`idFornitori`))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 11"); 

/*
-- -----------------------------------------------------
-- Table `FattureAcquisti`
-- -----------------------------------------------------
*/
$query="CREATE TABLE IF NOT EXISTS `".$database."`.`FattureAcquisti` (
  `idFatture` INT(11) NOT NULL AUTO_INCREMENT,
  `NumFattVendor` VARCHAR(45) NOT NULL,
  `DataFattVendor` DATETIME NOT NULL,
  `URLPdf` VARCHAR(100) NULL DEFAULT NULL,
  `Fornitori_idFornitori` INT(11) NOT NULL,
  `NumFattRegistrata` VARCHAR(45) NULL,
  `DataFattRegistrata` VARCHAR(45) NULL,
  PRIMARY KEY (`idFatture`),
  UNIQUE INDEX `NumeroFattura_UNIQUE` (`NumFattVendor` ASC),
  INDEX `fk_FattureAcquisti_Fornitori1_idx` (`Fornitori_idFornitori` ASC),
  CONSTRAINT `fk_FattureAcquisti_Fornitori1`
    FOREIGN KEY (`Fornitori_idFornitori`)
    REFERENCES `".$database."`.`Fornitori` (`idFornitori`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 12"); 

/*
-- -----------------------------------------------------
-- Table `InfoFattura`
-- -----------------------------------------------------
*/
$query="CREATE TABLE IF NOT EXISTS `".$database."`.`InfoFattura` (
  `idInfoFattura` INT(11) NOT NULL AUTO_INCREMENT,
  `PezziAcquistati` INT(11) NULL DEFAULT NULL,
  `PrezzodiAcquisto` FLOAT NULL DEFAULT NULL,
  `MotivodelAcquisto` VARCHAR(100) NULL DEFAULT NULL,
  `ExtraInfo` VARCHAR(100) NULL DEFAULT NULL,
  `FattureAcquisti_idFatture` INT(11) NOT NULL,
  `Prodotti_idProdotti` INT(11) NOT NULL,
  PRIMARY KEY (`idInfoFattura`),
  INDEX `fk_InfoFattura_FattureAcquisti1_idx` (`FattureAcquisti_idFatture` ASC),
  INDEX `fk_InfoFattura_Prodotti1_idx` (`Prodotti_idProdotti` ASC),
  CONSTRAINT `fk_InfoFattura_FattureAcquisti1`
    FOREIGN KEY (`FattureAcquisti_idFatture`)
    REFERENCES `".$database."`.`FattureAcquisti` (`idFatture`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_InfoFattura_Prodotti1`
    FOREIGN KEY (`Prodotti_idProdotti`)
    REFERENCES `".$database."`.`Prodotti` (`idProdotti`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 13"); 

/*
-- -----------------------------------------------------
-- Table `Progetti`
-- -----------------------------------------------------
*/
$query="CREATE TABLE IF NOT EXISTS `".$database."`.`Progetti` (
  `idProgetti` INT NOT NULL AUTO_INCREMENT,
  `NomeProgetto` VARCHAR(100) NOT NULL,
  `Descrizione` VARCHAR(150) NULL,
  `Committente` VARCHAR(45) NULL,
  `ProjectManager` VARCHAR(45) NULL,
  `DataInizioProgetto` DATETIME NULL,
  `Stato` VARCHAR(45) NULL,
  PRIMARY KEY (`idProgetti`))
ENGINE = InnoDB";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 14"); 

/*
-- -----------------------------------------------------
-- Table `InfoProgetti`
-- -----------------------------------------------------
*/
$query="CREATE TABLE IF NOT EXISTS `".$database."`.`InfoProgetti` (
  `idInfoProgetti` INT NOT NULL AUTO_INCREMENT,
  `Progetti_idProgetti` INT NOT NULL,
  `Prodotti_idProdotti` INT(11) NOT NULL,
  PRIMARY KEY (`idInfoProgetti`),
  INDEX `fk_InfoProgetti_Progetti1_idx` (`Progetti_idProgetti` ASC),
  INDEX `fk_InfoProgetti_Prodotti1_idx` (`Prodotti_idProdotti` ASC),
  CONSTRAINT `fk_InfoProgetti_Progetti1`
    FOREIGN KEY (`Progetti_idProgetti`)
    REFERENCES `".$database."`.`Progetti` (`idProgetti`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_InfoProgetti_Prodotti1`
    FOREIGN KEY (`Prodotti_idProdotti`)
    REFERENCES `".$database."`.`Prodotti` (`idProdotti`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 15"); 

/*
-- -----------------------------------------------------
-- Table `Options`
-- -----------------------------------------------------
*/
$query="CREATE TABLE IF NOT EXISTS `".$database."`.`Options` (
  `idoptions` INT NOT NULL,
  `version` VARCHAR(45) NOT NULL,
  `date` DATETIME NULL,
  PRIMARY KEY (`idoptions`))
ENGINE = InnoDB";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 16"); 

/*
-- -----------------------------------------------------
-- Data for table `CategorieProdotti`
-- -----------------------------------------------------
*/
$query="INSERT INTO `".$database."`.`CategorieProdotti` (`idCategorieProdotti`, `NomeCategoria`, `Descrizione`, `Immagine`) VALUES (1, 'Undefined', 'Undefined', '25251.jpg')";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 17 ".mysqli_error($mysqli)); 

$query="INSERT INTO `".$database."`.`CategorieProdotti` (`idCategorieProdotti`, `NomeCategoria`, `Descrizione`, `Immagine`) VALUES (2, 'Passive Components', NULL, '25252.jpg')";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 18 ".mysqli_error($mysqli)); 

$query="INSERT INTO `".$database."`.`CategorieProdotti` (`idCategorieProdotti`, `NomeCategoria`, `Descrizione`, `Immagine`) VALUES (3, 'Electromechanical', NULL, '25253.jpg')";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 19 ".mysqli_error($mysqli)); 

$query="INSERT INTO `".$database."`.`CategorieProdotti` (`idCategorieProdotti`, `NomeCategoria`, `Descrizione`, `Immagine`) VALUES (4, 'Opto-electronics', NULL, '25254.jpg')";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 20 ".mysqli_error($mysqli)); 

$query="INSERT INTO `".$database."`.`CategorieProdotti` (`idCategorieProdotti`, `NomeCategoria`, `Descrizione`, `Immagine`) VALUES (5, 'Semiconductors', NULL, '25255.jpg')";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 21 ".mysqli_error($mysqli)); 

$query="INSERT INTO `".$database."`.`CategorieProdotti` (`idCategorieProdotti`, `NomeCategoria`, `Descrizione`, `Immagine`) VALUES (6, 'Sensors', NULL, '25256.jpg')";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 22 ".mysqli_error($mysqli)); 

$query="INSERT INTO `".$database."`.`CategorieProdotti` (`idCategorieProdotti`, `NomeCategoria`, `Descrizione`, `Immagine`) VALUES (7, 'Wire & Cable', NULL, '25257.jpg')";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 23 ".mysqli_error($mysqli)); 

$query="INSERT INTO `".$database."`.`CategorieProdotti` (`idCategorieProdotti`, `NomeCategoria`, `Descrizione`, `Immagine`) VALUES (8, 'Power', NULL, '25258.jpg')";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 24 ".mysqli_error($mysqli)); 

$query="INSERT INTO `".$database."`.`CategorieProdotti` (`idCategorieProdotti`, `NomeCategoria`, `Descrizione`, `Immagine`) VALUES (9, 'Industrial Automation', NULL, '25259.jpg')";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 25 ".mysqli_error($mysqli)); 

$query="INSERT INTO `".$database."`.`CategorieProdotti` (`idCategorieProdotti`, `NomeCategoria`, `Descrizione`, `Immagine`) VALUES (10, 'Embedded Solutions', NULL, '252510.jpg')";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 26 ".mysqli_error($mysqli)); 

$query="INSERT INTO `".$database."`.`CategorieProdotti` (`idCategorieProdotti`, `NomeCategoria`, `Descrizione`, `Immagine`) VALUES (11, 'Circuit Protection', NULL, '252511.jpg')";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 27 ".mysqli_error($mysqli)); 

$query="INSERT INTO `".$database."`.`CategorieProdotti` (`idCategorieProdotti`, `NomeCategoria`, `Descrizione`, `Immagine`) VALUES (12, 'Connectors', NULL, '252512.jpg')";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 28 ".mysqli_error($mysqli)); 

$query="INSERT INTO `".$database."`.`CategorieProdotti` (`idCategorieProdotti`, `NomeCategoria`, `Descrizione`, `Immagine`) VALUES (13, 'Enclosures', NULL, '252513.jpg')";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 29 ".mysqli_error($mysqli)); 




/*
-- -----------------------------------------------------
-- Data for table `Magazzino`
-- -----------------------------------------------------
*/
$query="INSERT INTO `".$database."`.`Magazzino` (`idMagazzino`, `Nome`, `Settore`, `Scaffale`, `Piano`, `Identificazione`, `Descrizione`, `Extra`) VALUES (1, 'Undefined', NULL, NULL, NULL, NULL, NULL, NULL)";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 28". mysqli_error($mysqli)); 


/*
-- -----------------------------------------------------
-- Data for table `Produttore`
-- -----------------------------------------------------
*/
$query="INSERT INTO `".$database."`.`Produttore` (`idProduttore`, `NomeProduttore`, `Sito`) VALUES (1, 'Undefined', NULL)";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 29"); 

/*
-- -----------------------------------------------------
-- Data for table `TerminationsStyle`
-- -----------------------------------------------------
*/

$query="INSERT INTO `".$database."`.`TerminationsStyle` (`idTerminationsStyle`, `TerminationStyle`, `Descrizione`) VALUES (1, 'Undefined', 'Undefined')";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 30"); 

/*
-- -----------------------------------------------------
-- Data for table `SottoCategorieProdotti`
-- -----------------------------------------------------
*/

$query="INSERT INTO `".$database."`.`SottoCategorieProdotti` (`idSottoCategoriaProdotti`, `NomeSottoCategoria`, `Descrizione`, `CategorieProdotti_idCategorieProdotti`) VALUES (1, 'Undefined', 'Undefined', 1)";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 31". mysqli_error($mysqli)); 

$query="INSERT INTO `".$database."`.`SottoCategorieProdotti` (`idSottoCategoriaProdotti`, `NomeSottoCategoria`, `Descrizione`, `CategorieProdotti_idCategorieProdotti`) VALUES (2, 'Antennas', NULL, 2)";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 32"); 

$query="INSERT INTO `".$database."`.`SottoCategorieProdotti` (`idSottoCategoriaProdotti`, `NomeSottoCategoria`, `Descrizione`, `CategorieProdotti_idCategorieProdotti`) VALUES (3, 'Capacitors', NULL, 2)";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 33"); 

$query="INSERT INTO `".$database."`.`SottoCategorieProdotti` (`idSottoCategoriaProdotti`, `NomeSottoCategoria`, `Descrizione`, `CategorieProdotti_idCategorieProdotti`) VALUES (4, 'Inductors', NULL, 2)";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 34"); 

$query="INSERT INTO `".$database."`.`SottoCategorieProdotti` (`idSottoCategoriaProdotti`, `NomeSottoCategoria`, `Descrizione`, `CategorieProdotti_idCategorieProdotti`) VALUES (5, 'Resistors', NULL, 2)";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 35"); 

$query="INSERT INTO `".$database."`.`SottoCategorieProdotti` (`idSottoCategoriaProdotti`, `NomeSottoCategoria`, `Descrizione`, `CategorieProdotti_idCategorieProdotti`) VALUES (6, 'Potentiometers, Trimmers & Rheostats', NULL, 5)";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 36"); 

/*
-- -----------------------------------------------------
-- Data for table `TipologiaProdotti`
-- -----------------------------------------------------
*/
$query="INSERT INTO `".$database."`.`TipologiaProdotti` (`idTipologiaProdotti`, `TipologiaProdotto`, `Descrizione`, `SottoCategorieProdotti_idSottoCategoriaProdotti`) VALUES (1, 'Undefined', 'Undefined', 1)";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 37"); 

/*
-- -----------------------------------------------------
-- Data for table `Fornitori`
-- -----------------------------------------------------
*/
$query="INSERT INTO `".$database."`.`Fornitori` (`idFornitori`, `NomeFornitore`, `Sito`) VALUES (1, 'Mouser Electronics', 'http://www.mouser.it')";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 38"); 

$query="INSERT INTO `".$database."`.`Fornitori` (`idFornitori`, `NomeFornitore`, `Sito`) VALUES (2, 'Digi-Key Corporation', 'http://www.digikey.com')";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 39"); 

$query="INSERT INTO `".$database."`.`Fornitori` (`idFornitori`, `NomeFornitore`, `Sito`) VALUES (3, 'RS Components', 'http://www.rs-components.com')";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 40"); 

$query="INSERT INTO `".$database."`.`Fornitori` (`idFornitori`, `NomeFornitore`, `Sito`) VALUES (4, 'Farnell', 'http://www.farnell.com')";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 41"); 

$query="INSERT INTO `".$database."`.`Fornitori` (`idFornitori`, `NomeFornitore`, `Sito`) VALUES (5, 'TEM Electronic Components', 'http://www.tme.eu')";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 42"); 

/*
-- -----------------------------------------------------
-- Data for table `Options`
-- -----------------------------------------------------
*/
$query="INSERT INTO `".$database."`.`Options` (`idoptions`, `version`, `date`) VALUES (1, '1.2', NULL)";
mysqli_query($mysqli, $query) or die("Installation FAIL. Error # 42"); 


header("Location: index.php"); 	
mysqli_close($mysqli); 
	
?>