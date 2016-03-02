<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');
	
	require_once ("paypal_functions.php");


	if (isset($_REQUEST['token']))
	{
		$token = urlencode( $_REQUEST['token']);
	}
	else
	{
		$token = urlencode($_SESSION['pp_token']);
	}


	$nvpstr = "&TOKEN=" . $token;
	$resArray = hash_call("GetExpressCheckoutDetails", $nvpstr);
	
	$ack = strtoupper($resArray["ACK"]);
	if( $ack == "SUCCESS" OR $ack == "SUCESSWITHWARNING") 
	{
		$_SESSION['payer_id'] =	$resArray['PAYERID'];
		
		echo TAB_9.'<h2 class="ShopCheckOut" >Your PayPal Account details are:</h2>'."\n";
			
			//	confirm email and post details
			echo TAB_9.'<div id="ShopCheckOutReadback" >'."\n";
			
				$salutation = '';
				if (isset($resArray["SALUTATION"]) AND $resArray["SALUTATION"] != '' ) {$salutation = $resArray["SALUTATION"];}
			
				$firstname = '';
				if (isset($resArray["FIRSTNAME"]) AND $resArray["FIRSTNAME"] != '' ) {$firstname  = $resArray["FIRSTNAME"];}
				
				$middlename = '';
				if (isset($resArray["MIDDLENAME"]) AND $resArray["MIDDLENAME"] != '' ) {$middlename  = $resArray["MIDDLENAME"];}
 				
				$lastname = '';
				if (isset($resArray["LASTNAME"]) AND $resArray["LASTNAME"] != '' ) {$lastname = $resArray["LASTNAME"];}	

				$suffix = '';
				if (isset($resArray["SUFFIX"]) AND $resArray["SUFFIX"] != '' ) {$suffix = $resArray["SUFFIX"];}
				
				$paypal_fullname = $salutation .' '. $firstname  .' '. $middlename  
									.' '. $lastname .' '.$suffix;
										
				echo TAB_10.'<p><label>Name: </label><span class="Bold">'.$paypal_fullname.'</span></p>'. "\n";
				echo TAB_10.'<p><label>Email: </label><span class="Bold">'.$resArray["EMAIL"].'</span></p>'. "\n";
				//echo TAB_10.'<p><label>Phone: </label><span class="Bold">'.$resArray["PHONENUM"].'</span></p>'. "\n";
				echo TAB_10.'<p><label>Country: </label><span class="Bold">'.$resArray["COUNTRYCODE"].'</span></p>'. "\n";
				echo TAB_10.'<p><label>PayPal ID: </label><span class="Bold">'.$resArray["PAYERID"].'</span></p>'. "\n";
				echo TAB_10.'<p><label>Status: </label><span class="Bold">'.$resArray["PAYERSTATUS"].'</span></p>'. "\n";					
				echo TAB_10.'<p><label>Invoice: </label><span class="Bold">'.$resArray["INVNUM"].'</span></p>'. "\n";
			echo TAB_9.'</div>'."\n";

//////////////////////////////////////--- EDITED TO HERE - ////////////////////////////////////////////////////////////////////////////////	

/* 	
		echo '<br/>'.$email 				= $resArray["EMAIL"]; // ' Email address of payer.
		echo '<br/>'.$payerId 			= $resArray["PAYERID"]; // ' Unique PayPal customer account identification number.
		echo '<br/>'.$payerStatus		= $resArray["PAYERSTATUS"]; // ' Status of payer. Character length and limitations: 10 single-byte alphabetic characters.
		echo '<br/>'.$salutation			= $resArray["SALUTATION"]; // ' Payer's salutation.
		echo '<br/>'.$firstName			= $resArray["FIRSTNAME"]; // ' Payer's first name.
		echo '<br/>'.$middleName			= $resArray["MIDDLENAME"]; // ' Payer's middle name.
		echo '<br/>'.$lastName			= $resArray["LASTNAME"]; // ' Payer's last name.
		echo '<br/>'.$suffix				= $resArray["SUFFIX"]; // ' Payer's suffix.
		echo '<br/>'.$cntryCode			= $resArray["COUNTRYCODE"]; // ' Payer's country of residence in the form of ISO standard 3166 two-character country codes.
		echo '<br/>'.$business			= $resArray["BUSINESS"]; // ' Payer's business name.
		echo '<br/>'.$shipToName			= $resArray["SHIPTONAME"]; // ' Person's name associated with this address.
		echo '<br/>'.$shipToStreet		= $resArray["SHIPTOSTREET"]; // ' First street address.
		echo '<br/>'.$shipToStreet2		= $resArray["SHIPTOSTREET2"]; // ' Second street address.
		echo '<br/>'.$shipToCity			= $resArray["SHIPTOCITY"]; // ' Name of city.
		echo '<br/>'.$shipToState		= $resArray["SHIPTOSTATE"]; // ' State or province
		echo '<br/>'.$shipToCntryCode	= $resArray["SHIPTOCOUNTRYCODE"]; // ' Country code. 
		echo '<br/>'.$shipToZip			= $resArray["SHIPTOZIP"]; // ' U.S. Zip code or other country-specific postal code.
		echo '<br/>'.$addressStatus 		= $resArray["ADDRESSSTATUS"]; // ' Status of street address on file with PayPal   
		echo '<br/>'.$invoiceNumber		= $resArray["INVNUM"]; // ' Your own invoice or tracking number, as set by you in the element of the same name in SetExpressCheckout request .
		echo '<br/>'.$phonNumber			= $resArray["PHONENUM"]; // ' Payer's contact telephone number. Note:  PayPal returns a contact telephone number only if your Merchant account profile settings require that the buyer enter one. 
	 */
		//echo TAB_9.'<h2 class="ShopCheckOut" >Please complete your purchase by clicking the "Pay Now" Button:</h2>'."\n";
		
		echo TAB_10.'<p>' ."\n";	
			echo TAB_11.'<a class="ShopCheckoutEdit HideOnClick" href="/modules/pay_gate/paypal_express_payment.php"'. "\n";		
			echo TAB_11.' title="Click to make Payment" >'. "\n";
				echo TAB_12.'<img class="PayNow" src="/_images_user/'.PAYPAL_BUTTON_PAYNOW.'" alt="PAY with PayPal" />' ."\n";
			echo TAB_11.'</a>'. "\n";		
		echo TAB_10.'</p>' ."\n";		
		echo TAB_10.'<p class="ShowOnClick" ></p>' ."\n";
		
		
	
	} 
	else  
	{	
		//	display warning , link back to STAGE 2 to try again and write to error file and email
		$error_type = 'GetExpressCheckoutDetails API call failed';
		require('modules/pay_gate/paypal_API_logError.php');	
	}
	
	
?>