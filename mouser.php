<?php
/**
 * Electronics Components Inventory - ECI
 *
 * Insert from vendor - Mouser.com
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
 	
if(isset($_POST['vpn'])) {
// Mouser Part Number
$strQuery = $_POST['vpn'];

try {
	$client = new SoapClient('http://api.mouser.com/service/searchapi.asmx?WSDL', array('soap_version' => SOAP_1_2, 'trace' => true)); 
	
	// Header of the SOAP 1.2 Request
	$headerbody = array('AccountInfo'=>array('PartnerID'=> get_mouserID() ));
	$header = new SoapHeader('http://api.mouser.com/service', 'MouserHeader', $headerbody);
	$client->__setSoapHeaders($header);
	
	// Body of the SOAP 1.2 Request
	$parametersQuery = array('mouserPartNumber' => $strQuery);
	
	// Execute the SOAP request
	$result = $client->SearchByPartNumber($parametersQuery);
	
	if(!isset($result->SearchByPartNumberResult->Parts->MouserPart))
	{
		die("<div class=\"col_12\" id=\"soap_result\" value=\"not_found\" style=\"font: 0.9em/0% Arimo,arial,verdana,sans-serif; font-style: italic;\">Part NOT Found</div>");
	}
	
	$num_di_parts = $result->SearchByPartNumberResult->NumberOfResult;

	if($num_di_parts == 0)
	{
		die("<div class=\"col_12\" id=\"soap_result\" value=\"not_found\" style=\"font: 0.9em/0% Arimo,arial,verdana,sans-serif; font-style: italic;\">Part NOT Found</div>");
	}

	if($num_di_parts > 1)
	{
		echo "<div class=\"col_12\" id=\"soap_result\" style=\"font: 0.9em/0% Arimo,arial,verdana,sans-serif; font-style: italic;\">Warning: Not Unique Part</div>";
		for($i=0; $i<$num_di_parts; $i++)
		{
			if($result->SearchByPartNumberResult->Parts->MouserPart[$i]->Availability == 'Yes')
			{
				$result4 = get_object_vars($result->SearchByPartNumberResult->Parts->MouserPart[$i]);
				$noAvail = 0;
				break;
			}
			else
			{
				$noAvail = 1;
			}
		}
		// One Result with availability = 0
		if($noAvail == 1)
		{
			$result4 = get_object_vars($result->SearchByPartNumberResult->Parts->MouserPart[0]);
		}
		
	}
	else
	{
		$result4 = get_object_vars($result->SearchByPartNumberResult->Parts->MouserPart);
	}
	
	
	
/**** Enable to DEBUG 
	echo "REQUEST HEADER:<br>" . htmlentities($client->__getLastRequestHeaders()) . "<br>";
	echo "REQUEST:<br>" . htmlentities($client->__getLastRequest()) . "<br><br>";
	// Body of the SOAP 1.2 Request
	echo var_dump($result);
	
	$result1 = get_object_vars($result); 
	$result2 = get_object_vars($result1['SearchByPartNumberResult']);
	$numberOfResult = $result2['NumberOfResult'];
	echo "Number of Result:" . $numberOfResult . "<br>";
	if($numberOfResult != 1)
	{
		die("Ambiguità: Numero di articoli superiore ad 1!!");
		
	}
	
	$result3 = get_object_vars($result2['Parts']); 
	$result4 = get_object_vars($result->SearchByPartNumberResult->Parts->MouserPart[$i]);	
	echo "Availability:" . $result4['Availability'] . "<br>";
	echo "DataSheetUrl:" . $result4['DataSheetUrl'] . "<br>";
	echo "Description:" . $result4['Description'] . "<br>";
	echo "Image Path:" . $result4['ImagePath'] . "<br>";
	echo "Category:" . $result4['Category'] . "<br>";
	echo "Manufacturer:" . $result4['Manufacturer'] . "<br>";
	echo "Manufacturer Part Number:" . $result4['ManufacturerPartNumber'] . "<br>";
	echo "Mouser Part Number:" . $result4['MouserPartNumber'] . "<br>";
****/
	$productDataUrl = $result4['ProductDetailUrl'];
	
	/* Config Language and Currency */
	$search = '#('. preg_quote('//').')(.*?)('. preg_quote('/').')#si';
	$replace = 'eu.mouser.com';
	$productDataUrl = preg_replace($search, '$1'.$replace.'$3', $productDataUrl, 1);
		
	
} catch (SoapFault $e) {
print_r($e);
}


$itemHtmlData = get_data($productDataUrl); 

// New DOM object
$dom = new DOMDocument();
// Load the HTML page in DOM
@$dom->loadHTML($itemHtmlData);

//get element by id - Category (For Mouser is the 1° Breadcrumb. Example: Passive Components)
$category_div = $dom->getElementById('ctl00_ContentMain_bc_rptrBreadcrumbs_ctl01_lnkBreadcrumb');
if(!$category_div)
{
    die("Error: Category not Found");
}
 
//get element by id - SubCategory (For Mouser is the 1° Breadcrumb. Example: Frequency Control & Timing Devices)
$subcategory_div = $dom->getElementById('ctl00_ContentMain_bc_rptrBreadcrumbs_ctl02_lnkBreadcrumb');
if(!$subcategory_div)
{
    die("Error: SubCategory not Found");
}
 
// Product has Termination Style?
for($i=1; $i<99; $i++)
{
	if($i < 10)
	{
		$codice = "ctl00_ContentMain_Specifications_dlspec_ctl0". $i . "_lblDimension";
		$termstylee_div = @$dom->getElementById($codice)->nodeValue;
		if($termstylee_div == 'Termination Style' OR $termstylee_div == 'Mounting Style')
		{
			$termstyle_div = $dom->getElementById("ctl00_ContentMain_Specifications_dlspec_ctl0".$i."_lblName")->nodeValue;
			break;									
		}
	}
	else
	{
		$codice = "ctl00_ContentMain_Specifications_dlspec_ctl". $i . "_lblDimension";
		if(!empty($dom->getElementById($codice)->nodeValue)) { $termstylee_div = $dom->getElementById($codice)->nodeValue; }

		if($termstylee_div == 'Termination Style' OR $termstylee_div == 'Mounting Style')
		{
			$termstyle_div = $dom->getElementById("ctl00_ContentMain_Specifications_dlspec_ctl".$i."_lblName")->nodeValue;
			break;
		}
	}	
}
 
if(!isset($termstyle_div)) { $termstyle_div = ''; }
if(empty($result4['ImagePath'])) { $imageURL = 'images/no-image.gif'; } else { $imageURL = $result4['ImagePath']; }

// Print the variable HTML
echo "<ul class=\"breadcrumbs\">
<li><a href=\"\">Home</a></li>
<li><a href=\"\">" . $category_div->nodeValue ." </a></li>
<li><a href=\"\">" . $subcategory_div->nodeValue . "</a></li>
<li><a href=\"\">" . $result4['Category'] . "</a></li>
</ul>
<div class=\"col_3\">
<img class=\"caption\" title=\"". $result4['ManufacturerPartNumber'] ."\" src=\"". $imageURL ." \"width=\"150\" height=\"73\" />
</div>
<div class=\"col_9\">
<strong>Manufacturer Part No: </strong>" . $result4['ManufacturerPartNumber'] . "
<br><strong>Manufacturer: </strong>" . $result4['Manufacturer'] . "
<br><strong>Description: </strong>" . $result4['Description'] . "
<br><strong>Termination Style: </strong>" . $termstyle_div . "
<br><strong>Product Detail: <a href=\"". $productDataUrl ."\" target=\"_blank\">" . $result4['ManufacturerPartNumber'] . "</a></strong></div>

<input type=\"hidden\" name=\"category\" value=\"". $category_div->nodeValue ."\"/>
<input type=\"hidden\" name=\"subCategory\" value=\"" . $subcategory_div->nodeValue . "\"/>
<input type=\"hidden\" name=\"ProdCat\" value=\"" . $result4['Category'] . "\"/>
<input type=\"hidden\" id=\"soap_manupartno\" name=\"ManuPartNo\" value=\"" . $result4['ManufacturerPartNumber'] . "\"/>
<input type=\"hidden\" name=\"ImagePath\" value=\"" . $imageURL . "\"/>
<input type=\"hidden\" name=\"manufacturer\" value=\"" . $result4['Manufacturer'] . "\"/>
<input type=\"hidden\" name=\"Descr\" value=\"" . $result4['Description']  . "\"/>
<input type=\"hidden\" name=\"termination\" value=\"" . $termstyle_div . "\"/>
<input type=\"hidden\" name=\"vendorURL\" value=\"" . $productDataUrl . "\"/>";


}
else 
{
	echo "Missing Vendor Part Number";
}


/* FUNCTIONS */
function get_data($url) {


	$header = array('Accept-Language: it-IT,it;q=0.8,en-US;q=0.5,en;q=0.3',
 	'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
 	'Connection:keep-alive'); 
 	$preferences = get_preferences(); 
	$cookie = 'preferences=pl='.$preferences['language'].'&pc_eu='.$preferences['currency'].'&ps=eu ';
					
	// initialise the CURL library
	$ch = curl_init();
	
	// specify the URL to be retrieved
	curl_setopt($ch, CURLOPT_URL, $url);
	
	// Header HTTP
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 
	
	// Follow Redirect
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	
	// specify the useragent: this is a required courtesy to site owners
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.01 (compatible; MSIE 6.0; Windows NT 5.1)");
	
	// we want to get the contents of the URL and store it in a variable
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	// Cookie
	curl_setopt($ch, CURLOPT_COOKIE, $cookie);
	
	// Watch the header
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	
	// return the contents as a variable
	$data = curl_exec($ch);
	
	/* Watch the header for DEBUG
		$headerSent = curl_getinfo($ch, CURLINFO_HEADER_OUT); 
		echo $headerSent;
	*/
	// free resources
	curl_close($ch);
	return $data;
}


function get_preferences()
{
	require 'config.ini.php';
	
	switch ($MOUSER_LANGUAGE) {
	  case 'en':
	  $pref['language'] = 'en-GB';
	  break;
 
	  case 'it':
	  $pref['language'] = 'it-IT';
	  break;
 
	  case 'de':
	  $pref['language'] = 'de-DE';
	  break;
	  
	  case 'fr':
	  $pref['language'] = 'fr-FR';
	  break;
	  
	  case 'es':
	  $pref['language'] = 'es-ES';
	  break;
 
	  default:
	  $pref['language'] = 'en-GB';
	  }
	  
	switch ($MOUSER_CURRENCY) {
	  case 'eur':
	  $pref['currency'] = 'EUR';
	  break;
 
	  case 'usd':
	  $pref['currency'] = 'USD';
	  break;
 
	  default:
	  $pref['currency'] = 'EUR';
	  }
	  
	  return $pref;

}

function get_mouserID()
{
	require_once 'config.ini.php';
	return $MOUSER_PARTNER_ID;
}


?>

