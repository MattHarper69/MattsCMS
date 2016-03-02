<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');


	global $ip_alert_exceptions;
	
	if 
	(	
		isset($_SERVER['HTTP_REFERER']) 									//	if referred
		AND ALERT_SITE_REFERRAL												//	if set to ON
		AND !strpos($_SERVER['HTTP_REFERER'], $_SERVER['SERVER_NAME'])		//	if not already been to this site		
		AND !in_array($_SERVER['REMOTE_ADDR'], $ip_alert_exceptions) 				//	exceptions
	)
	{
				
		$referringSite = $_SERVER['HTTP_REFERER'];
		
		
		//	headers
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
					
		//	to:	--------------------------------
		$to = ALERT_EMAIL;
	
		//---Sybject:
		$subject = 'Site refered - from: '.$referringSite;	

					
		//--------------------------------------------message body:-------------------------------------------------------------
		
		$message = 	TAB_1.'<html>' ."\n"
						.TAB_2.'<head>' ."\n"
							.TAB_3.'<title>Site Referral alert - for: '.SITE_NAME.'</title>' ."\n"
						.TAB_2.'</head>' ."\n"
						.TAB_2.'<body>' ."\n"	
						
							.TAB_3.'<h3>A User linked to '.SITE_NAME.' Website...</h3>' ."\n"
															
							.TAB_3.'<p>From: <a href="'.$referringSite.'">'.$referringSite.'</a></p>' ."\n"
							
							.TAB_3.'<p>Site visited: <strong>'.$_SERVER['SERVER_NAME'].'</strong></p>' ."\n"
							
							.TAB_3.'<p>Time: '.date("D - d M Y - H:i T").'</p>' ."\n"
							
							//.$email_str
							
							.TAB_3.'<p>User&#39;s IP address: <strong>'.TAB_2.$_SERVER['REMOTE_ADDR'].'</strong></p>' ."\n"
							
							.TAB_3.'<p>------ END of MESSAGE ------</p>' ."\n"
						
						.TAB_2.'</body>' ."\n"
					.TAB_1.'</html>'
					;	
				//--------------------------------------------------------------------------------------------------------------------------------------------------
				
		//echo $message;
				
		$message = wordwrap($message, 70);
			
		//----------compile and send email
		mail ( $to, $subject, $message ,$headers);	
				
	}


?>