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
	$subject = 'Order: '.$order_id.' from: '.SITE_NAME;	
	
	//-----Customer ID $string
	if ( isset($user_id) AND $user_id != '' AND $user_id != NULL)
	{
		$display_user_id = TAB_3.'Customer id: '.$user_id.'<br/>' ."\n";	
	}
	else {$display_user_id = '';}

	//-----Customer Name $string
	if ( (isset($first_name) AND $first_name != '' AND $first_name != NULL) AND (isset($last_name) AND $last_name != '' AND $last_name != NULL) )
	{
		$full_name = TAB_3.'Name: '.$first_name.' '.$last_name.'<br/>' ."\n";	
	}
	else {$full_name = '';}
	
	//-----Customer EMAIL $string
	if ( $cust_email != '' AND $cust_email != NULL)
	{
		$email_str = TAB_3.'Email: <a href="mailto:'.$cust_email.'">'.$cust_email.'</a><br/>' ."\n";	
	}
	else {$email_str = TAB_3.'Email: <em> NOT PROVIDED</em><br/>' ."\n";}

	//-----Customer phone $string
	if ( $cust_phone != '' AND $cust_phone != NULL)
	{
		$phone_str = TAB_3.'Phone: '.$cust_phone.'<br/>' ."\n";	
	}
	else {$phone_str = TAB_3.'Phone: <em> NOT PROVIDED</em><br/>' ."\n";}
	
	//-----build customers Postal address $string
	if ( isset($address_1) AND $address_1 != '' AND $address_1 != NULL)
	{
		$post_address = TAB_3.'<h3>Post to:</h3>'.$address_1.'<br/>' ."\n";
			
		if ( $address_2 != '' AND $address_2 != NULL) {$post_address .= TAB_3.$address_2.'<br/>'."\n";}	
			
		if ( $city != '' OR $city != NULL) {$post_address .= TAB_3.$city.'<br/>' ."\n";}
				
		$post_address .= TAB_3.$state.' '.$zip.'<br/>' ."\n";

		$post_address .= TAB_3.$country_name.'<br/>' ."\n";
	}
	else {$post_address = '';}
	
	//-----build customers Billing address $string
	if ( isset($bill_address_1) AND $bill_address_1 != '' AND $bill_address_1 != NULL)
	{
		$bill_address = TAB_3.'<h3>Bill to:</h3>'.$bill_address_1.'<br/>'."\n";
						
		if ( $bill_address_2 != '' AND $bill_address_2 != NULL) {$bill_address .= TAB_3.$bill_address_2.'<br/>' ."\n";}					

		if ( $bill_city != '' AND $bill_city != NULL) {$bill_address .= TAB_3.$bill_city.'<br/>' ."\n";}	
				
		$bill_address .= TAB_3.$bill_state.' '.$bill_zip.'<br/>' ."\n";

		$bill_address .= TAB_3.$bill_country_name.'<br/>' ."\n";

	}
	else {$bill_address = '';}

	//----Extra information:
	if (isset($extra_info)) {$extra_info = $extra_info;}
	else  {$extra_info = '';}
		
		
		//--------------------------------------------message body:------------------------------------------------------------------
		$message = 	TAB_1.'<html>' ."\n"
						.TAB_2.'<head>' ."\n"
							.TAB_3.'<title>Order from: '.SITE_NAME.' Website</title>' ."\n"
						.TAB_2.'</head>' ."\n"
						.TAB_2.'<body>' ."\n"	
						
							.TAB_3.'<h3>An order was placed on the '.SITE_NAME.' Website...</h3>' ."\n"
							
							.TAB_3.'<p>Order n#: '.$order_id.'</p>' ."\n"
							
							.TAB_3.'<p>Time of Order: '.$time_of_order.'</p>' ."\n"

							.TAB_3.'<h3>Customer details:</h3>' ."\n"
					
							.$display_user_id
							.$full_name
							.$email_str
							.$phone_str
							
							.$post_address
							
							.$bill_address
							
							.TAB_3.'<h3>order details:</h3>' ."\n"


							.TAB_3.'<table border="1">' ."\n"
								.TAB_4.'<tr><th>Item and price</th><th>Quantity</th><th>Discount</th><th>Total Price</th></tr>' ."\n"
							
								.$email_items
							
								.TAB_4.'<tr><th>Total Items:</th><th>'.$total_quantity.'</th>' ."\n"
								
									.TAB_5.'<th style="font-weight: normal;" >'
										.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($total_discount,2).SHOP_CURRENCY_SYMBOL_SUFFIX.'</th>'."\n"
									.TAB_5.'<th>'.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($sub_total,2).SHOP_CURRENCY_SYMBOL_SUFFIX.'</th>'."\n"
								.TAB_4.'</tr>' ."\n"
							.TAB_3.'</table>' ."\n"
							
							.TAB_3.'Total Postage and Packaging is: <strong>'
							.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($postage_to_pay,2).SHOP_CURRENCY_SYMBOL_SUFFIX.'</strong>'."\n"
							
							.TAB_3.'<table border="1">' ."\n"
								.TAB_4.'<tr><td>Total Order Cost is: <strong>'
								.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($grand_total,2).SHOP_CURRENCY_SYMBOL_SUFFIX.'</strong></td></tr>' ."\n"
							.TAB_3.'</table>' ."\n"
							.TAB_3.'<p>Promo / Coupon Code entered: <strong>'.$promo_code.'</strong></p>' ."\n"	
							.TAB_3.'<p>Payment Method: <strong>'.$payment_method_name.'</strong></p>' ."\n"
							.TAB_3.'<p>Post Method: <strong>'.$postage_method.'</strong></p>' ."\n"
							
							. $extra_info
							
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