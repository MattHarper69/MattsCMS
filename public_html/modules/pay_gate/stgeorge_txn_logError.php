<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');		


	$err_text =  '<br/><br/>DATE: ' . date("D - d M Y - H:i:s T") . "\n\n"
				.'<br/><br/>St George API IPG transaction error' . "\n\n";
	
	$err_text .= '<br/>TXNREFERENCE: ' 	. get( $webpayRef, "TXNREFERENCE") . "\n"
				.'<br/>RESULT: ' 		. get( $webpayRef, "RESULT") . "\n"
				.'<br/>AUTHCODE: ' 		. get( $webpayRef, "AUTHCODE") . "\n"
				.'<br/>RESPONSETEXT: ' 	. get( $webpayRef, "RESPONSETEXT") . "\n"
				.'<br/>RESPONSECODE: ' 	. get( $webpayRef, "RESPONSECODE") . "\n"
				.'<br/>ERROR: ' 		. get( $webpayRef, "ERROR") . "\n";
				
	$err_text .= "\n" . '<br/><br/>ORDER DETAILS:' . "\n"

				.'<br/>Invoice: '.$invoice_num . "\n"				
				.'<br/>Cost: '.$_SESSION['Payment_Amount'] . "\n"
				.'<br/>Name: '.$_SESSION['checkout_name'] . "\n"
				.'<br/>Email: '.$_SESSION['checkout_email'] . "\n"
				.'<br/>Phone: '.$_SESSION['checkout_phone'] . "\n"
				.'<br/>Address: '.$_SESSION['checkout_address_1'] . "\n"
				.'<br/> 		'.$_SESSION['checkout_address_2'] . "\n"
				.'<br/>=========================================================================' . "\n\n"
				;
				
		//	Log Error to file
		$log_err_file = '/'.$_SERVER['DOCUMENT_ROOT'].'/../_errors/errors-payment.log';
		$fh = fopen($log_err_file, 'a');
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
		
			mail($email, "St George API IPG TXN ERROR", $err_text, $email_headers);		
		}

		
?>