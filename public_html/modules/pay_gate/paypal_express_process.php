<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );

	$file_path_offset = '../../';
	
	$payment_method_type = $_REQUEST['paymeth'];

//----Get Common code to all pages
	require_once ($file_path_offset.'includes/common.php');
	require_once ($file_path_offset.'includes/access.php');	
	
	require_once (CODE_NAME.'_shop_configs.php');
	require_once (CODE_NAME.'_alert_configs.php');

	
	//	Is  CART Empty OR NOT at STAGE 2 CHECKOUT ....if So should not be here.....go away.....
	if (count($_SESSION['cart_items']) < 1 OR $_SESSION['check_out_stage'] < 3) 
	{
		header('location: /index.php?p='.SHOP_PAGE_ID); 
		exit();	  		
	}	
	
	//	read from db to get the payment method selected
	$mysql_err_msg = 'Payment Method information unavailable';	
	$sql_statement = 'SELECT config_file_name, pay_method_name FROM _shop_pay_types WHERE pay_method_id = 3';
		
	$payment_method_info = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
	$payment_method_name = $payment_method_info['pay_method_name'];
	
	require_once (CODE_NAME.$payment_method_info['config_file_name']);
	
	require_once ("paypal_functions.php");

	
	// Check to see if the Request object contains a variable named 'token'	
	$token = "";
	if (isset($_REQUEST['token']))
	{
		$token = $_REQUEST['token'];
	}

	// If the Request object contains the variable 'token' then it means that the user is coming from PayPal site.	
	if ( $token != "" )
	{
		//	PROCESS STAGE: 2
		
		$_SESSION['pp_token'] = $token;
		$_SESSION['check_out_stage'] = 4;
		 
		//	send to CONFIRM order page...
		$qry_str = $_SERVER['QUERY_STRING'];

		header ("location: /index.php?p=".SHOP_PAGE_ID."&view=checkout"."&".$qry_str);
		exit();
			  

	}	
	else
	{
		//	PROCESS STAGE: 1
		

		//	lock table while getting next invoice number
		$mysql_err_msg = 'Locking tables';
		$sql_statement = 'LOCK TABLES used_invoice_number WRITE';
		mysql_query (ReadDB ($sql_statement, $mysql_err_msg));

		//	read from db to get the next invoice number
		$mysql_err_msg = 'accesing next invoice num';	
		$sql_statement = 'INSERT INTO used_invoice_number SET used = "used"';
			
		ReadDB ($sql_statement, $mysql_err_msg);

		//	get order_id:	
		$invoice_num = mysql_insert_id();	

		// unlock table
		$mysql_err_msg = 'unlocking tables';
		$sql_statement = 'UNLOCK TABLES';
		mysql_query (ReadDB ($sql_statement, $mysql_err_msg));
		
		$_SESSION["invoice_num"] = $invoice_num;

		$nvpstr = '';
		//$nvpstr = "&AMT=" . $_SESSION["Payment_Amount"];
		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_AMT=". $_SESSION["Payment_Amount"];
		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_PAYMENTACTION=" . PAYMENT_TYPE;
		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_INVNUM=" . $invoice_num;		
		$nvpstr = $nvpstr . "&RETURNURL=" . urlencode(RETURN_URL);
		$nvpstr = $nvpstr . "&CANCELURL=" . urlencode(CANCEL_URL);
		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_CURRENCYCODE=" . SHOP_CURRENCY_ISO3;

		$nvpstr = $nvpstr . $_SESSION['pp_express_item_str'];

		$_SESSION["currencyCodeType"] = SHOP_CURRENCY_ISO3;	  
		//$_SESSION["PaymentType"] = $paymentType;		


		$resArray = hash_call("SetExpressCheckout", $nvpstr);
		

		$ack = strtoupper($resArray["ACK"]);

		if($ack == "SUCCESS" OR $ack == "SUCCESSWITHWARNING")
		{
			RedirectToPayPal ( $resArray["TOKEN"] );
		} 
		else  
		{
			//	display warning , link back to STAGE 2 to try again and write to error file and email
			$error_type = 'SetExpressCheckout API call failed';
			require('paypal_API_logError.php');	
		}
			
	
	}
		
?>