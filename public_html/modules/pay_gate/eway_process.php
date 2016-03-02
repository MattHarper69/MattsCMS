<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');


	//sleep(3);	//	For testing processing gif

	//	set VARs (not used)
	$txtInvDesc = '';
	$txtOption1 = 'Note: CustomerFirstName is the FULL name';
	$txtOption2 = '';
	$txtOption3 = '';
	
	//	lock table while getting next invoice number	//	just to be sure....
	$mysql_err_msg = 'Locking tables';
	$sql_statement = 'LOCK TABLES used_invoice_number WRITE';
	mysql_query (ReadDB ($sql_statement, $mysql_err_msg));	

	//	read from db to get the next invoice number
	$mysql_err_msg = 'accesing next invoice num';	
	$sql_statement = 'INSERT INTO used_invoice_number SET used = "used"';
			
	ReadDB ($sql_statement, $mysql_err_msg);

	//	get order_id:
	$invoice_num = mysql_insert_id();
	$_SESSION['invoice_num'] = $invoice_num;
	
	// unlock table
	$mysql_err_msg = 'unlocking tables';
	$sql_statement = 'UNLOCK TABLES';
	mysql_query (ReadDB ($sql_statement, $mysql_err_msg));	
	
	

	$CC_number = str_replace(' ', '', $CC_number);	//	strip spaces
	$total_amount = $grand_total_price * 100;		//  convert to correct number format
	$checkout_address = $checkout_address_1 . ' ' . $checkout_address_2 . ', ' . $checkout_state;

	// Set the payment details
	$eway = new EwayPaymentLive($eWAY_CustomerID, $eWAY_PaymentMethod, $eWAY_UseLive);

	$eway->setTransactionData("TotalAmount", $total_amount); 		//mandatory field
	$eway->setTransactionData("CardHoldersName", $CC_name); 		//mandatory field
	$eway->setTransactionData("CardNumber", $CC_number); 			//mandatory field
	$eway->setTransactionData("CardExpiryMonth", $CC_exp_month); 	//mandatory field
	$eway->setTransactionData("CardExpiryYear", $CC_exp_year); 		//mandatory field
	$eway->setTransactionData("CVN", $CC_CVN); 						//mandatory field
	
  	// the following are used
	$eway->setTransactionData("CustomerFirstName", $checkout_name);
	$eway->setTransactionData("CustomerLastName", "");
	$eway->setTransactionData("CustomerEmail", $checkout_email);
	$eway->setTransactionData("CustomerAddress", $checkout_address);
	$eway->setTransactionData("CustomerPostcode", $checkout_postcode);
	
 	//	the following are not used (except Option1)
	$eway->setTransactionData("CustomerInvoiceDescription", $txtInvDesc);
	$eway->setTransactionData("CustomerInvoiceRef", $invoice_num);
	$eway->setTransactionData("TrxnNumber", "");
	$eway->setTransactionData("Option1", $txtOption1);
	$eway->setTransactionData("Option2", $txtOption2);
	$eway->setTransactionData("Option3", $txtOption3);


	$eway->setCurlPreferences(CURLOPT_SSL_VERIFYPEER, 0); // Require for Windows hosting

	// Send the transaction
	$ewayResponseFields = $eway->doPayment();


	if( strtolower($ewayResponseFields["EWAYTRXNSTATUS"]) == "false" )
	{
		
		echo TAB_9.'<p class="WarningMSG" >Credit Card Transaction Failed</p>' . "\n";
		echo TAB_9.'<p class="WarningMSG" >'. $ewayResponseFields['EWAYTRXNERROR'] . '.</p>' . "\n";
		echo TAB_9.'<p class="WarningMSG" >please try again</p>' . "\n";		
	
		//	Log error to File and email 
		require('eway_txn_logError.php');	

	}
	
	elseif(strtolower($ewayResponseFields["EWAYTRXNSTATUS"])=="true")
	{
		// payment succesfully sent to gateway
		
		// Payment succeeded get values returned:
			// Result = $ewayResponseFields["EWAYTRXNSTATUS"]
			// AuthCode = $ewayResponseFields["EWAYAUTHCODE"]
			// Error = $ewayResponseFields["EWAYTRXNERROR"]
			// eWAYInvoiceRef = $ewayResponseFields["EWAYTRXNREFERENCE"]
			// Amount = $ewayResponseFields["EWAYRETURNAMOUNT"]
			// Txn Number = $ewayResponseFields["EWAYTRXNNUMBER"]
			// Option1 = $ewayResponseFields["EWAYOPTION1"]
			// Option2 = $ewayResponseFields["EWAYOPTION2"]
			// Option3 = $ewayResponseFields["EWAYOPTION3"]

		
		
		$_SESSION['currencyCodeType'] 			= 'n/a';
		
		$_SESSION['txn_id'] 					= $ewayResponseFields['EWAYTRXNNUMBER'];	
		$_SESSION['total_amount_payed'] 		= $ewayResponseFields['EWAYRETURNAMOUNT'] / 100; 			
		$_SESSION['payment_status'] 			= $ewayResponseFields['EWAYTRXNSTATUS'];

		
		header("location: modules/".SHOP_DB_NAME_PREFIX."/".SHOP_DB_NAME_PREFIX."_process_order.php" );
		exit();				
	   
	   
	}
	
	else
	{
		// invalid response recieved from server.
		echo TAB_9.'<p class="WarningMSG" >Error: An invalid response was recieved from the payment gateway.</p>' . "\n";
		echo TAB_9.'<p class="WarningMSG" >please try again</p>' . "\n";
	}


	/////////////////////////////////////////////////////////////////////////
	///////			Class and functions below					/////////////
	/////////////////////////////////////////////////////////////////////////
  
class EwayPaymentLive 
{
    var $myGatewayURL;
    var $myCustomerID;
    var $myTransactionData = array();
    var $myCurlPreferences = array();

    //Class Constructor
	function EwayPaymentLive($customerID = EWAY_DEFAULT_CUSTOMER_ID, $method = EWAY_DEFAULT_PAYMENT_METHOD ,$liveGateway  = EWAY_DEFAULT_LIVE_GATEWAY) 
	{
		$this->myCustomerID = $customerID;
		
	    switch($method)
		{

		    case 'REAL_TIME';

		    		if($liveGateway)
		    			$this->myGatewayURL = EWAY_PAYMENT_LIVE_REAL_TIME;
		    		else
	    				$this->myGatewayURL = EWAY_PAYMENT_LIVE_REAL_TIME_TESTING_MODE;
	    		break;
	    	 case 'REAL_TIME_CVN';
		    		if($liveGateway)
		    			$this->myGatewayURL = EWAY_PAYMENT_LIVE_REAL_TIME_CVN;
		    		else
	    				$this->myGatewayURL = EWAY_PAYMENT_LIVE_REAL_TIME_CVN_TESTING_MODE;
	    		break;
	    	case 'GEO_IP_ANTI_FRAUD';
		    		if($liveGateway)
		    			$this->myGatewayURL = EWAY_PAYMENT_LIVE_GEO_IP_ANTI_FRAUD;
		    		else
		    			//in testing mode process with REAL-TIME
	    				$this->myGatewayURL = EWAY_PAYMENT_LIVE_GEO_IP_ANTI_FRAUD_TESTING_MODE;
	    		break;
    	}
	}
	
	
	//Payment Function
	function doPayment() 
	{
		$xmlRequest = "<ewaygateway><ewayCustomerID>" . $this->myCustomerID . "</ewayCustomerID>";
		foreach($this->myTransactionData as $key => $value)
		{
			$xmlRequest .= "<$key>$value</$key>";	
			
		}

        $xmlRequest .= "</ewaygateway>";
		
		$xmlResponse = $this->sendTransactionToEway($xmlRequest);

		if($xmlResponse != "")
		{
			$responseFields = $this->parseResponse($xmlResponse);
			return $responseFields;
		}
		else die("Error in XML response from eWAY: " + $xmlResponse);
	}

	//Send XML Transaction Data and receive XML response
	function sendTransactionToEway($xmlRequest) 
	{
		$ch = curl_init($this->myGatewayURL);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        foreach($this->myCurlPreferences as $key=>$value)
        	curl_setopt($ch, $key, $value);

        $xmlResponse = curl_exec($ch);

        if(curl_errno( $ch ) == CURLE_OK)
        	return $xmlResponse;
	}
	
	
	//Parse XML response from eway and place them into an array
	function parseResponse($xmlResponse)
	{
		//print 'parseRespon()<br />';
		$xml_parser = xml_parser_create();
		xml_parse_into_struct($xml_parser,  $xmlResponse, $xmlData, $index);
        	$responseFields = array();
        	foreach($xmlData as $data)
		{
			//print '--> ' . $data['level'] . '<br />';
		    	if($data["level"] == 2 && isset($data['value']))
			{
			//	print_r($data);
			//	print '-----> ' . $data['value'] . '<br />';
        			{$responseFields[$data["tag"]] = $data["value"];}
			}
		}

        return $responseFields;
	}
	
	
	//Set Transaction Data
	//Possible fields: "TotalAmount", "CustomerFirstName", "CustomerLastName", "CustomerEmail", "CustomerAddress", "CustomerPostcode", "CustomerInvoiceDescription", "CustomerInvoiceRef",
	//"CardHoldersName", "CardNumber", "CardExpiryMonth", "CardExpiryYear", "TrxnNumber", "Option1", "Option2", "Option3", "CVN", "CustomerIPAddress", "CustomerBillingCountry"
	function setTransactionData($field, $value) 
	{
		//if($field=="TotalAmount")
		//	$value = round($value*100);
		$this->myTransactionData["eway" . $field] = htmlentities(trim($value));
	}
	
	
	//receive special preferences for Curl
	function setCurlPreferences($field, $value) 
	{
		$this->myCurlPreferences[$field] = $value;
	}
		
	
	//obtain visitor IP even if is under a proxy
	function getVisitorIP()
	{
		$ip = $_SERVER["REMOTE_ADDR"];
		$proxy = $_SERVER["HTTP_X_FORWARDED_FOR"];
		if(ereg("^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$",$proxy))
		        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		return $ip;
	}
}
?>
