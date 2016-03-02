<?php

	/*----------------------------------------------------*\
	|				NEEDS MODIFYING		??				   |
	\*----------------------------------------------------*/


// no direct access
	defined('SITE_KEY') or die('File not found - <a href="/">please click here to return to the home page</a>');

	
	//	headers
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: '.$_SESSION['checkout_email']. " \r\n";				
					
	//	to:	--------------------------------
	$to = ALERT_ADD_TO_CART_EMAIL;

	//---Sybject:
	$subject = 'Item added to cart alert - from: '.SITE_NAME;	
	
	//	email supplied ??
	if (isset($_SESSION['checkout_email']))
	{
		$email_str = TAB_3.'<p>Email: <a href="mailto:'.$_SESSION['checkout_email'].'" >'.$_SESSION['checkout_email'].'</a></p>' ."\n";
	}
	else {$email_str = '';}

	//	Get Cart Contents:	
	foreach ( $_SESSION['cart_items'] as $prod_id => $item_quantity )
	{

		//	read from db to get prices and other display info	----------
		$mysql_err_msg = 'Shopping Cart Item information unavailable';	
		$sql_statement = 'SELECT * FROM sweeps_items, sweeps_cat_asign'

														.' WHERE sweeps_cat_asign.prod_id = "'.$prod_id.'"'
														.' AND sweeps_cat_asign.item_id = sweeps_items.item_id'
														.' AND active = "on"';
								
		$items_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));

	
		//	=============	Compile Item listing for Email	=========================
				
		$item_total = $items_info['price'] * $item_quantity;	
		$sub_total += $item_total;
		$total_quantity	+= $item_quantity;	
			
		$email_items .=   
						 TAB_4.'<tr>'."\n"
							.TAB_5.'<td align="left" >'.$items_info['item_name'].' @ '
								.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($items_info['price'],2).SHOP_CURRENCY_SYMBOL_SUFFIX.'</td>'."\n"
							.TAB_5.'<td align="center" >'.$item_quantity.'</td>'."\n"
							.TAB_5.'<td align="right"> '						
								.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($item_total,2).SHOP_CURRENCY_SYMBOL_SUFFIX.'</td>'."\n"
						.TAB_4.'</tr>'."\n";	
		
	}	

				
		//--------------------------------------------message body:-------------------------------------------------------------
		
		$message = 	TAB_1.'<html>' ."\n"
						.TAB_2.'<head>' ."\n"
							.TAB_3.'<title>Item added to cart alert - from: '.SITE_NAME.'</title>' ."\n"
						.TAB_2.'</head>' ."\n"
						.TAB_2.'<body>' ."\n"	
						
							.TAB_3.'<h3>An Item was added to the shopping cart on the '.SITE_NAME.' Website...</h3>' ."\n"

							.TAB_3.'<p>Time: '.date("D - d M Y - H:i T").'</p>' ."\n"
							
							.$email_str
							
							.TAB_3.'<p><strong>User&#39;s IP address: </strong>'.TAB_2.$_SERVER['REMOTE_ADDR'].'</p>' ."\n"
							
							.TAB_3.'<p><strong>From Page: </strong>'.TAB_2.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].'</p>' ."\n"
							
							.TAB_3.'<h3>Cart contents:</h3>' ."\n"


							.TAB_3.'<table border="1">' ."\n"
								.TAB_4.'<tr><th>Item and price</th><th>Quantity</th><th>Unit Price</th></tr>' ."\n"
							
								.$email_items
							
								.TAB_4.'<tr><th>Total Items:</th><th>'.$total_quantity.'</th><th>'
									.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($sub_total,2).SHOP_CURRENCY_SYMBOL_SUFFIX.'</th></tr>' ."\n"
							.TAB_3.'</table>' ."\n"

							.TAB_3.'<p>Promo / Coupon Code entered: <strong>'.$_SESSION['promo_code'].'</strong></p>' ."\n"
	

							
							.TAB_3.'<p>------ END of MESSAGE ------</p>' ."\n"
						
						.TAB_2.'</body>' ."\n"
					.TAB_1.'</html>'
					;	
				//--------------------------------------------------------------------------------------------------------------------------------------------------
				
//echo $message;
				
				$message = wordwrap($message, 70);
			
				//----------compile and send email
				mail ( $to, $subject, $message ,$headers);
				
			
?>