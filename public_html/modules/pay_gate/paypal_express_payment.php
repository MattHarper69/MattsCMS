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

	//	may need this to stop error msgs
	ini_set('session.bug_compat_42',0);
	ini_set('session.bug_compat_warn',0);
	
	$finalPaymentAmount =  $_SESSION["Payment_Amount"];

	//Format the other parameters that were stored in the session from the previous calls	
	$token 				= urlencode($_SESSION['pp_token']);
	//$paymentType 		= urlencode($_SESSION['PaymentType']);
	$currencyCodeType 	= urlencode($_SESSION['currencyCodeType']);
	$payerID 			= urlencode($_SESSION['payer_id']);

	$serverName 		= urlencode($_SERVER['SERVER_NAME']);

	$nvpstr  = '&TOKEN=' . $token . '&PAYERID=' . $payerID 
				.'&PAYMENTREQUEST_0_PAYMENTACTION='. PAYMENT_TYPE . '&PAYMENTREQUEST_0_AMT=' . $finalPaymentAmount			
				.'&PAYMENTREQUEST_0_CURRENCYCODE=' . $currencyCodeType . '&IPADDRESS=' . $serverName; 

	$resArray = hash_call("DoExpressCheckoutPayment",$nvpstr);
	
	$ack = strtoupper($resArray["ACK"]);
	if( $ack == "SUCCESS" OR $ack == "SUCCESSWITHWARNING" )
	{

		$_SESSION['txn_id']	= $resArray['PAYMENTINFO_0_TRANSACTIONID']; // ' Unique transaction ID of the payment. Note:  If the PaymentAction of the request was Authorization or Order, this value is your AuthorizationID for use with the Authorization & Capture APIs. 
		
		$_SESSION['total_amount_payed']	= $resArray['PAYMENTINFO_0_AMT'];  //' The final amount charged, including any shipping and taxes from your Merchant Profile.	

		
		$_SESSION['payment_status']	= $resArray['PAYMENTINFO_0_PAYMENTSTATUS']; 

		// Status of the payment: 
		//		'Completed: The payment has been completed, and the funds have been added successfully to your account balance.
		//		'Pending: The payment is pending. See the PendingReason element for more information. 

		
//////////////////////////////////////--- EDITED TO HERE - ////////////////////////////////////////////////////////////////////////////////	
		
		$transactionType 	= $resArray["TRANSACTIONTYPE"]; //' The type of transaction Possible values: l  cart l  express-checkout 
		$paymentType		= $resArray["PAYMENTTYPE"];  //' Indicates whether the payment is instant or delayed. Possible values: l  none l  echeck l  instant 
		$orderTime 			= $resArray["ORDERTIME"];  //' Time/date stamp of payment

		$currencyCode		= $resArray["CURRENCYCODE"];  //' A three-character currency code for one of the currencies listed in PayPay-Supported Transactional Currencies. Default: USD. 
		$feeAmt				= $resArray["FEEAMT"];  //' PayPal fee amount charged for the transaction
		$settleAmt			= $resArray["SETTLEAMT"];  //' Amount deposited in your PayPal account after a currency conversion.
		$taxAmt				= $resArray["TAXAMT"];  //' Tax charged on the transaction.
		$exchangeRate		= $resArray["EXCHANGERATE"];  //' Exchange rate if a currency conversion occurred. Relevant only if your are billing in their non-primary currency. If the customer chooses to pay with a currency other than the non-primary currency, the conversion occurs in the customer’s account.
		


		/*
		'The reason the payment is pending:
		'  none: No pending reason 
		'  address: The payment is pending because your customer did not include a confirmed shipping address and your Payment Receiving Preferences is set such that you want to manually accept or deny each of these payments. To change your preference, go to the Preferences section of your Profile. 
		'  echeck: The payment is pending because it was made by an eCheck that has not yet cleared. 
		'  intl: The payment is pending because you hold a non-U.S. account and do not have a withdrawal mechanism. You must manually accept or deny this payment from your Account Overview. 		
		'  multi-currency: You do not have a balance in the currency sent, and you do not have your Payment Receiving Preferences set to automatically convert and accept this payment. You must manually accept or deny this payment. 
		'  verify: The payment is pending because you are not yet verified. You must verify your account before you can accept this payment. 
		'  other: The payment is pending for a reason other than those listed above. For more information, contact PayPal customer service. 
		*/
		
		$pendingReason	= $resArray["PENDINGREASON"];  

		/*
		'The reason for a reversal if TransactionType is reversal:
		'  none: No reason code 
		'  chargeback: A reversal has occurred on this transaction due to a chargeback by your customer. 
		'  guarantee: A reversal has occurred on this transaction due to your customer triggering a money-back guarantee. 
		'  buyer-complaint: A reversal has occurred on this transaction due to a complaint about the transaction from your customer. 
		'  refund: A reversal has occurred on this transaction because you have given the customer a refund. 
		'  other: A reversal has occurred on this transaction due to a reason not listed above. 
		*/
		
		$reasonCode		= $resArray["REASONCODE"];

		//	process ORDER
		header("location: ../".SHOP_DB_NAME_PREFIX."/".SHOP_DB_NAME_PREFIX."_process_order.php" );
		exit();		
		
	}
	else  
	{
		//	display warning , link back to STAGE 2 to try again and write to error file and email
		$error_type = 'GetExpressCheckoutDetails API call failed.';
		require('paypal_API_logError.php');	
	}	
?>