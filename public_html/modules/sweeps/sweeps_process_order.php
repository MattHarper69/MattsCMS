<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );

	$file_path_offset = '../../';
	

//----Get Common code to all pages
	require_once ($file_path_offset.'includes/common.php');
	require_once ($file_path_offset.'includes/access.php');	
	
	require_once (CODE_NAME.'_shop_configs.php');
	require_once ('sweeps_php_functions.php');	
		
	//	Is  CART Empty OR NOT at STAGE 2 CHECKOUT ....if So should not be here.....go away.....
	if (!isset($_SESSION['cart_items']) OR $_SESSION['check_out_stage'] < 3) 
	{
		header('location: /index.php?p='.SHOP_PAGE_ID); 
		exit();	  		
	}

	//	read from db to get the payment method selected
	$mysql_err_msg = 'Payment Method information unavailable';	
	$sql_statement = 'SELECT config_file_name, pay_method_name FROM _shop_pay_types WHERE pay_method_id = "'.$_SESSION['pay_method_type'].'"';
		
	$payment_method_info = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
	$payment_method_name = $payment_method_info['pay_method_name'];
	
	require_once (CODE_NAME.$payment_method_info['config_file_name']);	

	
	//	Log Order
	$time_of_order = date("D, dS M Y @ H:i:s T");	//---for invoice / email 
	$sql_time = date("Y-m-d H:i:s");		//----for mySQL	
	
	//	is this a test TXN - if so suffix invoice number if testing to avoid conflicts
	$invoice_num = $_SESSION['invoice_num'];

	if 
	(
			($_SESSION['pay_method_type'] == 1 AND EWAY_DEFAULT_LIVE_GATEWAY == FALSE)
		OR	($_SESSION['pay_method_type'] == 2 AND PAYPAL_SANDBOX_FLAG == 1)
		OR	($_SESSION['pay_method_type'] == 3 AND PAYPAL_SANDBOX_FLAG == 1)		
	)
	{
		$invoice_num .= '-T';
		$test_mode = 'test';
	}
	
	if (substr($_SERVER['SERVER_ADDR'], 0, 8) == "192.168." )
	{		
		$invoice_num .= 'L';
		$test_mode .= ' - local';
	}

	$hash_inv_num = 'mds' . md5(md5($_SESSION['invoice_num']));
	
	if (isset($_SESSION['agent_login_id']) AND $_SESSION['agent_login_id'] != '')	
	{$agent_id = $_SESSION['agent_login_id'];}
	else {$agent_id = '';}
	
	if (isset($_SESSION['agent_login_name']) AND $_SESSION['agent_login_name'] != '')	
	{$agent_login_name = $_SESSION['agent_login_name'];}
	else {$agent_login_name = '';}	

	if (isset($_SESSION['promo_code']) AND $_SESSION['promo_code'] != '')	
	{$promo_code = $_SESSION['promo_code'];}
	else {$promo_code = '';}	
	
	$mysql_err_msg = 'Unable to record Order info';	
	$sql_statement = 'INSERT INTO sweeps_orders SET '
		
							.' order_time = "'.$sql_time.'"'
							.', IP_add = "'.$_SERVER['REMOTE_ADDR'].'"'	
							.', invoice_num = "'.$_SESSION['invoice_num'].'"'
							.', test_mode = "'.$test_mode.'"'
							.', agent_id = "'.$agent_id.'"'
							.', agent_name = "'.$agent_login_name.'"'							
							.', cust_name = "'.$_SESSION['checkout_name'].'"'
							.', cust_email = "'.$_SESSION['checkout_email'].'"'
							.', cust_phone = "'.$_SESSION['checkout_phone'].'"'
							.', cust_address_1 = "'.$_SESSION['checkout_address_1'].'"'
							.', cust_address_2 = "'.$_SESSION['checkout_address_2'].'"'
							.', cust_state = "'.$_SESSION['checkout_state'].'"'
							.', cust_pcode = "'.$_SESSION['checkout_postcode'].'"'
							.', cust_country = "'.$_SESSION['checkout_country'].'"'
							.', promo_code_entered = "'.$promo_code.'"'
							.', payment_method = "'.$payment_method_name.'"'
							.', payment_transaction_id = "'.$_SESSION['txn_id'].'"'
							.', total_amount_payed = "'.$_SESSION['total_amount_payed'].'"'
							.', currency_code = "'.$_SESSION['currencyCodeType'].'"'
							.', payment_status = "'.$_SESSION['payment_status'].'"'							
							.', hash_inv_num = "'.$hash_inv_num.'"'
							;

	ReadDB ($sql_statement, $mysql_err_msg);

	//	get order_id:	
	$order_id = mysql_insert_id();	
	
	
	// 	Zero stuff
	$cart_contents = array();
	$discount_exists = FALSE;
	$item_num = 0;
	$paypal_form_items = '';
	$inv_html_item_listing = '';
	$inv_text_item_listing = '';
	$sub_total = 0;
	$total_discount = 0;
	$total_quantity	= 0;
    $grand_total = 0;	
		
	//	Get Cart Contents:	
	foreach ( $_SESSION['cart_items'] as $prod_id => $item_quantity )
	{

		// 	Lock items tabel when getting next ticket n# etc - so that buyers don't end up with the same numbers
		$mysql_err_msg = 'Locking tables';
		$sql_statement = 'LOCK TABLES sweeps_items WRITE, sweeps_cat_asign WRITE';
		mysql_query (ReadDB ($sql_statement, $mysql_err_msg));	
		
		//	read from db to get prices and other display info	----------
		$mysql_err_msg = 'Shopping Cart Item information unavailable';	
		$sql_statement = 'SELECT * FROM sweeps_items, sweeps_cat_asign'

														.' WHERE sweeps_cat_asign.prod_id = "'.$prod_id.'"'
														.' AND sweeps_cat_asign.item_id = sweeps_items.item_id'
														.' AND active = "on"';
								
		$items_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));

		//	update Ticket start N#
		
		$mysql_err_msg = 'Updating ticket start number';

		$sql_statement = 'UPDATE sweeps_items SET ticket_start = ticket_start + '.$item_quantity
		
												.' WHERE item_id = "'.$items_info['item_id'].'"';
		//echo $sql_statement;
		mysql_query (ReadDB ($sql_statement, $mysql_err_msg));	
		
/////////////////	START NEW CODE 18/9/12	////////////////////////////////

		//	update Items Sold and In-stock N#
		
		$mysql_err_msg = 'Updating Items Sold number';

		$sql_statement = 'UPDATE sweeps_items SET items_sold = items_sold + '.$item_quantity
		
												.' WHERE item_id = "'.$items_info['item_id'].'"';

		mysql_query (ReadDB ($sql_statement, $mysql_err_msg));	

		$sql_statement = 'UPDATE sweeps_items SET in_stock = in_stock - '.$item_quantity
		
												.' WHERE item_id = "'.$items_info['item_id'].'"';

		mysql_query (ReadDB ($sql_statement, $mysql_err_msg));	
		
/////////////////	END NEW CODE 18/9/12	////////////////////////////////
		
		// unlock table
		$mysql_err_msg = 'unlocking tables';
		$sql_statement = 'UNLOCK TABLES';
		mysql_query (ReadDB ($sql_statement, $mysql_err_msg));
		
				
		//	has a coupon / promo code been entered
		
		$discount = 0;
		$price = $items_info['price'];
		for ($p=1; $p<5; $p++)
		{																																
			if 
			( 					
					$items_info['promo_code_'.$p] != '' AND $items_info['promo_code_'.$p] != NULL 
				AND	isset($_SESSION['promo_code']) AND $_SESSION['promo_code'] != '' AND $_SESSION['promo_code'] != NULL
				AND strtolower($_SESSION['promo_code']) == 	strtolower($items_info['promo_code_'.$p])					
			)
						
			{ 
				$price = $items_info['promo_price_'.$p]; 
				$discount = $items_info['price'] - $price;
				$discount_exists = TRUE;
			}
						
		}		
		
		
	
		//	==============	Write to "ordered carts" db	=============================
		$ticket_end = $items_info['ticket_start'] + $item_quantity - 1;	
		$sql_statement = 	'INSERT INTO sweeps_ordered_carts SET'
			
									.'  order_id = "'.$order_id.'"'
									.', item_id = "'.$items_info['item_id'].'"'
									.', item_name = "'.$items_info['item_name'].'"'
									.', product_code = "'.$items_info['item_code'].'"'									
									.', discount = "'.$discount.'"'
									.', price = "'.$price.'"'
									.', quantity = "'.$item_quantity.'"'
									.', item_total = "'.$price * $item_quantity.'"'									
									.', ticket_start = "'.$items_info['ticket_start'].'"'
									.', ticket_end = "'.$ticket_end.'"'
									;								

		mysql_query (ReadDB ($sql_statement, $mysql_err_msg));

		//	=============	Compile Item listing for Invoice for Display and Email	=========================
				
		$item_total = $price * $item_quantity;
		$item_discount = $discount * $item_quantity;
		$sub_total += $item_total;
		$total_discount += $item_discount;
		$total_quantity	+= $item_quantity;	
		$ticket_end = $items_info['ticket_start'] + $item_quantity - 1;
		
		$inv_html_item_listing .= TAB_11.'<tr class="ShopReceiptTableRow" >' ."\n";
		
			//	item Name:
			if (strlen($items_info['item_name']) > 40)
			{
				$items_info['item_name'] = substr_replace ($items_info['item_name'] , '...' , 40 );
			}
			
			$inv_html_item_listing .= TAB_12.'<td class="ShopReceiptTableCell" >'.$items_info['item_name'].'</td>' ."\n";		
			$inv_text_item_listing .= $items_info['item_name'] . AddCharsToStr (26 - strlen($items_info['item_name']), ' ');
			
			//	item Code:
			$inv_html_item_listing .= TAB_12.'<td class="ShopReceiptTableCell" align="center" >'.$items_info['item_code'].'</td>' ."\n";
			$inv_text_item_listing .= $items_info['item_code'] . AddCharsToStr (8 - strlen($items_info['item_code']), ' ');
			
			//	item Date:
			$event_date = date("j/n/Y", strtotime($items_info['event_date']));
			$inv_html_item_listing .= TAB_12.'<td class="ShopReceiptTableCell" align="center" >'.$event_date .'</td>' ."\n";
			$inv_text_item_listing .= AddCharsToStr (10 - strlen($event_date), ' ') . $event_date;
			
			//	Quantity
			$inv_html_item_listing .= TAB_12.'<td class="ShopReceiptTableCell" align="center" >'. $item_quantity .'</td>' ."\n";
			$inv_text_item_listing .= AddCharsToStr (8 - strlen($item_quantity), ' ') . $item_quantity;
			
			//	ticket numbers
			if ($item_quantity > 1)
			{
				$tix_nums = $items_info['ticket_start'].' - '
				.($ticket_end);
				
			}
			else
			{
				$tix_nums = $items_info['ticket_start'];					
			}
			
			$inv_html_item_listing .= TAB_12.'<td class="ShopReceiptTableCell" align="center" >'. $tix_nums .'</td>' ."\n";
			$inv_text_item_listing .= '    ' . $items_info['ticket_start'] . AddCharsToStr (8 - strlen($items_info['ticket_start']), ' ');
			
					
			$inv_html_item_listing .= TAB_12.'<td class="ShopReceiptTableTableCell" align="center" >' ."\n";
			//	item discount (if exists)
			if ($discount_exists == TRUE)
			{
				$item_discount = SHOP_CURRENCY_SYMBOL_PREFIX.number_format($item_discount, 2).SHOP_CURRENCY_SYMBOL_SUFFIX;
				$inv_html_item_listing .= TAB_13 . $item_discount ."\n";
			}
			$inv_html_item_listing .= TAB_12.'</td>' ."\n";
			
			//	item sub price
			$sub_price = $item_quantity * ($items_info['price'] - $discount);
			$show_sub_price = SHOP_CURRENCY_SYMBOL_PREFIX.number_format($sub_price, 2).SHOP_CURRENCY_SYMBOL_SUFFIX;
			$inv_html_item_listing .= TAB_12.'<td class="ShopReceiptTableCell" align="center" >'. $show_sub_price .'</td>' ."\n";
			$inv_text_item_listing .= AddCharsToStr (11 - strlen($show_sub_price), ' ') . $show_sub_price . PHP_EOL;
			
		$inv_html_item_listing .= TAB_11.'</tr>' ."\n";

		//	do a 2nd row for end ticket if needed
		if ($item_quantity > 1)
		{
			$inv_text_item_listing .= '                                                       - ' . $ticket_end . PHP_EOL;
		}
		
		
		$grand_total = $grand_total + $sub_price;			
			

		
		//	=============	Compile Item listing for PayPay Standard Form	====================
		if ($_SESSION['pay_method_type'] == 2)
		{
			$item_num++;
			$paypal_form_items .= 
						
						 TAB_4.'<input type="hidden" name="item_name_'.$item_num.'" value="'.$items_info['item_name'].'" />' ."\n"
						.TAB_4.'<input type="hidden" name="quantity_'.$item_num.'" value="'.$item_quantity.'" />' ."\n"
						.TAB_4.'<input type="hidden" name="amount_'.$item_num.'" value="'.$price.'" />' ."\n"
						;			
		}

	}
		
	if (isset($_SESSION['postage_to_pay']))	
	{$postage_to_pay = $_SESSION['postage_to_pay'];}
	else {$postage_to_pay = 0;}
	//	get total COST
	$grand_total = $sub_total + $postage_to_pay;
	
	$mysql_err_msg = 'Unable to record Grand total in Order info';	
	$sql_statement = 'UPDATE sweeps_orders SET total_payment = "'.$grand_total.'" WHERE order_id = '.$order_id;
							
	ReadDB ($sql_statement, $mysql_err_msg);	
	
	//	make an Invoice
	include_once ('sweeps_invoice_maker.php');

	//	write invoice html code to db
	$mysql_err_msg = 'Unable to record Invoice data to db';	
	$sql_statement = 'UPDATE sweeps_orders SET invoice_html = "'.htmlspecialchars($invoice_html).'" WHERE order_id = '.$order_id;
	ReadDB ($sql_statement, $mysql_err_msg);	
	
	//	send email to Merchant	
	if (SHOP_SEND_EMAIL_TO_MERCHANT == 1)
	{ include_once ('sweeps_email_order_merch.php'); }
	
	
	//	send email to Customer
	if (SHOP_SEND_EMAIL_TO_CUSTOMER == 1) 
	{ include_once ('sweeps_email_order_cust.php'); }
	
//////////////////////////////////////--- EDITED TO HERE - ////////////////////////////////////////////////////////////////////////////////	


	//	create Recently purchased List
	$_SESSION['purchased_items'] = $_SESSION['cart_items'];
	
	//	EMPTY the CART and reset		
	EmptyCartAndReset();
	
	//	send to Payment made page...
	$qry_str = $_SERVER['QUERY_STRING'];

	header ("location: /index.php?p=".SHOP_PAGE_ID."&view=paymade&invoice=".$hash_inv_num);
	exit();
		
?>