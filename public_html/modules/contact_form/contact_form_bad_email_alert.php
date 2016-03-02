<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

		$bad_thing = "";
		$bad_ip_detected = "";
		$bad_email_detected = "";
		$bad_text_detected = "";
			
		if ($bad_ip_found == 1)
		{ 
			$bad_thing = $_SERVER['REMOTE_ADDR'];
			$email_or_ip = 'IP address';
			$bad_ip_detected = " ( detected black-listed )";
		}
		
		if ($bad_email_found == 1)
		{ 
			$bad_thing = $email_from;
			$email_or_ip = 'EMAIL';
			$bad_email_detected = " ( detected black-listed )";
		}	
		
		if ($bad_text_found == 1)
		{ 
			$bad_thing = $email_from;
			$email_or_ip = 'message text';
			$bad_text_detected = " ( detected bad text )";
		}	
		
		//	headers
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
					
		//	to:	--------------------------------
		$to = ALERT_EMAIL;
	
		//---Sybject:
		$subject = 'Contact Form Abuse - from: '.$bad_thing;	


					
		//--------------------------------------------message body:-------------------------------------------------------------
		
		$message = 	TAB_1.'<html>' ."\n"
						.TAB_2.'<head>' ."\n"
							.TAB_3.'<title>Contact Form Abuse - for: '.SITE_NAME.'</title>' ."\n"
						.TAB_2.'</head>' ."\n"
						.TAB_2.'<body>' ."\n"	
						
							.TAB_3.'<h3>A User tried to use a Black-listed '.$email_or_ip.' on '.SITE_NAME.' Website...</h3>' ."\n"
															
							.TAB_3.'<p>From: <a href="mailto:'.$email_from.'">'.$email_from.'</a>'.$bad_email_detected.'</p>' ."\n"
							
							.TAB_3.'<p>Site visited: <strong>'.$_SERVER['SERVER_NAME'].'</strong></p>' ."\n"
							
							.TAB_3.'<p>Time: '.date("D - d M Y - H:i T").'</p>' ."\n"

							.TAB_3.'<p>User&#39;s IP address: <strong>'.TAB_2.$_SERVER['REMOTE_ADDR'].$bad_ip_detected.'</strong></p>' ."\n"
							
							.TAB_3.'<p>message '.$bad_text_detected. ' : <br/>'.$textarea_text.'</p>' ."\n"
							
							.TAB_3.'<p>------ END of MESSAGE ------</p>' ."\n"
						
						.TAB_2.'</body>' ."\n"
					.TAB_1.'</html>'
					;	
				
			//---------------------------------------------------------------------------------------------------------------------------
		
//echo $message;
//exit();				
		$message = wordwrap($message, 70);
			
		//----------compile and send email
		mail ( $to, $subject, $message ,$headers);	
				


?>