<?php

// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	//	headers
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: '.$cust_email. " \r\n";				
					
	//	to:	--------------------------------
	$to = SHOP_ORDERS_EMAIL;

	//---Sybject:
	$subject = 'Quote requested for: '.$item_name.' from: '.SITE_NAME;	
///////////////////////////////////////			EDITED to HERE		//////////////////////////////////////////	

	//-----Customer Name $string
	if ( (isset($cust_name) AND $cust_name != '' AND $cust_name != NULL) )
	{
		$full_name = TAB_3.'Name: '.$cust_name.'<br/>' ."\n";	
	}
	else {$full_name = '';}
	
	//-----Customer EMAIL $string
	if ( $cust_email != '' AND $cust_email != NULL)
	{
		$email_str = TAB_3.'Email: <a href="mailto:'.$cust_email.'">'.$cust_email.'</a><br/>' ."\n";	
	}
	else {$email_str = TAB_3.'Email: <em> NOT PROVIDED</em><br/>' ."\n";}

		
		
		//--------------------------------------------message body:------------------------------------------------------------------
		$message = 	TAB_1.'<html>' ."\n"
						.TAB_2.'<head>' ."\n"
							.TAB_3.'<title>Quote Requested from: '.SITE_NAME.' Website</title>' ."\n"
						.TAB_2.'</head>' ."\n"
						.TAB_2.'<body>' ."\n"	
						
							.TAB_3.'<h3>A Quote was requested on the '.SITE_NAME.' Website...</h3>' ."\n"
							
							.TAB_3.'<p>Quote ID: '.$msg_id.'</p>' ."\n"
							
							.TAB_3.'<p>Time of Order: '.$time_sent.'</p>' ."\n"

							.TAB_3.'<h3>Customer details:</h3>' ."\n"
					
							.$full_name
							.$email_str
							
							.TAB_3.'<h3>order details:</h3>' ."\n"

							.TAB_3.'<h4>Item: '.$item_name.'</h3>' ."\n"
							
							.TAB_3.'<p>Quantity entered: '.$add2cart_quantity.'</p>' ."\n"

							.TAB_3.$item_desc ."\n"
							
							.TAB_3.'Total Price = '.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($total_price,2).SHOP_CURRENCY_SYMBOL_SUFFIX ."\n"
							
							.TAB_3.'<p>----------------------------------------------------</p>' ."\n"
							
							.TAB_3.'<p><strong>User&#39;s IP address: </strong>'.TAB_2.$_SERVER['REMOTE_ADDR'].'</p>' ."\n"
							
							.TAB_3.'<p>------ END of MESSAGE ------</p>' ."\n"
						
						.TAB_2.'</body>' ."\n"
					.TAB_1.'</html>'
					;	
				//------------------------------------------------------------------------------------------------------------------------------------------
				
//echo $message;
//exit();				
				$message = wordwrap($message, 70);
			
				//----------compile and send email
				mail ( $to, $subject, $message ,$headers);
				
			
?>