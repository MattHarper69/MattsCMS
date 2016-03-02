<?php

//--------Set key to start include files
	define( 'SITE_KEY', 1 );

	$file_path_offset = '../../';


//----Get Common code to all pages
	require_once ($file_path_offset.'includes/common.php');
	require_once ($file_path_offset.'includes/access.php');	
	
	require_once (CODE_NAME.'_shop_configs.php');
	
	//$payment_method_type = 2;		
	$payment_method_type = $_REQUEST['paymeth'];
	
if (isset($_SESSION['cust_email'])) {$cust_email = $_SESSION['cust_email'];} else {$cust_email = '';}
if (isset($_SESSION['cust_phone'])) {$cust_phone = $_SESSION['cust_phone'];} else {$cust_phone = '';}
if (isset($_SESSION['postage_method'])) {$postage_method = $_SESSION['postage_method'];} else {$postage_method = '';}
if (isset($_SESSION['postage_to_pay'])) {$postage_to_pay = $_SESSION['postage_to_pay'];} else {$postage_to_pay = '';}
if (isset($_SESSION['promo_code'])) {$promo_code = $_SESSION['promo_code'];} else {$promo_code = '';}

	
	//	read from db to get the payment method selected
	$mysql_err_msg = 'Payment Method information unavailable';	
	$sql_statement = 'SELECT config_file_name, pay_method_name FROM _shop_pay_types WHERE pay_method_id = "'.$payment_method_type.'"';
		
	$payment_method_info = mysql_fetch_array(ReadDB ($sql_statement, $mysql_err_msg));
	
	require_once (CODE_NAME.$payment_method_info[0]);
	
	$payment_method_name = $payment_method_info[1];

	
	//--------Redirect to Shutdown page if site is shutdown
	if (SITE_SHUTDOWN == 1)
	{
		header("location: /shutdown.php"); 
		exit();
	}
	
	//	Is  CART Empty OR NOT at STAGE 2 CHECKOUT ....if So should not be here.....go away.....
	if (!isset($_SESSION['cart_items']) OR count($_SESSION['cart_items']) < 1 OR !isset($_SESSION['check_out_state_2'])) 
	{
		header('location: /index.php?p='.SHOP_PAGE_ID); 
		exit();	  		
	}	
	
	//	Log Order

	$time_of_order = date("D - d M Y - H:i T");	//---for email 
	$sql_time = date("Y-m-d H:i:s");		//----for mySQL	
	
	
	$mysql_err_msg = 'Unable to record Order info';	
	$sql_statement = 'INSERT INTO shop_orders SET '
		
							.'  order_time = "'.$sql_time.'"' 	
							.', cust_email = "'.$cust_email.'"'	
							.', cust_phone = "'.$cust_phone.'"'
							.', ship_method = "'.$postage_method.'"'	
							.', ship_cost = "'.$postage_to_pay.'"'
							.', promo_code_entered = "'.$promo_code.'"'
							;

	ReadDB ($sql_statement, $mysql_err_msg);

	//	get order_id:	
	$order_id = mysql_insert_id();	
	
	
	// 	Zero stuff
	$cart_contents = array();
	$item_num = 0;
	$paypal_form_items = '';
	$email_items = '';
	$sub_total = 0;
	$total_discount = 0;
	$total_quantity = 0;
	$extra_info = '<h3>Product Descriptions:</h3>';
	
	//	Get Cart Contents:	
	foreach ( $_SESSION['cart_items'] as $prod_id => $item_quantity )
	{

		//	read from db to get prices and other display info	----------
		$mysql_err_msg = 'Shopping Cart Item information unavailable';	
		$sql_statement = 'SELECT * FROM shop_items, shop_cat_asign'

														.' WHERE shop_cat_asign.prod_id = "'.$prod_id.'"'
														.' AND shop_cat_asign.item_id = shop_items.item_id'
														.' AND active = "on"';
								
		$items_info = mysql_fetch_array (ReadDB ($sql_statement, $mysql_err_msg));

		
				//	has a coupon / promo code been entered
				
				$discount = 0;
				$price = $items_info['price'];
				for ($p=1; $p<5; $p++)
				{																																
					if 
					( 					
							$items_info['promo_code_'.$p] != '' AND $items_info['promo_code_'.$p] != NULL 
						AND	$promo_code != '' AND $promo_code != NULL
						AND strtolower($promo_code) == 	strtolower($items_info['promo_code_'.$p])					
					)
								
					{ 
						$price = $items_info['promo_price_'.$p]; 
						$discount = $items_info['price'] - $price;
					}
					//else {$discount = 0;}
	
				}		
		
	
		//	==============	Write to "ordered carts" db	=============================
			
		$sql_statement = 	'INSERT INTO shop_ordered_carts SET'
			
									.'  order_id = "'.$order_id.'"'
									.', item_id = "'.$items_info['item_id'].'"'
									.', item_name = "'.$items_info['item_name'].'"'
									.', discount = "'.$discount.'"'
									.', price = "'.$price.'"'
									.', product_code = "'.$items_info['product_code'].'"'
									.', quantity = "'.$item_quantity.'"'
									;								

		mysql_query (ReadDB ($sql_statement, $mysql_err_msg));
	
		//	=============	Compile Item listing for Email	=========================
				
		$item_total = $price * $item_quantity;
		$item_discount = $discount * $item_quantity;
		$sub_total += $item_total;
		$total_discount += $item_discount;
		$total_quantity	+= $item_quantity;	
			
		$email_items .=   
						 TAB_4.'<tr>'."\n"
							.TAB_5.'<td align="left" >'.$items_info['item_name'].' @ '
								.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($items_info['price'],2).SHOP_CURRENCY_SYMBOL_SUFFIX.'</td>'."\n"
							.TAB_5.'<td align="center" >'.$item_quantity.'</td>'."\n"
							.TAB_5.'<td align="right"> '						
								.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($item_discount,2).SHOP_CURRENCY_SYMBOL_SUFFIX.'</td>'."\n"
							.TAB_5.'<td align="right"> '						
								.SHOP_CURRENCY_SYMBOL_PREFIX.number_format($item_total,2).SHOP_CURRENCY_SYMBOL_SUFFIX.'</td>'."\n"
						.TAB_4.'</tr>'."\n";	
	
		//	add Custom build description
		
		
		$extra_info .= '<h4>Item: '.$items_info['item_name'].':</h4>'."\n";
		$extra_info .= $items_info['description'] ."\n";
		
		//	=============	Compile Item listing for PayPay Form	====================
		if ($payment_method_type == 2)
		{
			$item_num++;
			$paypal_form_items .= 
						
						 TAB_4.'<input type="hidden" name="item_name_'.$item_num.'" value="'.$items_info['item_name'].'" />' ."\n"
						.TAB_4.'<input type="hidden" name="quantity_'.$item_num.'" value="'.$item_quantity.'" />' ."\n"
						.TAB_4.'<input type="hidden" name="amount_'.$item_num.'" value="'.$price.'" />' ."\n"
						;			
		}
	
	}

	//	get total COST
	$grand_total = $sub_total + $postage_to_pay;
/* 	
	//	suffix invoice number if testing to avoid conflicts
	if 
	(
			($payment_method_type == 1 AND EWAY_DEFAULT_LIVE_GATEWAY == FALSE)
		OR	($payment_method_type == 2 AND PAYPAL_SANDBOX_FLAG == TRUE)
	)
	{
		$order_id .= '-T';
	}
	
	if (substr($_SERVER['SERVER_ADDR'], 0, 8) == "192.168." )
	{		
		$order_id .= 'L';			
	}
*/
	//	send email to Merchant	
	include_once ('email_order_merch.php');
	
	//	send email to Customer - NOT implimented yet
	//if (SHOP_SEND_EMAIL_TO_CUSTOMER == 1) 
	//{ include_once ('email_order_cust.php'); }
	

	if ($payment_method_type == 2)
	{	
		require_once ('../pay_gate/paypal_standard_form.php');
		
/* 		
		//	store the pay now button in the db for later use and or reference
		$mysql_err_msg = 'Unable to record Order info';	
		$sql_statement = 'UPDATE sweeps_orders SET paypal_form = "'.$stored_paypal_form.'" WHERE order_id = "'.$order_id.'"';
	//echo $sql_statement;
		ReadDB ($sql_statement, $mysql_err_msg);
*/

		//	store the pay now button for display when customer cancels and returns
		$_SESSION['canx_paypal'] = $stored_paypal_form;		
		
		//	display (hidden) pay pal form
		echo TAB_1.'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' ."\n";
		echo TAB_1.'<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">' ."\n";
			echo TAB_2.'<head>' ."\n";
				echo TAB_3.'<title>Processing Order - Please Wait</title>' ."\n";
			echo TAB_2.'</head>' ."\n";
			echo TAB_2.'<body>' ."\n";
																		
				echo $check_out_paypal_form;
							
				echo TAB_3.'<script language="JavaScript" type="text/javascript">' ."\n";
					echo TAB_4.'document.write("<h2><img src=/images_misc/loading.gif />'
								.'Processing Order - Please Wait...</h2>");'."\n";
					echo TAB_4.'document.forms.paypal.submit();' ."\n";
				echo TAB_3.'</script>' ."\n";
							
			echo TAB_2.'</body>' ."\n";
		echo TAB_1.'</html>';
	}

	
	//	create Recently purchased List
	$_SESSION['purchased_items'] = $_SESSION['cart_items'];
	
	//	EMPTY the CART			
	unset ($_SESSION['cart_items']);	
	
?>