<?php
 
 //// specify the path to this file, ie:	http://112.140.181.217/modules/pay_gate/paypal_IPN_listener.php
 //// in the PayPal account: My Account -> Profile -> Instant Payment Notification Preferences
 
//--------Set key to start include files
	define( 'SITE_KEY', 1 );

	$file_path_offset = '../../';
	
	
//----Get Common code to all pages
	require_once ('../../includes/common.php');		
	require_once (CODE_NAME.'_shop_configs.php');
	require_once (CODE_NAME.'_paypal_configs.php');
	
	error_reporting(E_ALL ^ E_NOTICE);

	if (PAYPAL_SANDBOX_FLAG == TRUE)
	{
		define ('PAYPAL_IPN_URL', 'ssl://www.sandbox.paypal.com');		//	FOR TESTING	
	}
	else
	{
		define ('PAYPAL_IPN_URL', 'ssl://www.paypal.com');
	}

	//	ad: "?ipn_email=name@domain.com" to the IPN URL specified in paypal account to override ipn email set in shop_configs.php file
	if (isset($_GET['ipn_email']))
	{ 
		$email = $_GET['ipn_email']; 
	}
	else
	{
		$email = PAYPAL_IPN_SEND_MSG_EMAIL;	
	}
	
	$is_this_test_buy = 0;
	
	$header = ''; 
		
	$email_headers  = 'MIME-Version: 1.0' . "\r\n";
	$email_headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$email_headers .= 'From: ' . SITE_NAME . " \r\n";
	
	$email_text = '<p>This is an automated email, sent by the website: <strong>' . SITE_NAME . '</strong>' . "\n" 
				. ', acknowledging an <strong>IPN</strong> message received by <strong>Paypal</strong>.</p>' . "\n\n"
				. '<p><em>Note: This should NOT be considered as a confirmation of payment' . "\n" 
				. ' - refer only to official emails from Paypal directly for this purpose.</em></p><br/>' . "\n\n";
				
	
	// Read the post from PayPal and add 'cmd' 
	$request = 'cmd=_notify-validate'; 
	if(function_exists('get_magic_quotes_gpc')) 
	{  	
		$get_magic_quotes_exits = true; 
	} 
	foreach ($_POST as $key => $value) 
	// Handle escape characters, which depends on setting of magic quotes 
	{  	
		if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1)
		{  		
			$value = urlencode(stripslashes($value)); 	
		} 
		else 
		{ 		
			$value = urlencode($value); 	
		}
		
		$request .= '&'.$key.'='.$value;

	} 
	// Post back to PayPal to validate 
	$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n"; 
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n"; 
	$header .= "Content-Length: " . strlen($request) . "\r\n\r\n"; 
	
	$fp = fsockopen (PAYPAL_IPN_URL, 443, $errno, $errstr, 30); 

	// Process validation from PayPal 
	// TODO: This sample does not test the HTTP response code. All HTTP response codes must be handles or you should use an 
	// HTTP library, such as cUrl  

	
	// assign posted variables to local variables
	$invoice_num = $_POST['invoice'];
	$item_name = $_POST['item_name'];
	$item_number = $_POST['item_number'];
	$payment_status = $_POST['payment_status'];
	$payment_amount = $_POST['mc_gross'];
	$payment_currency = $_POST['mc_currency'];
	$txn_id = $_POST['txn_id'];
	$receiver_email = $_POST['receiver_email'];
	$payer_email = $_POST['payer_email'];

	
	
	if (!$fp) 
	{
		// HTTP ERROR
		$email_text .= '<strong>HTTP ERROR</strong>: error n# = '.$errno . "\n\n" . 'error: ' . $errstr . "<br/><br/>\n\n";
		mail($email, "PayPal IPN-HTTP ERROR", $email_text, $email_headers);
	} 
	else 
	{ 
		// NO HTTP ERROR 
		fputs ($fp, $header . $request); 
		
		while (!feof($fp)) 
		{ 	
			$verification = @fgets ($fp, 1024);		// suppress with '@' to avoid error
			//$verification = fgets ($fp, 1024);
						
			// VERIFIED
			if (strcmp ($verification, "VERIFIED") == 0) 
			{ 		
	
				$pay_verify_err = '';
				
				// Check the payment_status is Completed 
				if ($payment_status != 'Completed') 
				{
					$pay_verify_err .= 'Payment Status not Completed<br/>' . "\n";
				}
 				
				// Check that txn_id has not been previously processed 	
				$sql_statement ='SELECT payment_transaction_id FROM '.SHOP_DB_NAME_PREFIX.'_orders WHERE payment_transaction_id = "'.$txn_id.'"';
				$txn_result = ReadDB ($sql_statement, $mysql_err_msg);
				$matches = mysql_num_rows($txn_result);
			
				if ($matches > 0)
				{
					$pay_verify_err .= 'Transaction: ' . $txn_id . ' already in database<br/>' . "\n";
				}
				
	
										
				// Check that receiver_email is the Primary PayPal email
				if ($receiver_email != PAYPAL_PRIMARY_EMAIL) 
				{
					$pay_verify_err .= 'Receiver email ( ' . $receiver_email . ' )'
									. ' does not match the Primary PayPal email stored on site ( ' . PAYPAL_PRIMARY_EMAIL . ' )<br/>' . "\n";
				}
				
				// Compare the customers db stored email address with the email sent from paypal				
				// need to get the suffix codes, used for testing, removed 
				if (strstr( $invoice_num ,'-T'))
				{
					$invoice_num = $invoice_num - '-T';
					// Request was a test purchase
					$is_this_test_buy = 1;
				}
	
				if (strstr( $invoice_num ,'L'))
				{
					$invoice_num = $invoice_num - 'L';
					// Request was a test purchase from the LOCAL SERVER
					$is_this_test_buy = 2;
				}		
							
				
				$sql_statement ='SELECT * FROM '.SHOP_DB_NAME_PREFIX.'_orders WHERE invoice_num = "'.$invoice_num.'"';
				//echo $sql_statement;
				$order_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));	

				$order_id = $order_info['order_id'];
				
				if ( $order_info['cust_email'] != $payer_email )
				{
					$pay_verify_caution .= 'CAUTION: the customers recorded email ( ' . $order_info['cust_email'] . ' )'
										. ' and the email sent from paypal ( ' . $payer_email . ' ) are not the same<br/>' . "\n";
				}				
				
				// Check that payment_amount is correct				
				if ($payment_amount != $order_info['total_payment']) 
				{
					$pay_verify_err .= 'Total Payment amount recorded ( ' . $order_info['total_payment'] . ' )'
									. ' does not match amount sent from paypal ( ' . $payment_amount . ' )<br/>' . "\n";
				}
				
				//	BELLS will ring if over specified amount
				if ($payment_amount > SHOP_AMOUNT_TRIGGERS_CAUTION) 
				{
					$pay_verify_caution .= 'CAUTION: Total Payment amount is over: ' 
					. SHOP_CURRENCY_SYMBOL_PREFIX . SHOP_AMOUNT_TRIGGERS_CAUTION . SHOP_CURRENCY_SYMBOL_SUFFIX . '<br/>' . "\n";
				}
				
				//	Payment made and looks to be good....
				if($pay_verify_err == '')
				{
					$denied = ' - OK';				
					
					// log $txn_id in db as payment_transaction_id and payment confirmed (but only if not local testing site)
					if ($is_this_test_buy < 2)
					{
						$mysql_err_msg = 'Unable to record Order info';	
						$sql_statement = 'UPDATE '.SHOP_DB_NAME_PREFIX.'_orders SET'
						
																		.' payment_transaction_id = "'.$txn_id.'"'
																		.',payment_status = "'.$payment_status.'"'
																		.' WHERE invoice_num = "'.$invoice_num.'"'
																		;
						ReadDB ($sql_statement, $mysql_err_msg);
					}
				
				
					// Process payment
					$payment_status = 'ok';
					require_once (PAYPAL_IPN_ACTION_CONFIRMED);			
				}

				//	There are Payment errors....Do not Process payment	
				else
				{
					$denied = ' - PAYMENT DENIED';
					$payment_status = 'denied';
					require_once (PAYPAL_IPN_ACTION_DENIED);
				}
				
				//	record sent values for email logging
				foreach ($_POST as $key => $value)
				{ 		
					$key_values .= $key . " = " .$value . '<br/><br/>' . "\n\n"; 		
				}
				
				if (PAYPAL_IPN_SEND_MSG == 1 OR $pay_verify_err != '')
				{				
					$email_text .= 'Message from PayPal IPN: <strong>' . $verification. $denied .'</strong><br/><br/>' . "\n\n"	
								. '<strong>' . $pay_verify_err . '</strong><br/><br/>'
								. '<strong>' . $pay_verify_caution . '</strong><br/><br/>'
								. '<strong>Values received:</strong><br/><br/>' . "\n\n"
								. "\n\n" . $key_values . '<br/><br/>' . "\n\n" . 'Sent request string:<br/><br/>' . "\n\n" . $request;
									
					mail($email, 'PayPal IPN-VERIFIED' . $denied, $email_text, $email_headers);
					
					if ($pay_verify_err != '')
					{
						//	Log Values to a file if Data incorrect
						$log_IPN_err_file = '/'.$_SERVER['DOCUMENT_ROOT'].'/../_errors/errors-payment.log';
						$fh = fopen($log_IPN_err_file, 'a') or die("can't open file");
						$file_data = "\n\n" . 'PayPal IPN error:';
						$file_data .= "\n\n" . 'invoice: '.$invoice_num;
						$file_data .= "\n\n" . 'Data incorect';
						$file_data .= "\n\n" . $key_values_logfile . $request;
						$file_data .= "\n\n" . '=========================================================================' . "\n\n";
						fwrite($fh, $file_data);
						fclose($fh);						
					}
				}				
			}
			
			// INVALID
			else if (strcmp ($verification, "INVALID") == 0 AND SHOP_SEND_IPN_CONFIRM_MSG == 1) 
			{ 		
				// If 'INVALID', send an email. TODO: Log for manual investigation. 		
				foreach ($_POST as $key => $value)
				{ 		
					$key_values .= $key . " = " .$value . '<br/><br/>' . "\n\n";
					$key_values_logfile .= $key . " = " .$value . "\n\n";
				} 
				
				$email_text .= 'Message from PayPal IPN: <strong>' . $verification. '</strong><br/><br/>' . "\n\n"
							. '<strong>Values received:</strong><br/><br/>' 
							. "\n\n" . $key_values . '<br/><br/>' . "\n\n" . 'Sent request string:<br/><br/>' . "\n\n" . $request;
								
				mail($email, "PayPal IPN-INVALID", $email_text, $email_headers);				
		
				//	Log Values to a file if INVALID
				$log_IPN_err_file = '/'.$_SERVER['DOCUMENT_ROOT'].'/../_errors/error_log.txt';			
				$fh = fopen($log_IPN_err_file, 'a') or die("can't open file");
				$file_data = "\n\n" . 'PayPay IPN error:';
				$file_data .= "\n\n" . 'invoice: '.$invoice_num;				
				$file_data .= 'INVALID msg' . "\n\n" . $key_values_logfile . $request;
				$file_data .= "\n\n" . '=========================================================================' . "\n\n";				
				fwrite($fh, $file_data);
				fclose($fh);		
		
		
			}	 
		
		} 
		
		fclose ($fp); 
		
	}

?>