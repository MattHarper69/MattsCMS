<?php
 
	// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');


	$mysql_err_msg = 'Unable to get Order info for customer email';	
	$sql_statement ='SELECT * FROM sweeps_ordered_carts WHERE order_id = "'.$order_id.'"';
	//echo $sql_statement;
	
	$ordered_cart_info_result = ReadDB ($sql_statement, $mysql_err_msg);
	
	$email_item_listing = '';
	while ($ordered_cart_info = mysql_fetch_array ($ordered_cart_info_result))		
	{
		//	=============	Compile Item listing for Email	=========================
		$price = $ordered_cart_info['price'] + $ordered_cart_info['discount'];
		$item_total = $ordered_cart_info['price'] * $ordered_cart_info['quantity'];
		$item_discount = $ordered_cart_info['discount'] * $ordered_cart_info['quantity'];
		$sub_total += $item_total;
		$total_discount += $item_discount;
		$total_quantity	+= $ordered_cart_info['quantity'];	
		$ticket_end = $ordered_cart_info['ticket_start'] + $ordered_cart_info['quantity'];		
		
		
		$email_item_listing .=   
								TAB_4.'<tr>'."\n"
									.TAB_5.'<td align="left" >'.$ordered_cart_info['item_name'].' @ '
										.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($price,2).SHOP_CURRENCY_SYMBOL_SUFFIX.'</td>'."\n"
									.TAB_5.'<td align="center" >'.$ordered_cart_info['ticket_start'].'</td>'."\n"
									.TAB_5.'<td align="center" >'.$ticket_end.'</td>'."\n"
									.TAB_5.'<td align="center" >'.$ordered_cart_info['quantity'].'</td>'."\n"
									.TAB_5.'<td align="right"> '						
										.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($item_discount,2).SHOP_CURRENCY_SYMBOL_SUFFIX.'</td>'."\n"
									.TAB_5.'<td align="right"> '						
										.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($item_total,2).SHOP_CURRENCY_SYMBOL_SUFFIX.'</td>'."\n"
								.TAB_4.'</tr>'."\n";	
	}
	
	//	get total COST
	$grand_total = $sub_total;
	
	if ($payment_status == 'ok')
	{
		//	Flag email as Payment DENIED
		$paid_str = 'PAYED';
		
		$email_heading = TAB_3.'<h2>Payment has been received for Your Order from: ' . SITE_NAME . ' - Thank you.</h2>' ."\n";	
	}
	
	if ($payment_status == 'denied')
	{
		//	Flag email as Payment DENIED
		$paid_str = 'PAYMENT DENIED';
		
		$email_heading = TAB_3 . '<h2>NOTICE: your Payment to: '. SITE_NAME .' has been DENIED</h2>' ."\n"						
						.TAB_3 . '<p>Please contact ' . SITE_NAME . ' by the email: <a href="mailto:'. SHOP_ORDERS_EMAIL .'">' . "\n"
						.TAB_3 . SHOP_ORDERS_EMAIL .'</a> for more information.</p>' . "\n";	
	}


	//	email customer ============================================
	
	//	headers
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: '.SITE_NAME. " \r\n";
	
	//	to:	--------------------------------
	$to = $order_info['cust_email'];

	//---Sybject:
	$subject = 'Order: '.$invoice_num.' from: ' .SITE_NAME. ' - ' . $paid_str;
	
	//-----Agent Name $string
	if ( $order_info['agent_name'] != '' AND $order_info['agent_name'] != NULL)
	{
		$agent_details = TAB_3.'Agent: '.$order_info['agent_name'].'<br/>' ."\n";	
	}	
	
	//-----Customer Name $string
	if ( $order_info['cust_name'] != '' AND $order_info['cust_name'] != NULL)
	{
		$full_name = TAB_3.'Name: '.$order_info['cust_name'].'<br/>' ."\n";	
	}
	
	//-----Customer EMAIL $string
	if ( $order_info['cust_email'] != '' AND $order_info['cust_email'] != NULL)
	{
		$email_str = TAB_3.'Email: '.$order_info['cust_email'].'<br/>' ."\n";	
	}
	
	//-----Customer PHONE $string
	if ( $order_info['phone'] != '' AND $order_info['phone'] != NULL)
	{
		$phone_str = TAB_3.'Phone: '.$order_info['phone'].'<br/>' ."\n";	
	}	
	

	//-----build customers Billing address $string
	if ( $order_info['cust_address_1'] != '' AND $order_info['cust_address_1'] != NULL)
	{
		$bill_address = TAB_3.'<h3>Address:</h3>'.$order_info['cust_address_1'].'<br/>'."\n";
						
		if ( $order_info['cust_address_2'] != '' AND $order_info['cust_address_2'] != NULL) 
		{$bill_address .= TAB_3.$order_info['cust_address_2'].'<br/>' ."\n";}					
	
		$bill_address .= TAB_3.$order_info['cust_state'].' '.$order_info['pcode'].'<br/>' ."\n";
	}
		
	
	$time = date ("D - d M Y - H:i T", strtotime($order_info['order_time']));
	
		//--------------------------------------------message body:-------------------------------------
		
		$message = 	TAB_1.'<html>' ."\n"
						.TAB_2.'<head>' ."\n"
							.TAB_3.'<title>Order from: '.SITE_NAME.' - ' . $paid_str . '</title>' ."\n"
						.TAB_2.'</head>' ."\n"
						.TAB_2.'<body>' ."\n"	
						
							.$email_heading
							
							.TAB_3.'<p>Order n#: '.$invoice_num.'</p>' ."\n"
							
							.TAB_3.'<p>Date and Time of Order: '.$time.'</p>' ."\n"

							.TAB_3.'<h3>Your details:</h3>' ."\n"
					
							.$agent_details
							.$full_name
							.$email_str
							.$phone_str
							
							.$bill_address
							
							.TAB_3.'<h3>order details:</h3>' ."\n"


							.TAB_3.'<table border="1">' ."\n"
								.TAB_4.'<tr><th>Item and price</th><th>Start N#</th><th>End N#</th>'
											.'<th>Quantity</th><th>Discount</th><th>Total Price</th></tr>' ."\n"
							
								.$email_item_listing
							
								.TAB_4.'<tr><th>Total Items:</th><th colspan="2">---</th><th>'.$total_quantity.'</th>' ."\n"
								
									.TAB_5.'<th style="font-weight: normal;" >'
										.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($total_discount,2).SHOP_CURRENCY_SYMBOL_SUFFIX.'</th>'."\n"
									.TAB_5.'<th>'.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($sub_total,2).SHOP_CURRENCY_SYMBOL_SUFFIX.'</th>'."\n"
								.TAB_4.'</tr>' ."\n"
							.TAB_3.'</table>' ."\n"
								
							.TAB_3.'<table border="1">' ."\n"
								.TAB_4.'<tr><td>Total Order Cost is: <strong>'
								.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($grand_total,2).SHOP_CURRENCY_SYMBOL_SUFFIX.'</strong></td></tr>' ."\n"
							.TAB_3.'</table>' ."\n"
							.TAB_3.'<p>Promo / Coupon Code entered: <strong>'.$order_info['promo_code_entered'].'</strong></p>' ."\n"			
							
							.TAB_3.'<p>------ END of MESSAGE ------</p>' ."\n"
						
						.TAB_2.'</body>' ."\n"
					.TAB_1.'</html>'
					;	
					
		//-------------------------------------------------------------------------------------------------------------------
				
		$message = wordwrap($message, 70);
			
		//----------compile and send email
		mail ( $to, $subject, $message ,$headers);	

	
?>