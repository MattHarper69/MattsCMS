<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');		

	
	if (isset($_SESSION['pp_token']))
	{$pp_token = $_SESSION['pp_token'];}
	
	else {$pp_token = 'n/a';}


	
	//	display warning and link back to STAGE 2 to try again
	echo TAB_10.'<p class="WarningMSG" >There was An ERROR pocessing the Payment with PayPay !</p>'. "\n";
	echo TAB_10.'<p>' ."\n";	

		//$query_str = '?p='.SHOP_PAGE_ID.'&amp;view=checkout&amp;checkstate=2';
		echo TAB_11.'<a class="ShopCheckoutEdit" href="/index.php?p='.SHOP_PAGE_ID.'&amp;view=checkout&amp;checkstate=2"'. "\n";		
		echo TAB_11.' title="Click to attempt Payment again" >[ Try again ]</a>'. "\n";		
	echo TAB_11.'</p>' ."\n";
	
	$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
	$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
	$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
	$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
	
	
	$err_text =  '<br/><br/>DATE: ' . date("D - d M Y - H:i:s T") . "\n\n"
				.'<br/><br/>' . $error_type . "\n\n"
				.'<br/>Detailed Error Message: ' . $ErrorLongMsg . "\n"
				.'<br/>Short Error Message: ' . $ErrorShortMsg . "\n"
				.'<br/>Error Code: ' . $ErrorCode . "\n"
				.'<br/>Error Severity Code: ' . $ErrorSeverityCode . "\n\n"
				
				.'<br/><br/>ORDER DETAILS:' . "\n"
				.'<br/><br/>TOKEN:'.$pp_token . "\n"
				.'<br/>Invoice: '.$_SESSION['invoice_num'] . "\n"				
				.'<br/>Cost: '.$_SESSION['Payment_Amount'] . "\n"
				.'<br/>Name: '.$_SESSION['checkout_name'] . "\n"
				.'<br/>Email: '.$_SESSION['checkout_email'] . "\n"
				.'<br/>Phone: '.$_SESSION['checkout_phone'] . "\n"
				.'<br/>Address: '.$_SESSION['checkout_address_1'] . "\n"
				.'<br/> 		   '.$_SESSION['checkout_address_2'] . "\n"
				.'<br/>=========================================================================' . "\n\n"
				;
				
		//	Log Error to file
		$log_PP_err_file = '/'.$_SERVER['DOCUMENT_ROOT'].'/../_errors/errors-payment.log';
		$fh = fopen($log_PP_err_file, 'a') or die("can't open file");
		$log_text = str_replace('<br/>' , '' ,  $err_text);		
		fwrite($fh, $log_text);
		fclose($fh);			

		//	send email
		if (ALERT_ERRORS_PAYGATE == 1)
		{
			$email = ALERT_ERRORS_PAYGATE_EMAIL;	
			$header = ''; 
				
			$email_headers  = 'MIME-Version: 1.0' . "\r\n";
			$email_headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$email_headers .= 'From: ' . SHOP_PAYGATE_ERRORS_EMAIL_FROM . " \r\n";
		
			mail($email, "PayPal API ERROR", $err_text, $email_headers);		
		}
		
?>