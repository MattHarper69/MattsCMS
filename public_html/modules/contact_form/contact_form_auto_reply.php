<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	$message = '';
	for ($i = 0; $i	< count ($email_msg_value); $i++ )
	{
		// 	replace new lines with a space - prevents a user from adding headers:
		$readback_value[$i] = preg_replace('/[\r|\n]+/', " ", $readback_value[$i]);
		
		$message .=  	  TAB_2 . '-  ' . $readback_label[$i] . ' ' . addCharsToStr (12 - strlen($readback_label[$i]), ' ') 
						. nl2br($readback_value[$i]) . PHP_EOL;
	}

		$from = $email_return;

	
		//	headers
		$headers  = 'MIME-Version: 1.0'  . PHP_EOL;
		$headers .= 'Content-type: text/plain; charset=iso-8859-1'  . PHP_EOL;
		if ($from != '')
		{
			$headers .= 'From: ' .$from  . PHP_EOL;		
		}
			
 			
					
		//	to:	--------------------------------
		$to = $email_from;
						
		//	Sybject:	-------------------------
		// $subject value is specified in contact_form.php / email_form.php
		
		//	Message:	-------------------------
		$message = 	
					  TAB_1 . '******************************' . AddCharsToStr (strlen(SITE_NAME), '*') . '*************************' . PHP_EOL	
					. TAB_1 . '*                             ' . AddCharsToStr (strlen(SITE_NAME), ' ') . '                        *' . PHP_EOL
					. TAB_1 . '*   Your Message/Inquiry for: ' . SITE_NAME                              . ' Website was received   *' . PHP_EOL
					. TAB_1 . '*                             ' . AddCharsToStr (strlen(SITE_NAME), ' ') . '                        *' . PHP_EOL
					. TAB_1 . '******************************' . AddCharsToStr (strlen(SITE_NAME), '*') . '*************************' . PHP_EOL
					
					. PHP_EOL
					. TAB_1 . 'Time of Message:'.TAB_2.$time_sent . PHP_EOL
					. PHP_EOL
					
					. $message
					
					. PHP_EOL
					. TAB_1 . $form_info['auto_reply_msg'] . PHP_EOL
					. PHP_EOL
					. TAB_1 . 'Please note: this is an auto-generated email - Replies to this email may NOT be monitored' . PHP_EOL					

					;	

				  
	//	wrap msg	---------------------------
	$message = wordwrap($message, 100);
	
	//	compile and send email	---------------------------
	mail ( $to, $subject, $message ,$headers);	
	
?>